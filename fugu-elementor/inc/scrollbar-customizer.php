<?php
/**
 * Scrollbar front-end output.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output Scrollbar CSS.
 */
function fugu_elementor_scrollbar_styles() {
    $width = get_theme_mod('wp_scrollbar_width', 4);
    $track = get_theme_mod('wp_scrollbar_track_color', '#dddddd');
    $thumb = get_theme_mod('wp_scrollbar_thumb_color', '#ff0000');
    $hover = get_theme_mod('wp_scrollbar_thumb_hover_color', '#ffffff');

    $width = (int) $width;
    if ($width < 1) {
        $width = 1;
    }

    $width_px = $width . 'px';

    ?>
    <style id="wp-scrollbar-customizer-styles">
    :root {
        --wp-scrollbar-width: <?php echo esc_html($width_px); ?>;
        --wp-scrollbar-track: <?php echo esc_attr($track); ?>;
        --wp-scrollbar-thumb: <?php echo esc_attr($thumb); ?>;
        --wp-scrollbar-thumb-hover: <?php echo esc_attr($hover); ?>;
    }
    
    /* WebKit scrollbars */
    ::-webkit-scrollbar {
        width: var(--wp-scrollbar-width);
        background: var(--wp-scrollbar-track);
    }

    body.scrollbar::-webkit-scrollbar {
        width: var(--wp-scrollbar-width);
        background: var(--wp-scrollbar-track);
        display: block;
    }

    ::-webkit-scrollbar-track {
        background: var(--wp-scrollbar-track);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--wp-scrollbar-thumb);
        width: var(--wp-scrollbar-width);
    }

    html.scrolling ::-webkit-scrollbar {
        width: var(--wp-scrollbar-width);
    }

    html.scrolling ::-webkit-scrollbar-thumb {
        background: var(--wp-scrollbar-thumb-hover);
        width: var(--wp-scrollbar-width);
    }
    </style>
    <?php
}
add_action('wp_head', 'fugu_elementor_scrollbar_styles');
