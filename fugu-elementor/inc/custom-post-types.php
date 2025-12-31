<?php
/**
 * Custom Post Types
 * Register custom post types for the theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register GDR Post Type
 */
function fugu_elementor_register_galgdr_cpt() {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                  => _x('GDR', 'Post Type General Name', 'fugu-elementor'),
        'singular_name'         => _x('GDR', 'Post Type Singular Name', 'fugu-elementor'),
        'menu_name'             => __('GDR', 'fugu-elementor'),
        'name_admin_bar'        => __('GDR', 'fugu-elementor'),
        'archives'              => __('GDR Archives', 'fugu-elementor'),
        'all_items'             => __('Todos los GDR', 'fugu-elementor'),
        'add_new_item'          => __('Añadir Nuevo GDR', 'fugu-elementor'),
        'add_new'               => __('Añadir Nuevo', 'fugu-elementor'),
        'new_item'              => __('Nuevo GDR', 'fugu-elementor'),
        'edit_item'             => __('Editar GDR', 'fugu-elementor'),
        'update_item'           => __('Actualizar GDR', 'fugu-elementor'),
        'view_item'             => __('Ver GDR', 'fugu-elementor'),
        'search_items'          => __('Buscar GDR', 'fugu-elementor'),
        'not_found'             => __('No encontrado', 'fugu-elementor'),
        'not_found_in_trash'    => __('No encontrado en papelera', 'fugu-elementor'),
    );

    $args = array(
        'label'                 => __('GDR', 'fugu-elementor'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-groups',
        'has_archive'           => true,
        'show_in_rest'          => true,
        'rewrite'               => false,
    );

    register_post_type('galgdr', $args);
}
add_action('init', 'fugu_elementor_register_galgdr_cpt', 0);

/**
 * Generate correct permalink for GDR posts
 */
function fugu_elementor_galgdr_permalink($post_link, $post) {
    if ($post->post_type !== 'galgdr') {
        return $post_link;
    }
    
    // Solo modificar si no tiene ya la estructura correcta
    if (strpos($post_link, '/gdr/') !== false && strpos($post_link, '/gdr/gdr/') === false) {
        return $post_link;
    }
    
    $terms = wp_get_object_terms($post->ID, 'provincia');
    if (!empty($terms) && !is_wp_error($terms)) {
        return home_url('/gdr/' . $terms[0]->slug . '/' . $post->post_name . '/');
    } else {
        return home_url('/gdr/sin-provincia/' . $post->post_name . '/');
    }
}
add_filter('post_type_link', 'fugu_elementor_galgdr_permalink', 10, 2);

/**
 * Register Municipio Post Type
 */
function fugu_elementor_register_municipio_cpt() {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                  => _x('Municipios', 'Post Type General Name', 'fugu-elementor'),
        'singular_name'         => _x('Municipio', 'Post Type Singular Name', 'fugu-elementor'),
        'menu_name'             => __('Municipios', 'fugu-elementor'),
        'name_admin_bar'        => __('Municipio', 'fugu-elementor'),
        'archives'              => __('Municipios Archives', 'fugu-elementor'),
        'all_items'             => __('Todos los Municipios', 'fugu-elementor'),
        'add_new_item'          => __('Añadir Nuevo Municipio', 'fugu-elementor'),
        'add_new'               => __('Añadir Nuevo', 'fugu-elementor'),
        'new_item'              => __('Nuevo Municipio', 'fugu-elementor'),
        'edit_item'             => __('Editar Municipio', 'fugu-elementor'),
        'update_item'           => __('Actualizar Municipio', 'fugu-elementor'),
        'view_item'             => __('Ver Municipio', 'fugu-elementor'),
        'search_items'          => __('Buscar Municipios', 'fugu-elementor'),
        'not_found'             => __('No encontrado', 'fugu-elementor'),
        'not_found_in_trash'    => __('No encontrado en papelera', 'fugu-elementor'),
    );

    $args = array(
        'label'                 => __('Municipios', 'fugu-elementor'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-location',
        'has_archive'           => true,
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'municipios'),
    );

    register_post_type('municipio', $args);
}
add_action('init', 'fugu_elementor_register_municipio_cpt', 0);

/**
 * Register Provincia Taxonomy
 */
function fugu_elementor_register_provincia_taxonomy() {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                       => _x('Provincias', 'Taxonomy General Name', 'fugu-elementor'),
        'singular_name'              => _x('Provincia', 'Taxonomy Singular Name', 'fugu-elementor'),
        'menu_name'                  => __('Provincias', 'fugu-elementor'),
        'all_items'                  => __('Todas las Provincias', 'fugu-elementor'),
        'parent_item'                => __('Provincia Padre', 'fugu-elementor'),
        'parent_item_colon'          => __('Provincia Padre:', 'fugu-elementor'),
        'new_item_name'              => __('Nueva Provincia', 'fugu-elementor'),
        'add_new_item'               => __('Añadir Nueva Provincia', 'fugu-elementor'),
        'edit_item'                  => __('Editar Provincia', 'fugu-elementor'),
        'update_item'                => __('Actualizar Provincia', 'fugu-elementor'),
        'view_item'                  => __('Ver Provincia', 'fugu-elementor'),
        'search_items'               => __('Buscar Provincias', 'fugu-elementor'),
        'not_found'                  => __('No encontrado', 'fugu-elementor'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'has_archive'                => true,
        'rewrite'                    => array(
            'slug'                   => 'gdr',
            'with_front'             => false,
            'hierarchical'           => true,
        ),
    );

    register_taxonomy('provincia', array('galgdr'), $args);
}
add_action('init', 'fugu_elementor_register_provincia_taxonomy', 0);

/**
 * Add custom rewrite rules for /gdr/{provincia}/ archives
 */
function fugu_elementor_galgdr_provincia_rewrite_rules() {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }
    
    // Archive by provincia: /gdr/toledo/
    add_rewrite_rule(
        '^gdr/([^/]+)/?$',
        'index.php?provincia=$matches[1]',
        'top'
    );
    
    // Single GDR: /gdr/toledo/nombre-del-gdr/
    add_rewrite_rule(
        '^gdr/([^/]+)/([^/]+)/?$',
        'index.php?post_type=galgdr&name=$matches[2]',
        'top'
    );
}
add_action('init', 'fugu_elementor_galgdr_provincia_rewrite_rules');

/**
 * Modify main query for provincia archives to show GDR
 */
function fugu_elementor_modify_galgdr_provincia_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        // For provincia taxonomy archives
        if (is_tax('provincia')) {
            $query->set('post_type', 'galgdr');
            $query->set('posts_per_page', -1);
        }
        // For GDR post type archive
        if (is_post_type_archive('galgdr')) {
            $query->set('posts_per_page', -1);
        }
    }
}
add_action('pre_get_posts', 'fugu_elementor_modify_galgdr_provincia_query');

/**
 * Modify page title for provincia archive pages
 */
function fugu_elementor_galgdr_provincia_title($title) {
    if (is_tax('provincia')) {
        $term = get_queried_object();
        if ($term) {
            return 'GDR de ' . $term->name;
        }
    }
    return $title;
}
add_filter('pre_get_document_title', 'fugu_elementor_galgdr_provincia_title', 10, 1);
add_filter('wp_title', 'fugu_elementor_galgdr_provincia_title', 10, 1);

/**
 * Make provincia query var public
 */
function fugu_elementor_add_query_vars($vars) {
    $vars[] = 'provincia';
    return $vars;
}
add_filter('query_vars', 'fugu_elementor_add_query_vars');

/**
 * Custom Elementor Query for GDR filtered by Provincia
 * Use Query ID: galgdr_provincia
 */
function fugu_elementor_galgdr_provincia_query($query) {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }
    
    // Set post type to galgdr
    $query->set('post_type', 'galgdr');
    
    // If we're on a provincia taxonomy page, Elementor will automatically filter
    // No need to manually set the tax_query - Elementor handles it
}
add_action('elementor/query/galgdr_provincia', 'fugu_elementor_galgdr_provincia_query');

/**
 * Auto-assign provincia to GDR posts without provincia
 * Runs once to fix existing posts
 */
function fugu_elementor_auto_assign_provincia_to_galgdr() {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }
    
    // Check if we've already run this
    if (get_option('fugu_elementor_provincia_assigned', false)) {
        return;
    }
    
    // Get all GDR posts
    $galgdr_posts = get_posts(array(
        'post_type'      => 'galgdr',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ));
    
    // Map of provincia keywords to find in titles
    $provincia_map = array(
        'ALBACETE' => array('albacete'),
        'CIUDAD REAL' => array('ciudad real', 'ciudad-real'),
        'CUENCA' => array('cuenca'),
        'GUADALAJARA' => array('guadalajara'),
        'TOLEDO' => array('toledo'),
    );
    
    foreach ($galgdr_posts as $post) {
        // Check if post already has provincia assigned
        $existing_terms = wp_get_object_terms($post->ID, 'provincia');
        if (!empty($existing_terms) && !is_wp_error($existing_terms)) {
            continue; // Already has provincia
        }
        
        // Try to detect provincia from title
        $title_lower = strtolower($post->post_title);
        $provincia_found = null;
        
        foreach ($provincia_map as $provincia_name => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($title_lower, $keyword) !== false) {
                    $provincia_found = $provincia_name;
                    break 2;
                }
            }
        }
        
        // If found, assign it
        if ($provincia_found) {
            $term = term_exists($provincia_found, 'provincia');
            if ($term) {
                wp_set_object_terms($post->ID, array($term['term_id']), 'provincia');
            }
        }
    }
    
    // Mark as done
    update_option('fugu_elementor_provincia_assigned', true);
}
add_action('admin_init', 'fugu_elementor_auto_assign_provincia_to_galgdr');

/**
 * Add Siglas (Acronym) custom field to GDR
 */
