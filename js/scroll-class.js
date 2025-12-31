/**
 * Scroll Class
 * Add 'scroll' class to body after scrolling past threshold
 */
(function($) {
    'use strict';

    // Get settings from PHP
    var settings = window.elementorBlankScrollClass || {};
    var threshold = parseInt(settings.threshold) || 100;
    var body = $('body');
    var hasScrolled = false;

    function updateScrollClass() {
        var scrollTop = $(window).scrollTop();
        
        if (scrollTop > threshold) {
            if (!hasScrolled) {
                body.addClass('scroll');
                hasScrolled = true;
            }
        } else {
            if (hasScrolled) {
                body.removeClass('scroll');
                hasScrolled = false;
            }
        }
    }

    // Check on page load
    $(document).ready(function() {
        updateScrollClass();
    });

    // Check on scroll
    $(window).on('scroll', function() {
        updateScrollClass();
    });

})(jQuery);
