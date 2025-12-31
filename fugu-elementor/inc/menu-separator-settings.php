<?php
/**
 * Menu Separator front-end output.
 *
 * @package FuguElementor
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output Menu Separator CSS.
 */
function fugu_elementor_menu_separator_styles() {
    $enable_horizontal = get_theme_mod('enable_horizontal_separator', false);
    $enable_submenu = get_theme_mod('enable_submenu_separator', false);

    if (!$enable_horizontal && !$enable_submenu) {
        return;
    }

    $css = '<style id="menu-separator-styles">';

    if ($enable_horizontal) {
        $h_color = get_theme_mod('horizontal_separator_color', '#dddddd');
        $h_width = get_theme_mod('horizontal_separator_width', 1);
        $h_height = get_theme_mod('horizontal_separator_height', 60);
        $h_rotation = get_theme_mod('horizontal_separator_rotation', 0);

        $transform = 'translateY(-50%)';
        if ($h_rotation != 0) {
            $transform = 'translateY(-50%) rotate(' . esc_attr($h_rotation) . 'deg)';
        }

        $css .= '
        /* Horizontal Menu Separator */
        .menu-item:not(:last-child)::after,
        nav .menu-item:not(:last-child)::after,
        .nav-menu > .menu-item:not(:last-child)::after {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            transform: ' . $transform . ';
            height: ' . esc_attr($h_height) . '%;
            width: ' . esc_attr($h_width) . 'px;
            background-color: ' . esc_attr($h_color) . ';
        }

        .menu-item,
        nav .menu-item,
        .nav-menu > .menu-item {
            position: relative;
        }
        ';
    }

    if ($enable_submenu) {
        $s_color = get_theme_mod('submenu_separator_color', '#dddddd');
        $s_width = get_theme_mod('submenu_separator_width', 1);

        $css .= '
        /* Submenu Separator */
        .sub-menu .menu-item:not(:last-child),
        .submenu .menu-item:not(:last-child),
        nav .sub-menu .menu-item:not(:last-child),
        .menu-item .sub-menu .menu-item:not(:last-child) {
            border-bottom: ' . esc_attr($s_width) . 'px solid ' . esc_attr($s_color) . ';
        }
        ';
    }

    $css .= '</style>';

    echo $css;
}
add_action('wp_head', 'fugu_elementor_menu_separator_styles');