function fugu_elementor_add_galgdr_siglas_meta_box() {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }
    
    add_meta_box(
        'galgdr_siglas',
        __('Siglas', 'fugu-elementor'),
        'fugu_elementor_galgdr_siglas_callback',
        'galgdr',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_galgdr_siglas_meta_box');

function fugu_elementor_galgdr_siglas_callback($post) {
    wp_nonce_field('galgdr_siglas_nonce', 'galgdr_siglas_nonce_field');
    $value = get_post_meta($post->ID, '_galgdr_siglas', true);
    echo '<label for="galgdr_siglas_field">' . __('Siglas:', 'fugu-elementor') . '</label>';
    echo '<input type="text" id="galgdr_siglas_field" name="galgdr_siglas_field" value="' . esc_attr($value) . '" class="widefat" placeholder="' . __('Ej: ADIMAN', 'fugu-elementor') . '">';
}

function fugu_elementor_save_galgdr_siglas($post_id) {
    if (!isset($_POST['galgdr_siglas_nonce_field']) || 
        !wp_verify_nonce($_POST['galgdr_siglas_nonce_field'], 'galgdr_siglas_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['galgdr_siglas_field'])) {
        update_post_meta($post_id, '_galgdr_siglas', sanitize_text_field($_POST['galgdr_siglas_field']));
    }
}
add_action('save_post_galgdr', 'fugu_elementor_save_galgdr_siglas');

/**
 * Register Siglas custom field for REST API and Elementor
 */
function fugu_elementor_register_galgdr_siglas_meta() {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }
    
    register_post_meta('galgdr', '_galgdr_siglas', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'description' => __('Siglas del GDR', 'fugu-elementor'),
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'fugu_elementor_register_galgdr_siglas_meta');

/**
 * Add GDR and Provincia relationship meta boxes to Municipio
 */
function fugu_elementor_add_municipio_meta_boxes() {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }
    
    add_meta_box(
        'municipio_galgdr_relationship',
        __('GDR Asociado', 'fugu-elementor'),
        'fugu_elementor_municipio_galgdr_callback',
        'municipio',
        'side',
        'default'
    );
    
    add_meta_box(
        'municipio_provincia_relationship',
        __('Provincia', 'fugu-elementor'),
        'fugu_elementor_municipio_provincia_callback',
        'municipio',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_municipio_meta_boxes');

function fugu_elementor_municipio_galgdr_callback($post) {
    wp_nonce_field('municipio_galgdr_nonce', 'municipio_galgdr_nonce_field');
    $selected_galgdr = get_post_meta($post->ID, '_municipio_galgdr_asociado', true);
    
    // Get all GDR posts
    $galgdr_posts = get_posts(array(
        'post_type'      => 'galgdr',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ));
    
    echo '<label for="municipio_galgdr_select">' . __('Selecciona GDR:', 'fugu-elementor') . '</label><br>';
    echo '<select id="municipio_galgdr_select" name="municipio_galgdr_asociado" style="width: 100%;">';
    echo '<option value="">' . __('-- Ninguno --', 'fugu-elementor') . '</option>';
    
    foreach ($galgdr_posts as $galgdr_post) {
        $selected = ($selected_galgdr == $galgdr_post->ID) ? 'selected="selected"' : '';
        echo '<option value="' . esc_attr($galgdr_post->ID) . '" ' . $selected . '>' . esc_html($galgdr_post->post_title) . '</option>';
    }
    
    echo '</select>';
}

function fugu_elementor_municipio_provincia_callback($post) {
    wp_nonce_field('municipio_provincia_nonce', 'municipio_provincia_nonce_field');
    $selected_provincia = get_post_meta($post->ID, '_municipio_provincia', true);
    
    // Get all provincia terms
    $provincias = get_terms(array(
        'taxonomy'   => 'provincia',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));
    
    echo '<label for="municipio_provincia_select">' . __('Selecciona Provincia:', 'fugu-elementor') . '</label><br>';
    echo '<select id="municipio_provincia_select" name="municipio_provincia" style="width: 100%;">';
    echo '<option value="">' . __('-- Ninguna --', 'fugu-elementor') . '</option>';
    
    if (!is_wp_error($provincias) && !empty($provincias)) {
        foreach ($provincias as $provincia) {
            $selected = ($selected_provincia == $provincia->term_id) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr($provincia->term_id) . '" ' . $selected . '>' . esc_html($provincia->name) . '</option>';
        }
    }
    
    echo '</select>';
}

function fugu_elementor_save_municipio_galgdr($post_id) {
    if (!isset($_POST['municipio_galgdr_nonce_field']) || 
        !wp_verify_nonce($_POST['municipio_galgdr_nonce_field'], 'municipio_galgdr_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['municipio_galgdr_asociado'])) {
        $galgdr_id = absint($_POST['municipio_galgdr_asociado']);
        update_post_meta($post_id, '_municipio_galgdr_asociado', $galgdr_id);
    }
    
    if (isset($_POST['municipio_provincia'])) {
        $provincia_id = absint($_POST['municipio_provincia']);
        update_post_meta($post_id, '_municipio_provincia', $provincia_id);
    }
}
add_action('save_post_municipio', 'fugu_elementor_save_municipio_galgdr');

/**
 * Register GDR and Provincia relationship meta for REST API
 */
function fugu_elementor_register_municipio_galgdr_meta() {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }
    
    register_post_meta('municipio', '_municipio_galgdr_asociado', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
        'description' => __('ID del GDR asociado', 'fugu-elementor'),
        'sanitize_callback' => 'absint',
        'auth_callback' => '__return_true'
    ));
    
    register_post_meta('municipio', '_municipio_provincia', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
        'description' => __('ID de la provincia asociada', 'fugu-elementor'),
        'sanitize_callback' => 'absint',
        'auth_callback' => '__return_true'
    ));
}
add_action('init', 'fugu_elementor_register_municipio_galgdr_meta');

/**
 * Add meta fields to REST API response for municipio
 */
function fugu_elementor_add_municipio_meta_to_rest() {
    register_rest_field('municipio', 'galgdr_asociado', array(
        'get_callback' => function($post) {
            return get_post_meta($post['id'], '_municipio_galgdr_asociado', true);
        },
        'schema' => array(
            'description' => 'ID del GDR asociado',
            'type' => 'integer'
        ),
    ));
    
    register_rest_field('municipio', 'provincia_asociada', array(
        'get_callback' => function($post) {
            return get_post_meta($post['id'], '_municipio_provincia', true);
        },
        'schema' => array(
            'description' => 'ID de la provincia asociada',
            'type' => 'integer'
        ),
    ));
}
add_action('rest_api_init', 'fugu_elementor_add_municipio_meta_to_rest');

/**
 * Custom Elementor Query for Municipios related to GDR
 * Use Query ID: municipios_de_gal
 */
function fugu_elementor_municipios_de_gal_query($query) {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return;
    }
    
    if (is_singular('galgdr')) {
        $gal_id = get_the_ID();
        $meta_query = array(
            array(
                'key'     => '_municipio_galgdr_asociado',
                'value'   => $gal_id,
                'compare' => '='
            )
        );
        $query->set('meta_query', $meta_query);
    }
}
add_action('elementor/query/municipios_de_gal', 'fugu_elementor_municipios_de_gal_query');

/**
 * Shortcodes for displaying Municipio relationships in Elementor
 */
function fugu_elementor_municipio_galgdr_name_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => get_the_ID()
    ), $atts);
    
    $post_id = isset($_GET['municipio_id']) ? absint($_GET['municipio_id']) : $atts['id'];
    $galgdr_id = get_post_meta($post_id, '_municipio_galgdr_asociado', true);
    
    if ($galgdr_id) {
        $url = get_permalink($galgdr_id);
        $title = get_the_title($galgdr_id);
        return '<a href="' . esc_url($url) . '">' . esc_html($title) . '</a>';
    }
    
    return '';
}
add_shortcode('municipio_galgdr_name', 'fugu_elementor_municipio_galgdr_name_shortcode');

/**
 * Shortcode to display GDR featured image
 * Usage: [municipio_galgdr_image] or [municipio_galgdr_image id="123" size="full"]
 */
function fugu_elementor_municipio_galgdr_image_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => get_the_ID(),
        'size' => 'full'
    ), $atts);
    
    $post_id = isset($_GET['municipio_id']) ? absint($_GET['municipio_id']) : $atts['id'];
    $galgdr_id = get_post_meta($post_id, '_municipio_galgdr_asociado', true);
    
    if ($galgdr_id && has_post_thumbnail($galgdr_id)) {
        return get_the_post_thumbnail($galgdr_id, $atts['size']);
    }
    
    return '';
}
add_shortcode('municipio_galgdr_image', 'fugu_elementor_municipio_galgdr_image_shortcode');

function fugu_elementor_municipio_provincia_name_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => get_the_ID()
    ), $atts);
    
    $post_id = isset($_GET['municipio_id']) ? absint($_GET['municipio_id']) : $atts['id'];
    $provincia_id = get_post_meta($post_id, '_municipio_provincia', true);
    
    if ($provincia_id) {
        $term = get_term($provincia_id, 'provincia');
        if ($term && !is_wp_error($term)) {
            return $term->name;
        }
    }
    
    return '';
}
add_shortcode('municipio_provincia_name', 'fugu_elementor_municipio_provincia_name_shortcode');

function fugu_elementor_galgdr_siglas_shortcode($atts) {
    $post_id = get_the_ID();
    $siglas = get_post_meta($post_id, '_galgdr_siglas', true);
    
    return $siglas ? esc_html($siglas) : '';
}
add_shortcode('galgdr_siglas', 'fugu_elementor_galgdr_siglas_shortcode');

/**
 * Shortcode to convert post ID to title
 * Usage: [post_title id="123"] or with Dynamic Tags: [post_title id="{dynamic}"]
 */
function fugu_elementor_post_title_shortcode($atts) {
    $atts = shortcode_atts(array('id' => 0), $atts);
    $id = absint($atts['id']);
    
    if ($id) {
        return esc_html(get_the_title($id));
    }
    return '';
}
add_shortcode('post_title', 'fugu_elementor_post_title_shortcode');

/**
 * Shortcode to convert term ID to name
 * Usage: [term_name id="123" taxonomy="provincia"]
 */
function fugu_elementor_term_name_shortcode($atts) {
    $atts = shortcode_atts(array('id' => 0, 'taxonomy' => 'provincia'), $atts);
    $id = absint($atts['id']);
    
    if ($id) {
        $term = get_term($id, $atts['taxonomy']);
        if ($term && !is_wp_error($term)) {
            return esc_html($term->name);
        }
    }
    return '';
}
add_shortcode('term_name', 'fugu_elementor_term_name_shortcode');

/**
 * Shortcode to display GDR link from proyectos
 * Usage: [proyectos_gdr_link] or [proyectos_gdr_link id="123"]
 */
function fugu_elementor_proyectos_gdr_link_shortcode($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts);
    $post_id = absint($atts['id']);
    
    if ($post_id) {
        $gdr_id = get_post_meta($post_id, '_proyectos_gdr', true);
        
        if ($gdr_id) {
            $gdr_post = get_post($gdr_id);
            if ($gdr_post) {
                $url = get_permalink($gdr_id);
                $title = get_the_title($gdr_id);
                return '<a href="' . esc_url($url) . '">' . esc_html($title) . '</a>';
            }
        }
    }
    return '';
}
add_shortcode('proyectos_gdr_link', 'fugu_elementor_proyectos_gdr_link_shortcode');

/**
 * Shortcode to display Provincia name from proyectos
 * Usage: [proyectos_provincia] or [proyectos_provincia id="123"]
 */
function fugu_elementor_proyectos_provincia_shortcode($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts);
    $post_id = absint($atts['id']);
    
    if ($post_id) {
        $provincia_id = get_post_meta($post_id, '_proyectos_provincia', true);
        
        if ($provincia_id) {
            $term = get_term($provincia_id, 'provincia');
            if ($term && !is_wp_error($term)) {
                return esc_html($term->name);
            }
        }
    }
    return '';
}
add_shortcode('proyectos_provincia', 'fugu_elementor_proyectos_provincia_shortcode');

/**
 * Shortcode to display Municipio name from proyectos
 * Usage: [proyectos_municipio] or [proyectos_municipio id="123"]
 */
function fugu_elementor_proyectos_municipio_shortcode($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts);
    $post_id = absint($atts['id']);
    
    if ($post_id) {
        $municipio_id = get_post_meta($post_id, '_proyectos_municipio', true);
        
        if ($municipio_id) {
            return esc_html(get_the_title($municipio_id));
        }
    }
    return '';
}
add_shortcode('proyectos_municipio', 'fugu_elementor_proyectos_municipio_shortcode');

/**
 * Shortcode to display GDR Siglas with link from proyectos
 * Usage: [proyectos_gdr_siglas_link] or [proyectos_gdr_siglas_link id="123"]
 */
function fugu_elementor_proyectos_gdr_siglas_link_shortcode($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts);
    $post_id = absint($atts['id']);
    
    if ($post_id) {
        $siglas = get_post_meta($post_id, '_proyectos_gdr_siglas', true);
        $gdr_id = get_post_meta($post_id, '_proyectos_gdr', true);
        
        if ($siglas && $gdr_id) {
            $url = get_permalink($gdr_id);
            return '<a href="' . esc_url($url) . '">' . esc_html($siglas) . '</a>';
        }
    }
    return '';
}
add_shortcode('proyectos_gdr_siglas_link', 'fugu_elementor_proyectos_gdr_siglas_link_shortcode');

/**
 * Register Noticias Slider Post Type
 */
