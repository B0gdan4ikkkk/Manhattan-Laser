/**
 * Повторная инициализация слайдера/сравнения после AJAX на архиве Before/After
 * (before-after-archive.js). Должны существовать до первого вызова из fetch.then.
 */
(function () {
	function mlCreateArchiveBeforeAfterSwiper(el) {
		if (typeof Swiper === 'undefined' || !el) return;
		var wrap = el.closest('[data-ml-before-after-archive]');
		var nextBtn = wrap ? wrap.querySelector('.before-after-next') : null;
		var prevBtn = wrap ? wrap.querySelector('.before-after-prev') : null;
		var archiveCfg = {
			slidesPerView: 1,
			spaceBetween: 20,
			allowTouchMove: false,
			loop: true,
			breakpoints: {
				768: {
					slidesPerView: 2,
					spaceBetween: 20
				}
			},
			loop: false,
			watchOverflow: true
		};
		if (nextBtn && prevBtn) {
			archiveCfg.navigation = {
				nextEl: nextBtn,
				prevEl: prevBtn
			};
		}
		new Swiper(el, archiveCfg);
	}

	function mlSetupBeforeAfterCompareBlock(block) {
		var hit = block.querySelector('.before-after-compare__hit');
		var vline = block.querySelector('.before-after-vline');
		var knob = block.querySelector('.before-after-knob');
		if (!hit) return;
		function splitFromClientX(clientX) {
			var r = block.getBoundingClientRect();
			if (r.width <= 0) return 50;
			return Math.max(0, Math.min(100, ((clientX - r.left) / r.width) * 100));
		}
		function applySplit(v) {
			var n = Math.max(0, Math.min(100, parseFloat(v, 10)));
			n = Math.round(n * 1000) / 1000;
			block.style.setProperty('--split', String(n));
			var pos = n + '%';
			if (vline) vline.style.left = pos;
			if (knob) knob.style.left = pos;
			hit.setAttribute('aria-valuenow', String(Math.round(n)));
		}
		function onPointerMove(e) {
			if (!hit.hasPointerCapture(e.pointerId)) return;
			applySplit(splitFromClientX(e.clientX));
		}
		function onPointerUp(e) {
			hit.releasePointerCapture(e.pointerId);
		}
		hit.addEventListener('pointerdown', function (e) {
			if (e.button !== 0 && e.pointerType === 'mouse') return;
			e.preventDefault();
			hit.setPointerCapture(e.pointerId);
			applySplit(splitFromClientX(e.clientX));
		});
		hit.addEventListener('pointermove', onPointerMove);
		hit.addEventListener('pointerup', onPointerUp);
		hit.addEventListener('pointercancel', onPointerUp);
		hit.addEventListener('keydown', function (e) {
			var cur = parseFloat(block.style.getPropertyValue('--split') || getComputedStyle(block).getPropertyValue('--split'), 10);
			if (isNaN(cur)) cur = 50;
			var step = e.shiftKey ? 10 : 2;
			if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
				e.preventDefault();
				applySplit(cur - step);
			} else if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
				e.preventDefault();
				applySplit(cur + step);
			} else if (e.key === 'Home') {
				e.preventDefault();
				applySplit(0);
			} else if (e.key === 'End') {
				e.preventDefault();
				applySplit(100);
			}
		});
		applySplit(50);
	}

	window.mlCreateArchiveBeforeAfterSwiper = mlCreateArchiveBeforeAfterSwiper;
	window.mlSetupBeforeAfterCompareBlock = mlSetupBeforeAfterCompareBlock;
	window.mlInitBeforeAfterArchiveSwiper = function (container) {
		if (!container) return;
		container.querySelectorAll('.before-after-swiper--archive').forEach(function (el) {
			mlCreateArchiveBeforeAfterSwiper(el);
		});
	};
	window.mlInitBeforeAfterCompareInContainer = function (container) {
		if (!container) return;
		container.querySelectorAll('.before-after-compare').forEach(function (block) {
			mlSetupBeforeAfterCompareBlock(block);
		});
	};
})();

