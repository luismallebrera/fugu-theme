/**
 * Scroll Class
 * Add 'scroll' class to body after scrolling past threshold
 */
(function($) {
    'use strict';

    var settings = window.fuguElementorScrollClass || {};
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
        } else if (hasScrolled) {
            body.removeClass('scroll');
            hasScrolled = false;
        }
    }

    $(document).ready(function() {
        updateScrollClass();
    });

    $(window).on('scroll', function() {
        updateScrollClass();
    });

})(jQuery);
