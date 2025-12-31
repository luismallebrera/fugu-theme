/**
 * Smooth Scrolling using Lenis
 */
(function() {
    'use strict';

    if (document.readyState !== 'loading') {
        smoothScrollReady();
    } else {
        document.addEventListener('DOMContentLoaded', smoothScrollReady);
    }

    function smoothScrollReady() {
        if (typeof Lenis !== 'undefined') {
            var settings = window.fuguElementorSmoothScrollingParams || {
                smoothWheel: 1,
                anchorOffset: 0,
                lerp: 0.07,
                duration: 1.2,
                anchorLinks: false,
                gsapSync: false
            };

            var lenisSettings = {
                smoothWheel: parseInt(settings.smoothWheel, 10),
                smoothTouch: false,
                normalizeWheel: true
            };

            if (settings.lerp > 0) {
                lenisSettings.lerp = parseFloat(settings.lerp);
            } else if (settings.duration > 0) {
                lenisSettings.duration = parseFloat(settings.duration);
            }

            var lenis = new Lenis(lenisSettings);

            lenis.on('scroll', function(e) {
                if (typeof smoothScrollLenisCallback !== 'undefined') {
                    smoothScrollLenisCallback(e);
                }
            });

            window.lenis = lenis;

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }

            requestAnimationFrame(raf);

            if (settings.gsapSync && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                lenis.on('scroll', ScrollTrigger.update);
                gsap.ticker.add(function(time) {
                    lenis.raf(time * 1000);
                });
                gsap.ticker.lagSmoothing(0);
            }

            if (settings.anchorLinks) {
                document.querySelectorAll('a').forEach(function(item) {
                    if (item.hash && item.hash[0] === '#') {
                        item.addEventListener('click', function() {
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
