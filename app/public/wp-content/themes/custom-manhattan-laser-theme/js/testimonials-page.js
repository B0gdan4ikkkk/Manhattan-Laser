(function () {
	function closeAllTestimonialFilters() {
		document.querySelectorAll('[data-testimonials-filter-dd].is-open').forEach(function (wrap) {
			var btn = wrap.querySelector('.testimonials-filter-dd__btn');
			var panel = wrap.querySelector('.testimonials-filter-dd__panel');
			if (panel) {
				panel.setAttribute('hidden', '');
			}
			if (btn) {
				btn.setAttribute('aria-expanded', 'false');
			}
			wrap.classList.remove('is-open');
		});
	}

	function createTestimonialsLoadingOverlay() {
		var wrap = document.createElement('div');
		wrap.className =
			'testimonials-loading-overlay pointer-events-none absolute inset-0 z-20 flex flex-col items-center justify-center gap-3 rounded-lg bg-[#12100E]/85 backdrop-blur-[2px]';
		wrap.setAttribute('role', 'status');
		wrap.setAttribute('aria-live', 'polite');
		var spin = document.createElement('span');
		spin.className =
			'inline-block h-9 w-9 animate-spin rounded-full border-2 border-[#f5f5f0]/20 border-t-[#f5f5f0]';
		spin.setAttribute('aria-hidden', 'true');
		var txt = document.createElement('span');
		txt.className = 'font-sans text-[14px] text-[#f5f5f0]/90';
		txt.textContent =
			window.mlTestimonials.i18n && window.mlTestimonials.i18n.loading
				? window.mlTestimonials.i18n.loading
				: 'Loading…';
		wrap.appendChild(spin);
		wrap.appendChild(txt);
		return wrap;
	}

	function fetchTestimonialsFilter(form) {
		if (!form || typeof window.mlTestimonials === 'undefined') {
			return;
		}
		var col = document.getElementById('testimonials-reviews-column');
		if (!col || !window.mlTestimonials.filterAction) {
			form.submit();
			return;
		}
		var fdService = form.querySelector('input[name="filter_service"]');
		var fdDoctor = form.querySelector('input[name="filter_doctor"]');
		var fd = new FormData();
		fd.append('action', window.mlTestimonials.filterAction);
		fd.append('nonce', window.mlTestimonials.nonce);
		fd.append('filter_service', fdService ? String(fdService.value) : '0');
		fd.append('filter_doctor', fdDoctor ? String(fdDoctor.value) : '0');

		col.setAttribute('aria-busy', 'true');
		var loadingOverlay = createTestimonialsLoadingOverlay();
		col.appendChild(loadingOverlay);

		fetch(window.mlTestimonials.ajaxUrl, {
			method: 'POST',
			body: fd,
			credentials: 'same-origin'
		})
			.then(function (r) {
				return r.json();
			})
			.then(function (data) {
				if (!data || !data.success || typeof data.content_html !== 'string') {
					throw new Error('fail');
				}
				col.innerHTML = data.content_html;
				window.mlTestimonials.filterDoctor = parseInt(String(data.filter_doctor), 10) || 0;
				window.mlTestimonials.filterService = parseInt(String(data.filter_service), 10) || 0;
				window.mlTestimonials.hasMore = !!data.has_more;
				window.mlTestimonials.nextPage = 2;

				if (window.mlTestimonials.pageUrl) {
					try {
						var u = new URL(window.mlTestimonials.pageUrl, window.location.origin);
						u.searchParams.set('filter_doctor', String(window.mlTestimonials.filterDoctor));
						u.searchParams.set('filter_service', String(window.mlTestimonials.filterService));
						window.history.replaceState({}, '', u);
					} catch (err) {}
				}
			})
			.catch(function () {
				form.submit();
			})
			.finally(function () {
				if (loadingOverlay && loadingOverlay.parentNode) {
					loadingOverlay.parentNode.removeChild(loadingOverlay);
				}
				col.removeAttribute('aria-busy');
			});
	}

	function initTestimonialsFilterDropdowns() {
		document.querySelectorAll('[data-testimonials-filter-dd]').forEach(function (wrap) {
			var btn = wrap.querySelector('.testimonials-filter-dd__btn');
			var panel = wrap.querySelector('.testimonials-filter-dd__panel');
			var inputName = wrap.getAttribute('data-filter-input');
			var form = wrap.closest('form');
			var hidden = form && inputName ? form.querySelector('input[name="' + inputName + '"]') : null;
			if (!btn || !panel || !form || !hidden) {
				return;
			}

			btn.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var willOpen = !wrap.classList.contains('is-open');
				closeAllTestimonialFilters();
				if (willOpen) {
					panel.removeAttribute('hidden');
					btn.setAttribute('aria-expanded', 'true');
					wrap.classList.add('is-open');
				}
			});

			panel.querySelectorAll('.testimonials-filter-dd__option').forEach(function (opt) {
				opt.addEventListener('click', function (e) {
					e.preventDefault();
					e.stopPropagation();
					var val = opt.getAttribute('data-value');
					if (val === null) {
						return;
					}
					hidden.value = val;
					var labelEl = wrap.querySelector('.testimonials-filter-dd__text');
					if (labelEl) {
						labelEl.textContent = opt.textContent.trim();
					}
					panel.querySelectorAll('.testimonials-filter-dd__option').forEach(function (o) {
						o.removeAttribute('aria-selected');
					});
					opt.setAttribute('aria-selected', 'true');
					closeAllTestimonialFilters();
					fetchTestimonialsFilter(form);
				});
			});
		});

		document.addEventListener('click', function (e) {
			if (e.target.closest('[data-testimonials-filter-dd]')) {
				return;
			}
			closeAllTestimonialFilters();
		});
	}

	initTestimonialsFilterDropdowns();

	function closeAllReviewModalDropdowns() {
		document.querySelectorAll('[data-review-modal-dd].is-open').forEach(function (wrap) {
			var btn = wrap.querySelector('.testimonials-review-modal-dd__btn');
			var panel = wrap.querySelector('.testimonials-review-modal-dd__panel');
			if (panel) {
				panel.setAttribute('hidden', '');
			}
			if (btn) {
				btn.setAttribute('aria-expanded', 'false');
			}
			wrap.classList.remove('is-open');
		});
	}

	function resetReviewModalDropdowns(form) {
		if (!form) {
			return;
		}
		form.querySelectorAll('[data-review-modal-dd]').forEach(function (wrap) {
			var ph = wrap.getAttribute('data-placeholder') || '';
			var label = wrap.querySelector('.testimonials-review-modal-dd__btn-text');
			var hidden = wrap.querySelector('input[type="hidden"]');
			if (label) {
				label.textContent = ph;
			}
			if (hidden) {
				hidden.value = '';
			}
			wrap.querySelectorAll('.testimonials-review-modal-dd__option').forEach(function (opt) {
				opt.removeAttribute('aria-selected');
			});
		});
		closeAllReviewModalDropdowns();
	}

	function initReviewModalDropdowns() {
		document.querySelectorAll('[data-review-modal-dd]').forEach(function (wrap) {
			var btn = wrap.querySelector('.testimonials-review-modal-dd__btn');
			var panel = wrap.querySelector('.testimonials-review-modal-dd__panel');
			var hidden = wrap.querySelector('input[type="hidden"]');
			var labelEl = wrap.querySelector('.testimonials-review-modal-dd__btn-text');
			if (!btn || !panel || !hidden) {
				return;
			}

			function syncAriaSelected() {
				var v = hidden.value;
				panel.querySelectorAll('.testimonials-review-modal-dd__option').forEach(function (opt) {
					var ov = opt.getAttribute('data-value');
					if (ov !== null && String(ov) === String(v) && v !== '') {
						opt.setAttribute('aria-selected', 'true');
					} else {
						opt.removeAttribute('aria-selected');
					}
				});
			}

			btn.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var willOpen = !wrap.classList.contains('is-open');
				closeAllReviewModalDropdowns();
				if (willOpen) {
					panel.removeAttribute('hidden');
					btn.setAttribute('aria-expanded', 'true');
					wrap.classList.add('is-open');
				}
			});

			panel.querySelectorAll('.testimonials-review-modal-dd__option').forEach(function (opt) {
				opt.addEventListener('click', function (e) {
					e.preventDefault();
					e.stopPropagation();
					var val = opt.getAttribute('data-value');
					if (val === null) {
						return;
					}
					hidden.value = val;
					if (labelEl) {
						labelEl.textContent = opt.textContent.trim();
					}
					syncAriaSelected();
					closeAllReviewModalDropdowns();
				});
			});
		});

		document.addEventListener('click', function (e) {
			if (e.target.closest('[data-review-modal-dd]')) {
				return;
			}
			closeAllReviewModalDropdowns();
		});
	}

	initReviewModalDropdowns();

	function initReadMoreReviews() {
		var root = document.getElementById('main-content');
		if (!root || typeof window.mlTestimonials === 'undefined') {
			return;
		}
		root.addEventListener('click', function (e) {
			var btn = e.target.closest('[data-testimonials-read-more]');
			if (!btn) {
				return;
			}
			var list = document.getElementById('testimonials-reviews-list');
			if (!list) {
				return;
			}
			var hasMore = !!window.mlTestimonials.hasMore;
			var nextPage = parseInt(String(window.mlTestimonials.nextPage), 10);
			if (isNaN(nextPage)) {
				nextPage = 2;
			}
			if (!hasMore || btn.getAttribute('aria-busy') === 'true') {
				return;
			}
			btn.setAttribute('aria-busy', 'true');
			var label = btn.querySelector('.testimonials-read-more__label');
			var orig = label ? label.textContent : '';
			if (label && window.mlTestimonials.i18n && window.mlTestimonials.i18n.loading) {
				label.textContent = window.mlTestimonials.i18n.loading;
			}
			var spinner = document.createElement('span');
			spinner.className =
				'testimonials-read-more__spinner mr-2 inline-block h-4 w-4 shrink-0 animate-spin rounded-full border-2 border-[#FBF5E7]/30 border-t-[#FBF5E7]';
			spinner.setAttribute('aria-hidden', 'true');
			btn.insertBefore(spinner, btn.firstChild);
			list.classList.add('opacity-50', 'pointer-events-none', 'transition-opacity', 'duration-200');

			var fd = new FormData();
			fd.append('action', window.mlTestimonials.action);
			fd.append('nonce', window.mlTestimonials.nonce);
			fd.append('page', String(nextPage));
			fd.append('filter_doctor', String(window.mlTestimonials.filterDoctor));
			fd.append('filter_service', String(window.mlTestimonials.filterService));

			fetch(window.mlTestimonials.ajaxUrl, {
				method: 'POST',
				body: fd,
				credentials: 'same-origin'
			})
				.then(function (r) {
					return r.json();
				})
				.then(function (data) {
					if (!data || !data.success) {
						throw new Error('fail');
					}
					if (data.html) {
						list.insertAdjacentHTML('beforeend', data.html);
					}
					window.mlTestimonials.hasMore = !!data.has_more;
					window.mlTestimonials.nextPage = nextPage + 1;
					if (!window.mlTestimonials.hasMore) {
						btn.style.display = 'none';
					}
					btn.setAttribute('aria-busy', 'false');
					if (label) {
						label.textContent = orig;
					}
					var sp = btn.querySelector('.testimonials-read-more__spinner');
					if (sp) {
						sp.remove();
					}
					list.classList.remove('opacity-50', 'pointer-events-none', 'transition-opacity', 'duration-200');
				})
				.catch(function () {
					btn.setAttribute('aria-busy', 'false');
					if (label) {
						label.textContent = orig;
					}
					var sp2 = btn.querySelector('.testimonials-read-more__spinner');
					if (sp2) {
						sp2.remove();
					}
					list.classList.remove('opacity-50', 'pointer-events-none', 'transition-opacity', 'duration-200');
				});
		});
	}

	initReadMoreReviews();

	function paintStars(wrap, value) {
		var hidden = wrap.querySelector('input[type="hidden"][name="ml_review_rating"]');
		if (!hidden) return;
		hidden.value = value > 0 ? String(value) : '';
		wrap.querySelectorAll('.ml-review-star').forEach(function (btn) {
			var n = parseInt(btn.getAttribute('data-star'), 10);
			btn.classList.toggle('is-off', value < 1 || n > value);
		});
	}

	document.querySelectorAll('[data-ml-review-stars]').forEach(function (wrap) {
		var hidden = wrap.querySelector('input[type="hidden"][name="ml_review_rating"]');
		var initial = hidden && hidden.value !== '' ? parseInt(hidden.value, 10) : 0;
		if (isNaN(initial)) initial = 0;
		if (initial > 5) initial = 5;
		if (initial < 0) initial = 0;
		paintStars(wrap, initial);
		wrap.querySelectorAll('.ml-review-star').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var v = parseInt(btn.getAttribute('data-star'), 10);
				if (!isNaN(v)) paintStars(wrap, v);
			});
		});
	});

	var modal = document.getElementById('testimonials-review-modal');
	var panel = modal ? modal.querySelector('.testimonials-review-modal__panel') : null;

	function openReviewModal() {
		if (!modal) return;
		closeAllReviewModalDropdowns();
		var modalNotice = document.getElementById('testimonials-review-modal-notice');
		if (modalNotice) {
			modalNotice.classList.add('hidden');
			modalNotice.textContent = '';
			modalNotice.className =
				'mt-6 hidden rounded-[16px] px-4 py-3 text-center font-sans text-[14px] leading-snug';
		}
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('testimonials-review-modal-open');
		if (panel) {
			try {
				panel.focus();
			} catch (e) {}
		}
	}

	function closeReviewModal() {
		if (!modal) return;
		closeAllReviewModalDropdowns();
		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('testimonials-review-modal-open');
	}

	document.querySelectorAll('[data-open-review-modal]').forEach(function (el) {
		el.addEventListener('click', function (e) {
			e.preventDefault();
			openReviewModal();
		});
	});

	document.querySelectorAll('[data-close-review-modal]').forEach(function (el) {
		el.addEventListener('click', function (e) {
			e.preventDefault();
			closeReviewModal();
		});
	});

	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') {
			return;
		}
		if (modal && modal.classList.contains('is-open')) {
			closeReviewModal();
			return;
		}
		closeAllTestimonialFilters();
	});

	function initReviewFormAjax() {
		var form = document.getElementById('testimonials-review-form');
		if (!form || typeof window.mlTestimonials === 'undefined') {
			return;
		}
		var pageNotice = document.getElementById('testimonials-page-review-notice');
		var modalNotice = document.getElementById('testimonials-review-modal-notice');
		var submitBtn = form.querySelector('.testimonials-review-form__submit');
		var submitLabel = form.querySelector('.testimonials-review-form__submit-label');
		var starsWrap = form.querySelector('[data-ml-review-stars]');

		function showModalMessage(text, type) {
			if (!modalNotice || !text) {
				return;
			}
			modalNotice.classList.remove('hidden');
			modalNotice.textContent = '';
			var p = document.createElement('p');
			p.className =
				type === 'warning'
					? 'rounded-[16px] border border-amber-200/80 bg-[#fffbeb] px-4 py-3 text-[14px] text-amber-950'
					: 'rounded-[16px] border border-red-200/80 bg-[#fef2f2] px-4 py-3 text-[14px] text-red-900';
			p.textContent = text;
			modalNotice.appendChild(p);
			modalNotice.setAttribute('role', 'alert');
		}

		function setPageSuccessMessage(text) {
			if (!pageNotice || !text) {
				return;
			}
			pageNotice.classList.remove('hidden');
			pageNotice.textContent = '';
			var p = document.createElement('p');
			p.className =
				'rounded-[20px] border border-[#f5f5f0]/20 bg-[#2e2621] px-4 py-3 text-center font-sans text-[14px] text-[#f5f5f0]';
			p.textContent = text;
			pageNotice.appendChild(p);
			pageNotice.setAttribute('role', 'status');
		}

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			if (modalNotice) {
				modalNotice.classList.add('hidden');
				modalNotice.textContent = '';
			}

			var fd = new FormData(form);
			if (!fd.get('action') && window.mlTestimonials.reviewSubmitAction) {
				fd.append('action', window.mlTestimonials.reviewSubmitAction);
			}

			if (submitBtn) {
				submitBtn.disabled = true;
			}
			var origLabel = submitLabel ? submitLabel.textContent : '';
			if (submitLabel && window.mlTestimonials.i18n && window.mlTestimonials.i18n.sending) {
				submitLabel.textContent = window.mlTestimonials.i18n.sending;
			}

			fetch(window.mlTestimonials.ajaxUrl, {
				method: 'POST',
				body: fd,
				credentials: 'same-origin'
			})
				.then(function (r) {
					return r.json();
				})
				.then(function (data) {
					if (!data) {
						throw new Error('empty');
					}
					if (data.silent) {
						return;
					}
					if (data.success) {
						setPageSuccessMessage(data.message || '');
						closeReviewModal();
						form.reset();
						resetReviewModalDropdowns(form);
						if (starsWrap) {
							paintStars(starsWrap, 0);
						}
						var ratingHidden = form.querySelector('#ml_review_rating_val');
						if (ratingHidden) {
							ratingHidden.value = '';
						}
						if (pageNotice) {
							try {
								pageNotice.scrollIntoView({ behavior: 'smooth', block: 'start' });
							} catch (err) {}
						}
						return;
					}
					if (data.message) {
						showModalMessage(data.message, data.message_type === 'warning' ? 'warning' : 'error');
					}
				})
				.catch(function () {
					var errMsg =
						window.mlTestimonials.i18n && window.mlTestimonials.i18n.networkError
							? window.mlTestimonials.i18n.networkError
							: '';
					showModalMessage(errMsg || 'Error', 'error');
				})
				.finally(function () {
					if (submitBtn) {
						submitBtn.disabled = false;
					}
					if (submitLabel) {
						submitLabel.textContent = origLabel;
					}
				});
		});
	}

	initReviewFormAjax();
})();
