(function() {
	function initNeuroTimeline() {
		var root = document.querySelector('.neuro-timeline');
		if (!root) {
			return;
		}

		// Mobile layout is static by design up to 768px.
		if (window.matchMedia && window.matchMedia('(max-width: 768px)').matches) {
			return;
		}

		var items = root.querySelectorAll('.neuro-timeline__item');
		if (!items.length) {
			return;
		}

		var activeClass = 'is-active';
		var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		function clearActive() {
			root.querySelectorAll('.neuro-timeline__node.' + activeClass).forEach(function(n) {
				n.classList.remove(activeClass);
			});
			root.querySelectorAll('.neuro-timeline__node-wrapper.' + activeClass).forEach(function(w) {
				w.classList.remove(activeClass);
			});
			root.querySelectorAll('.neuro-timeline__item.' + activeClass).forEach(function(li) {
				li.classList.remove(activeClass);
			});
		}

		function setActive(node) {
			if (!node) {
				return;
			}
			var row = node.closest('.neuro-timeline__item');
			var wrap = node.closest('.neuro-timeline__node-wrapper');
			if (!row) {
				return;
			}
			clearActive();
			node.classList.add(activeClass);
			if (wrap) {
				wrap.classList.add(activeClass);
			}
			row.classList.add(activeClass);
		}

		if (reduceMotion) {
			return;
		}

		var userHasScrolled = false;
		function markUserScrolled() {
			userHasScrolled = true;
		}
		window.addEventListener('scroll', markUserScrolled, { passive: true });
		window.addEventListener('wheel', markUserScrolled, { passive: true });
		window.addEventListener('touchmove', markUserScrolled, { passive: true });

		var scheduled = false;

		function updateActiveFromViewport() {
			if (!userHasScrolled) {
				return;
			}
			var vh = window.innerHeight || document.documentElement.clientHeight || 0;
			if (vh < 1) {
				return;
			}
			var bandTop = vh * 0.34;
			var bandBot = vh * 0.66;
			var bestNode = null;
			var bestOverlap = 0;

			items.forEach(function(li) {
				var r = li.getBoundingClientRect();
				var overlap = Math.max(0, Math.min(r.bottom, bandBot) - Math.max(r.top, bandTop));
				if (overlap > bestOverlap) {
					bestOverlap = overlap;
					var n = li.querySelector('[data-neuro-node]');
					if (n) {
						bestNode = n;
					}
				}
			});

			if (bestNode && bestOverlap > 4) {
				setActive(bestNode);
			}
		}

		function onScrollOrResize() {
			if (scheduled) {
				return;
			}
			scheduled = true;
			requestAnimationFrame(function() {
				scheduled = false;
				updateActiveFromViewport();
			});
		}

		window.addEventListener('scroll', onScrollOrResize, { passive: true });
		window.addEventListener('resize', onScrollOrResize);
		if (window.visualViewport) {
			window.visualViewport.addEventListener('resize', onScrollOrResize);
		}

		var lenis = window.customManhattanLenis;
		if (lenis && typeof lenis.on === 'function') {
			lenis.on('scroll', function() {
				markUserScrolled();
				onScrollOrResize();
			});
		}

		updateActiveFromViewport();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initNeuroTimeline);
	} else {
		initNeuroTimeline();
	}
})();
