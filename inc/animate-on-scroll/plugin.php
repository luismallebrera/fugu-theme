<?php
/**
 * Animate On Scroll (AOS) Plugin
 * Loads AOS library and initializes animations
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Enqueue AOS library from CDN
 */
function aos_enqueue_scripts() {
    // AOS CSS
    wp_enqueue_style(
        'aos-css',
        'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css',
        array(),
        '2.3.4'
    );
    
    // Custom AOS Animations CSS
    wp_enqueue_style(
        'aos-custom-animations',
        get_template_directory_uri() . '/inc/animate-on-scroll/custom-animations.css',
        array('aos-css'),
        '1.1.0'
    );
    
    // AOS JS
    wp_enqueue_script(
        'aos-js',
        'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js',
        array(),
        '2.3.4',
        true
    );
    
    // AOS Initialization
    wp_add_inline_script(
        'aos-js',
        'document.addEventListener("DOMContentLoaded", function() {
            AOS.init({
                duration: ' . (int) get_theme_mod('aos_duration', 800) . ',
                easing: "' . esc_js( get_theme_mod('aos_easing', 'ease-in-out') ) . '",
                once: ' . ( get_theme_mod('aos_once', true) ? 'true' : 'false' ) . ',
                mirror: ' . ( get_theme_mod('aos_mirror', false) ? 'true' : 'false' ) . ',
                offset: ' . (int) get_theme_mod('aos_offset', 120) . ',
                disable: ' . ( get_theme_mod('aos_disable_mobile', false) ? '"mobile"' : 'false' ) . ',
                startEvent: "DOMContentLoaded",
                animatedClassName: "aos-animate",
                initClassName: "aos-init"
            });
        });'
    );
}
add_action( 'wp_enqueue_scripts', 'aos_enqueue_scripts' );
