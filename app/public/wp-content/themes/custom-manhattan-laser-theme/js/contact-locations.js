(function () {
	'use strict';

	var LOCATIONS = {
		'upper-east-side': {
			locationTitle: 'Upper East Side',
			address: '808 Lexington Ave, Fl 2,\nNew York, NY 10065',
			mapAddress: '808 Lexington Ave, Fl 2, New York, NY 10065',
			phone: '212-334-2470',
			phoneHref: 'tel:+12123342470',
			email: 'manhattanlaser123@gmail.com',
			emailHref: 'mailto:manhattanlaser123@gmail.com',
			company: 'Manhattan Laser',
			hours: 'Monday – Friday,\n9:00 AM – 6:00 PM',
		},
		midtown: {
			locationTitle: 'Midtown',
			address: '56 E 34th St, 2nd Floor,\nNew York, NY 10016',
			mapAddress: '56 E 34th St, 2nd Floor, New York, NY 10016',
			phone: '212-334-2470',
			phoneHref: 'tel:+12123342470',
			email: 'manhattanlaser123@gmail.com',
			emailHref: 'mailto:manhattanlaser123@gmail.com',
			company: 'Manhattan Laser',
			hours: 'Monday – Friday,\n9:00 AM – 6:00 PM',
		},
		brooklyn: {
			locationTitle: 'Brooklyn',
			address: '450 Atlantic Ave, 4th Floor,\nBrooklyn, NY 11217',
			mapAddress: '450 Atlantic Ave, 4th Floor, Brooklyn, NY 11217',
			phone: '212-334-2470',
			phoneHref: 'tel:+12123342470',
			email: 'manhattanlaser123@gmail.com',
			emailHref: 'mailto:manhattanlaser123@gmail.com',
			company: 'Manhattan Laser',
			hours: 'Monday – Friday,\n9:00 AM – 6:00 PM',
		},
	};

	function setText(root, sel, text) {
		var el = root.querySelector(sel);
		if (el) el.textContent = text;
	}

	function applyLocation(root, key) {
		var data = LOCATIONS[key];
		if (!data) return;

		setText(root, '[data-contact-field="locationTitle"]', data.locationTitle);
		setText(root, '[data-contact-field="address"]', data.address);
		setText(root, '[data-contact-field="company"]', data.company);
		setText(root, '[data-contact-field="hours"]', data.hours);

		var phone = root.querySelector('.contact-loc-phone');
		if (phone) {
			phone.textContent = data.phone;
			phone.setAttribute('href', data.phoneHref || 'tel:');
		}

		var email = root.querySelector('.contact-loc-email');
		if (email) {
			email.textContent = data.email;
			email.setAttribute('href', data.emailHref || 'mailto:');
		}

		var mapAddr = data.mapAddress || '';
		if (mapAddr) {
			if (typeof window.mlContactMapGoToAddress === 'function') {
				window.mlContactMapGoToAddress(mapAddr);
			} else {
				window._mlContactMapPendingAddress = mapAddr;
			}
		}
	}

	function setActiveTab(root, activeBtn) {
		var tabs = root.querySelectorAll('.contact-loc-tab');
		tabs.forEach(function (btn) {
			var on = btn === activeBtn;
			btn.classList.toggle('is-active', on);
			btn.setAttribute('aria-selected', on ? 'true' : 'false');
			if (on) {
				btn.classList.remove('text-[#F4EFE880]');
				btn.classList.add('text-[#F4EFE8]');
			} else {
				btn.classList.add('text-[#F4EFE880]');
				btn.classList.remove('text-[#F4EFE8]');
			}
		});

		var panel = root.querySelector('[data-contact-panel]');
		if (panel && activeBtn.id) {
			panel.setAttribute('aria-labelledby', activeBtn.id);
		}
	}

	function init(root) {
		var tabs = root.querySelectorAll('.contact-loc-tab[data-contact-loc]');
		if (!tabs.length) return;

		var first = tabs[0];
		var firstKey = first.getAttribute('data-contact-loc');
		applyLocation(root, firstKey);
		setActiveTab(root, first);

		tabs.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var key = btn.getAttribute('data-contact-loc');
				if (!key || !LOCATIONS[key]) return;
				setActiveTab(root, btn);
				applyLocation(root, key);
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('[data-contact-locations]').forEach(init);
	});
})();