document.addEventListener('DOMContentLoaded', function() {
	var lenisInstance = null;
	window.customManhattanLenis = null;

	// Высота 1vh в пикселях — один раз при загрузке, статично (не меняется при скролле/resize)
	(function setVhOnce() {
		var h = window.innerHeight;
		document.documentElement.style.setProperty('--vh', (h * 0.01).toFixed(2) + 'px');
	})();

	// Мягкая прокрутка страницы (колёсико / тач) — Lenis; отключаем при prefers-reduced-motion
	(function initSmoothScroll() {
		if (typeof Lenis === 'undefined') return;
		var mq = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)');
		if (mq && mq.matches) return;
		var isMobileTouch = window.matchMedia && window.matchMedia('(hover: none) and (pointer: coarse)').matches;
		if (isMobileTouch) return;

		lenisInstance = new Lenis({
			lerp: 0.075,
			smoothWheel: true,
			wheelMultiplier: 0.88,
			touchMultiplier: 1.65,
			syncTouch: true,
			syncTouchLerp: 0.065
		});
		window.customManhattanLenis = lenisInstance;

		function raf(time) {
			lenisInstance.raf(time);
			requestAnimationFrame(raf);
		}
		requestAnimationFrame(raf);
	})();

	(function syncLenisWithScrollTrigger() {
		if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
		gsap.registerPlugin(ScrollTrigger);
		if (lenisInstance && typeof lenisInstance.on === 'function') {
			lenisInstance.on('scroll', ScrollTrigger.update);
		}
	})();

	const header = document.querySelector('.header');
	const headerInner = document.querySelector('.header__inner');
	const pcMenu = document.querySelector('.pc-menu');
	const searchTrigger = document.querySelector('.search-trigger');
	const searchOverlay = document.querySelector('.search-overlay');
	const searchClose = document.querySelector('.search-close');
	const searchInput = document.querySelector('.search-overlay__input');
	const searchResults = document.querySelector('[data-search-results]');
	var headerSearchTimer = null;
	var headerSearchRequestId = 0;

	function clearHeaderSearchResults() {
		if (!searchResults) return;
		searchResults.innerHTML = '';
		searchResults.hidden = true;
	}

	function showHeaderSearchLoading() {
		if (!searchResults) return;
		searchResults.hidden = false;
		searchResults.innerHTML = '<p class="py-2 text-[14px] text-[#F4EFE8]/75">Searching treatments...</p>';
	}

	function renderHeaderSearchItems(items) {
		if (!searchResults) return;
		searchResults.innerHTML = '';
		searchResults.hidden = false;

		if (!items || !items.length) {
			searchResults.innerHTML = '<p class="py-2 text-[14px] text-[#F4EFE8]/75">No treatments found.</p>';
			return;
		}

		var list = document.createElement('ul');
		list.className = 'divide-y divide-[#F4EFE8]/10';

		items.forEach(function(item) {
			var row = document.createElement('li');
			var link = document.createElement('a');
			link.href = item.url || '#';
			link.className = 'block py-3 text-[16px] text-[#F4EFE8] transition-opacity hover:opacity-80';
			link.textContent = item.title || '';
			row.appendChild(link);
			list.appendChild(row);
		});

		searchResults.appendChild(list);
	}

	function requestHeaderSearch(query) {
		if (typeof mlHeaderSearch === 'undefined' || !mlHeaderSearch.ajaxUrl || !mlHeaderSearch.nonce) return;
		var requestId = ++headerSearchRequestId;
		var body = new URLSearchParams();
		body.set('action', 'ml_header_treatments_search');
		body.set('nonce', mlHeaderSearch.nonce);
		body.set('s', query);

		fetch(mlHeaderSearch.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			body: body.toString()
		})
			.then(function(res) {
				return res.json();
			})
			.then(function(data) {
				if (requestId !== headerSearchRequestId) return;
				if (!data || !data.success || !data.data) {
					renderHeaderSearchItems([]);
					return;
				}
				renderHeaderSearchItems(Array.isArray(data.data.items) ? data.data.items : []);
			})
			.catch(function() {
				if (requestId !== headerSearchRequestId) return;
				renderHeaderSearchItems([]);
			});
	}

	// Анимация заголовка по буквам (как на myhealthprac.com)
	const titleEl = document.querySelector('.hero-title-animate');
	if (titleEl) {
		const text = titleEl.textContent.trim();
		titleEl.textContent = '';
		const delayStep = 35;
		text.split('').forEach(function(char, i) {
			const span = document.createElement('span');
			span.className = 'letter' + (char === ' ' ? ' letter--space' : '');
			span.textContent = char === ' ' ? '\u00A0' : char;
			span.style.animationDelay = (i * delayStep) + 'ms';
			titleEl.appendChild(span);
		});
	}

	const testimonialsSwiperElEarly = document.querySelector('.testimonials-swiper');
	// FAQ: <details name="…"> — нативный аккордеон + разметка для краулеров без JS

	if (testimonialsSwiperElEarly && typeof Swiper !== 'undefined') {
		new Swiper('.testimonials-swiper', {
			slidesPerView: 2,
			spaceBetween: 14,
			loop: true,
			speed: 480,
			breakpoints: {
				480: { slidesPerView: 2, spaceBetween: 16 },
				640: { slidesPerView: 2, spaceBetween: 16 },
				768: { slidesPerView: 2, spaceBetween: 18 },
				1024: { slidesPerView: 3, spaceBetween: 20 },
				1280: { slidesPerView: 3, spaceBetween: 22 },
				1536: { slidesPerView: 4, spaceBetween: 24 }
			},
			navigation: {
				nextEl: '.testimonials-swiper-next',
				prevEl: '.testimonials-swiper-prev'
			}
		});
	}

	// Счётчики (hero, medical team legacy и др.): обёртка + [data-counter] / data-suffix
	function initAnimatedCountersSection(countersWrap) {
		if (!countersWrap) return;
		const counters = countersWrap.querySelectorAll('[data-counter]');
		if (!counters.length) return;

		const duration = 1800;
		const easing = function(t) { return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2; };

		function animateCounter(el) {
			const target = parseInt(el.getAttribute('data-counter'), 10);
			if (isNaN(target)) return;
			const suffix = el.getAttribute('data-suffix') || '';
			const start = performance.now();
			function step(now) {
				const elapsed = now - start;
				const progress = Math.min(elapsed / duration, 1);
				const eased = easing(progress);
				const value = Math.round(eased * target);
				el.textContent = value + suffix;
				if (progress < 1) requestAnimationFrame(step);
			}
			requestAnimationFrame(step);
		}

		const obs = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (!entry.isIntersecting) return;
				obs.disconnect();
				counters.forEach(animateCounter);
			});
		}, { rootMargin: '0px', threshold: 0.3 });
		obs.observe(countersWrap);
	}

	document.querySelectorAll('.hero-counters, .medical-team-legacy-counters').forEach(initAnimatedCountersSection);

	if (!header || !headerInner || !pcMenu) return;

	function openSearch() {
		if (!searchOverlay || !searchTrigger) return;
		if (lenisInstance && typeof lenisInstance.stop === 'function') lenisInstance.stop();
		header.classList.add('search-open');
		searchOverlay.classList.add('is-open');
		pcMenu.classList.remove('is-open');
		headerInner.classList.add('bg-[#1F2A44]/30', 'backdrop-blur-[10px]');
		searchTrigger.setAttribute('aria-expanded', 'true');
		if (searchInput) {
			searchInput.value = '';
			searchInput.focus();
		}
		clearHeaderSearchResults();
	}

	function closeSearch() {
		if (!searchOverlay || !searchTrigger) return;
		header.classList.remove('search-open');
		if (lenisInstance && typeof lenisInstance.start === 'function') lenisInstance.start();
		searchOverlay.classList.remove('is-open');
		searchTrigger.setAttribute('aria-expanded', 'false');
        headerInner.classList.remove('bg-[#1F2A44]/30', 'backdrop-blur-[10px]');
		clearHeaderSearchResults();
		if (headerSearchTimer) {
			clearTimeout(headerSearchTimer);
			headerSearchTimer = null;
		}
	}

	if (searchTrigger && searchOverlay) {
		searchTrigger.addEventListener('click', function(e) {
			e.preventDefault();
			openSearch();
		});
	}
	if (searchInput) {
		searchInput.addEventListener('keydown', function(e) {
			if (e.key === 'Enter') {
				e.preventDefault();
			}
		});
		searchInput.addEventListener('input', function() {
			var query = String(searchInput.value || '').trim();

			if (headerSearchTimer) {
				clearTimeout(headerSearchTimer);
				headerSearchTimer = null;
			}
			if (query.length < 2) {
				clearHeaderSearchResults();
				return;
			}

			showHeaderSearchLoading();
			headerSearchTimer = setTimeout(function() {
				requestHeaderSearch(query);
			}, 250);
		});
	}
	if (searchClose) searchClose.addEventListener('click', closeSearch);

	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape' && header.classList.contains('search-open')) closeSearch();
	});

	header.addEventListener('mouseenter', function() {
		if (header.classList.contains('search-open')) return;
		if (window.innerWidth <= 1024) return;
		pcMenu.classList.add('is-open');
		headerInner.classList.add('bg-[#1F2A44]/30', 'backdrop-blur-[10px]');
	});

	header.addEventListener('mouseleave', function() {
		if (window.innerWidth <= 1024) return;
		pcMenu.classList.remove('is-open');
		headerInner.classList.remove('bg-[#1F2A44]/30', 'backdrop-blur-[10px]');
	});

	var mobileMenu = document.getElementById('mobile-menu');
	var mobileMenuClose = document.querySelector('.mobile-menu__close');
	var hamburger = document.querySelector('.header__hamburger');

	function openMobileMenu() {
		if (!mobileMenu) return;
		if (lenisInstance && typeof lenisInstance.stop === 'function') lenisInstance.stop();
		mobileMenu.classList.add('is-open');
		mobileMenu.setAttribute('aria-hidden', 'false');
		if (hamburger) {
			hamburger.classList.add('is-open');
			hamburger.setAttribute('aria-expanded', 'true');
			hamburger.setAttribute('aria-label', 'Close menu');
		}
		document.body.style.overflow = 'hidden';
	}
	function closeMobileMenu() {
		if (!mobileMenu) return;
		mobileMenu.classList.remove('is-open');
		if (lenisInstance && typeof lenisInstance.start === 'function') lenisInstance.start();
		mobileMenu.setAttribute('aria-hidden', 'true');
		if (hamburger) {
			hamburger.classList.remove('is-open');
			hamburger.setAttribute('aria-expanded', 'false');
			hamburger.setAttribute('aria-label', 'Open menu');
		}
		document.body.style.overflow = '';
	}

	if (hamburger && mobileMenu) {
		hamburger.addEventListener('click', function() {
			if (window.innerWidth > 1024) return;
			if (mobileMenu.classList.contains('is-open')) {
				closeMobileMenu();
			} else {
				openMobileMenu();
			}
		});
	}
	if (mobileMenuClose) {
		mobileMenuClose.addEventListener('click', closeMobileMenu);
	}
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('is-open')) closeMobileMenu();
	});

	// Аккордеон в мобильном меню
	document.querySelectorAll('[data-mobile-accordion]').forEach(function(btn) {
		btn.addEventListener('click', function() {
			var item = btn.closest('.mobile-menu__item--accordion');
			var sublist = item ? item.querySelector('.mobile-menu__sublist') : null;
			if (!item || !sublist) return;
			var isOpen = item.classList.toggle('is-open');
			sublist.classList.toggle('hidden', !isOpen);
			btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});
	});

	// Treatments Swiper
	const treatmentsSwiperEl = document.querySelector('.treatments-swiper');
	if (treatmentsSwiperEl && typeof Swiper !== 'undefined') {
		new Swiper('.treatments-swiper', {
			slidesPerView: 1,
			spaceBetween: 20,
			
			breakpoints: {
				640: { slidesPerView: 2.2,
					   spaceBetween: 20
				 },
				1024: { slidesPerView: 2.2,
					spaceBetween: 20 },
				1280: { slidesPerView: 2.9,
					spaceBetween: 20 }
			},
			navigation: {
				nextEl: '.treatments-swiper-next',
				prevEl: '.treatments-swiper-prev'
			},
			loop: true
		});
	}

	// Doctor profile: certifications (mobile only layout; desktop uses CSS grid)
	const doctorCertSwiperEl = document.querySelector('.doctor-certifications-swiper');
	if (doctorCertSwiperEl && typeof Swiper !== 'undefined') {
		new Swiper('.doctor-certifications-swiper', {
			slidesPerView: 2,
			spaceBetween: 20,
			centeredSlides: false,
			loop: false,
			watchOverflow: true,
			navigation: {
				nextEl: '.doctor-certifications-swiper-next',
				prevEl: '.doctor-certifications-swiper-prev'
			}
		});
	}

	// Treatments Swiper
	const blogSwiperEl = document.querySelector('.blog-swiper');
	if (blogSwiperEl && typeof Swiper !== 'undefined') {
		new Swiper('.blog-swiper', {
			slidesPerView: 1,
			spaceBetween: 20,
			
			breakpoints: {
				640: { slidesPerView: 2.5,
					   spaceBetween: 20
				 },
				1024: { slidesPerView: 2.2,
					spaceBetween: 20 },
				1280: { slidesPerView: 2.9,
					spaceBetween: 20 }
			},
			loop: true
		});
	}

	// Before/After Swiper + draggable split line
	if (typeof Swiper !== 'undefined') {
		document.querySelectorAll('.before-after-swiper:not(.before-after-swiper--archive)').forEach(function (el) {
			new Swiper(el, {
				slidesPerView: 1,
				spaceBetween: 40,
				allowTouchMove: false,
				breakpoints: {
					748: {
						spaceBetween: 0
					}
				},
				navigation: {
					nextEl: '.before-after-next',
					prevEl: '.before-after-prev'
				},
				loop: false,
				watchOverflow: true
			});
		});
		// Архив Before/After: 1 слайд на мобиле, 2 на планшете/ПК (остальные страницы без изменений)
		document.querySelectorAll('.before-after-swiper--archive').forEach(function (el) {
			window.mlCreateArchiveBeforeAfterSwiper(el);
		});
	}
	document.querySelectorAll('.before-after-compare').forEach(function (block) {
		window.mlSetupBeforeAfterCompareBlock(block);
	});

	// Таблицы с горизонтальным скроллом (single treatment): тёмная цены + светлое сравнение
	function initHorizontalTableScrollbar(root, selectors) {
		var scrollEl = root.querySelector(selectors.scroll);
		var track = root.querySelector(selectors.track);
		var thumb = root.querySelector(selectors.thumb);
		var wrap = selectors.wrap ? root.querySelector(selectors.wrap) : null;
		var hint = selectors.hint ? root.querySelector(selectors.hint) : null;
		if (!scrollEl || !track || !thumb) return;

		var mq = window.matchMedia ? window.matchMedia('(max-width: 767.98px)') : null;

		function isMobile() {
			return !mq || mq.matches;
		}

		function updateThumb() {
			if (!isMobile()) return;

			var cw = scrollEl.clientWidth;
			var sw = scrollEl.scrollWidth;
			var maxScroll = Math.max(0, sw - cw);
			var trackW = track.getBoundingClientRect().width;

			if (maxScroll <= 0 || trackW <= 0) {
				thumb.style.width = trackW > 0 ? trackW + 'px' : '100%';
				thumb.style.transform = 'translateX(0)';
				if (wrap) wrap.classList.add('is-inactive');
				if (hint) hint.style.display = 'none';
				return;
			}

			if (wrap) wrap.classList.remove('is-inactive');
			if (hint) hint.style.display = '';

			var thumbW = Math.max((cw / sw) * trackW, 36);
			var maxLeft = Math.max(0, trackW - thumbW);
			var ratio = maxScroll > 0 ? scrollEl.scrollLeft / maxScroll : 0;
			var left = ratio * maxLeft;

			thumb.style.width = thumbW + 'px';
			thumb.style.transform = 'translateX(' + left + 'px)';
		}

		var dragStartX = 0;
		var dragThumbStartLeft = 0;

		function scrollFromThumbLeft(leftPx) {
			var cw = scrollEl.clientWidth;
			var sw = scrollEl.scrollWidth;
			var maxScroll = Math.max(0, sw - cw);
			var trackW = track.getBoundingClientRect().width;
			var thumbW = thumb.getBoundingClientRect().width;
			var maxLeft = Math.max(0, trackW - thumbW);
			if (maxLeft <= 0 || maxScroll <= 0) return;
			var r = Math.max(0, Math.min(1, leftPx / maxLeft));
			scrollEl.scrollLeft = r * maxScroll;
		}

		scrollEl.addEventListener('scroll', updateThumb, { passive: true });
		window.addEventListener('resize', updateThumb);

		if (typeof ResizeObserver !== 'undefined') {
			var ro = new ResizeObserver(updateThumb);
			ro.observe(scrollEl);
			ro.observe(track);
		}

		if (mq && mq.addEventListener) {
			mq.addEventListener('change', updateThumb);
		} else if (mq && mq.addListener) {
			mq.addListener(updateThumb);
		}

		track.addEventListener('pointerdown', function(e) {
			if (!isMobile()) return;
			if (e.target === thumb || (thumb && thumb.contains(e.target))) return;
			var rect = track.getBoundingClientRect();
			var thumbW = thumb.getBoundingClientRect().width;
			var trackW = rect.width;
			var x = e.clientX - rect.left - thumbW / 2;
			scrollFromThumbLeft(x);
			updateThumb();
		});

		thumb.addEventListener('pointerdown', function(e) {
			if (!isMobile()) return;
			e.preventDefault();
			e.stopPropagation();
			thumb.classList.add('is-dragging');
			dragStartX = e.clientX;
			var rect = track.getBoundingClientRect();
			var thumbRect = thumb.getBoundingClientRect();
			dragThumbStartLeft = thumbRect.left - rect.left;

			function onMove(ev) {
				var sw = scrollEl.scrollWidth;
				var cw = scrollEl.clientWidth;
				var maxScroll = Math.max(0, sw - cw);
				var trackW = track.getBoundingClientRect().width;
				var thumbW = thumb.getBoundingClientRect().width;
				var maxLeft = Math.max(0, trackW - thumbW);
				if (maxLeft <= 0 || maxScroll <= 0) return;
				var dx = ev.clientX - dragStartX;
				var nextLeft = dragThumbStartLeft + dx;
				nextLeft = Math.max(0, Math.min(maxLeft, nextLeft));
				var r = nextLeft / maxLeft;
				scrollEl.scrollLeft = r * maxScroll;
			}

			function onUp() {
				thumb.classList.remove('is-dragging');
				document.removeEventListener('pointermove', onMove);
				document.removeEventListener('pointerup', onUp);
				document.removeEventListener('pointercancel', onUp);
			}

			document.addEventListener('pointermove', onMove, { passive: true });
			document.addEventListener('pointerup', onUp);
			document.addEventListener('pointercancel', onUp);
		});

		requestAnimationFrame(updateThumb);
	}

	var tableScrollConfigs = [
		{
			rootSel: '[data-pricing-scroll-root]',
			scroll: '[data-pricing-table-scroll]',
			track: '[data-pricing-scrollbar-track]',
			thumb: '[data-pricing-scrollbar-thumb]',
			wrap: '.pricing-cost-scrollbar-wrap',
			hint: '.pricing-cost-swipe-hint',
		},
		{
			rootSel: '[data-comparison-scroll-root]',
			scroll: '[data-comparison-table-scroll]',
			track: '[data-comparison-scrollbar-track]',
			thumb: '[data-comparison-scrollbar-thumb]',
			wrap: '.comparison-scrollbar-wrap',
			hint: '.comparison-swipe-hint',
		},
	];

	tableScrollConfigs.forEach(function(cfg) {
		document.querySelectorAll(cfg.rootSel).forEach(function(root) {
			initHorizontalTableScrollbar(root, {
				scroll: cfg.scroll,
				track: cfg.track,
				thumb: cfg.thumb,
				wrap: cfg.wrap,
				hint: cfg.hint,
			});
		});
	});

	// Форма записи (single treatment): кастомные селекты поверх нативного <select>
	(function initMlBookingCustomSelects() {
		var wraps = document.querySelectorAll('.ml-booking-form .ml-booking-cs');
		if (!wraps.length) return;

		function isPlaceholderOption(opt) {
			return opt && (!opt.value || opt.disabled);
		}

		Array.prototype.forEach.call(wraps, function(wrap) {
			var sel = wrap.querySelector('select.ml-booking-cs__native');
			var trigger = wrap.querySelector('.ml-booking-cs__trigger');
			var valueEl = wrap.querySelector('.ml-booking-cs__value');
			var list = wrap.querySelector('.ml-booking-cs__list');
			if (!sel || !trigger || !valueEl || !list) return;

			function syncDisplay() {
				var opt = sel.options[sel.selectedIndex];
				var text = opt ? opt.textContent.trim() : '';
				valueEl.textContent = text;
				if (isPlaceholderOption(opt)) {
					valueEl.classList.add('ml-booking-cs__value--placeholder');
				} else {
					valueEl.classList.remove('ml-booking-cs__value--placeholder');
				}
				Array.prototype.forEach.call(list.querySelectorAll('.ml-booking-cs__option'), function(li, i) {
					if (i === sel.selectedIndex) {
						li.setAttribute('aria-selected', 'true');
					} else {
						li.removeAttribute('aria-selected');
					}
				});
			}

			function buildOptionsOnce() {
				list.innerHTML = '';
				Array.prototype.forEach.call(sel.options, function(opt, i) {
					var li = document.createElement('li');
					li.className = 'ml-booking-cs__option';
					li.setAttribute('role', 'option');
					li.setAttribute('tabindex', '-1');
					li.textContent = opt.textContent;
					li.addEventListener('mousedown', function(e) {
						e.preventDefault();
					});
					li.addEventListener('click', function(e) {
						e.stopPropagation();
						sel.selectedIndex = i;
						sel.dispatchEvent(new Event('change', { bubbles: true }));
						syncDisplay();
						list.hidden = true;
						wrap.classList.remove('ml-booking-cs--open');
						trigger.setAttribute('aria-expanded', 'false');
					});
					list.appendChild(li);
				});
				syncDisplay();
			}

			function setOpen(open) {
				if (open) {
					Array.prototype.forEach.call(wraps, function(w) {
						if (w === wrap) return;
						var l = w.querySelector('.ml-booking-cs__list');
						var t = w.querySelector('.ml-booking-cs__trigger');
						if (l && !l.hidden) {
							l.hidden = true;
							w.classList.remove('ml-booking-cs--open');
							if (t) t.setAttribute('aria-expanded', 'false');
						}
					});
					syncDisplay();
				}
				list.hidden = !open;
				wrap.classList.toggle('ml-booking-cs--open', open);
				trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
			}

			trigger.addEventListener('click', function(e) {
				e.stopPropagation();
				setOpen(list.hidden);
			});

			trigger.addEventListener('keydown', function(e) {
				if (e.key === 'Escape') {
					if (!list.hidden) {
						setOpen(false);
						e.preventDefault();
					}
					return;
				}
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					setOpen(list.hidden);
				}
				if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
					e.preventDefault();
					if (list.hidden) setOpen(true);
				}
			});

			sel.addEventListener('change', syncDisplay);

			buildOptionsOnce();
		});

		document.addEventListener('click', function(e) {
			var t = e.target;
			Array.prototype.forEach.call(wraps, function(wrap) {
				if (wrap.contains(t)) return;
				var list = wrap.querySelector('.ml-booking-cs__list');
				var trig = wrap.querySelector('.ml-booking-cs__trigger');
				if (list && !list.hidden) {
					list.hidden = true;
					wrap.classList.remove('ml-booking-cs--open');
					if (trig) trig.setAttribute('aria-expanded', 'false');
				}
			});
		});

		document.addEventListener('keydown', function(e) {
			if (e.key !== 'Escape') return;
			Array.prototype.forEach.call(wraps, function(wrap) {
				var list = wrap.querySelector('.ml-booking-cs__list');
				var trig = wrap.querySelector('.ml-booking-cs__trigger');
				if (list && !list.hidden) {
					list.hidden = true;
					wrap.classList.remove('ml-booking-cs--open');
					if (trig) trig.setAttribute('aria-expanded', 'false');
				}
			});
		});
	})();

	// Форма записи: кастомная валидация + AJAX + спиннер
	(function initMlBookingAjaxForm() {
		if (typeof mlBookingAjax === 'undefined') return;
		var form = document.getElementById('ml-booking-form');
		if (!form) return;

		var feedback = document.getElementById('ml-booking-feedback');
		var btn = form.querySelector('.ml-booking-submit');
		var labelEl = form.querySelector('.ml-booking-submit__label');
		if (!btn || !labelEl) return;

		var labelDefault = labelEl.textContent.trim();

		function clearMlBookingErrors() {
			form.querySelectorAll('.ml-booking-field--error').forEach(function(el) {
				el.classList.remove('ml-booking-field--error');
			});
			var pl = form.querySelector('.ml-booking-privacy');
			if (pl) pl.classList.remove('ml-booking-privacy--error');
		}

		function hideFeedback() {
			if (!feedback) return;
			feedback.setAttribute('hidden', '');
			feedback.textContent = '';
			feedback.className = 'ml-booking-feedback mb-8 w-full max-w-full rounded-md border px-4 py-3 text-[15px] font-sans text-[#F4EFE8]';
			feedback.removeAttribute('role');
		}

		function showFeedback(message, kind) {
			if (!feedback) return;
			feedback.removeAttribute('hidden');
			feedback.textContent = message;
			feedback.setAttribute('role', kind === 'error' || kind === 'privacy' ? 'alert' : 'status');
			feedback.className =
				'ml-booking-feedback mb-8 w-full max-w-full rounded-md border px-4 py-3 text-[15px] font-sans text-[#F4EFE8] ' +
				(kind === 'success'
					? 'ml-booking-feedback--success'
					: kind === 'privacy'
						? 'ml-booking-feedback--privacy'
						: 'ml-booking-feedback--error');
			feedback.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
		}

		function setLoading(loading) {
			if (loading) {
				btn.classList.add('is-loading');
				btn.disabled = true;
				btn.setAttribute('aria-busy', 'true');
				labelEl.textContent = mlBookingAjax.i18n.sending || '…';
			} else {
				btn.classList.remove('is-loading');
				btn.disabled = false;
				btn.removeAttribute('aria-busy');
				labelEl.textContent = labelDefault;
			}
		}

		/**
		 * @returns {{ ok: boolean, privacyOnly: boolean }}
		 */
		function validateMlBookingForm() {
			clearMlBookingErrors();
			var fieldErrorCount = 0;
			var privacyError = false;
			var firstFocus = null;

			function markField(wrap, focusEl) {
				fieldErrorCount++;
				if (wrap) wrap.classList.add('ml-booking-field--error');
				if (focusEl && !firstFocus) firstFocus = focusEl;
			}

			function reqText(selector) {
				var el = form.querySelector(selector);
				if (!el || !String(el.value || '').trim()) {
					markField(el ? el.closest('.ml-booking-field') : null, el || null);
				}
			}

			reqText('[name="ml_booking_first_name"]');
			reqText('[name="ml_booking_last_name"]');

			var email = form.querySelector('[name="ml_booking_email"]');
			if (!email || !String(email.value || '').trim() || !email.validity.valid) {
				markField(email ? email.closest('.ml-booking-field') : null, email || null);
			}

			function reqSelect(name) {
				var sel = form.querySelector('[name="' + name + '"]');
				if (!sel || !String(sel.value || '').trim()) {
					var wrap = sel ? sel.closest('.ml-booking-field') : null;
					var trig = wrap ? wrap.querySelector('.ml-booking-cs__trigger') : null;
					markField(wrap, trig || sel || null);
				}
			}

			reqSelect('ml_booking_city');
			reqSelect('ml_booking_doctor');
			reqSelect('ml_booking_service');
			reqSelect('ml_booking_time');

			var dateEl = form.querySelector('[name="ml_booking_date"]');
			if (!dateEl || !String(dateEl.value || '').trim()) {
				markField(dateEl ? dateEl.closest('.ml-booking-field') : null, dateEl || null);
			}

			var privacy = form.querySelector('#ml-booking-privacy');
			var privacyLabel = form.querySelector('.ml-booking-privacy');
			if (privacy && !privacy.checked) {
				privacyError = true;
				if (privacyLabel) privacyLabel.classList.add('ml-booking-privacy--error');
				if (!firstFocus) firstFocus = privacy;
			}

			var hasErrors = fieldErrorCount > 0 || privacyError;
			if (hasErrors && firstFocus) {
				try {
					firstFocus.focus({ preventScroll: true });
				} catch (err) {}
				firstFocus.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
			}

			return {
				ok: !hasErrors,
				privacyOnly: privacyError && fieldErrorCount === 0,
			};
		}

		form.addEventListener('input', function(e) {
			var t = e.target;
			if (t && t.matches && t.matches('.ml-booking-input')) {
				var w = t.closest('.ml-booking-field');
				if (w) w.classList.remove('ml-booking-field--error');
			}
		});

		form.addEventListener('change', function(e) {
			var t = e.target;
			if (!t || !t.matches) return;
			if (t.matches('.ml-booking-cs__native')) {
				var w = t.closest('.ml-booking-field');
				if (w) w.classList.remove('ml-booking-field--error');
			}
			if (t.matches('#ml-booking-privacy') || t.name === 'ml_booking_privacy') {
				var pl = form.querySelector('.ml-booking-privacy');
				if (pl) pl.classList.remove('ml-booking-privacy--error');
			}
		});

		form.addEventListener('submit', function(e) {
			e.preventDefault();

			var v = validateMlBookingForm();
			if (!v.ok) {
				var msg;
				if (v.privacyOnly && mlBookingAjax.i18n.privacyRequired) {
					msg = mlBookingAjax.i18n.privacyRequired;
					showFeedback(msg, 'privacy');
				} else {
					msg = mlBookingAjax.i18n.formInvalid || mlBookingAjax.i18n.privacyRequired;
					showFeedback(msg, 'error');
				}
				return;
			}

			hideFeedback();

			var fd = new FormData(form);
			fd.set('action', 'ml_booking');

			setLoading(true);

			fetch(mlBookingAjax.url, {
				method: 'POST',
				body: fd,
				credentials: 'same-origin',
			})
				.then(function(res) {
					return res.json().catch(function() {
						throw new Error('json');
					});
				})
				.then(function(data) {
					setLoading(false);
					if (data.success && data.data && data.data.message) {
						showFeedback(data.data.message, 'success');
						clearMlBookingErrors();
						form.reset();
						form.querySelectorAll('select.ml-booking-cs__native').forEach(function(sel) {
							sel.dispatchEvent(new Event('change', { bubbles: true }));
						});
						return;
					}
					var msg =
						data.data && data.data.message
							? data.data.message
							: mlBookingAjax.i18n.networkError || '';
					var errCode = data.data && data.data.code ? data.data.code : '';
					var kind = errCode === 'privacy' ? 'privacy' : 'error';
					showFeedback(msg || (mlBookingAjax.i18n.networkError || ''), kind);
				})
				.catch(function() {
					setLoading(false);
					showFeedback(mlBookingAjax.i18n.networkError || '', 'error');
				});
		});
	})();
});
