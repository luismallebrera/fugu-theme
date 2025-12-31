<?php
/**
 * Cleanup: Remove provincia taxonomy from all municipio posts
 * Run this once to clean up existing data
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove provincia terms from all municipio posts
 */
function fugu_theme_cleanup_municipio_provincias() {
    // Check if we've already run this cleanup
    if (get_option('fugu_theme_municipio_provincias_cleaned', false)) {
        return;
    }
    
    // Get all municipio posts
    $municipios = get_posts(array(
        'post_type'      => 'municipio',
        'posts_per_page' => -1,
        'post_status'    => 'any',
    ));
    
    foreach ($municipios as $municipio) {
        // Remove all provincia terms from this municipio
        wp_set_object_terms($municipio->ID, array(), 'provincia');
    }
    
    // Mark as cleaned
    update_option('fugu_theme_municipio_provincias_cleaned', true);
}
add_action('admin_init', 'fugu_theme_cleanup_municipio_provincias');
