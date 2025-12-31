<?php
/**
 * Municipio Popup Support
 * Auto-open popup when municipio_id is in URL
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * JavaScript to auto-open popup when municipio_id parameter exists
 */
function fugu_theme_popup_auto_open() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Check if municipio_id is in URL
        const urlParams = new URLSearchParams(window.location.search);
        const municipioId = urlParams.get('municipio_id');
        
        if (municipioId && typeof elementorProFrontend !== 'undefined') {
            // Auto-open popup with slight delay
            setTimeout(function() {
                elementorProFrontend.modules.popup.showPopup({ id: 7468 });
            }, 500);
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'fugu_theme_popup_auto_open');
