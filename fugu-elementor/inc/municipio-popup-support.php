<?php
/**
 * Municipio Popup Support
 * Auto-open popup when municipio_id is in URL
 */

if (!defined('ABSPATH')) {
    exit;
}

function fugu_elementor_popup_auto_open() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        const urlParams = new URLSearchParams(window.location.search);
        const municipioId = urlParams.get('municipio_id');
        
        if (municipioId && typeof elementorProFrontend !== 'undefined') {
            setTimeout(function() {
                elementorProFrontend.modules.popup.showPopup({ id: 7468 });
            }, 500);
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'fugu_elementor_popup_auto_open');
