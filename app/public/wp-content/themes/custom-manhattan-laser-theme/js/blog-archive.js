(function () {
	'use strict';

	var cfg = typeof mlBlogArchive === 'undefined' ? null : mlBlogArchive;
	if (!cfg || !cfg.ajaxUrl || !cfg.pageId) {
		return;
	}

	var root = document.getElementById('blog-page-root');
	var main = document.getElementById('blog-page-main');
	var form = document.getElementById('blog-page-search-form');
	if (!root || !main) {
		return;
	}

	function getSearchInput() {
		return document.getElementById('blog-search-field');
	}

	function getActiveCat() {
		var active = document.querySelector('.js-blog-filter-link.blog-page__filter-link--active');
		if (!active) {
			return '0';
		}
		return active.getAttribute('data-cat') || '0';
	}

	function setActiveCat(catId) {
		catId = String(catId);
		document.querySelectorAll('.js-blog-filter-link').forEach(function (a) {
			var on = a.getAttribute('data-cat') === catId;
			a.classList.toggle('blog-page__filter-link--active', on);
		});
	}

	function buildListUrl(catId, s, paged) {
		var u = new URL(cfg.blogUrl, window.location.origin);
		u.searchParams.delete('cat');
		u.searchParams.delete('s');
		u.searchParams.delete('paged');
		var n = parseInt(catId, 10);
		if (n > 0) {
			u.searchParams.set('cat', String(n));
		}
		if (s && String(s).trim() !== '') {
			u.searchParams.set('s', String(s).trim());
		}
		paged = parseInt(paged, 10) || 1;
		if (paged > 1) {
			u.searchParams.set('paged', String(paged));
		}
		return u.pathname + u.search + u.hash;
	}

	function updateFilterHrefs(s) {
		document.querySelectorAll('.js-blog-filter-link').forEach(function (a) {
			var cid = a.getAttribute('data-cat') || '0';
			a.setAttribute('href', buildListUrl(cid, s, 1));
		});
	}

	function getPagedFromHref(href) {
		try {
			var u = new URL(href, window.location.origin);
			var p = u.searchParams.get('paged') || u.searchParams.get('page');
			if (p) {
				return Math.max(1, parseInt(p, 10) || 1);
			}
			var path = u.pathname;
			var m = path.match(/\/page\/(\d+)\/?$/);
			if (m) {
				return Math.max(1, parseInt(m[1], 10) || 1);
			}
		} catch (e) {}
		return 1;
	}

	function fetchPosts(opts) {
		opts = opts || {};
		var pushUrl = opts.pushUrl !== false;
		var cat = opts.cat != null ? String(opts.cat) : getActiveCat();
		var s = opts.s != null ? opts.s : (getSearchInput() ? getSearchInput().value : '');
		var paged = opts.paged != null ? parseInt(opts.paged, 10) : 1;
		if (isNaN(paged) || paged < 1) {
			paged = 1;
		}

		main.setAttribute('aria-busy', 'true');
		main.classList.add('blog-page__main--loading');

		var body = new URLSearchParams();
		body.set('action', 'ml_blog_posts');
		body.set('nonce', cfg.nonce);
		body.set('page_id', String(cfg.pageId));
		body.set('cat', String(Math.max(0, parseInt(cat, 10) || 0)));
		body.set('s', s ? String(s).trim() : '');
		body.set('paged', String(paged));

		return fetch(cfg.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
			},
			body: body.toString(),
		})
			.then(function (r) {
				return r.json();
			})
			.then(function (data) {
				if (!data || !data.success || !data.data || typeof data.data.html !== 'string') {
					throw new Error('Bad response');
				}
				main.innerHTML = data.data.html;
				if (pushUrl) {
					window.history.pushState(
						{ mlBlog: true, cat: cat, s: s, paged: paged },
						'',
						buildListUrl(cat, s, paged)
					);
				}
				setActiveCat(cat);
				updateFilterHrefs(s ? String(s).trim() : '');
				if (getSearchInput() && opts.s != null) {
					getSearchInput().value = s;
				}
			})
			.catch(function () {
				window.location.reload();
			})
			.finally(function () {
				main.removeAttribute('aria-busy');
				main.classList.remove('blog-page__main--loading');
			});
	}

	if (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var s = getSearchInput() ? getSearchInput().value : '';
			fetchPosts({ cat: getActiveCat(), s: s, paged: 1 });
		});
	}

	root.addEventListener('click', function (e) {
		var a = e.target.closest('.js-blog-filter-link');
		if (!a || !root.contains(a)) {
			return;
		}
		e.preventDefault();
		var cat = a.getAttribute('data-cat') || '0';
		var s = getSearchInput() ? getSearchInput().value.trim() : '';
		fetchPosts({ cat: cat, s: s, paged: 1 });
	});

	main.addEventListener('click', function (e) {
		var a = e.target.closest('.blog-page__pagination a');
		if (!a || !main.contains(a)) {
			return;
		}
		var href = a.getAttribute('href');
		if (!href || href === '#' || a.classList.contains('disabled')) {
			return;
		}
		e.preventDefault();
		var paged = getPagedFromHref(href);
		var s = getSearchInput() ? getSearchInput().value.trim() : '';
		fetchPosts({ cat: getActiveCat(), s: s, paged: paged });
	});

	window.addEventListener('popstate', function () {
		var u = new URL(window.location.href);
		var cat = u.searchParams.get('cat') || '0';
		var s = u.searchParams.get('s') || '';
		var paged = Math.max(1, parseInt(u.searchParams.get('paged') || '1', 10) || 1);
		var pathP = window.location.pathname.match(/\/page\/(\d+)\/?$/);
		if (pathP) {
			paged = Math.max(1, parseInt(pathP[1], 10) || 1);
		}
		if (getSearchInput()) {
			getSearchInput().value = s;
		}
		fetchPosts({ cat: cat, s: s, paged: paged, pushUrl: false });
	});
})();
