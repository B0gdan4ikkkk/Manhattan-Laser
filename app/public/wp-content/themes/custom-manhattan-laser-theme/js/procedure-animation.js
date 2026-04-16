(function() {
	// Защита от повторной инициализации (например, при повторных событиях load).
	var hasInitialized = false;

	function initProcedureAnimation() {
		// Базовые guard-checks: запуск только 1 раз, только при наличии GSAP/ScrollTrigger
		// и только если пользователь не попросил уменьшение анимации.
		if (hasInitialized) return;
		if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
		if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

		// Целевая секция с данными шагов.
		var section = document.getElementById('procedure-section');
		if (!section) return;

		var dataRaw = section.getAttribute('data-procedure-steps');
		if (!dataRaw) return;

		var steps = [];
		try {
			steps = JSON.parse(dataRaw);
		} catch (e) {
			return;
		}
		if (!Array.isArray(steps) || !steps.length) return;

		// Ключевые DOM-узлы для pin/rotation/markers.
		var pin = section.querySelector('.procedure-pin');
		var rotor = section.querySelector('.procedure-rotor');
		var dialWrap = section.querySelector('.procedure-dial-wrap');
		var markers = section.querySelectorAll('.procedure-marker');
		if (!pin || !rotor || !dialWrap || !markers.length) return;

		hasInitialized = true;
		gsap.registerPlugin(ScrollTrigger);

		var totalStepCount = steps.length;
		var totalSegments = Math.max(1, totalStepCount - 1);
		var stepScrollFactor = 0.72; // ПК по умолчанию; на телефоне выше — чуть длиннее «ход» pin.

		// Ждём полной стабилизации размеров секции и шрифтов перед инициализацией,
		// чтобы избежать «прыжка» start/end у ScrollTrigger.
		function afterStableLayout(cb) {
			var waits = [];
			if (document.fonts && document.fonts.ready) {
				waits.push(document.fonts.ready.catch(function() {}));
			}
			var ellipse = section.querySelector('.procedure-ellipse');
			if (ellipse && !ellipse.complete) {
				waits.push(new Promise(function(resolve) {
					ellipse.addEventListener('load', resolve, { once: true });
					ellipse.addEventListener('error', resolve, { once: true });
				}));
			}
			Promise.all(waits).finally(function() {
				requestAnimationFrame(function() {
					requestAnimationFrame(cb);
				});
			});
		}

		afterStableLayout(function() {
			// Pin + поворот ротора только от нативного скролла (без перехвата wheel/touch — иначе «зажёвывает» страницу).
			gsap.matchMedia().add('(min-width: 100px)', function() {
				var lastActiveIndex = -1;
				var centeredStepIndex = 0;
				var currentStepIndex = 0;
				var targetStepIndex = 0;
				var resizeDebounceTimer = null;
				var rotorTween = null;
				var revealTween = null;
				var recoveryTimer = null;
				var recoveryBurstTimers = [];

				function updateDeviceTuning() {
					stepScrollFactor = window.matchMedia('(max-width: 900px), (hover: none) and (pointer: coarse)').matches
						? 1.2
						: 0.72;
				}

				function renderMarkerVisibility(activeIndex, showActiveDetails) {
					var safeActive = Math.max(0, Math.min(totalStepCount - 1, activeIndex));
					var shouldShowDetails = showActiveDetails !== false;
					markers.forEach(function(marker, i) {
						var active = shouldShowDetails && i === safeActive;
						marker.classList.toggle('is-active', active);
						// Badge/маленький кружок всегда видны.
						marker.style.opacity = '1';
						marker.style.setProperty('--step-visible', '1');
						var contentEl = marker.querySelector('.procedure-marker-content');
						if (contentEl) {
							contentEl.style.opacity = '1';
						}
						var lineEl = marker.querySelector('.procedure-marker-line');
						if (lineEl) {
							lineEl.style.transition = 'opacity 0.35s ease';
							lineEl.style.opacity = active ? '1' : '0';
						}
						var stepEl = marker.querySelector('.procedure-marker-step');
						if (stepEl) {
							stepEl.style.transition = 'opacity 0.35s ease';
							stepEl.style.opacity = active ? '1' : '0';
						}
						var dotEl = marker.querySelector('.procedure-marker-dot');
						if (dotEl) {
							dotEl.style.opacity = '1';
						}
					});
				}

				function applyStepByIndex(stepIndex, instant) {
					var safeIndex = Math.max(0, Math.min(totalStepCount - 1, stepIndex));
					if (safeIndex === lastActiveIndex && !instant) return;
					lastActiveIndex = safeIndex;

					var nextRotation = safeIndex * 90;
					if (rotorTween) rotorTween.kill();
					if (instant) {
						rotorTween = null;
						gsap.set(rotor, { rotation: nextRotation });
						centeredStepIndex = safeIndex;
						renderMarkerVisibility(centeredStepIndex, true);
					} else {
						// На старте поворота уводим текст/линию текущего шага.
						renderMarkerVisibility(centeredStepIndex, false);
						rotorTween = gsap.to(rotor, {
							rotation: nextRotation,
							duration: 1.2,
							ease: 'power2.inOut',
							onComplete: function() {
								centeredStepIndex = safeIndex;
								renderMarkerVisibility(centeredStepIndex, true);
							}
						});
					}
				}

				function clearRecoveryBurstTimers() {
					recoveryBurstTimers.forEach(function(timerId) {
						clearTimeout(timerId);
					});
					recoveryBurstTimers = [];
				}

				function cancelInFlightTransition() {
					if (rotorTween) {
						rotorTween.kill();
						rotorTween = null;
					}
				}

				updateDeviceTuning();
				gsap.set(rotor, { rotation: 0 });
				renderMarkerVisibility(0, true);
				applyStepByIndex(0, true);

				// Плавный «подъезд» круга при приближении к секции:
				// снизу/уменьшен/затемнен -> нормальное состояние.
				revealTween = gsap.fromTo(
					dialWrap,
					{
						yPercent: 12,
						scale: 0.92,
						filter: 'brightness(0.72)'
					},
					{
						yPercent: 0,
						scale: 1,
						filter: 'brightness(1)',
						ease: 'none',
						scrollTrigger: {
							trigger: section,
							start: 'top bottom',
							end: 'top 70%',
							scrub: 0.8,
							invalidateOnRefresh: true
						}
					}
				);

				// Основной ScrollTrigger: pin секции + управление поворотом ротора.
				var st = ScrollTrigger.create({
					trigger: section,
					start: 'top top',
					end: function() {
						return '+=' + String(window.innerHeight * (totalSegments * stepScrollFactor));
					},
					pin: pin,
					pinType: 'transform',
					scrub: false,
					snap: false,
					anticipatePin: 0,
					invalidateOnRefresh: true,
					onUpdate: function(self) {
						var progress = self.progress;
						var snappedIndex = Math.max(0, Math.min(totalSegments, Math.round(progress * totalSegments)));
						currentStepIndex = snappedIndex;
						targetStepIndex = snappedIndex;
						applyStepByIndex(snappedIndex);
					}
				});

				function syncStateFromScroll() {
					var progress = st && typeof st.progress === 'number' ? st.progress : 0;
					var snappedIndex = Math.max(0, Math.min(totalSegments, Math.round(progress * totalSegments)));
					currentStepIndex = snappedIndex;
					targetStepIndex = snappedIndex;
					centeredStepIndex = snappedIndex;
					applyStepByIndex(snappedIndex, true);
				}

				function scheduleRecoverySync() {
					clearTimeout(recoveryTimer);
					clearRecoveryBurstTimers();
					recoveryTimer = setTimeout(function() {
						safeRefreshAndSync();
					}, 120);
					// Несколько повторных sync после возврата вкладки:
					// часть браузеров поздно восстанавливает реальный viewport.
					recoveryBurstTimers.push(setTimeout(safeRefreshAndSync, 260));
					recoveryBurstTimers.push(setTimeout(safeRefreshAndSync, 520));
				}

				function safeRefreshAndSync() {
					updateDeviceTuning();
					cancelInFlightTransition();
					if (typeof ScrollTrigger !== 'undefined') {
						ScrollTrigger.refresh();
					}
					requestAnimationFrame(function() {
						requestAnimationFrame(function() {
							syncStateFromScroll();
						});
					});
				}

				function onWindowResize() {
					// При resize пересчитываем start/end trigger с debounce.
					clearTimeout(resizeDebounceTimer);
					resizeDebounceTimer = setTimeout(function() {
						safeRefreshAndSync();
					}, 180);
				}

				function onVisibilityChange() {
					if (document.visibilityState === 'hidden') {
						cancelInFlightTransition();
						return;
					}
					if (document.visibilityState === 'visible') {
						scheduleRecoverySync();
					}
				}

				function onPageShow() {
					scheduleRecoverySync();
				}

				function onWindowFocus() {
					scheduleRecoverySync();
				}

				function onWindowBlur() {
					cancelInFlightTransition();
				}

				function onPageHide() {
					cancelInFlightTransition();
				}

				window.addEventListener('resize', onWindowResize);
				document.addEventListener('visibilitychange', onVisibilityChange);
				window.addEventListener('pageshow', onPageShow);
				window.addEventListener('focus', onWindowFocus);
				window.addEventListener('blur', onWindowBlur);
				window.addEventListener('pagehide', onPageHide);

				requestAnimationFrame(function() {
					ScrollTrigger.refresh();
				});

				return function() {
					window.removeEventListener('resize', onWindowResize);
					document.removeEventListener('visibilitychange', onVisibilityChange);
					window.removeEventListener('pageshow', onPageShow);
					window.removeEventListener('focus', onWindowFocus);
					window.removeEventListener('blur', onWindowBlur);
					window.removeEventListener('pagehide', onPageHide);
					clearTimeout(resizeDebounceTimer);
					clearTimeout(recoveryTimer);
					clearRecoveryBurstTimers();
					currentStepIndex = 0;
					targetStepIndex = 0;
					centeredStepIndex = 0;
					if (revealTween) revealTween.kill();
					if (rotorTween) rotorTween.kill();
					st.kill();
					gsap.set(rotor, { rotation: 0 });
					applyStepByIndex(0, true);
				};
			});
		});
	}

	if (document.readyState === 'complete') {
		initProcedureAnimation();
	} else {
		window.addEventListener('load', initProcedureAnimation, { once: true });
	}
})();

