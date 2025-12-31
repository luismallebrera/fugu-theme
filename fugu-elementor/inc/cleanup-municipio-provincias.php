<?php
/**
 * Cleanup: Remove provincia taxonomy from all municipio posts
 * Run this once to clean up existing data
 */

if (!defined('ABSPATH')) {
    exit;
}

function fugu_elementor_cleanup_municipio_provincias() {
    if (get_option('fugu_elementor_municipio_provincias_cleaned', false)) {
        return;
    }
    
    $municipios = get_posts(array(
        'post_type'      => 'municipio',
        'posts_per_page' => -1,
        'post_status'    => 'any',
    ));
    
    foreach ($municipios as $municipio) {
        wp_set_object_terms($municipio->ID, array(), 'provincia');
    }
    
    update_option('fugu_elementor_municipio_provincias_cleaned', true);
}
add_action('admin_init', 'fugu_elementor_cleanup_municipio_provincias');