function fugu_elementor_register_noticias_slider_cpt() {
    if (!get_theme_mod('enable_noticias_slider_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                  => _x('Noticias Slider', 'Post Type General Name', 'fugu-elementor'),
        'singular_name'         => _x('Noticia Slider', 'Post Type Singular Name', 'fugu-elementor'),
        'menu_name'             => __('Noticias Slider', 'fugu-elementor'),
        'name_admin_bar'        => __('Noticia Slider', 'fugu-elementor'),
        'archives'              => __('Noticias Slider Archives', 'fugu-elementor'),
        'attributes'            => __('Noticia Attributes', 'fugu-elementor'),
        'parent_item_colon'     => __('Parent Noticia:', 'fugu-elementor'),
        'all_items'             => __('Todas las Noticias', 'fugu-elementor'),
        'add_new_item'          => __('Añadir Nueva Noticia', 'fugu-elementor'),
        'add_new'               => __('Añadir Nueva', 'fugu-elementor'),
        'new_item'              => __('Nueva Noticia', 'fugu-elementor'),
        'edit_item'             => __('Editar Noticia', 'fugu-elementor'),
        'update_item'           => __('Actualizar Noticia', 'fugu-elementor'),
        'view_item'             => __('Ver Noticia', 'fugu-elementor'),
        'view_items'            => __('Ver Noticias', 'fugu-elementor'),
        'search_items'          => __('Buscar Noticias', 'fugu-elementor'),
        'not_found'             => __('No encontrado', 'fugu-elementor'),
        'not_found_in_trash'    => __('No encontrado en papelera', 'fugu-elementor'),
        'featured_image'        => __('Imagen destacada', 'fugu-elementor'),
        'set_featured_image'    => __('Establecer imagen destacada', 'fugu-elementor'),
        'remove_featured_image' => __('Eliminar imagen destacada', 'fugu-elementor'),
        'use_featured_image'    => __('Usar como imagen destacada', 'fugu-elementor'),
        'insert_into_item'      => __('Insertar en noticia', 'fugu-elementor'),
        'uploaded_to_this_item' => __('Subido a esta noticia', 'fugu-elementor'),
        'items_list'            => __('Lista de noticias', 'fugu-elementor'),
        'items_list_navigation' => __('Navegación de lista de noticias', 'fugu-elementor'),
        'filter_items_list'     => __('Filtrar lista de noticias', 'fugu-elementor'),
    );

    $args = array(
        'label'                 => __('Noticia Slider', 'fugu-elementor'),
        'description'           => __('Noticias para slider', 'fugu-elementor'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-slides',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('noticias_slider', $args);
}
add_action('init', 'fugu_elementor_register_noticias_slider_cpt', 0);

/**
 * Register Portfolio Post Type
 */
function fugu_elementor_register_portfolio_cpt() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                  => _x('Portfolio', 'Post Type General Name', 'fugu-elementor'),
        'singular_name'         => _x('Portfolio Item', 'Post Type Singular Name', 'fugu-elementor'),
        'menu_name'             => __('Portfolio', 'fugu-elementor'),
        'name_admin_bar'        => __('Portfolio Item', 'fugu-elementor'),
        'archives'              => __('Portfolio Archives', 'fugu-elementor'),
        'attributes'            => __('Portfolio Attributes', 'fugu-elementor'),
        'parent_item_colon'     => __('Parent Portfolio Item:', 'fugu-elementor'),
        'all_items'             => __('All Portfolio Items', 'fugu-elementor'),
        'add_new_item'          => __('Add New Portfolio Item', 'fugu-elementor'),
        'add_new'               => __('Add New', 'fugu-elementor'),
        'new_item'              => __('New Portfolio Item', 'fugu-elementor'),
        'edit_item'             => __('Edit Portfolio Item', 'fugu-elementor'),
        'update_item'           => __('Update Portfolio Item', 'fugu-elementor'),
        'view_item'             => __('View Portfolio Item', 'fugu-elementor'),
        'view_items'            => __('View Portfolio Items', 'fugu-elementor'),
        'search_items'          => __('Search Portfolio', 'fugu-elementor'),
        'not_found'             => __('Not found', 'fugu-elementor'),
        'not_found_in_trash'    => __('Not found in Trash', 'fugu-elementor'),
        'featured_image'        => __('Featured Image', 'fugu-elementor'),
        'set_featured_image'    => __('Set featured image', 'fugu-elementor'),
        'remove_featured_image' => __('Remove featured image', 'fugu-elementor'),
        'use_featured_image'    => __('Use as featured image', 'fugu-elementor'),
        'insert_into_item'      => __('Insert into portfolio item', 'fugu-elementor'),
        'uploaded_to_this_item' => __('Uploaded to this portfolio item', 'fugu-elementor'),
        'items_list'            => __('Portfolio items list', 'fugu-elementor'),
        'items_list_navigation' => __('Portfolio items list navigation', 'fugu-elementor'),
        'filter_items_list'     => __('Filter portfolio items list', 'fugu-elementor'),
    );

    $args = array(
        'label'                 => __('Portfolio Item', 'fugu-elementor'),
        'description'           => __('Portfolio items', 'fugu-elementor'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'),
        'taxonomies'            => array('portfolio_category', 'portfolio_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('portfolio', $args);
}
add_action('init', 'fugu_elementor_register_portfolio_cpt', 0);

/**
 * Replace excerpt with WYSIWYG Description field for Portfolio
 */
function fugu_elementor_add_portfolio_description_meta_box() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    remove_meta_box('postexcerpt', 'portfolio', 'normal');
    add_meta_box(
        'portfolio_description',
        __('Description', 'fugu-elementor'),
        'fugu_elementor_portfolio_description_callback',
        'portfolio',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_portfolio_description_meta_box');

function fugu_elementor_portfolio_description_callback($post) {
    wp_nonce_field('portfolio_description_nonce', 'portfolio_description_nonce_field');
    $description = get_post_meta($post->ID, '_portfolio_description', true);
    
    wp_editor($description, 'portfolio_description_editor', array(
        'textarea_name' => 'portfolio_description',
        'textarea_rows' => 10,
        'media_buttons' => true,
        'teeny' => false,
        'tinymce' => array(
            'toolbar1' => 'formatselect,bold,italic,underline,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink',
            'toolbar2' => 'forecolor,backcolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help'
        )
    ));
}

function fugu_elementor_save_portfolio_description($post_id) {
    if (!isset($_POST['portfolio_description_nonce_field']) || 
        !wp_verify_nonce($_POST['portfolio_description_nonce_field'], 'portfolio_description_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['portfolio_description'])) {
        update_post_meta($post_id, '_portfolio_description', wp_kses_post($_POST['portfolio_description']));
    }
}
add_action('save_post_portfolio', 'fugu_elementor_save_portfolio_description');

/**
 * Register Description custom field for Elementor
 * Make it available in REST API and Elementor
 */
function fugu_elementor_register_portfolio_description_meta() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    register_post_meta('portfolio', '_portfolio_description', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'description' => __('Portfolio Description', 'fugu-elementor'),
        'sanitize_callback' => 'wp_kses_post',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'fugu_elementor_register_portfolio_description_meta');

/**
 * Register Portfolio Category Taxonomy
 */
function fugu_elementor_register_portfolio_category() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                       => _x('Portfolio Categories', 'Taxonomy General Name', 'fugu-elementor'),
        'singular_name'              => _x('Portfolio Category', 'Taxonomy Singular Name', 'fugu-elementor'),
        'menu_name'                  => __('Categories', 'fugu-elementor'),
        'all_items'                  => __('All Categories', 'fugu-elementor'),
        'parent_item'                => __('Parent Category', 'fugu-elementor'),
        'parent_item_colon'          => __('Parent Category:', 'fugu-elementor'),
        'new_item_name'              => __('New Category Name', 'fugu-elementor'),
        'add_new_item'               => __('Add New Category', 'fugu-elementor'),
        'edit_item'                  => __('Edit Category', 'fugu-elementor'),
        'update_item'                => __('Update Category', 'fugu-elementor'),
        'view_item'                  => __('View Category', 'fugu-elementor'),
        'separate_items_with_commas' => __('Separate categories with commas', 'fugu-elementor'),
        'add_or_remove_items'        => __('Add or remove categories', 'fugu-elementor'),
        'choose_from_most_used'      => __('Choose from the most used', 'fugu-elementor'),
        'popular_items'              => __('Popular Categories', 'fugu-elementor'),
        'search_items'               => __('Search Categories', 'fugu-elementor'),
        'not_found'                  => __('Not Found', 'fugu-elementor'),
        'no_terms'                   => __('No categories', 'fugu-elementor'),
        'items_list'                 => __('Categories list', 'fugu-elementor'),
        'items_list_navigation'      => __('Categories list navigation', 'fugu-elementor'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );

    register_taxonomy('portfolio_category', array('portfolio'), $args);
}
add_action('init', 'fugu_elementor_register_portfolio_category', 0);

/**
 * Register Portfolio Tag Taxonomy
 */
function fugu_elementor_register_portfolio_tag() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                       => _x('Portfolio Tags', 'Taxonomy General Name', 'fugu-elementor'),
        'singular_name'              => _x('Portfolio Tag', 'Taxonomy Singular Name', 'fugu-elementor'),
        'menu_name'                  => __('Tags', 'fugu-elementor'),
        'all_items'                  => __('All Tags', 'fugu-elementor'),
        'parent_item'                => __('Parent Tag', 'fugu-elementor'),
        'parent_item_colon'          => __('Parent Tag:', 'fugu-elementor'),
        'new_item_name'              => __('New Tag Name', 'fugu-elementor'),
        'add_new_item'               => __('Add New Tag', 'fugu-elementor'),
        'edit_item'                  => __('Edit Tag', 'fugu-elementor'),
        'update_item'                => __('Update Tag', 'fugu-elementor'),
        'view_item'                  => __('View Tag', 'fugu-elementor'),
        'separate_items_with_commas' => __('Separate tags with commas', 'fugu-elementor'),
        'add_or_remove_items'        => __('Add or remove tags', 'fugu-elementor'),
        'choose_from_most_used'      => __('Choose from the most used', 'fugu-elementor'),
        'popular_items'              => __('Popular Tags', 'fugu-elementor'),
        'search_items'               => __('Search Tags', 'fugu-elementor'),
        'not_found'                  => __('Not Found', 'fugu-elementor'),
        'no_terms'                   => __('No tags', 'fugu-elementor'),
        'items_list'                 => __('Tags list', 'fugu-elementor'),
        'items_list_navigation'      => __('Tags list navigation', 'fugu-elementor'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );

    register_taxonomy('portfolio_tag', array('portfolio'), $args);
}
add_action('init', 'fugu_elementor_register_portfolio_tag', 0);

/**
 * Register Proyectos Post Type
 */
function fugu_elementor_register_proyectos_cpt() {
    if (!get_theme_mod('enable_proyectos_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                  => _x('Proyectos', 'Post Type General Name', 'fugu-elementor'),
        'singular_name'         => _x('Proyecto', 'Post Type Singular Name', 'fugu-elementor'),
        'menu_name'             => __('Proyectos', 'fugu-elementor'),
        'name_admin_bar'        => __('Proyecto', 'fugu-elementor'),
        'archives'              => __('Proyectos Archives', 'fugu-elementor'),
        'attributes'            => __('Proyecto Attributes', 'fugu-elementor'),
        'parent_item_colon'     => __('Parent Proyecto:', 'fugu-elementor'),
        'all_items'             => __('Todos los Proyectos', 'fugu-elementor'),
        'add_new_item'          => __('Añadir Nuevo Proyecto', 'fugu-elementor'),
        'add_new'               => __('Añadir Nuevo', 'fugu-elementor'),
        'new_item'              => __('Nuevo Proyecto', 'fugu-elementor'),
        'edit_item'             => __('Editar Proyecto', 'fugu-elementor'),
        'update_item'           => __('Actualizar Proyecto', 'fugu-elementor'),
        'view_item'             => __('Ver Proyecto', 'fugu-elementor'),
        'view_items'            => __('Ver Proyectos', 'fugu-elementor'),
        'search_items'          => __('Buscar Proyectos', 'fugu-elementor'),
        'not_found'             => __('No encontrado', 'fugu-elementor'),
        'not_found_in_trash'    => __('No encontrado en papelera', 'fugu-elementor'),
        'featured_image'        => __('Imagen Destacada', 'fugu-elementor'),
        'set_featured_image'    => __('Establecer imagen destacada', 'fugu-elementor'),
        'remove_featured_image' => __('Eliminar imagen destacada', 'fugu-elementor'),
        'use_featured_image'    => __('Usar como imagen destacada', 'fugu-elementor'),
        'insert_into_item'      => __('Insertar en proyecto', 'fugu-elementor'),
        'uploaded_to_this_item' => __('Subido a este proyecto', 'fugu-elementor'),
        'items_list'            => __('Lista de proyectos', 'fugu-elementor'),
        'items_list_navigation' => __('Navegación de lista de proyectos', 'fugu-elementor'),
        'filter_items_list'     => __('Filtrar lista de proyectos', 'fugu-elementor'),
    );

    $args = array(
        'label'                 => __('Proyecto', 'fugu-elementor'),
        'description'           => __('Proyectos', 'fugu-elementor'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'),
        'taxonomies'            => array('proyectos_category', 'proyectos_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-admin-multisite',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('proyectos', $args);
    
    // Register custom meta fields for proyectos
    register_post_meta('proyectos', '_proyectos_provincia', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
    ));
    
    register_post_meta('proyectos', '_proyectos_municipio', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
    ));
    
    register_post_meta('proyectos', '_proyectos_ayuda', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ));
    
    register_post_meta('proyectos', '_proyectos_gdr', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
    ));
    
    register_post_meta('proyectos', '_proyectos_gdr_siglas', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ));
}
add_action('init', 'fugu_elementor_register_proyectos_cpt', 0);

/**
 * Add custom fields to Proyectos
 */
function fugu_elementor_add_proyectos_provincia_meta_box() {
    if (!get_theme_mod('enable_proyectos_cpt', false)) {
        return;
    }
    
    add_meta_box(
        'proyectos_provincia',
        __('Provincia', 'fugu-elementor'),
        'fugu_elementor_proyectos_provincia_callback',
        'proyectos',
        'side',
        'default'
    );
    
    add_meta_box(
        'proyectos_municipio',
        __('Municipio', 'fugu-elementor'),
        'fugu_elementor_proyectos_municipio_callback',
        'proyectos',
        'side',
        'default'
    );
    
    add_meta_box(
        'proyectos_ayuda',
        __('Ayuda', 'fugu-elementor'),
        'fugu_elementor_proyectos_ayuda_callback',
        'proyectos',
        'side',
        'default'
    );
    
    add_meta_box(
        'proyectos_gdr',
        __('GDR', 'fugu-elementor'),
        'fugu_elementor_proyectos_gdr_callback',
        'proyectos',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_proyectos_provincia_meta_box');

function fugu_elementor_proyectos_provincia_callback($post) {
    wp_nonce_field('proyectos_provincia_nonce', 'proyectos_provincia_nonce_field');
    $selected_provincia = get_post_meta($post->ID, '_proyectos_provincia', true);
    
    // Get all provincia terms
    $provincias = get_terms(array(
        'taxonomy'   => 'provincia',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));
    
    echo '<label for="proyectos_provincia_select">' . __('Selecciona Provincia:', 'fugu-elementor') . '</label><br>';
    echo '<select id="proyectos_provincia_select" name="proyectos_provincia" style="width: 100%;">';
    echo '<option value="">' . __('-- Ninguna --', 'fugu-elementor') . '</option>';
    
    if (!is_wp_error($provincias) && !empty($provincias)) {
        foreach ($provincias as $provincia) {
            $selected = ($selected_provincia == $provincia->term_id) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr($provincia->term_id) . '" ' . $selected . '>' . esc_html($provincia->name) . '</option>';
        }
    }
    
    echo '</select>';
}

function fugu_elementor_proyectos_municipio_callback($post) {
    wp_nonce_field('proyectos_municipio_nonce', 'proyectos_municipio_nonce_field');
    $selected_municipio = get_post_meta($post->ID, '_proyectos_municipio', true);
    
    // Get all Municipio posts
    $municipios = get_posts(array(
        'post_type'      => 'municipio',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ));
    
    echo '<label for="proyectos_municipio_select">' . __('Selecciona Municipio:', 'fugu-elementor') . '</label><br>';
    echo '<select id="proyectos_municipio_select" name="proyectos_municipio" style="width: 100%;">';
    echo '<option value="">' . __('-- Ninguno --', 'fugu-elementor') . '</option>';
    
    if (!empty($municipios)) {
        foreach ($municipios as $municipio) {
            $selected = ($selected_municipio == $municipio->ID) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr($municipio->ID) . '" ' . $selected . '>' . esc_html($municipio->post_title) . '</option>';
        }
    }
    
    echo '</select>';
}

function fugu_elementor_proyectos_ayuda_callback($post) {
    wp_nonce_field('proyectos_ayuda_nonce', 'proyectos_ayuda_nonce_field');
    $value = get_post_meta($post->ID, '_proyectos_ayuda', true);
    echo '<label for="proyectos_ayuda_field">' . __('Ayuda:', 'fugu-elementor') . '</label>';
    echo '<input type="text" id="proyectos_ayuda_field" name="proyectos_ayuda" value="' . esc_attr($value) . '" class="widefat">';
}

function fugu_elementor_proyectos_gdr_callback($post) {
    wp_nonce_field('proyectos_gdr_nonce', 'proyectos_gdr_nonce_field');
    $selected_gdr = get_post_meta($post->ID, '_proyectos_gdr', true);
    
    // Get all GDR posts
    $gdrs = get_posts(array(
        'post_type'      => 'galgdr',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ));
    
    echo '<label for="proyectos_gdr_select">' . __('Selecciona GDR:', 'fugu-elementor') . '</label><br>';
    echo '<select id="proyectos_gdr_select" name="proyectos_gdr" style="width: 100%;">';
    echo '<option value="">' . __('-- Ninguno --', 'fugu-elementor') . '</option>';
    
    if (!empty($gdrs)) {
        foreach ($gdrs as $gdr) {
            $selected = ($selected_gdr == $gdr->ID) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr($gdr->ID) . '" ' . $selected . '>' . esc_html($gdr->post_title) . '</option>';
        }
    }
    
    echo '</select>';
}

function fugu_elementor_save_proyectos_provincia($post_id) {
    // Provincia
    if (isset($_POST['proyectos_provincia_nonce_field']) && 
        wp_verify_nonce($_POST['proyectos_provincia_nonce_field'], 'proyectos_provincia_nonce')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['proyectos_provincia'])) {
                    update_post_meta($post_id, '_proyectos_provincia', absint($_POST['proyectos_provincia']));
                }
            }
        }
    }
    
    // Municipio
    if (isset($_POST['proyectos_municipio_nonce_field']) && 
        wp_verify_nonce($_POST['proyectos_municipio_nonce_field'], 'proyectos_municipio_nonce')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['proyectos_municipio'])) {
                    update_post_meta($post_id, '_proyectos_municipio', absint($_POST['proyectos_municipio']));
                }
            }
        }
    }
    
    // Ayuda
    if (isset($_POST['proyectos_ayuda_nonce_field']) && 
        wp_verify_nonce($_POST['proyectos_ayuda_nonce_field'], 'proyectos_ayuda_nonce')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['proyectos_ayuda'])) {
                    update_post_meta($post_id, '_proyectos_ayuda', sanitize_text_field($_POST['proyectos_ayuda']));
                }
            }
        }
    }
    
    // GDR
    if (isset($_POST['proyectos_gdr_nonce_field']) && 
        wp_verify_nonce($_POST['proyectos_gdr_nonce_field'], 'proyectos_gdr_nonce')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['proyectos_gdr'])) {
                    $gdr_id = absint($_POST['proyectos_gdr']);
                    update_post_meta($post_id, '_proyectos_gdr', $gdr_id);
                    
                    // Auto-sync siglas from GDR
                    if ($gdr_id) {
                        $siglas = get_post_meta($gdr_id, '_galgdr_siglas', true);
                        update_post_meta($post_id, '_proyectos_gdr_siglas', $siglas);
                    } else {
                        delete_post_meta($post_id, '_proyectos_gdr_siglas');
                    }
                }
            }
        }
    }
}
add_action('save_post_proyectos', 'fugu_elementor_save_proyectos_provincia');

