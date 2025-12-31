<?php
/**
 * The site's entry point.
 * 
 * Loads the relevant template part,
 * the loop is executed (when needed) by the relevant template part.
 *
 * @package Elementor Blank Starter
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

$is_elementor_theme_exist = function_exists('elementor_theme_do_location');

if (is_singular()) {
    if (!$is_elementor_theme_exist || !elementor_theme_do_location('single')) {
        // Fallback for single posts/pages
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        endif;
    }
} elseif (is_archive() || is_home()) {
    if (!$is_elementor_theme_exist || !elementor_theme_do_location('archive')) {
        // Fallback for archive/blog
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        endif;
    }
} elseif (is_search()) {
    if (!$is_elementor_theme_exist || !elementor_theme_do_location('archive')) {
        // Fallback for search
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        else :
            echo '<p>No se encontraron resultados.</p>';
        endif;
    }
} else {
    if (!$is_elementor_theme_exist || !elementor_theme_do_location('single')) {
        // Fallback for 404
        echo '<h1>404 - Página no encontrada</h1>';
    }
}

get_footer();
