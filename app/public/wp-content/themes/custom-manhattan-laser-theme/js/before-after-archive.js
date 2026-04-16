(function () {
	if (typeof window.mlBeforeAfterArchive === 'undefined') {
		return;
	}

	var cfg = window.mlBeforeAfterArchive;
	var loadSliderInFlight = false;

	function closeFilterDetails(wrap) {
		if (!wrap || wrap.tagName !== 'DETAILS') return;
		wrap.open = false;
	}

	function setArchiveLoading(on) {
		var mount = document.getElementById('ml-archive-ba-slider-mount');
		var root = document.getElementById('ml-archive-ba-slider-root');
		var loadingEl = document.getElementById('ml-ba-archive-loading');
		var busy = on ? 'true' : 'false';
		if (mount) {
			mount.setAttribute('aria-busy', busy);
			mount.style.opacity = on ? '0.35' : '';
		}
		if (root) {
			root.setAttribute('aria-busy', busy);
		}
		if (loadingEl) {
			if (on) {
				loadingEl.classList.remove('hidden');
				loadingEl.classList.add('flex');
				loadingEl.style.display = 'flex';
				loadingEl.setAttribute('aria-hidden', 'false');
			} else {
				loadingEl.classList.add('hidden');
				loadingEl.classList.remove('flex');
				loadingEl.style.display = '';
				loadingEl.setAttribute('aria-hidden', 'true');
			}
		}
	}

	function initArchiveFilter() {
		var wrap = document.querySelector('[data-ml-ba-filter]');
		if (!wrap) return;

		wrap.querySelectorAll('.ml-ba-filter__opt').forEach(function (opt) {
			opt.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var id = opt.getAttribute('data-treatment-id');
				if (id === null) return;
				var labelEl = wrap.querySelector('.ml-ba-filter__label');
				if (labelEl) {
					labelEl.textContent = opt.textContent.trim();
				}
				wrap.querySelectorAll('.ml-ba-filter__opt').forEach(function (o) {
					o.removeAttribute('aria-selected');
					o.classList.remove('underline', 'decoration-[#F4EFE8]', 'underline-offset-4');
				});
				opt.setAttribute('aria-selected', 'true');
				opt.classList.add('underline', 'decoration-[#F4EFE8]', 'underline-offset-4');
				closeFilterDetails(wrap);
				loadSlider(parseInt(String(id), 10) || 0);
			});
		});

		document.addEventListener('click', function (e) {
			if (wrap.contains(e.target)) return;
			closeFilterDetails(wrap);
		});
	}

	function loadSlider(treatmentId) {
		var mount = document.getElementById('ml-archive-ba-slider-mount');
		var root = document.getElementById('ml-archive-ba-slider-root');
		if (!mount || !cfg.ajaxUrl || !cfg.action || !cfg.nonce) return;
		if (loadSliderInFlight) return;
		loadSliderInFlight = true;

		setArchiveLoading(true);

		var fd = new FormData();
		fd.append('action', cfg.action);
		fd.append('nonce', cfg.nonce);
		fd.append('treatment_id', String(treatmentId));

		var htmlApplied = false;

		fetch(cfg.ajaxUrl, {
			method: 'POST',
			body: fd,
			credentials: 'same-origin'
		})
			.then(function (r) {
				if (!r.ok) {
					throw new Error('http');
				}
				return r.json();
			})
			.then(function (res) {
				if (!res || !res.success || !res.data || typeof res.data.html !== 'string') {
					throw new Error('fail');
				}
				mount.innerHTML = res.data.html;
				htmlApplied = true;
				if (root) {
					try {
						if (typeof window.mlInitBeforeAfterArchiveSwiper === 'function') {
							window.mlInitBeforeAfterArchiveSwiper(root);
						}
						if (typeof window.mlInitBeforeAfterCompareInContainer === 'function') {
							window.mlInitBeforeAfterCompareInContainer(root);
						}
					} catch (initErr) {
						if (typeof console !== 'undefined' && console.error) {
							console.error(initErr);
						}
					}
				}
				if (cfg.pageUrl) {
					try {
						var u = new URL(cfg.pageUrl, window.location.origin);
						if (treatmentId > 0) {
							u.searchParams.set('treatment', String(treatmentId));
						} else {
							u.searchParams.delete('treatment');
						}
						window.history.replaceState({}, '', u);
					} catch (err) {}
				}
			})
			.catch(function () {
				if (!htmlApplied) {
					try {
						var u = new URL(cfg.pageUrl || window.location.pathname, window.location.origin);
						if (treatmentId > 0) {
							u.searchParams.set('treatment', String(treatmentId));
						} else {
							u.searchParams.delete('treatment');
						}
						window.location.assign(u.toString());
					} catch (e2) {
						window.location.reload();
					}
				}
			})
			.finally(function () {
				loadSliderInFlight = false;
				setArchiveLoading(false);
			});
	}

	document.addEventListener('DOMContentLoaded', function () {
		initArchiveFilter();
	});
})();
