<?php
/**
 * Animate On Scroll (AOS) loader for Fugu Elementor.
 */

if (!defined('ABSPATH')) {
    exit;
}

function fugu_elementor_aos_enqueue_scripts() {
    wp_enqueue_style(
        'fugu-elementor-aos',
        'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css',
        [],
        '2.3.4'
    );

    wp_enqueue_style(
        'fugu-elementor-aos-custom',
        get_template_directory_uri() . '/inc/animate-on-scroll/custom-animations.css',
        ['fugu-elementor-aos'],
        '1.1.0'
    );

    wp_enqueue_script(
        'fugu-elementor-aos-js',
        'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js',
        [],
        '2.3.4',
        true
    );

    $settings = [
        'duration' => (int) get_theme_mod('aos_duration', 800),
        'easing' => (string) get_theme_mod('aos_easing', 'ease-in-out'),
        'once' => (bool) get_theme_mod('aos_once', true),
        'mirror' => (bool) get_theme_mod('aos_mirror', false),
        'offset' => (int) get_theme_mod('aos_offset', 120),
        'disable' => get_theme_mod('aos_disable_mobile', false) ? 'mobile' : false,
        'startEvent' => 'DOMContentLoaded',
        'animatedClassName' => 'aos-animate',
        'initClassName' => 'aos-init',
    ];

    $inline = 'document.addEventListener("DOMContentLoaded", function() { AOS.init(' . wp_json_encode($settings) . '); });';

    wp_add_inline_script('fugu-elementor-aos-js', $inline);
}
add_action('wp_enqueue_scripts', 'fugu_elementor_aos_enqueue_scripts');
