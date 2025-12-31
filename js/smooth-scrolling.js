/**
 * Smooth Scrolling using Lenis
 */
(function() {
	'use strict';

	// Wait for DOM to be ready
	if (document.readyState !== 'loading') {
		smoothScrollReady();
	} else {
		document.addEventListener('DOMContentLoaded', smoothScrollReady);
	}

	function smoothScrollReady() {
		if (typeof Lenis != "undefined") {
			// Get settings from localized script
			const settings = window.elementorBlankSmoothScrollingParams || {
				smoothWheel: 1,
				anchorOffset: 0,
				lerp: 0.07,
				duration: 1.2,
				anchorLinks: false,
				gsapSync: false
			};

			let lenisSettings = {
				smoothWheel: parseInt(settings.smoothWheel),
				smoothTouch: false,
				normalizeWheel: true
			};

			if (settings.lerp > 0) {
				lenisSettings.lerp = parseFloat(settings.lerp);
			} else if (settings.duration > 0) {
				lenisSettings.duration = parseFloat(settings.duration);
			}

			// Initialize Lenis
			const lenis = new Lenis(lenisSettings);
			
			lenis.on('scroll', (e) => {
				if (typeof smoothScrollLenisCallback != "undefined") {
					smoothScrollLenisCallback(e);
				}
			});

			window.lenis = lenis;

			// Animation frame
			function raf(time) {
				lenis.raf(time);
				requestAnimationFrame(raf);
			}

			requestAnimationFrame(raf);

			// GSAP ScrollTrigger sync
			if (settings.gsapSync && typeof gsap != "undefined" && typeof ScrollTrigger != "undefined") {
				lenis.on('scroll', ScrollTrigger.update);
				gsap.ticker.add((time) => {
					lenis.raf(time * 1000);
				});
				gsap.ticker.lagSmoothing(0);
			}

			// Smooth anchor links
			if (settings.anchorLinks) {
				document.querySelectorAll("a").forEach(function(item) {
					if (item.hash && item.hash[0] == "#") {
						item.addEventListener("click", (e) => {
							lenis.scrollTo(item.hash, {
								offset: settings.anchorOffset
							});
						});
					}
				});
			}
		}
	}

})();
