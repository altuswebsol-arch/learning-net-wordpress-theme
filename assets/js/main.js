/**
 * Learning Net theme — front-end behaviour.
 * Matches the finalized preview: mobile nav toggle (cloned links),
 * cookie banner (Accept/Reject), sticky header shadow.
 * FAQ accordions use native <details> elements and need no script.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {

		/* ---------- Mobile menu (preview behaviour) ---------- */
		var toggle = document.getElementById('menuToggle');
		var panel = document.getElementById('mobilePanel');
		var nav = document.getElementById('mobileMenuLinks');
		if (toggle && panel && nav) {
			// Clone the desktop links into the mobile panel, preserving crash-link.
			document.querySelectorAll('.nav-links > .nav-link').forEach(function (link) {
				var mobileLink = link.cloneNode(true);
				mobileLink.removeAttribute('class');
				mobileLink.className = 'nav-link' + (link.classList.contains('crash-link') ? ' crash-link' : '');
				mobileLink.removeAttribute('aria-current');
				nav.appendChild(mobileLink);
			});
			var setOpen = function (open, returnFocus) {
				toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
				panel.hidden = !open;
				if (!open && returnFocus) {
					toggle.focus();
				}
			};
			toggle.addEventListener('click', function () {
				setOpen(toggle.getAttribute('aria-expanded') !== 'true', false);
			});
			panel.addEventListener('click', function (e) {
				if (e.target.closest('a')) {
					setOpen(false, false);
				}
			});
			document.addEventListener('click', function (e) {
				if (!panel.hidden && !e.target.closest('.mobile-menu')) {
					setOpen(false, false);
				}
			});
		}

		/* ---------- Cookie banner: Accept / Reject ---------- */
		var banner = document.getElementById('cookie-banner');
		if (banner) {
			var choice = null;
			try {
				choice = window.localStorage.getItem('ln_cookie_choice');
			} catch (e) {
				choice = null;
			}
			if (!choice) {
				banner.classList.add('show');
			}
			banner.querySelectorAll('[data-cookie-choice]').forEach(function (btn) {
				btn.addEventListener('click', function () {
					try {
						window.localStorage.setItem('ln_cookie_choice', btn.getAttribute('data-cookie-choice'));
					} catch (e) {
						/* storage unavailable — still hide the banner */
					}
					banner.classList.remove('show');
				});
			});
		}


		/* ---------- Reveal-on-scroll (matches finalized preview) ---------- */
		var reveals = document.querySelectorAll('.reveal');
		if (reveals.length) {
			if ('IntersectionObserver' in window) {
				var revealObserver = new IntersectionObserver(function (entries) {
					entries.forEach(function (entry) {
						if (entry.isIntersecting) {
							entry.target.classList.add('is-visible');
							revealObserver.unobserve(entry.target);
						}
					});
				}, { threshold: 0.08 });
				reveals.forEach(function (el) { revealObserver.observe(el); });
			} else {
				reveals.forEach(function (el) { el.classList.add('is-visible'); });
			}
		}

		/* ---------- Sticky header shadow on scroll ---------- */
		var header = document.getElementById('site-nav');
		if (header) {
			var onScroll = function () {
				header.classList.toggle('scrolled', window.scrollY > 8);
			};
			window.addEventListener('scroll', onScroll, { passive: true });
			onScroll();
		}
	});
})();