/**
 * Register Provincia custom field for Elementor
 * Make it available in REST API and Elementor
 */
function fugu_elementor_register_provincia_meta() {
    if (!get_theme_mod('enable_proyectos_cpt', false)) {
        return;
    }
    
    register_post_meta('proyectos', '_proyectos_provincia', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'description' => __('Provincia del proyecto', 'fugu-elementor'),
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'fugu_elementor_register_provincia_meta');

/**
 * Add Large Image custom field to Portfolio
 */
function fugu_elementor_add_portfolio_large_image_meta_box() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    add_meta_box(
        'portfolio_large_image',
        __('Large Image', 'fugu-elementor'),
        'fugu_elementor_portfolio_large_image_callback',
        'portfolio',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_portfolio_large_image_meta_box');

function fugu_elementor_portfolio_large_image_callback($post) {
    wp_nonce_field('portfolio_large_image_nonce', 'portfolio_large_image_nonce_field');
    $image_id = get_post_meta($post->ID, '_portfolio_large_image', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
    ?>
    <div class="portfolio-large-image-wrapper">
        <input type="hidden" id="portfolio_large_image_id" name="portfolio_large_image_id" value="<?php echo esc_attr($image_id); ?>">
        <div class="portfolio-large-image-preview" style="margin-bottom: 10px;">
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100%; height: auto; display: block;">
            <?php endif; ?>
        </div>
        <button type="button" class="button portfolio-upload-image-button"><?php _e('Set Large Image', 'fugu-elementor'); ?></button>
        <?php if ($image_id): ?>
            <button type="button" class="button portfolio-remove-image-button" style="margin-left: 5px;"><?php _e('Remove', 'fugu-elementor'); ?></button>
        <?php endif; ?>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var frame;
        $('.portfolio-upload-image-button').on('click', function(e) {
            e.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media({
                title: '<?php _e('Select Large Image', 'fugu-elementor'); ?>',
                button: {
                    text: '<?php _e('Use this image', 'fugu-elementor'); ?>'
                },
                multiple: false
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#portfolio_large_image_id').val(attachment.id);
                $('.portfolio-large-image-preview').html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto; display: block;">');
                if (!$('.portfolio-remove-image-button').length) {
                    $('.portfolio-upload-image-button').after('<button type="button" class="button portfolio-remove-image-button" style="margin-left: 5px;"><?php _e('Remove', 'fugu-elementor'); ?></button>');
                }
            });
            frame.open();
        });
        
        $(document).on('click', '.portfolio-remove-image-button', function(e) {
            e.preventDefault();
            $('#portfolio_large_image_id').val('');
            $('.portfolio-large-image-preview').html('');
            $(this).remove();
        });
    });
    </script>
    <?php
}

function fugu_elementor_save_portfolio_large_image($post_id) {
    if (!isset($_POST['portfolio_large_image_nonce_field']) || 
        !wp_verify_nonce($_POST['portfolio_large_image_nonce_field'], 'portfolio_large_image_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['portfolio_large_image_id'])) {
        update_post_meta($post_id, '_portfolio_large_image', absint($_POST['portfolio_large_image_id']));
    }
}
add_action('save_post_portfolio', 'fugu_elementor_save_portfolio_large_image');

/**
 * Register Large Image custom field for Elementor
 * Make it available in REST API and Elementor
 */
function fugu_elementor_register_portfolio_large_image_meta() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    register_post_meta('portfolio', '_portfolio_large_image', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
        'description' => __('Large Image ID', 'fugu-elementor'),
        'sanitize_callback' => 'absint',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'fugu_elementor_register_portfolio_large_image_meta');

/**
 * Add Medium Image custom field to Portfolio
 */
function fugu_elementor_add_portfolio_medium_image_meta_box() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    add_meta_box(
        'portfolio_medium_image',
        __('Medium Image', 'fugu-elementor'),
        'fugu_elementor_portfolio_medium_image_callback',
        'portfolio',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_portfolio_medium_image_meta_box');

function fugu_elementor_portfolio_medium_image_callback($post) {
    wp_nonce_field('portfolio_medium_image_nonce', 'portfolio_medium_image_nonce_field');
    $image_id = get_post_meta($post->ID, '_portfolio_medium_image', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
    ?>
    <div class="portfolio-medium-image-wrapper">
        <input type="hidden" id="portfolio_medium_image_id" name="portfolio_medium_image_id" value="<?php echo esc_attr($image_id); ?>">
        <div class="portfolio-medium-image-preview" style="margin-bottom: 10px;">
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100%; height: auto; display: block;">
            <?php endif; ?>
        </div>
        <button type="button" class="button portfolio-upload-medium-image-button"><?php _e('Set Medium Image', 'fugu-elementor'); ?></button>
        <?php if ($image_id): ?>
            <button type="button" class="button portfolio-remove-medium-image-button" style="margin-left: 5px;"><?php _e('Remove', 'fugu-elementor'); ?></button>
        <?php endif; ?>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var mediumFrame;
        $('.portfolio-upload-medium-image-button').on('click', function(e) {
            e.preventDefault();
            if (mediumFrame) {
                mediumFrame.open();
                return;
            }
            mediumFrame = wp.media({
                title: '<?php _e('Select Medium Image', 'fugu-elementor'); ?>',
                button: {
                    text: '<?php _e('Use this image', 'fugu-elementor'); ?>'
                },
                multiple: false
            });
            mediumFrame.on('select', function() {
                var attachment = mediumFrame.state().get('selection').first().toJSON();
                $('#portfolio_medium_image_id').val(attachment.id);
                $('.portfolio-medium-image-preview').html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto; display: block;">');
                if (!$('.portfolio-remove-medium-image-button').length) {
                    $('.portfolio-upload-medium-image-button').after('<button type="button" class="button portfolio-remove-medium-image-button" style="margin-left: 5px;"><?php _e('Remove', 'fugu-elementor'); ?></button>');
                }
            });
            mediumFrame.open();
        });
        
        $(document).on('click', '.portfolio-remove-medium-image-button', function(e) {
            e.preventDefault();
            $('#portfolio_medium_image_id').val('');
            $('.portfolio-medium-image-preview').html('');
            $(this).remove();
        });
    });
    </script>
    <?php
}

function fugu_elementor_save_portfolio_medium_image($post_id) {
    if (!isset($_POST['portfolio_medium_image_nonce_field']) || 
        !wp_verify_nonce($_POST['portfolio_medium_image_nonce_field'], 'portfolio_medium_image_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['portfolio_medium_image_id'])) {
        update_post_meta($post_id, '_portfolio_medium_image', absint($_POST['portfolio_medium_image_id']));
    }
}
add_action('save_post_portfolio', 'fugu_elementor_save_portfolio_medium_image');

/**
 * Register Medium Image custom field for Elementor
 * Make it available in REST API and Elementor
 */
function fugu_elementor_register_portfolio_medium_image_meta() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    register_post_meta('portfolio', '_portfolio_medium_image', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
        'description' => __('Medium Image ID', 'fugu-elementor'),
        'sanitize_callback' => 'absint',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'fugu_elementor_register_portfolio_medium_image_meta');

/**
 * Add Small Image custom field to Portfolio
 */
function fugu_elementor_add_portfolio_small_image_meta_box() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    add_meta_box(
        'portfolio_small_image',
        __('Small Image', 'fugu-elementor'),
        'fugu_elementor_portfolio_small_image_callback',
        'portfolio',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_portfolio_small_image_meta_box');

function fugu_elementor_portfolio_small_image_callback($post) {
    wp_nonce_field('portfolio_small_image_nonce', 'portfolio_small_image_nonce_field');
    $image_id = get_post_meta($post->ID, '_portfolio_small_image', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
    ?>
    <div class="portfolio-small-image-wrapper">
        <input type="hidden" id="portfolio_small_image_id" name="portfolio_small_image_id" value="<?php echo esc_attr($image_id); ?>">
        <div class="portfolio-small-image-preview" style="margin-bottom: 10px;">
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100%; height: auto; display: block;">
            <?php endif; ?>
        </div>
        <button type="button" class="button portfolio-upload-small-image-button"><?php _e('Set Small Image', 'fugu-elementor'); ?></button>
        <?php if ($image_id): ?>
            <button type="button" class="button portfolio-remove-small-image-button" style="margin-left: 5px;"><?php _e('Remove', 'fugu-elementor'); ?></button>
        <?php endif; ?>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var smallFrame;
        $('.portfolio-upload-small-image-button').on('click', function(e) {
            e.preventDefault();
            if (smallFrame) {
                smallFrame.open();
                return;
            }
            smallFrame = wp.media({
                title: '<?php _e('Select Small Image', 'fugu-elementor'); ?>',
                button: {
                    text: '<?php _e('Use this image', 'fugu-elementor'); ?>'
                },
                multiple: false
            });
            smallFrame.on('select', function() {
                var attachment = smallFrame.state().get('selection').first().toJSON();
                $('#portfolio_small_image_id').val(attachment.id);
                $('.portfolio-small-image-preview').html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto; display: block;">');
                if (!$('.portfolio-remove-small-image-button').length) {
                    $('.portfolio-upload-small-image-button').after('<button type="button" class="button portfolio-remove-small-image-button" style="margin-left: 5px;"><?php _e('Remove', 'fugu-elementor'); ?></button>');
                }
            });
            smallFrame.open();
        });
        
        $(document).on('click', '.portfolio-remove-small-image-button', function(e) {
            e.preventDefault();
            $('#portfolio_small_image_id').val('');
            $('.portfolio-small-image-preview').html('');
            $(this).remove();
        });
    });
    </script>
    <?php
}

function fugu_elementor_save_portfolio_small_image($post_id) {
    if (!isset($_POST['portfolio_small_image_nonce_field']) || 
        !wp_verify_nonce($_POST['portfolio_small_image_nonce_field'], 'portfolio_small_image_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['portfolio_small_image_id'])) {
        update_post_meta($post_id, '_portfolio_small_image', absint($_POST['portfolio_small_image_id']));
    }
}
add_action('save_post_portfolio', 'fugu_elementor_save_portfolio_small_image');

/**
 * Register Small Image custom field for Elementor
 * Make it available in REST API and Elementor
 */
function fugu_elementor_register_portfolio_small_image_meta() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    register_post_meta('portfolio', '_portfolio_small_image', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
        'description' => __('Small Image ID', 'fugu-elementor'),
        'sanitize_callback' => 'absint',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'fugu_elementor_register_portfolio_small_image_meta');

/**
 * Add Title Color custom field to Portfolio
 */
function fugu_elementor_add_portfolio_title_color_meta_box() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    add_meta_box(
        'portfolio_title_color',
        __('Title Color', 'fugu-elementor'),
        'fugu_elementor_portfolio_title_color_callback',
        'portfolio',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_portfolio_title_color_meta_box');

function fugu_elementor_portfolio_title_color_callback($post) {
    wp_nonce_field('portfolio_title_color_nonce', 'portfolio_title_color_nonce_field');
    $value = get_post_meta($post->ID, 'portfolio_title_color', true);
    $value = $value ? $value : 'light';
    ?>
    <div class="portfolio-title-color-wrapper">
        <label>
            <input type="radio" name="portfolio_title_color" value="dark" <?php checked($value, 'dark'); ?>>
            <span style="display: inline-block; width: 20px; height: 20px; background-color: #313C59; vertical-align: middle; margin-right: 5px; border: 1px solid #ccc;"></span>
            <?php _e('DARK', 'fugu-elementor'); ?>
        </label>
        <br><br>
        <label>
            <input type="radio" name="portfolio_title_color" value="light" <?php checked($value, 'light'); ?>>
            <span style="display: inline-block; width: 20px; height: 20px; background-color: #ffffff; vertical-align: middle; margin-right: 5px; border: 1px solid #ccc;"></span>
            <?php _e('LIGHT', 'fugu-elementor'); ?>
        </label>
    </div>
    <?php
}

function fugu_elementor_save_portfolio_title_color($post_id) {
    if (!isset($_POST['portfolio_title_color_nonce_field']) || 
        !wp_verify_nonce($_POST['portfolio_title_color_nonce_field'], 'portfolio_title_color_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['portfolio_title_color'])) {
        $allowed_values = array('dark', 'light');
        $color_value = sanitize_text_field($_POST['portfolio_title_color']);
        
        if (in_array($color_value, $allowed_values)) {
            update_post_meta($post_id, 'portfolio_title_color', $color_value);
        }
    }
}
add_action('save_post_portfolio', 'fugu_elementor_save_portfolio_title_color');

/**
 * Register Title Color custom field for Elementor
 * Make it available in REST API and Elementor
 */
function fugu_elementor_register_portfolio_title_color_meta() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    register_post_meta('portfolio', '_portfolio_title_color', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'description' => __('Title Color', 'fugu-elementor'),
        'sanitize_callback' => 'sanitize_hex_color',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'fugu_elementor_register_portfolio_title_color_meta');

/**
 * Add Button Text and Button Link custom fields to Portfolio
 */
function fugu_elementor_add_portfolio_button_meta_box() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    add_meta_box(
        'portfolio_button_fields',
        __('Button Settings', 'fugu-elementor'),
        'fugu_elementor_portfolio_button_callback',
        'portfolio',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_portfolio_button_meta_box');

function fugu_elementor_portfolio_button_callback($post) {
    wp_nonce_field('portfolio_button_nonce', 'portfolio_button_nonce_field');
    $button_text = get_post_meta($post->ID, '_portfolio_button_text', true);
    $button_link = get_post_meta($post->ID, '_portfolio_button_link', true);
    ?>
    <p>
        <label for="portfolio_button_text"><strong><?php _e('Button Text', 'fugu-elementor'); ?></strong></label><br>
        <input type="text" id="portfolio_button_text" name="portfolio_button_text" value="<?php echo esc_attr($button_text); ?>" class="widefat">
    </p>
    <p>
        <label for="portfolio_button_link"><strong><?php _e('Button Link', 'fugu-elementor'); ?></strong></label><br>
        <input type="url" id="portfolio_button_link" name="portfolio_button_link" value="<?php echo esc_url($button_link); ?>" class="widefat" placeholder="https://">
    </p>
    <?php
}

function fugu_elementor_save_portfolio_button($post_id) {
    if (!isset($_POST['portfolio_button_nonce_field']) || 
        !wp_verify_nonce($_POST['portfolio_button_nonce_field'], 'portfolio_button_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['portfolio_button_text'])) {
        update_post_meta($post_id, '_portfolio_button_text', sanitize_text_field($_POST['portfolio_button_text']));
    }
    
    if (isset($_POST['portfolio_button_link'])) {
        update_post_meta($post_id, '_portfolio_button_link', esc_url_raw($_POST['portfolio_button_link']));
    }
}
add_action('save_post_portfolio', 'fugu_elementor_save_portfolio_button');

/**
 * Register Button Text and Button Link custom fields for Elementor
 * Make them available in REST API and Elementor
 */
function fugu_elementor_register_portfolio_button_meta() {
    if (!get_theme_mod('enable_portfolio_cpt', false)) {
        return;
    }
    
    register_post_meta('portfolio', '_portfolio_button_text', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'description' => __('Button Text', 'fugu-elementor'),
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
    
    register_post_meta('portfolio', '_portfolio_button_link', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'description' => __('Button Link', 'fugu-elementor'),
        'sanitize_callback' => 'esc_url_raw',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'fugu_elementor_register_portfolio_button_meta');

/**
 * Register Proyectos Category Taxonomy
 */
function fugu_elementor_register_proyectos_category() {
    if (!get_theme_mod('enable_proyectos_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                       => _x('Categorías de Proyectos', 'Taxonomy General Name', 'fugu-elementor'),
        'singular_name'              => _x('Categoría de Proyecto', 'Taxonomy Singular Name', 'fugu-elementor'),
        'menu_name'                  => __('Categorías', 'fugu-elementor'),
        'all_items'                  => __('Todas las Categorías', 'fugu-elementor'),
        'parent_item'                => __('Categoría Padre', 'fugu-elementor'),
        'parent_item_colon'          => __('Categoría Padre:', 'fugu-elementor'),
        'new_item_name'              => __('Nuevo Nombre de Categoría', 'fugu-elementor'),
        'add_new_item'               => __('Añadir Nueva Categoría', 'fugu-elementor'),
        'edit_item'                  => __('Editar Categoría', 'fugu-elementor'),
        'update_item'                => __('Actualizar Categoría', 'fugu-elementor'),
        'view_item'                  => __('Ver Categoría', 'fugu-elementor'),
        'separate_items_with_commas' => __('Separar categorías con comas', 'fugu-elementor'),
        'add_or_remove_items'        => __('Añadir o eliminar categorías', 'fugu-elementor'),
        'choose_from_most_used'      => __('Elegir de las más usadas', 'fugu-elementor'),
        'popular_items'              => __('Categorías Populares', 'fugu-elementor'),
        'search_items'               => __('Buscar Categorías', 'fugu-elementor'),
        'not_found'                  => __('No Encontrado', 'fugu-elementor'),
        'no_terms'                   => __('No hay categorías', 'fugu-elementor'),
        'items_list'                 => __('Lista de categorías', 'fugu-elementor'),
        'items_list_navigation'      => __('Navegación de lista de categorías', 'fugu-elementor'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );

    register_taxonomy('proyectos_category', array('proyectos'), $args);
}
add_action('init', 'fugu_elementor_register_proyectos_category', 0);

/**
 * Register Proyectos Tag Taxonomy
 */
function fugu_elementor_register_proyectos_tag() {
    if (!get_theme_mod('enable_proyectos_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                       => _x('Etiquetas de Proyectos', 'Taxonomy General Name', 'fugu-elementor'),
        'singular_name'              => _x('Etiqueta de Proyecto', 'Taxonomy Singular Name', 'fugu-elementor'),
        'menu_name'                  => __('Etiquetas', 'fugu-elementor'),
        'all_items'                  => __('Todas las Etiquetas', 'fugu-elementor'),
        'parent_item'                => __('Etiqueta Padre', 'fugu-elementor'),
        'parent_item_colon'          => __('Etiqueta Padre:', 'fugu-elementor'),
        'new_item_name'              => __('Nuevo Nombre de Etiqueta', 'fugu-elementor'),
        'add_new_item'               => __('Añadir Nueva Etiqueta', 'fugu-elementor'),
        'edit_item'                  => __('Editar Etiqueta', 'fugu-elementor'),
        'update_item'                => __('Actualizar Etiqueta', 'fugu-elementor'),
        'view_item'                  => __('Ver Etiqueta', 'fugu-elementor'),
        'separate_items_with_commas' => __('Separar etiquetas con comas', 'fugu-elementor'),
        'add_or_remove_items'        => __('Añadir o eliminar etiquetas', 'fugu-elementor'),
        'choose_from_most_used'      => __('Elegir de las más usadas', 'fugu-elementor'),
        'popular_items'              => __('Etiquetas Populares', 'fugu-elementor'),
        'search_items'               => __('Buscar Etiquetas', 'fugu-elementor'),
        'not_found'                  => __('No Encontrado', 'fugu-elementor'),
        'no_terms'                   => __('No hay etiquetas', 'fugu-elementor'),
        'items_list'                 => __('Lista de etiquetas', 'fugu-elementor'),
        'items_list_navigation'      => __('Navegación de lista de etiquetas', 'fugu-elementor'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );

    register_taxonomy('proyectos_tag', array('proyectos'), $args);
}
add_action('init', 'fugu_elementor_register_proyectos_tag', 0);

/**
 * Register Noticias Post Type
 */
function fugu_elementor_register_noticias_cpt() {
    if (!get_theme_mod('enable_noticias_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                  => _x('Noticias', 'Post Type General Name', 'fugu-elementor'),
        'singular_name'         => _x('Noticia', 'Post Type Singular Name', 'fugu-elementor'),
        'menu_name'             => __('Noticias', 'fugu-elementor'),
        'name_admin_bar'        => __('Noticia', 'fugu-elementor'),
        'archives'              => __('Noticias Archives', 'fugu-elementor'),
        'attributes'            => __('Noticia Attributes', 'fugu-elementor'),
        'parent_item_colon'     => __('Parent Noticia:', 'fugu-elementor'),
        'all_items'             => __('Todas las Noticias', 'fugu-elementor'),
        'add_new_item'          => __('Añadir Nueva Noticia', 'fugu-elementor'),
        'add_new'               => __('Añadir Nueva', 'fugu-elementor'),
        'new_item'              => __('Nueva Noticia', 'fugu-elementor'),
        'edit_item'             => __('Editar Noticia', 'fugu-elementor'),
        'update_item'           => __('Actualizar Noticia', 'fugu-elementor'),
        'view_item'             => __('Ver Noticia', 'fugu-elementor'),
        'view_items'            => __('Ver Noticias', 'fugu-elementor'),
        'search_items'          => __('Buscar Noticias', 'fugu-elementor'),
        'not_found'             => __('No encontrado', 'fugu-elementor'),
        'not_found_in_trash'    => __('No encontrado en papelera', 'fugu-elementor'),
        'featured_image'        => __('Imagen Destacada', 'fugu-elementor'),
        'set_featured_image'    => __('Establecer imagen destacada', 'fugu-elementor'),
        'remove_featured_image' => __('Eliminar imagen destacada', 'fugu-elementor'),
        'use_featured_image'    => __('Usar como imagen destacada', 'fugu-elementor'),
        'insert_into_item'      => __('Insertar en noticia', 'fugu-elementor'),
        'uploaded_to_this_item' => __('Subido a esta noticia', 'fugu-elementor'),
        'items_list'            => __('Lista de noticias', 'fugu-elementor'),
        'items_list_navigation' => __('Navegación de lista de noticias', 'fugu-elementor'),
        'filter_items_list'     => __('Filtrar lista de noticias', 'fugu-elementor'),
    );

    $args = array(
        'label'                 => __('Noticia', 'fugu-elementor'),
        'description'           => __('Noticias', 'fugu-elementor'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'author', 'comments'),
        'taxonomies'            => array('noticias_category', 'noticias_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 7,
        'menu_icon'             => 'dashicons-megaphone',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('noticias', $args);
}
add_action('init', 'fugu_elementor_register_noticias_cpt', 0);

/**
 * Register Noticias Category Taxonomy
 */
function fugu_elementor_register_noticias_category() {
    if (!get_theme_mod('enable_noticias_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                       => _x('Categorías de Noticias', 'Taxonomy General Name', 'fugu-elementor'),
        'singular_name'              => _x('Categoría de Noticia', 'Taxonomy Singular Name', 'fugu-elementor'),
        'menu_name'                  => __('Categorías', 'fugu-elementor'),
        'all_items'                  => __('Todas las Categorías', 'fugu-elementor'),
        'parent_item'                => __('Categoría Padre', 'fugu-elementor'),
        'parent_item_colon'          => __('Categoría Padre:', 'fugu-elementor'),
        'new_item_name'              => __('Nuevo Nombre de Categoría', 'fugu-elementor'),
        'add_new_item'               => __('Añadir Nueva Categoría', 'fugu-elementor'),
        'edit_item'                  => __('Editar Categoría', 'fugu-elementor'),
        'update_item'                => __('Actualizar Categoría', 'fugu-elementor'),
        'view_item'                  => __('Ver Categoría', 'fugu-elementor'),
        'separate_items_with_commas' => __('Separar categorías con comas', 'fugu-elementor'),
        'add_or_remove_items'        => __('Añadir o eliminar categorías', 'fugu-elementor'),
        'choose_from_most_used'      => __('Elegir de las más usadas', 'fugu-elementor'),
        'popular_items'              => __('Categorías Populares', 'fugu-elementor'),
        'search_items'               => __('Buscar Categorías', 'fugu-elementor'),
        'not_found'                  => __('No Encontrado', 'fugu-elementor'),
        'no_terms'                   => __('No hay categorías', 'fugu-elementor'),
        'items_list'                 => __('Lista de categorías', 'fugu-elementor'),
        'items_list_navigation'      => __('Navegación de lista de categorías', 'fugu-elementor'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );

    register_taxonomy('noticias_category', array('noticias'), $args);
}
add_action('init', 'fugu_elementor_register_noticias_category', 0);

/**
 * Register Noticias Tag Taxonomy
 */
function fugu_elementor_register_noticias_tag() {
    if (!get_theme_mod('enable_noticias_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                       => _x('Etiquetas de Noticias', 'Taxonomy General Name', 'fugu-elementor'),
        'singular_name'              => _x('Etiqueta de Noticia', 'Taxonomy Singular Name', 'fugu-elementor'),
        'menu_name'                  => __('Etiquetas', 'fugu-elementor'),
        'all_items'                  => __('Todas las Etiquetas', 'fugu-elementor'),
        'parent_item'                => __('Etiqueta Padre', 'fugu-elementor'),
        'parent_item_colon'          => __('Etiqueta Padre:', 'fugu-elementor'),
        'new_item_name'              => __('Nuevo Nombre de Etiqueta', 'fugu-elementor'),
        'add_new_item'               => __('Añadir Nueva Etiqueta', 'fugu-elementor'),
        'edit_item'                  => __('Editar Etiqueta', 'fugu-elementor'),
        'update_item'                => __('Actualizar Etiqueta', 'fugu-elementor'),
        'view_item'                  => __('Ver Etiqueta', 'fugu-elementor'),
        'separate_items_with_commas' => __('Separar etiquetas con comas', 'fugu-elementor'),
        'add_or_remove_items'        => __('Añadir o eliminar etiquetas', 'fugu-elementor'),
        'choose_from_most_used'      => __('Elegir de las más usadas', 'fugu-elementor'),
        'popular_items'              => __('Etiquetas Populares', 'fugu-elementor'),
        'search_items'               => __('Buscar Etiquetas', 'fugu-elementor'),
        'not_found'                  => __('No Encontrado', 'fugu-elementor'),
        'no_terms'                   => __('No hay etiquetas', 'fugu-elementor'),
        'items_list'                 => __('Lista de etiquetas', 'fugu-elementor'),
        'items_list_navigation'      => __('Navegación de lista de etiquetas', 'fugu-elementor'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );

    register_taxonomy('noticias_tag', array('noticias'), $args);
}
add_action('init', 'fugu_elementor_register_noticias_tag', 0);

/**
 * Add custom meta box for Slider (toggle/checkbox)
 */
function fugu_elementor_add_slider_meta_box() {
    add_meta_box(
        'slider_toggle',
        __('Show in Slider', 'fugu-elementor'),
        'fugu_elementor_slider_toggle_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_add_slider_meta_box');

/**
 * Add Slider Link custom fields to all posts, CPTs and pages
 */
function fugu_elementor_add_slider_link_meta_boxes() {
    $post_types = get_post_types(array('public' => true), 'names');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'slider_link_fields',
            __('Slider Link', 'fugu-elementor'),
            'fugu_elementor_slider_link_callback',
            $post_type,
            'side',
            'default'
        );
    }
}
add_action('add_meta_boxes', 'fugu_elementor_add_slider_link_meta_boxes');

/**
 * Slider link meta box callback
 */
function fugu_elementor_slider_link_callback($post) {
    wp_nonce_field('slider_link_nonce', 'slider_link_nonce_field');
    
    $titulo_slider = get_post_meta($post->ID, '_slider_titulo_slider', true);
    $titulo_link = get_post_meta($post->ID, '_slider_titulo_link', true);
    $url_link = get_post_meta($post->ID, '_slider_url_link', true);
    
    // Check if this is a synced Noticias Slider post
    $original_post_id = get_post_meta($post->ID, '_original_post_id', true);
    $default_title = get_the_title($post->ID);
    $default_url = get_permalink($post->ID);
    
    if ($original_post_id && get_post($original_post_id)) {
        // Use original post's title and permalink as defaults
        $default_title = get_the_title($original_post_id);
        $default_url = get_permalink($original_post_id);
    }
    
    // Use post title as default if field is empty
    if (empty($titulo_slider)) {
        $titulo_slider = $default_title;
    }
    
    // Use post permalink as default if field is empty
    if (empty($url_link)) {
        $url_link = $default_url;
    }
    
    echo '<p><label for="slider_titulo_slider">' . __('Título Slider:', 'fugu-elementor') . '</label></p>';
    echo '<input type="text" id="slider_titulo_slider" name="slider_titulo_slider" value="' . esc_attr($titulo_slider) . '" class="widefat" placeholder="' . esc_attr($default_title) . '">';
    
    echo '<p style="margin-top: 15px;"><label for="slider_titulo_link">' . __('Título Link:', 'fugu-elementor') . '</label></p>';
    echo '<input type="text" id="slider_titulo_link" name="slider_titulo_link" value="' . esc_attr($titulo_link) . '" class="widefat" placeholder="' . __('Link title', 'fugu-elementor') . '">';
    
    echo '<p style="margin-top: 15px;"><label for="slider_url_link">' . __('URL Link:', 'fugu-elementor') . '</label></p>';
    echo '<input type="url" id="slider_url_link" name="slider_url_link" value="' . esc_url($url_link) . '" class="widefat" placeholder="' . esc_url($default_url) . '">';
    
    echo '<p class="description">' . __('Optional: Add custom titles and URL for slider display. Defaults to post title and permalink if empty.', 'fugu-elementor') . '</p>';
}

/**
 * Slider toggle meta box callback
 */
function fugu_elementor_slider_toggle_callback($post) {
    wp_nonce_field('slider_toggle_nonce', 'slider_toggle_nonce_field');
    
    $is_in_slider = get_post_meta($post->ID, '_show_in_slider', true);
    $checked = ($is_in_slider === '1') ? 'checked="checked"' : '';
    
    echo '<label style="display: block;">';
    echo '<input type="checkbox" name="show_in_slider" value="1" ' . $checked . '> ';
    echo __('Enable this post to appear in Noticias Slider', 'fugu-elementor');
    echo '</label>';
    echo '<p class="description">' . __('When enabled, this post will automatically sync to Noticias Slider CPT.', 'fugu-elementor') . '</p>';
}

/**
 * Save Slider toggle and sync to Noticias Slider
 */
function fugu_elementor_save_slider_toggle($post_id) {
    // Only process regular posts
    if (get_post_type($post_id) !== 'post') {
        return;
    }
    
    // Check nonce
    if (!isset($_POST['slider_toggle_nonce_field']) || 
        !wp_verify_nonce($_POST['slider_toggle_nonce_field'], 'slider_toggle_nonce')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Get the checkbox value
    $show_in_slider = isset($_POST['show_in_slider']) ? '1' : '0';
    update_post_meta($post_id, '_show_in_slider', $show_in_slider);
    
    // Sync to Noticias Slider based on toggle
    if ($show_in_slider === '1') {
        fugu_elementor_sync_to_noticias_slider($post_id);
    } else {
        fugu_elementor_remove_from_noticias_slider($post_id);
    }
}
add_action('save_post', 'fugu_elementor_save_slider_toggle');

/**
 * Save Slider Link custom fields
 */
function fugu_elementor_save_slider_link_fields($post_id) {
    // Check nonce
    if (!isset($_POST['slider_link_nonce_field']) || 
        !wp_verify_nonce($_POST['slider_link_nonce_field'], 'slider_link_nonce')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save Título Slider
    if (isset($_POST['slider_titulo_slider'])) {
        update_post_meta($post_id, '_slider_titulo_slider', sanitize_text_field($_POST['slider_titulo_slider']));
    }
    
    // Save Título Link
    if (isset($_POST['slider_titulo_link'])) {
        update_post_meta($post_id, '_slider_titulo_link', sanitize_text_field($_POST['slider_titulo_link']));
    }
    
    // Save URL Link
    if (isset($_POST['slider_url_link'])) {
        update_post_meta($post_id, '_slider_url_link', esc_url_raw($_POST['slider_url_link']));
    }
}
add_action('save_post', 'fugu_elementor_save_slider_link_fields');

/**
 * Add Slider column to posts admin list
 */
function fugu_elementor_add_slider_column($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        // Add Slider column after title
        if ($key === 'title') {
            $new_columns['show_in_slider'] = __('Slider', 'fugu-elementor');
        }
    }
    
    return $new_columns;
}
add_filter('manage_post_posts_columns', 'fugu_elementor_add_slider_column');

/**
 * Display Slider column content
 */
function fugu_elementor_display_slider_column($column, $post_id) {
    if ($column === 'show_in_slider') {
        $is_in_slider = get_post_meta($post_id, '_show_in_slider', true);
        $nonce = wp_create_nonce('toggle_slider_' . $post_id);
        
        if ($is_in_slider === '1') {
            echo '<a href="#" class="toggle-slider" data-post-id="' . esc_attr($post_id) . '" data-nonce="' . esc_attr($nonce) . '" data-status="1" style="text-decoration: none;">';
            echo '<span style="color: #46b450; font-weight: bold; cursor: pointer;">✓ Yes</span>';
            echo '</a>';
        } else {
            echo '<a href="#" class="toggle-slider" data-post-id="' . esc_attr($post_id) . '" data-nonce="' . esc_attr($nonce) . '" data-status="0" style="text-decoration: none;">';
            echo '<span style="color: #dc3232; cursor: pointer;">✕ No</span>';
            echo '</a>';
        }
    }
}
add_action('manage_post_posts_custom_column', 'fugu_elementor_display_slider_column', 10, 2);

/**
 * Add AJAX handler for toggling slider
 */
function fugu_elementor_ajax_toggle_slider() {
    // Verify nonce
    $post_id = intval($_POST['post_id']);
    $nonce = sanitize_text_field($_POST['nonce']);
    
    if (!wp_verify_nonce($nonce, 'toggle_slider_' . $post_id)) {
        wp_send_json_error('Invalid nonce');
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error('No permission');
        return;
    }
    
    // Get current status and toggle
    $current = get_post_meta($post_id, '_show_in_slider', true);
    $new_status = ($current === '1') ? '0' : '1';
    
    // Update meta
    update_post_meta($post_id, '_show_in_slider', $new_status);
    
    // Sync to Noticias Slider
    if ($new_status === '1') {
        fugu_elementor_sync_to_noticias_slider($post_id);
    } else {
        fugu_elementor_remove_from_noticias_slider($post_id);
    }
    
    wp_send_json_success(array('status' => $new_status));
}
add_action('wp_ajax_toggle_slider', 'fugu_elementor_ajax_toggle_slider');

/**
 * Add JavaScript for AJAX toggle
 */
function fugu_elementor_slider_toggle_script() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'edit-post') {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(document).on('click', '.toggle-slider', function(e) {
                e.preventDefault();
                
                var $link = $(this);
                var postId = $link.data('post-id');
                var nonce = $link.data('nonce');
                var currentStatus = $link.data('status');
                
                // Show loading
                $link.find('span').html('⟳ ...');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'toggle_slider',
                        post_id: postId,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            var newStatus = response.data.status;
                            
                            // Update display
                            if (newStatus === '1') {
                                $link.find('span').html('✓ Yes').css('color', '#46b450').css('font-weight', 'bold');
                                $link.data('status', '1');
                            } else {
                                $link.find('span').html('✕ No').css('color', '#dc3232').css('font-weight', 'normal');
                                $link.data('status', '0');
                            }
                            
                            // Update nonce for next toggle
                            $.post(ajaxurl, {
                                action: 'get_new_slider_nonce',
                                post_id: postId
                            }, function(data) {
                                if (data.success) {
                                    $link.data('nonce', data.data.nonce);
                                }
                            });
                        } else {
                            alert('Error: ' + response.data);
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('Error toggling slider status');
                        location.reload();
                    }
                });
            });
        });
        </script>
        <?php
    }
}
add_action('admin_footer', 'fugu_elementor_slider_toggle_script');

/**
 * Generate new nonce for next toggle
 */
function fugu_elementor_ajax_new_slider_nonce() {
    $post_id = intval($_POST['post_id']);
    $nonce = wp_create_nonce('toggle_slider_' . $post_id);
    wp_send_json_success(array('nonce' => $nonce));
}
add_action('wp_ajax_get_new_slider_nonce', 'fugu_elementor_ajax_new_slider_nonce');

/**
 * Make Slider column sortable
 */
function fugu_elementor_sortable_slider_column($columns) {
    $columns['show_in_slider'] = 'show_in_slider';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'fugu_elementor_sortable_slider_column');

/**
 * Handle Slider column sorting
 */
function fugu_elementor_slider_column_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    if ($query->get('orderby') === 'show_in_slider') {
        $query->set('meta_key', '_show_in_slider');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'fugu_elementor_slider_column_orderby');

/**
 * Register Perfil Contratante Post Type
 */
function fugu_elementor_register_perfil_contratante_cpt() {
    if (!get_theme_mod('enable_perfil_contratante_cpt', false)) {
        return;
    }

    $labels = array(
        'name'                  => _x('Perfil del contratante', 'Post Type General Name', 'fugu-elementor'),
        'singular_name'         => _x('Perfil del contratante', 'Post Type Singular Name', 'fugu-elementor'),
        'menu_name'             => __('Perfil del contratante', 'fugu-elementor'),
        'name_admin_bar'        => __('Perfil del contratante', 'fugu-elementor'),
        'archives'              => __('Archivo de perfil del contratante', 'fugu-elementor'),
        'attributes'            => __('Atributos del perfil del contratante', 'fugu-elementor'),
        'all_items'             => __('Todos los perfil del contratante', 'fugu-elementor'),
        'add_new_item'          => __('Añadir nuevo perfil del contratante', 'fugu-elementor'),
        'add_new'               => __('Añadir nuevo', 'fugu-elementor'),
        'new_item'              => __('Nuevo perfil del contratante', 'fugu-elementor'),
        'edit_item'             => __('Editar perfil del contratante', 'fugu-elementor'),
        'update_item'           => __('Actualizar perfil del contratante', 'fugu-elementor'),
        'view_item'             => __('Ver perfil del contratante', 'fugu-elementor'),
        'view_items'            => __('Ver perfil del contratante', 'fugu-elementor'),
        'search_items'          => __('Buscar perfil del contratante', 'fugu-elementor'),
        'not_found'             => __('No encontrado', 'fugu-elementor'),
        'not_found_in_trash'    => __('No encontrado en papelera', 'fugu-elementor'),
        'featured_image'        => __('Imagen destacada', 'fugu-elementor'),
        'set_featured_image'    => __('Establecer imagen destacada', 'fugu-elementor'),
        'remove_featured_image' => __('Eliminar imagen destacada', 'fugu-elementor'),
        'use_featured_image'    => __('Usar como imagen destacada', 'fugu-elementor'),
    );

    $args = array(
        'label'                 => __('Perfil del contratante', 'fugu-elementor'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-portfolio',
        'has_archive'           => true,
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'perfil-contratante'),
    );

    register_post_type('perfil_contratante', $args);
}
add_action('init', 'fugu_elementor_register_perfil_contratante_cpt');

/**
 * Register Licitación taxonomy for Perfil del contratante
 */
function fugu_elementor_register_licitacion_taxonomy() {
    if (!get_theme_mod('enable_perfil_contratante_cpt', false)) {
        return;
    }

    $labels = array(
        'name'              => _x('Estados de licitación', 'Taxonomy General Name', 'fugu-elementor'),
        'singular_name'     => _x('Estado de licitación', 'Taxonomy Singular Name', 'fugu-elementor'),
        'search_items'      => __('Buscar estados de licitación', 'fugu-elementor'),
        'all_items'         => __('Todos los estados de licitación', 'fugu-elementor'),
        'parent_item'       => __('Estado padre', 'fugu-elementor'),
        'parent_item_colon' => __('Estado padre:', 'fugu-elementor'),
        'edit_item'         => __('Editar estado de licitación', 'fugu-elementor'),
        'update_item'       => __('Actualizar estado de licitación', 'fugu-elementor'),
        'add_new_item'      => __('Añadir nuevo estado de licitación', 'fugu-elementor'),
        'new_item_name'     => __('Nombre del estado de licitación', 'fugu-elementor'),
        'menu_name'         => __('Licitación', 'fugu-elementor'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
        'rewrite'           => array(
            'slug'         => 'licitacion',
            'with_front'   => false,
            'hierarchical' => true,
        ),
    );

    register_taxonomy('licitacion', array('perfil_contratante'), $args);

    $default_terms = array(
        'actuales'   => __('Actuales', 'fugu-elementor'),
        'anteriores' => __('Anteriores', 'fugu-elementor'),
    );

    foreach ($default_terms as $slug => $label) {
        if (!term_exists($slug, 'licitacion')) {
            wp_insert_term($label, 'licitacion', array('slug' => $slug));
        }
    }
}
add_action('init', 'fugu_elementor_register_licitacion_taxonomy');

/**
 * Add PDF meta box to Perfil Contratante
 */
function fugu_elementor_perfil_contratante_add_meta_boxes() {
    if (!get_theme_mod('enable_perfil_contratante_cpt', false)) {
        return;
    }

    add_meta_box(
        'perfil_contratante_pdfs',
        __('Documentos PDF', 'fugu-elementor'),
        'fugu_elementor_perfil_contratante_pdfs_callback',
        'perfil_contratante',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'fugu_elementor_perfil_contratante_add_meta_boxes');

function fugu_elementor_perfil_contratante_pdfs_callback($post) {
    wp_nonce_field('perfil_contratante_pdfs_nonce', 'perfil_contratante_pdfs_nonce_field');

    $primary_pdf   = get_post_meta($post->ID, '_perfil_contratante_pdf_primary', true);
    $secondary_pdf = get_post_meta($post->ID, '_perfil_contratante_pdf_secondary', true);
    $tertiary_pdf  = get_post_meta($post->ID, '_perfil_contratante_pdf_tertiary', true);

    $fields = array(
        array(
            'label' => __('Anuncio de licitación (PDF)', 'fugu-elementor'),
            'name'  => 'perfil_contratante_pdf_primary',
            'value' => $primary_pdf,
        ),
        array(
            'label' => __('Pliegos de licitación (PDF)', 'fugu-elementor'),
            'name'  => 'perfil_contratante_pdf_secondary',
            'value' => $secondary_pdf,
        ),
        array(
            'label' => __('Anuncio de desistimiento (PDF)', 'fugu-elementor'),
            'name'  => 'perfil_contratante_pdf_tertiary',
            'value' => $tertiary_pdf,
        ),
    );

    echo '<div class="perfil-contratante-pdf-fields">';

    foreach ($fields as $field) {
        $has_value = !empty($field['value']);
        $preview_text = $has_value ? esc_html(basename($field['value'])) : '';
        $preview_style = $has_value ? '' : ' style="display:none"';
        $remove_style = $has_value ? '' : ' style="display:none"';

        echo '<div class="perfil-contratante-pdf-field">';
        echo '<p><strong>' . esc_html($field['label']) . '</strong></p>';
        echo '<div class="perfil-contratante-pdf-controls">';
        echo '<input type="text" class="widefat perfil-contratante-pdf-input" name="' . esc_attr($field['name']) . '" value="' . esc_url($field['value']) . '" placeholder="https://" />';
        echo '<p class="description">' . esc_html__('Selecciona o pega la URL de un PDF.', 'fugu-elementor') . '</p>';
        echo '<p class="perfil-contratante-pdf-actions">';
        echo '<button type="button" class="button perfil-contratante-upload">' . esc_html__('Subir o seleccionar PDF', 'fugu-elementor') . '</button> ';
        echo '<button type="button" class="button-link-delete perfil-contratante-remove"' . $remove_style . '>' . esc_html__('Quitar', 'fugu-elementor') . '</button>';
        echo '</p>';
        echo '<p><a class="perfil-contratante-pdf-preview" href="' . esc_url($field['value']) . '" target="_blank" rel="noopener noreferrer"' . $preview_style . '>' . $preview_text . '</a></p>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
}

/**
 * Save Perfil Contratante PDF meta values
 */
function fugu_elementor_save_perfil_contratante_pdfs($post_id) {
    if (!get_theme_mod('enable_perfil_contratante_cpt', false)) {
        return;
    }

    if (!isset($_POST['perfil_contratante_pdfs_nonce_field']) ||
        !wp_verify_nonce($_POST['perfil_contratante_pdfs_nonce_field'], 'perfil_contratante_pdfs_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        '_perfil_contratante_pdf_primary'   => 'perfil_contratante_pdf_primary',
        '_perfil_contratante_pdf_secondary' => 'perfil_contratante_pdf_secondary',
        '_perfil_contratante_pdf_tertiary'  => 'perfil_contratante_pdf_tertiary',
    );

    foreach ($fields as $meta_key => $form_key) {
        if (isset($_POST[$form_key]) && !empty($_POST[$form_key])) {
            update_post_meta($post_id, $meta_key, esc_url_raw($_POST[$form_key]));
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}
add_action('save_post_perfil_contratante', 'fugu_elementor_save_perfil_contratante_pdfs');

/**
 * Register Perfil Contratante PDF meta for REST usage
 */
function fugu_elementor_register_perfil_contratante_meta() {
    if (!get_theme_mod('enable_perfil_contratante_cpt', false)) {
        return;
    }

    $meta_args = array(
        'single'            => true,
        'type'              => 'string',
        'show_in_rest'      => true,
        'sanitize_callback' => 'esc_url_raw',
        'auth_callback'     => function() {
            return current_user_can('edit_posts');
        },
    );

    register_post_meta('perfil_contratante', '_perfil_contratante_pdf_primary', $meta_args);
    register_post_meta('perfil_contratante', '_perfil_contratante_pdf_secondary', $meta_args);
    register_post_meta('perfil_contratante', '_perfil_contratante_pdf_tertiary', $meta_args);
}
add_action('init', 'fugu_elementor_register_perfil_contratante_meta');

/**
 * Enqueue admin assets for Perfil Contratante PDF selector
 */
function fugu_elementor_perfil_contratante_admin_assets($hook) {
    $screen = get_current_screen();

    if (!get_theme_mod('enable_perfil_contratante_cpt', false)) {
        return;
    }

    if (!$screen || $screen->post_type !== 'perfil_contratante') {
        return;
    }

    wp_enqueue_media();
    wp_register_script('elementor-blank-perfil-contratante-admin', false, array('jquery'), false, true);
    wp_enqueue_script('elementor-blank-perfil-contratante-admin');

    $script = <<<'JS'
jQuery(function($){
    function setupField($field){
        var frame;

        $field.find('.perfil-contratante-upload').on('click', function(event){
            event.preventDefault();

            if (!frame) {
                frame = wp.media({
                    title: '%1$s',
                    button: { text: '%2$s' },
                    library: { type: 'application/pdf' },
                    multiple: false
                });

                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    var url = attachment.url || '';
                    $field.find('.perfil-contratante-pdf-input').val(url);
                    $field.find('.perfil-contratante-pdf-preview').attr('href', url).text(attachment.filename || url).show();
                    $field.find('.perfil-contratante-remove').show();
                });
            }

            frame.open();
        });

        $field.find('.perfil-contratante-remove').on('click', function(event){
            event.preventDefault();
            $field.find('.perfil-contratante-pdf-input').val('');
            $field.find('.perfil-contratante-pdf-preview').attr('href', '#').text('').hide();
            $(this).hide();
        });

        if ($field.find('.perfil-contratante-pdf-input').val()){
            $field.find('.perfil-contratante-pdf-preview').show();
            $field.find('.perfil-contratante-remove').show();
        }
    }

    $('.perfil-contratante-pdf-field').each(function(){
        setupField($(this));
    });
});
JS;

    $script = sprintf(
        $script,
        esc_js(__('Selecciona un PDF', 'fugu-elementor')),
        esc_js(__('Usar PDF', 'fugu-elementor'))
    );

    wp_add_inline_script('elementor-blank-perfil-contratante-admin', $script);
}
add_action('admin_enqueue_scripts', 'fugu_elementor_perfil_contratante_admin_assets');

/**
 * Append Perfil Contratante downloads to single content
 */
function fugu_elementor_get_perfil_contratante_downloads_html($post_id = 0) {
    if (!get_theme_mod('enable_perfil_contratante_cpt', false)) {
        return '';
    }

    $post_id = $post_id ?: get_the_ID();
    if (!$post_id || get_post_type($post_id) !== 'perfil_contratante') {
        return '';
    }

    $downloads = array();

    $primary = get_post_meta($post_id, '_perfil_contratante_pdf_primary', true);
    if (!empty($primary)) {
        $downloads[] = array(
            'label' => __('Anuncio de licitación', 'fugu-elementor'),
            'url'   => esc_url($primary),
        );
    }

    $secondary = get_post_meta($post_id, '_perfil_contratante_pdf_secondary', true);
    if (!empty($secondary)) {
        $downloads[] = array(
            'label' => __('Pliegos de licitación', 'fugu-elementor'),
            'url'   => esc_url($secondary),
        );
    }

    $tertiary = get_post_meta($post_id, '_perfil_contratante_pdf_tertiary', true);
    if (!empty($tertiary)) {
        $downloads[] = array(
            'label' => __('Anuncio de desistimiento', 'fugu-elementor'),
            'url'   => esc_url($tertiary),
        );
    }

    if (empty($downloads)) {
        return '';
    }

    $buttons = '';

    foreach ($downloads as $download) {
        $buttons .= '<a class="perfil-contratante-download-button button" href="' . $download['url'] . '" target="_blank" rel="noopener noreferrer">' . esc_html($download['label']) . '</a>';
    }

    return '<div class="perfil-contratante-downloads">'
        . '<div class="perfil-contratante-downloads__buttons">' . $buttons . '</div>'
        . '</div>';
}

function fugu_elementor_perfil_contratante_append_downloads($content) {
    if (!is_singular('perfil_contratante') || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    return $content . fugu_elementor_get_perfil_contratante_downloads_html();
}
add_filter('the_content', 'fugu_elementor_perfil_contratante_append_downloads');

function fugu_elementor_perfil_contratante_downloads_shortcode($atts) {
    $attrs = shortcode_atts(array('id' => 0), $atts, 'perfil_contratante_documentos');
    $post_id = absint($attrs['id']);

    if (!$post_id) {
        $post_id = get_the_ID();
    }

    return fugu_elementor_get_perfil_contratante_downloads_html($post_id);
}
add_shortcode('perfil_contratante_documentos', 'fugu_elementor_perfil_contratante_downloads_shortcode');

/**
 * Sync post to Noticias Slider CPT when Slider = Yes
 */
function fugu_elementor_sync_to_noticias_slider($post_id) {
    // Get the original post
    $post = get_post($post_id);
    if (!$post || $post->post_status !== 'publish') {
        return;
    }
    
    // Get slider fields from original post
    $titulo_slider = get_post_meta($post_id, '_slider_titulo_slider', true);
    $titulo_link = get_post_meta($post_id, '_slider_titulo_link', true);
    $url_link = get_post_meta($post_id, '_slider_url_link', true);
    
    // Use defaults if empty
    if (empty($titulo_slider)) {
        $titulo_slider = $post->post_title;
    }
    if (empty($url_link)) {
        $url_link = get_permalink($post_id);
    }
    
    // Check if already synced
    $existing = get_post_meta($post_id, '_noticias_slider_id', true);
    
    if ($existing && get_post($existing)) {
        // Update existing noticias_slider post
        wp_update_post(array(
            'ID'           => $existing,
            'post_title'   => $post->post_title,
            'post_content' => $post->post_content,
            'post_excerpt' => $post->post_excerpt,
            'post_status'  => 'publish',
        ));
        
        // Sync thumbnail
        if (has_post_thumbnail($post_id)) {
            $thumbnail_id = get_post_thumbnail_id($post_id);
            set_post_thumbnail($existing, $thumbnail_id);
        }
        
        // Sync slider fields
        update_post_meta($existing, '_slider_titulo_slider', $titulo_slider);
        update_post_meta($existing, '_slider_titulo_link', $titulo_link);
        update_post_meta($existing, '_slider_url_link', $url_link);
    } else {
        // Create new noticias_slider post
        $slider_id = wp_insert_post(array(
            'post_type'    => 'noticias_slider',
            'post_title'   => $post->post_title,
            'post_content' => $post->post_content,
            'post_excerpt' => $post->post_excerpt,
            'post_status'  => 'publish',
        ));
        
        if ($slider_id && !is_wp_error($slider_id)) {
            // Link posts
            update_post_meta($post_id, '_noticias_slider_id', $slider_id);
            update_post_meta($slider_id, '_original_post_id', $post_id);
            
            // Sync thumbnail
            if (has_post_thumbnail($post_id)) {
                $thumbnail_id = get_post_thumbnail_id($post_id);
                set_post_thumbnail($slider_id, $thumbnail_id);
            }
            
            // Sync slider fields
            update_post_meta($slider_id, '_slider_titulo_slider', $titulo_slider);
            update_post_meta($slider_id, '_slider_titulo_link', $titulo_link);
            update_post_meta($slider_id, '_slider_url_link', $url_link);
        }
    }
}

/**
 * Remove post from Noticias Slider CPT when Slider != Yes
 */
function fugu_elementor_remove_from_noticias_slider($post_id) {
    $slider_id = get_post_meta($post_id, '_noticias_slider_id', true);

    if ($slider_id && get_post($slider_id)) {
        wp_delete_post($slider_id, true); // true = force delete permanently
        delete_post_meta($post_id, '_noticias_slider_id');
    }
}

/**
 * Resolve GDR post ID from shortcode context
 */
function fugu_elementor_resolve_galgdr_id($maybe_id) {
    $post_id = absint($maybe_id);

    if ($post_id) {
        return $post_id;
    }

    $queried_id = get_queried_object_id();
    if ($queried_id && get_post_type($queried_id) === 'galgdr') {
        return $queried_id;
    }

    if (is_singular('galgdr')) {
        return get_the_ID();
    }

    $current_post = get_post();
    if ($current_post && $current_post->post_type === 'galgdr') {
        return $current_post->ID;
    }

    return 0;
}

/**
 * Retrieve municipios linked to a GDR
 */
function fugu_elementor_get_galgdr_municipios_ids($galgdr_id) {
    if (!$galgdr_id) {
        return array();
    }

    $query = new WP_Query(
        array(
            'post_type'              => 'municipio',
            'posts_per_page'         => -1,
            'post_status'            => 'publish',
            'orderby'                => 'title',
            'order'                  => 'ASC',
            'meta_key'               => '_municipio_galgdr_asociado',
            'meta_value'             => (string) $galgdr_id,
            'meta_compare'           => '=',
            'meta_type'              => 'NUMERIC',
            'no_found_rows'          => true,
            'fields'                 => 'ids',
            'update_post_term_cache' => false,
        )
    );

    $ids = $query->posts;
    wp_reset_postdata();

    return $ids;
}

function fugu_elementor_format_municipio_name($municipio_id, $link_names) {
    $name = esc_html(get_the_title($municipio_id));

    if ($link_names) {
        return '<a class="gdr-municipios__link" href="' . esc_url(get_permalink($municipio_id)) . '">' . $name . '</a>';
    }

    return $name;
}

/**
 * Shortcode: List municipios associated with a GDR as an unordered list
 */
function fugu_elementor_gdr_municipios_shortcode($atts) {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return '';
    }

    $atts = shortcode_atts(
        array(
            'id'    => 0,
            'title' => '',
            'link'  => 'yes',
        ),
        $atts,
        'gdr_municipios'
    );

    $post_id = fugu_elementor_resolve_galgdr_id($atts['id']);
    if (!$post_id) {
        return '';
    }

    $municipios = fugu_elementor_get_galgdr_municipios_ids($post_id);
    if (empty($municipios)) {
        return '';
    }

    $heading = trim($atts['title']);
    if ($heading === '') {
        $heading = get_the_title($post_id);
        if (!empty($heading)) {
            /* translators: %s: GDR name */
            $heading = sprintf(__('Municipios asociados a %s', 'fugu-elementor'), $heading);
        }
    }

    $title_html = '';
    if ($heading !== '') {
        $title_html = '<h2 class="gdr-municipios__title">' . esc_html($heading) . '</h2>';
    }

    $link_names = strtolower($atts['link']) !== 'no';

    $items = '';
    foreach ($municipios as $municipio_id) {
        $label = fugu_elementor_format_municipio_name($municipio_id, $link_names);
        $items .= '<li class="gdr-municipios__item">' . $label . '</li>';
    }

    return '<div class="gdr-municipios">' . $title_html . '<ul class="gdr-municipios__list">' . $items . '</ul></div>';
}
add_shortcode('gdr_municipios', 'fugu_elementor_gdr_municipios_shortcode');

/**
 * Shortcode: Return municipios associated with a GDR as plain text
 */
function fugu_elementor_galgdr_municipios_name_shortcode($atts) {
    if (!get_theme_mod('enable_galgdr_cpt', false)) {
        return '';
    }

    $atts = shortcode_atts(
        array(
            'id'        => 0,
            'separator' => '<br>',
            'link'      => 'yes',
            'format'    => 'text',
            'item_class' => 'soda-entry-list__item',
            'separator_class' => 'soda-entry-list__separator',
        ),
        $atts,
        'galgdr_municipios_name'
    );

    $post_id = fugu_elementor_resolve_galgdr_id($atts['id']);
    if (!$post_id) {
        return '';
    }

    $municipios = fugu_elementor_get_galgdr_municipios_ids($post_id);
    if (empty($municipios)) {
        return '';
    }

    $separator = $atts['separator'];
    if (in_array(strtolower($separator), array('vertical', 'horizontal'), true)) {
        $separator = (strtolower($separator) === 'vertical') ? ' | ' : ' / ';
    }
    $link_names = strtolower($atts['link']) !== 'no';

    $items = array();
    foreach ($municipios as $municipio_id) {
        $items[] = fugu_elementor_format_municipio_name($municipio_id, $link_names);
    }

    $format = strtolower($atts['format']);

    if ($format === 'spans') {
        $item_class = trim(sanitize_text_field($atts['item_class']));
        $separator_class = trim(sanitize_text_field($atts['separator_class']));

        $output = '';
        $last_index = count($items) - 1;

        foreach ($items as $index => $label) {
            $output .= '<span style="white-space:nowrap" class="' . esc_attr($item_class) . '">' . $label . '</span>';

            if ($separator !== '' && $index !== $last_index) {
                $output .= '<span style="white-space:nowrap" class="' . esc_attr($separator_class) . '">' . esc_html($separator) . '</span>';
            }
        }

        $container_style = 'display: flex; flex-wrap: wrap;';

        return '<div class="soda-entry-list" style="' . esc_attr($container_style) . '">' . $output . '</div>';
    }

    return implode($separator, $items);
}
add_shortcode('galgdr_municipios_name', 'fugu_elementor_galgdr_municipios_name_shortcode');

/**
 * Flush rewrite rules on theme activation
 */
function fugu_elementor_flush_rewrite_rules() {
    fugu_elementor_register_portfolio_cpt();
    fugu_elementor_register_portfolio_category();
    fugu_elementor_register_portfolio_tag();
    fugu_elementor_register_proyectos_cpt();
    fugu_elementor_register_proyectos_category();
    fugu_elementor_register_proyectos_tag();
    fugu_elementor_register_noticias_cpt();
    fugu_elementor_register_noticias_category();
    fugu_elementor_register_noticias_tag();
    fugu_elementor_register_perfil_contratante_cpt();
    fugu_elementor_register_gdr_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'fugu_elementor_flush_rewrite_rules');
