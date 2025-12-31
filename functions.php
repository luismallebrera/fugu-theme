<?php
/**
 * Elementor Blank Starter Theme
 * Tema optimizado para construir todo con Elementor
 */

// Soporte para Elementor
add_action('after_setup_theme', 'fugu_theme_setup');
function fugu_theme_setup() {
    // Soporte para imágenes destacadas
    add_theme_support('post-thumbnails');
    
    // Soporte para título del sitio
    add_theme_support('title-tag');
    
    // Soporte para Elementor
    add_theme_support('elementor');
    
    // Soporte para editor de bloques
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    
    // Soporte para logo personalizado
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Add custom image size
    add_image_size('post_image', 1000, 550, true);
}

/**
 * Disable default WordPress intermediate image sizes
 */
add_filter('intermediate_image_sizes_advanced', 'fugu_theme_disable_image_sizes');
function fugu_theme_disable_image_sizes($sizes) {
    unset($sizes['medium_large']); // 768px
    unset($sizes['large']);         // 1024px
    unset($sizes['1536x1536']);     // 1536px
    unset($sizes['2048x2048']);     // 2048px
    return $sizes;
}

/**
 * Sanitize uploaded filenames
 * Remove spaces, special characters, convert to lowercase, use dashes
 */
add_filter('sanitize_file_name', 'fugu_theme_sanitize_filename', 10, 1);
function fugu_theme_sanitize_filename($filename) {
    // Get file extension
    $info = pathinfo($filename);
    $ext = !empty($info['extension']) ? '.' . $info['extension'] : '';
    $name = basename($filename, $ext);
    
    // Convert to lowercase
    $name = strtolower($name);
    
    // Remove accents
    $name = remove_accents($name);
    
    // Replace spaces and underscores with dashes
    $name = str_replace(['_', ' '], '-', $name);
    
    // Remove special characters (keep only alphanumeric and dashes)
    $name = preg_replace('/[^a-z0-9\-]/', '', $name);
    
    // Remove multiple consecutive dashes
    $name = preg_replace('/-+/', '-', $name);
    
    // Remove leading/trailing dashes
    $name = trim($name, '-');
    
    return $name . $ext;
}

/**
 * Allow SVG uploads
 */
add_filter('upload_mimes', 'fugu_theme_allow_svg_upload');
function fugu_theme_allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}

/**
 * Fix SVG display in media library
 */
add_filter('wp_check_filetype_and_ext', 'fugu_theme_fix_svg_mime_type', 10, 4);
function fugu_theme_fix_svg_mime_type($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype($filename, $mimes);
    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
}

/**
 * Register navigation menus
 */
add_action('after_setup_theme', 'fugu_theme_register_menus');
function fugu_theme_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'fugu-theme'),
        'mobile'  => __('Mobile Menu', 'fugu-theme'),
        'topbar'  => __('Topbar Menu', 'fugu-theme'),
    ));
}

// Registrar áreas de widgets para Elementor
add_action('widgets_init', 'fugu_theme_widgets_init');
function fugu_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'fugu-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Widget area for sidebar', 'fugu-theme'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}

// Cargar estilos y scripts
add_action('wp_enqueue_scripts', 'fugu_theme_scripts');
function fugu_theme_scripts() {
    // Estilo principal
    wp_enqueue_style('elementor-blank-style', get_stylesheet_uri(), array(), '1.0');
    
    // Select2 CSS
    wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0');
    
    // Select2 JS
    wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0', true);
    
    // Ensure sprintf is available for Elementor
    wp_enqueue_script('wp-util');
    
    // Script personalizado
    wp_enqueue_script('elementor-blank-script', get_template_directory_uri() . '/scripts.js', array(), '1.0', true);
    
    // Scroll Class Script
    if (get_theme_mod('enable_scroll_class', false)) {
        wp_enqueue_script(
            'elementor-blank-scroll-class',
            get_template_directory_uri() . '/js/scroll-class.js',
            array('jquery'),
            '1.0',
            true
        );
        
        wp_localize_script('elementor-blank-scroll-class', 'elementorBlankScrollClass', array(
            'threshold' => intval(get_theme_mod('scroll_class_threshold', 100)),
        ));
    }
    
    // Smooth Scrolling con Lenis
    if (get_theme_mod('enable_smooth_scrolling', false)) {
        // Cargar Lenis desde CDN
        wp_enqueue_script('lenis', 'https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js', array(), '1.0.42', true);
        
        // Cargar GSAP y ScrollTrigger si está habilitado
        if (get_theme_mod('smooth_scrolling_gsap', false)) {
            wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true);
            wp_enqueue_script('scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array('gsap'), '3.12.5', true);
        }
        
        // Cargar nuestro script de smooth scrolling
        wp_enqueue_script(
            'elementor-blank-smooth-scrolling',
            get_template_directory_uri() . '/js/smooth-scrolling.js',
            array('lenis'),
            '1.0',
            true
        );
        
        // Pasar parámetros al JavaScript
        wp_localize_script('elementor-blank-smooth-scrolling', 'elementorBlankSmoothScrollingParams', array(
            'smoothWheel'   => get_theme_mod('smooth_scrolling_disable_wheel', false) ? 0 : 1,
            'anchorOffset'  => intval(get_theme_mod('smooth_scrolling_anchor_offset', 0)),
            'lerp'          => floatval(get_theme_mod('smooth_scrolling_lerp', 0.07)),
            'duration'      => floatval(get_theme_mod('smooth_scrolling_duration', 1.2)),
            'anchorLinks'   => get_theme_mod('smooth_scrolling_anchor_links', false),
            'gsapSync'      => get_theme_mod('smooth_scrolling_gsap', false),
        ));
    }
    
    // Page Transitions
    if (get_theme_mod('enable_page_transitions', false)) {
        $transition_duration = intval(get_theme_mod('page_transitions_duration', 900));
        $transition_exit_animation = get_theme_mod('page_transitions_exit_animation', 'slide-up');
        $transition_entrance_animation = get_theme_mod('page_transitions_entrance_animation', 'slide-up');
        $transition_color = get_theme_mod('page_transitions_color', '#000000');
        $transition_borders_color = get_theme_mod('page_transitions_borders_color', '#121e50');
        
        // Page Transitions CSS
        wp_enqueue_style(
            'elementor-blank-page-transitions',
            get_template_directory_uri() . '/css/page-transitions.css',
            array(),
            '5.2'
        );
        
        // Add inline CSS for dynamic settings
        $duration_seconds = ($transition_duration / 1000);
        
        // Get transition position and set z-index accordingly
        $transition_position = get_theme_mod('page_transitions_position', 'under');
        $panel_z_index = ($transition_position === 'above') ? '99998' : '801';
        $borders_z_index = ($transition_position === 'above') ? '99997' : '800';
        
        // Build border CSS conditionally
        $enable_borders = get_theme_mod('enable_page_transitions_borders', true);
        $borders_css = '';
        if ($enable_borders) {
            $borders_css = "
                .transition-borders-bg {
                    background-color: {$transition_borders_color};
                    transition-duration: {$duration_seconds}s;
                    z-index: {$borders_z_index};
                }
            ";
        }
        
        // Build dynamic CSS based on animation combination
        // Handle exit animation
        $exit_css = '';
        if ($transition_exit_animation === 'fade') {
            $exit_css = "
                .transition-pannel-bg {
                    background: {$transition_color};
                    transform: scaleY(1);
                    opacity: 0;
                    visibility: hidden;
                    z-index: {$panel_z_index};
                    transition-property: opacity, visibility;
                    transition-timing-function: ease-in-out;
                    transition-duration: {$duration_seconds}s;
                    transition-delay: 0s, {$duration_seconds}s;
                }
                body.close .transition-pannel-bg.active {
                    opacity: 1;
                    visibility: visible;
                    transition-delay: 0s, 0s;
                    transform: scaleY(1);
                }
            ";
        } else {
            // Slide exit
            $exit_css = "
                .transition-pannel-bg {
                    background: {$transition_color};
                    transform: scaleY(0);
                    transform-origin: bottom;
                    z-index: {$panel_z_index};
                    transition-property: transform;
                    transition-timing-function: cubic-bezier(0.83, 0, 0.17, 1);
                    transition-duration: {$duration_seconds}s;
                }
                body.close .transition-pannel-bg {
                    transform-origin: bottom;
                }
                body.close .transition-pannel-bg.active {
                    transform: scaleY(1);
                }
            ";
        }
        
        // Handle entrance animation
        $entrance_css = '';
        if ($transition_entrance_animation === 'fade') {
            $entrance_css = "
                .transition-pannel-bg.initial-load {
                    opacity: 1 !important;
                    visibility: visible !important;
                    transform: scaleY(1) !important;
                    transition: none !important;
                }
                body:not(.fade-entrance) .transition-pannel-bg.initial-load {
                    opacity: 0 !important;
                    visibility: hidden !important;
                }
                body.fade-entrance.page-loaded .transition-pannel-bg:not(.active) {
                    opacity: 0;
                    visibility: hidden;
                    transition-property: opacity, visibility;
                    transition-timing-function: ease-in-out, step-end;
                    transition-duration: {$duration_seconds}s, 0s;
                }
            ";
        } else {
            // Slide entrance
            $entrance_css = "
                .transition-pannel-bg.initial-load {
                    transform: scaleY(1) !important;
                    transform-origin: top;
                    transition: none !important;
                }
                body:not(.slide-up-entrance) .transition-pannel-bg.initial-load {
                    transform: scaleY(0) !important;
                }
                body.slide-up-entrance.page-loaded .transition-pannel-bg:not(.active) {
                    transform: scaleY(0);
                    transform-origin: top;
                }
            ";
        }
        
        $custom_css = $exit_css . $entrance_css . $borders_css;
        wp_add_inline_style('elementor-blank-page-transitions', $custom_css);
        
        wp_enqueue_script(
            'elementor-blank-page-transitions',
            get_template_directory_uri() . '/js/page-transitions.js',
            array('jquery'),
            '1.8',
            true
        );
        
        // Pasar parámetros al JavaScript
        wp_localize_script('elementor-blank-page-transitions', 'elementorBlankPageTransitions', array(
            'enabled'   => true,
            'duration'  => $transition_duration,
            'selectors' => get_theme_mod('page_transitions_selectors', '.menu li a, .elementor-widget-image > a, .soda-post-nav-next a, .soda-post-nav-prev a'),
            'exitAnimation' => $transition_exit_animation,
            'entranceAnimation' => $transition_entrance_animation,
            'enableEntrance' => get_theme_mod('enable_page_transitions_entrance', true),
        ));
    }
}

add_action('customize_register', 'fugu_theme_customize_header_settings', 15);
function fugu_theme_customize_header_settings($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('header_settings_section')) {
        $wp_customize->add_section('header_settings_section', array(
            'title'    => __('Header Settings', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 47,
        ));
    }

    $wp_customize->add_setting('header_position', array(
        'default'           => 'fixed',
        'sanitize_callback' => 'fugu_theme_sanitize_header_position',
    ));
    $wp_customize->add_control('header_position', array(
        'type'        => 'radio',
        'section'     => 'header_settings_section',
        'label'       => __('Header Position', 'fugu-theme'),
        'description' => __('Choose whether the header should be fixed or relative.', 'fugu-theme'),
        'choices'     => array(
            'fixed'    => __('Fixed', 'fugu-theme'),
            'relative' => __('Relative', 'fugu-theme'),
        ),
    ));

    $wp_customize->add_setting('enable_scroll_class', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_scroll_class', array(
        'type'        => 'checkbox',
        'section'     => 'header_settings_section',
        'label'       => __('Enable Scroll Class', 'fugu-theme'),
        'description' => __('Add a "scroll" class to body after scrolling past threshold.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('scroll_class_threshold', array(
        'default'           => 100,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_class_threshold', array(
        'type'            => 'number',
        'section'         => 'header_settings_section',
        'label'           => __('Scroll Threshold (px)', 'fugu-theme'),
        'description'     => __('Amount of pixels to scroll before adding the scroll class.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1000,
            'step' => 10,
        ),
        'active_callback' => function($control) {
            $setting = $control->manager->get_setting('enable_scroll_class');
            return $setting ? (bool) $setting->value() : false;
        },
    ));
}

add_action('customize_register', 'fugu_theme_customize_custom_post_types', 20);
function fugu_theme_customize_custom_post_types($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('custom_post_types_section')) {
        $wp_customize->add_section('custom_post_types_section', array(
            'title'    => __('Custom Post Types', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 70,
        ));
    }

    $wp_customize->add_setting('enable_portfolio_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_portfolio_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Portfolio', 'fugu-theme'),
        'description' => __('Enable Portfolio custom post type with categories and tags.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('enable_proyectos_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_proyectos_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Proyectos', 'fugu-theme'),
        'description' => __('Enable Proyectos custom post type with categories and tags.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('enable_noticias_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_noticias_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Noticias', 'fugu-theme'),
        'description' => __('Enable Noticias custom post type with categories and tags.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('enable_noticias_slider_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_noticias_slider_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Noticias Slider', 'fugu-theme'),
        'description' => __('Enable Noticias Slider custom post type for news slider.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('enable_galgdr_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_galgdr_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable GDR & Municipios', 'fugu-theme'),
        'description' => __('Enable GDR and Municipios custom post types with Provincia taxonomy.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('enable_perfil_contratante_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_perfil_contratante_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Perfil Contratante', 'fugu-theme'),
        'description' => __('Enable Perfil Contratante custom post type with GDR taxonomy.', 'fugu-theme'),
    ));
}

add_action('customize_register', 'fugu_theme_customize_menu_separators', 25);
function fugu_theme_customize_menu_separators($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('menu_separator_section')) {
        $wp_customize->add_section('menu_separator_section', array(
            'title'    => __('Menu Separators', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 31,
        ));
    }

    $wp_customize->add_setting('enable_horizontal_separator', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_horizontal_separator', array(
        'type'        => 'checkbox',
        'section'     => 'menu_separator_section',
        'label'       => __('Enable Horizontal Menu Separator', 'fugu-theme'),
        'description' => __('Add vertical separator between horizontal menu items.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('horizontal_separator_color', array(
        'default'           => '#dddddd',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'horizontal_separator_color',
        array(
            'section'         => 'menu_separator_section',
            'label'           => __('Horizontal Separator Color', 'fugu-theme'),
            'active_callback' => 'fugu_theme_is_horizontal_separator_enabled',
        )
    ));

    $wp_customize->add_setting('horizontal_separator_width', array(
        'default'           => 1,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('horizontal_separator_width', array(
        'type'            => 'number',
        'section'         => 'menu_separator_section',
        'label'           => __('Horizontal Separator Width', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_horizontal_separator_enabled',
    ));

    $wp_customize->add_setting('horizontal_separator_height', array(
        'default'           => 60,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('horizontal_separator_height', array(
        'type'            => 'number',
        'section'         => 'menu_separator_section',
        'label'           => __('Horizontal Separator Height (%)', 'fugu-theme'),
        'description'     => __('Height as percentage of menu item height.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 20,
            'max'  => 100,
            'step' => 5,
        ),
        'active_callback' => 'fugu_theme_is_horizontal_separator_enabled',
    ));

    $wp_customize->add_setting('horizontal_separator_rotation', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('horizontal_separator_rotation', array(
        'type'            => 'number',
        'section'         => 'menu_separator_section',
        'label'           => __('Horizontal Separator Rotation (degrees)', 'fugu-theme'),
        'description'     => __('Rotate the separator line.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => -45,
            'max'  => 45,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_horizontal_separator_enabled',
    ));

    $wp_customize->add_setting('enable_submenu_separator', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_submenu_separator', array(
        'type'        => 'checkbox',
        'section'     => 'menu_separator_section',
        'label'       => __('Enable Submenu Separator', 'fugu-theme'),
        'description' => __('Add horizontal separator between submenu items.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('submenu_separator_color', array(
        'default'           => '#dddddd',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'submenu_separator_color',
        array(
            'section'         => 'menu_separator_section',
            'label'           => __('Submenu Separator Color', 'fugu-theme'),
            'active_callback' => 'fugu_theme_is_submenu_separator_enabled',
        )
    ));

    $wp_customize->add_setting('submenu_separator_width', array(
        'default'           => 1,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('submenu_separator_width', array(
        'type'            => 'number',
        'section'         => 'menu_separator_section',
        'label'           => __('Submenu Separator Width (Thickness)', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_submenu_separator_enabled',
    ));
}

add_action('customize_register', 'fugu_theme_customize_scroll_indicator', 30);
function fugu_theme_customize_scroll_indicator($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('scroll_indicator_section')) {
        $wp_customize->add_section('scroll_indicator_section', array(
            'title'    => __('Scroll Indicator', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 50,
        ));
    }

    $wp_customize->add_setting('enable_scroll_indicator', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_scroll_indicator', array(
        'type'        => 'checkbox',
        'section'     => 'scroll_indicator_section',
        'label'       => __('Enable Scroll Indicator', 'fugu-theme'),
        'description' => __('Show a vertical scroll progress indicator.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('scroll_indicator_color', array(
        'default'           => '#313C59',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'scroll_indicator_color',
        array(
            'section'         => 'scroll_indicator_section',
            'label'           => __('Indicator Color', 'fugu-theme'),
            'active_callback' => 'fugu_theme_is_scroll_indicator_enabled',
        )
    ));

    $wp_customize->add_setting('scroll_indicator_width', array(
        'default'           => 4,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_width', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Indicator Width (px)', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 20,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal', array(
        'default'           => 'right',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_indicator_horizontal',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal', array(
        'type'            => 'radio',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Horizontal Position', 'fugu-theme'),
        'choices'         => array(
            'left'  => __('Left', 'fugu-theme'),
            'right' => __('Right', 'fugu-theme'),
        ),
        'active_callback' => 'fugu_theme_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal_offset', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal_offset', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Horizontal Offset', 'fugu-theme'),
        'description'     => __('Distance from the edge.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_scroll_indicator_horizontal_offset_active',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_indicator_horizontal_unit',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal_unit', array(
        'type'            => 'radio',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Horizontal Unit', 'fugu-theme'),
        'choices'         => array(
            'px' => 'px',
            '%'  => '%',
            'vw' => 'vw',
        ),
        'active_callback' => 'fugu_theme_scroll_indicator_horizontal_offset_active',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal_custom', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal_custom', array(
        'type'            => 'checkbox',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Use Custom Horizontal Value', 'fugu-theme'),
        'description'     => __('Enable to use calc() or custom CSS values.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal_custom_value', array(
        'default'           => 'calc(2% - 2px)',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_indicator_custom_value',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal_custom_value', array(
        'type'            => 'text',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Custom Horizontal Value', 'fugu-theme'),
        'description'     => __('Example: calc(2% - 2px) or 5vw.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_scroll_indicator_horizontal_custom_active',
    ));

    $wp_customize->add_setting('scroll_indicator_vertical_offset', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_vertical_offset', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Vertical Position', 'fugu-theme'),
        'description'     => __('Distance from bottom.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_scroll_indicator_vertical_offset_active',
    ));

    $wp_customize->add_setting('scroll_indicator_vertical_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_indicator_vertical_unit',
    ));
    $wp_customize->add_control('scroll_indicator_vertical_unit', array(
        'type'            => 'radio',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Vertical Unit', 'fugu-theme'),
        'choices'         => array(
            'px' => 'px',
            '%'  => '%',
            'vh' => 'vh',
        ),
        'active_callback' => 'fugu_theme_scroll_indicator_vertical_offset_active',
    ));

    $wp_customize->add_setting('scroll_indicator_vertical_custom', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('scroll_indicator_vertical_custom', array(
        'type'            => 'checkbox',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Use Custom Vertical Value', 'fugu-theme'),
        'description'     => __('Enable to use calc() or custom CSS values.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_vertical_custom_value', array(
        'default'           => 'calc(50vh - 50px)',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_indicator_custom_value',
    ));
    $wp_customize->add_control('scroll_indicator_vertical_custom_value', array(
        'type'            => 'text',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Custom Vertical Value', 'fugu-theme'),
        'description'     => __('Example: calc(50vh - 50px) or 10%.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_scroll_indicator_vertical_custom_active',
    ));

    $wp_customize->add_setting('scroll_indicator_height', array(
        'default'           => 100,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_height', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Indicator Height (px)', 'fugu-theme'),
        'description'     => __('Total height of the indicator bar.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 50,
            'max'  => 500,
            'step' => 10,
        ),
        'active_callback' => 'fugu_theme_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_bg_color', array(
        'default'           => 'rgba(49, 60, 89, 0.1)',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'scroll_indicator_bg_color',
        array(
            'section'         => 'scroll_indicator_section',
            'label'           => __('Background Color', 'fugu-theme'),
            'description'     => __('Background track color.', 'fugu-theme'),
            'active_callback' => 'fugu_theme_is_scroll_indicator_enabled',
        )
    ));

    $wp_customize->add_setting('scroll_indicator_border_radius', array(
        'default'           => 10,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_border_radius', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Border Radius (px)', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_scroll_indicator_enabled',
    ));
}

add_action('customize_register', 'fugu_theme_customize_animate_on_scroll', 32);
function fugu_theme_customize_animate_on_scroll($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('animate_on_scroll_section')) {
        $wp_customize->add_section('animate_on_scroll_section', array(
            'title'    => __('Animate on Scroll', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 52,
        ));
    }

    $wp_customize->add_setting('enable_animate_on_scroll', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_animate_on_scroll', array(
        'type'        => 'checkbox',
        'section'     => 'animate_on_scroll_section',
        'label'       => __('Enable Animate on Scroll', 'fugu-theme'),
        'description' => __('Enable the animate on scroll plugin functionality.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('aos_duration', array(
        'default'           => 800,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('aos_duration', array(
        'type'            => 'number',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Animation Duration (ms)', 'fugu-theme'),
        'description'     => __('Duration of animations in milliseconds.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 100,
            'max'  => 3000,
            'step' => 50,
        ),
        'active_callback' => 'fugu_theme_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_easing', array(
        'default'           => 'ease-in-out',
        'sanitize_callback' => 'fugu_theme_sanitize_aos_easing',
    ));
    $wp_customize->add_control('aos_easing', array(
        'type'            => 'select',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Animation Easing', 'fugu-theme'),
        'description'     => __('Easing function for animations.', 'fugu-theme'),
        'choices'         => array(
            'linear'          => __('Linear', 'fugu-theme'),
            'ease'            => __('Ease', 'fugu-theme'),
            'ease-in'         => __('Ease In', 'fugu-theme'),
            'ease-out'        => __('Ease Out', 'fugu-theme'),
            'ease-in-out'     => __('Ease In Out', 'fugu-theme'),
            'ease-in-back'    => __('Ease In Back', 'fugu-theme'),
            'ease-out-back'   => __('Ease Out Back', 'fugu-theme'),
            'ease-in-out-back' => __('Ease In Out Back', 'fugu-theme'),
        ),
        'active_callback' => 'fugu_theme_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_once', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('aos_once', array(
        'type'            => 'checkbox',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Animate Once', 'fugu-theme'),
        'description'     => __('Animation occurs only once when scrolling down.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_mirror', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('aos_mirror', array(
        'type'            => 'checkbox',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Mirror Animation', 'fugu-theme'),
        'description'     => __('Animate elements when scrolling past them.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_offset', array(
        'default'           => 120,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('aos_offset', array(
        'type'            => 'number',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Animation Offset (px)', 'fugu-theme'),
        'description'     => __('Offset from the original trigger point.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 500,
            'step' => 10,
        ),
        'active_callback' => 'fugu_theme_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_disable_mobile', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('aos_disable_mobile', array(
        'type'            => 'checkbox',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Disable on Mobile', 'fugu-theme'),
        'description'     => __('Disable animations on mobile devices for better performance.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_animate_on_scroll_enabled',
    ));
}

add_action('customize_register', 'fugu_theme_customize_smooth_scrolling', 33);
function fugu_theme_customize_smooth_scrolling($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('smooth_scrolling_section')) {
        $wp_customize->add_section('smooth_scrolling_section', array(
            'title'    => __('Smooth Scrolling', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 53,
        ));
    }

    $wp_customize->add_setting('enable_smooth_scrolling', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_smooth_scrolling', array(
        'type'        => 'checkbox',
        'section'     => 'smooth_scrolling_section',
        'label'       => __('Enable Smooth Scrolling', 'fugu-theme'),
        'description' => __('Enable Lenis smooth scrolling on your website.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('smooth_scrolling_disable_wheel', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('smooth_scrolling_disable_wheel', array(
        'type'            => 'checkbox',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Disable Mouse Wheel', 'fugu-theme'),
        'description'     => __('Disable smooth scrolling for mouse wheel.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_anchor_links', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('smooth_scrolling_anchor_links', array(
        'type'            => 'checkbox',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Smooth Anchor Links', 'fugu-theme'),
        'description'     => __('Enable smooth scrolling for anchor links.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_gsap', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('smooth_scrolling_gsap', array(
        'type'            => 'checkbox',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Synchronize with GSAP/ScrollTrigger', 'fugu-theme'),
        'description'     => __('Enable GSAP ScrollTrigger synchronization.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_anchor_offset', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('smooth_scrolling_anchor_offset', array(
        'type'            => 'number',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Smooth Anchor Link Offset (px)', 'fugu-theme'),
        'description'     => __('Offset for smooth anchor links in pixels.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 500,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_lerp', array(
        'default'           => 0.07,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('smooth_scrolling_lerp', array(
        'type'            => 'number',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Linear Interpolation (lerp) Intensity', 'fugu-theme'),
        'description'     => __('Between 0 and 1. Lower = smoother. Set to 0 to use duration instead.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1,
            'step' => 0.01,
        ),
        'active_callback' => 'fugu_theme_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_duration', array(
        'default'           => 1.2,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('smooth_scrolling_duration', array(
        'type'            => 'number',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Duration of Scroll Animation (seconds)', 'fugu-theme'),
        'description'     => __('Set lerp to 0 to use this value.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 5,
            'step' => 0.1,
        ),
        'active_callback' => 'fugu_theme_is_smooth_scrolling_enabled',
    ));
}

add_action('customize_register', 'fugu_theme_customize_scroll_to_top', 35);
function fugu_theme_customize_scroll_to_top($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('scroll_to_top_section')) {
        $wp_customize->add_section('scroll_to_top_section', array(
            'title'    => __('Scroll to Top Button', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 55,
        ));
    }

    $wp_customize->add_setting('enable_scroll_to_top', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_scroll_to_top', array(
        'type'        => 'checkbox',
        'section'     => 'scroll_to_top_section',
        'label'       => __('Enable Scroll to Top Button', 'fugu-theme'),
        'description' => __('Show a button to scroll back to the top of the page.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('scroll_to_top_style', array(
        'default'           => 'circle',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_to_top_style',
    ));
    $wp_customize->add_control('scroll_to_top_style', array(
        'type'            => 'radio',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Button Style', 'fugu-theme'),
        'choices'         => array(
            'circle'  => __('Circle', 'fugu-theme'),
            'square'  => __('Square', 'fugu-theme'),
            'rounded' => __('Rounded', 'fugu-theme'),
        ),
        'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_size', array(
        'default'           => 50,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_size', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Button Size (px)', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 30,
            'max'  => 100,
            'step' => 5,
        ),
        'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_bg_color', array(
        'default'           => '#313C59',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'scroll_to_top_bg_color',
        array(
            'section'         => 'scroll_to_top_section',
            'label'           => __('Background Color', 'fugu-theme'),
            'choices'         => array('alpha' => true),
            'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
        )
    ));

    $wp_customize->add_setting('scroll_to_top_icon_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'scroll_to_top_icon_color',
        array(
            'section'         => 'scroll_to_top_section',
            'label'           => __('Icon Color', 'fugu-theme'),
            'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
        )
    ));

    $wp_customize->add_setting('scroll_to_top_hover_bg_color', array(
        'default'           => '#1e2640',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'scroll_to_top_hover_bg_color',
        array(
            'section'         => 'scroll_to_top_section',
            'label'           => __('Hover Background Color', 'fugu-theme'),
            'choices'         => array('alpha' => true),
            'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
        )
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal', array(
        'default'           => 'right',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_to_top_horizontal',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal', array(
        'type'            => 'radio',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Horizontal Position', 'fugu-theme'),
        'choices'         => array(
            'left'  => __('Left', 'fugu-theme'),
            'right' => __('Right', 'fugu-theme'),
        ),
        'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal_offset', array(
        'default'           => 30,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal_offset', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Horizontal Offset', 'fugu-theme'),
        'description'     => __('Distance from edge.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_scroll_to_top_horizontal_offset_active',
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_to_top_horizontal_unit',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal_unit', array(
        'type'            => 'radio',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Horizontal Unit', 'fugu-theme'),
        'choices'         => array(
            'px' => 'px',
            '%'  => '%',
            'vw' => 'vw',
        ),
        'active_callback' => 'fugu_theme_scroll_to_top_horizontal_offset_active',
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal_custom', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal_custom', array(
        'type'            => 'checkbox',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Use Custom Horizontal Value', 'fugu-theme'),
        'description'     => __('Enable to use calc() or custom CSS values.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal_custom_value', array(
        'default'           => 'calc(2% - 2px)',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_to_top_custom_value',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal_custom_value', array(
        'type'            => 'text',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Custom Horizontal Value', 'fugu-theme'),
        'description'     => __('Example: calc(2% - 2px) or 5vw.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_scroll_to_top_horizontal_custom_active',
    ));

    $wp_customize->add_setting('scroll_to_top_vertical_offset', array(
        'default'           => 30,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_vertical_offset', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Vertical Position', 'fugu-theme'),
        'description'     => __('Distance from bottom.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_scroll_to_top_vertical_offset_active',
    ));

    $wp_customize->add_setting('scroll_to_top_vertical_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_to_top_vertical_unit',
    ));
    $wp_customize->add_control('scroll_to_top_vertical_unit', array(
        'type'            => 'radio',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Vertical Unit', 'fugu-theme'),
        'choices'         => array(
            'px' => 'px',
            '%'  => '%',
            'vh' => 'vh',
        ),
        'active_callback' => 'fugu_theme_scroll_to_top_vertical_offset_active',
    ));

    $wp_customize->add_setting('scroll_to_top_vertical_custom', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('scroll_to_top_vertical_custom', array(
        'type'            => 'checkbox',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Use Custom Vertical Value', 'fugu-theme'),
        'description'     => __('Enable to use calc() or custom CSS values.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_vertical_custom_value', array(
        'default'           => 'calc(5vh - 10px)',
        'sanitize_callback' => 'fugu_theme_sanitize_scroll_to_top_custom_value',
    ));
    $wp_customize->add_control('scroll_to_top_vertical_custom_value', array(
        'type'            => 'text',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Custom Vertical Value', 'fugu-theme'),
        'description'     => __('Example: calc(5vh - 10px) or 3%.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_scroll_to_top_vertical_custom_active',
    ));

    $wp_customize->add_setting('scroll_to_top_show_after', array(
        'default'           => 300,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_show_after', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Show After Scroll (px)', 'fugu-theme'),
        'description'     => __('Show button after scrolling X pixels.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 100,
            'max'  => 1000,
            'step' => 50,
        ),
        'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_animation_speed', array(
        'default'           => 300,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_animation_speed', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Animation Speed (ms)', 'fugu-theme'),
        'description'     => __('Animation speed in milliseconds.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 100,
            'max'  => 1000,
            'step' => 50,
        ),
        'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_zindex', array(
        'default'           => 9999,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_zindex', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Z-Index', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 999999,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_scroll_to_top_enabled',
    ));
}

add_action('customize_register', 'fugu_theme_customize_scrollbar', 40);
function fugu_theme_customize_scrollbar($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('fugu_theme_scrollbar')) {
        $wp_customize->add_section('fugu_theme_scrollbar', array(
            'title'    => __('Scrollbar', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 160,
        ));
    }

    $wp_customize->add_setting('wp_scrollbar_width', array(
        'default'           => 4,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('wp_scrollbar_width', array(
        'type'        => 'number',
        'section'     => 'fugu_theme_scrollbar',
        'label'       => __('Scrollbar Width (px)', 'fugu-theme'),
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 64,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('wp_scrollbar_track_color', array(
        'default'           => '#dddddd',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'wp_scrollbar_track_color',
        array(
            'section' => 'fugu_theme_scrollbar',
            'label'   => __('Scrollbar Track Color', 'fugu-theme'),
            'choices' => array('alpha' => true),
        )
    ));

    $wp_customize->add_setting('wp_scrollbar_thumb_color', array(
        'default'           => '#ff0000',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'wp_scrollbar_thumb_color',
        array(
            'section' => 'fugu_theme_scrollbar',
            'label'   => __('Scrollbar Thumb Color', 'fugu-theme'),
            'choices' => array('alpha' => true),
        )
    ));

    $wp_customize->add_setting('wp_scrollbar_thumb_hover_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'wp_scrollbar_thumb_hover_color',
        array(
            'section'     => 'fugu_theme_scrollbar',
            'label'       => __('Scrollbar Thumb (html.scrolling) Color', 'fugu-theme'),
            'description' => __('Color when html has the "scrolling" class applied.', 'fugu-theme'),
            'choices'     => array('alpha' => true),
        )
    ));
}

add_action('customize_register', 'fugu_theme_customize_grid_line', 45);
function fugu_theme_customize_grid_line($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('grid_line_section')) {
        $wp_customize->add_section('grid_line_section', array(
            'title'    => __('Grid Line Overlay', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 60,
        ));
    }

    $wp_customize->add_setting('grid_line_enable', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('grid_line_enable', array(
        'type'        => 'checkbox',
        'section'     => 'grid_line_section',
        'label'       => __('Enable Grid Line', 'fugu-theme'),
        'description' => __('Display a grid line overlay on the page.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('grid_line_breakpoint_desktop', array(
        'default'           => 1024,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_breakpoint_desktop', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Desktop Min Width', 'fugu-theme'),
        'description'     => __('Minimum width for desktop (default: 1024px).', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 3000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_breakpoint_tablet', array(
        'default'           => 768,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_breakpoint_tablet', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Tablet Min Width', 'fugu-theme'),
        'description'     => __('Minimum width for tablet (default: 768px).', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 3000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_breakpoint_mobile_landscape', array(
        'default'           => 420,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_breakpoint_mobile_landscape', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Mobile Landscape Min Width', 'fugu-theme'),
        'description'     => __('Minimum width for mobile landscape (default: 420px).', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 3000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_line_color', array(
        'default'           => '#eeeeee',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'grid_line_line_color',
        array(
            'section'         => 'grid_line_section',
            'label'           => __('Line Color', 'fugu-theme'),
            'description'     => __('Color of the grid lines.', 'fugu-theme'),
            'choices'         => array('alpha' => true),
            'active_callback' => 'fugu_theme_is_grid_line_enabled',
        )
    ));

    $wp_customize->add_setting('grid_line_column_color', array(
        'default'           => 'transparent',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'grid_line_column_color',
        array(
            'section'         => 'grid_line_section',
            'label'           => __('Column Color', 'fugu-theme'),
            'description'     => __('Background color between lines.', 'fugu-theme'),
            'choices'         => array('alpha' => true),
            'active_callback' => 'fugu_theme_is_grid_line_enabled',
        )
    ));

    $wp_customize->add_setting('grid_line_columns', array(
        'default'           => 12,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_columns', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Number of Columns', 'fugu-theme'),
        'description'     => __('Number of grid columns to display.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 24,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_outline', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('grid_line_outline', array(
        'type'            => 'checkbox',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Outline', 'fugu-theme'),
        'description'     => __('Draw an outline around the grid overlay.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_max_width', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_theme_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_max_width', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Max Width', 'fugu-theme'),
        'description'     => __('Maximum width of the grid overlay (supports px, %, vw, calc).', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_width_desktop', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_theme_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_width_desktop', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Width (Desktop)', 'fugu-theme'),
        'description'     => __('Width of the grid container on desktop.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_width_tablet', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_theme_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_width_tablet', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Width (Tablet)', 'fugu-theme'),
        'description'     => __('Width of the grid container on tablet.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_width_mobile_landscape', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_theme_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_width_mobile_landscape', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Width (Mobile Landscape)', 'fugu-theme'),
        'description'     => __('Width of the grid container on mobile landscape.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_width_mobile', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_theme_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_width_mobile', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Width (Mobile)', 'fugu-theme'),
        'description'     => __('Width of the grid container on mobile.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_line_width', array(
        'default'           => '1px',
        'sanitize_callback' => 'fugu_theme_sanitize_css_dimension_line',
    ));
    $wp_customize->add_control('grid_line_line_width', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Line Width', 'fugu-theme'),
        'description'     => __('Thickness of each grid line.', 'fugu-theme'),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_direction', array(
        'default'           => 90,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_direction', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Line Direction (degrees)', 'fugu-theme'),
        'description'     => __('Angle of the grid lines.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => -360,
            'max'  => 360,
            'step' => 15,
        ),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_z_index', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_z_index', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Z-Index', 'fugu-theme'),
        'description'     => __('Stacking order of the grid overlay.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => -9999,
            'max'  => 9999,
            'step' => 1,
        ),
        'active_callback' => 'fugu_theme_is_grid_line_enabled',
    ));
}

add_action('customize_register', 'fugu_theme_customize_page_transitions', 20);
function fugu_theme_customize_page_transitions($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('page_transitions_section')) {
        $wp_customize->add_section('page_transitions_section', array(
            'title'    => __('Page Transitions', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 55,
        ));
    }

    $wp_customize->add_setting('enable_page_transitions', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_page_transitions', array(
        'type'        => 'checkbox',
        'section'     => 'page_transitions_section',
        'label'       => __('Enable Page Transitions', 'fugu-theme'),
        'description' => __('Enable smooth transitions between page navigation.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('page_transitions_duration', array(
        'default'           => 900,
        'sanitize_callback' => 'fugu_theme_sanitize_transition_duration',
    ));
    $wp_customize->add_control('page_transitions_duration', array(
        'type'        => 'number',
        'section'     => 'page_transitions_section',
        'label'       => __('Transition Duration (ms)', 'fugu-theme'),
        'description' => __('Duration of the transition animation in milliseconds.', 'fugu-theme'),
        'input_attrs' => array(
            'min'  => 100,
            'max'  => 3000,
            'step' => 50,
        ),
    ));

    $wp_customize->add_setting('page_transitions_exit_animation', array(
        'default'           => 'slide-up',
        'sanitize_callback' => 'fugu_theme_sanitize_transition_animation',
    ));
    $wp_customize->add_control('page_transitions_exit_animation', array(
        'type'        => 'select',
        'section'     => 'page_transitions_section',
        'label'       => __('Exit Animation Type', 'fugu-theme'),
        'description' => __('Choose the animation style when leaving a page.', 'fugu-theme'),
        'choices'     => array(
            'slide-up' => __('Slide', 'fugu-theme'),
            'fade'     => __('Fade', 'fugu-theme'),
        ),
    ));

    $wp_customize->add_setting('page_transitions_entrance_animation', array(
        'default'           => 'slide-up',
        'sanitize_callback' => 'fugu_theme_sanitize_transition_animation',
    ));
    $wp_customize->add_control('page_transitions_entrance_animation', array(
        'type'        => 'select',
        'section'     => 'page_transitions_section',
        'label'       => __('Entrance Animation Type', 'fugu-theme'),
        'description' => __('Choose the animation style when entering a page.', 'fugu-theme'),
        'choices'     => array(
            'slide-up' => __('Slide', 'fugu-theme'),
            'fade'     => __('Fade', 'fugu-theme'),
        ),
    ));

    $wp_customize->add_setting('page_transitions_color', array(
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'page_transitions_color',
        array(
            'section'     => 'page_transitions_section',
            'label'       => __('Panel Color', 'fugu-theme'),
            'description' => __('Background color of the transition panel.', 'fugu-theme'),
        )
    ));

    $wp_customize->add_setting('enable_page_transitions_borders', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_page_transitions_borders', array(
        'type'        => 'checkbox',
        'section'     => 'page_transitions_section',
        'label'       => __('Enable Transition Borders', 'fugu-theme'),
        'description' => __('Enable border frame animation during page transitions.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('enable_page_transitions_entrance', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_page_transitions_entrance', array(
        'type'        => 'checkbox',
        'section'     => 'page_transitions_section',
        'label'       => __('Enable Entrance Animation', 'fugu-theme'),
        'description' => __('Enable entrance animation on page load.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('page_transitions_position', array(
        'default'           => 'under',
        'sanitize_callback' => 'fugu_theme_sanitize_transition_position',
    ));
    $wp_customize->add_control('page_transitions_position', array(
        'type'        => 'radio',
        'section'     => 'page_transitions_section',
        'label'       => __('Transition Position', 'fugu-theme'),
        'description' => __('Choose whether transitions appear above or under page content.', 'fugu-theme'),
        'choices'     => array(
            'above' => __('Above Page Content', 'fugu-theme'),
            'under' => __('Under Page Content', 'fugu-theme'),
        ),
    ));

    $wp_customize->add_setting('page_transitions_borders_color', array(
        'default'           => '#121e50',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'page_transitions_borders_color',
        array(
            'section'         => 'page_transitions_section',
            'label'           => __('Borders Color', 'fugu-theme'),
            'description'     => __('Background color of the transition borders.', 'fugu-theme'),
            'active_callback' => function($control) {
                $setting = $control->manager->get_setting('enable_page_transitions_borders');
                return $setting ? (bool) $setting->value() : true;
            },
        )
    ));

    $wp_customize->add_setting('page_transitions_selectors', array(
        'default'           => '.menu li a, .elementor-widget-image > a, .soda-post-nav-next a, .soda-post-nav-prev a',
        'sanitize_callback' => 'fugu_theme_sanitize_selectors',
    ));
    $wp_customize->add_control('page_transitions_selectors', array(
        'type'        => 'textarea',
        'section'     => 'page_transitions_section',
        'label'       => __('Click Selectors', 'fugu-theme'),
        'description' => __('CSS selectors for elements that trigger transitions (comma-separated).', 'fugu-theme'),
    ));
}

add_action('customize_register', 'fugu_theme_customize_custom_cursor', 25);
function fugu_theme_customize_custom_cursor($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-theme'),
            'description' => __('General theme options', 'fugu-theme'),
        ));
    }

    if (!$wp_customize->get_section('custom_cursor_section')) {
        $wp_customize->add_section('custom_cursor_section', array(
            'title'    => __('Custom Cursor', 'fugu-theme'),
            'panel'    => 'theme_options',
            'priority' => 35,
        ));
    }

    $active_if_enabled = function($control) {
        $setting = $control->manager->get_setting('enable_custom_cursor');
        return $setting ? (bool) $setting->value() : false;
    };

    $active_if_ring = function($control) use ($active_if_enabled) {
        if (!$active_if_enabled($control)) {
            return false;
        }
        $style_setting = $control->manager->get_setting('cursor_style');
        $value = $style_setting ? $style_setting->value() : 'dot';
        return in_array($value, array('ring', 'both'), true);
    };

    $active_if_dot = function($control) use ($active_if_enabled) {
        if (!$active_if_enabled($control)) {
            return false;
        }
        $style_setting = $control->manager->get_setting('cursor_style');
        $value = $style_setting ? $style_setting->value() : 'dot';
        return in_array($value, array('dot', 'both'), true);
    };

    $wp_customize->add_setting('enable_custom_cursor', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_custom_cursor', array(
        'type'        => 'checkbox',
        'section'     => 'custom_cursor_section',
        'label'       => __('Enable Custom Cursor', 'fugu-theme'),
        'description' => __('Replace default cursor with a modern custom cursor.', 'fugu-theme'),
    ));

    $wp_customize->add_setting('hide_default_cursor', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('hide_default_cursor', array(
        'type'            => 'checkbox',
        'section'         => 'custom_cursor_section',
        'label'           => __('Hide Default Cursor', 'fugu-theme'),
        'description'     => __('Hide the system cursor when the custom cursor is active.', 'fugu-theme'),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('disable_cursor_on_mobile', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_theme_sanitize_checkbox',
    ));
    $wp_customize->add_control('disable_cursor_on_mobile', array(
        'type'            => 'checkbox',
        'section'         => 'custom_cursor_section',
        'label'           => __('Disable on Mobile/Touch Devices', 'fugu-theme'),
        'description'     => __('Automatically disable the custom cursor on touch devices.', 'fugu-theme'),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_style', array(
        'default'           => 'dot',
        'sanitize_callback' => 'fugu_theme_sanitize_cursor_style',
    ));
    $wp_customize->add_control('cursor_style', array(
        'type'            => 'radio',
        'section'         => 'custom_cursor_section',
        'label'           => __('Cursor Style', 'fugu-theme'),
        'choices'         => array(
            'dot'  => __('Dot', 'fugu-theme'),
            'ring' => __('Ring', 'fugu-theme'),
            'both' => __('Dot + Ring', 'fugu-theme'),
        ),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_size', array(
        'default'           => 8,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_size', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Cursor Dot Size (px)', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 4,
            'max'  => 30,
            'step' => 1,
        ),
        'active_callback' => $active_if_dot,
    ));

    $wp_customize->add_setting('cursor_color', array(
        'default'           => '#000000',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'cursor_color',
        array(
            'section'         => 'custom_cursor_section',
            'label'           => __('Cursor Color', 'fugu-theme'),
            'active_callback' => $active_if_enabled,
        )
    ));

    $wp_customize->add_setting('cursor_ring_size', array(
        'default'           => 40,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_ring_size', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Ring Size (px)', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 20,
            'max'  => 80,
            'step' => 1,
        ),
        'active_callback' => $active_if_ring,
    ));

    $wp_customize->add_setting('cursor_ring_border', array(
        'default'           => 2,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_ring_border', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Ring Border Width (px)', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
        'active_callback' => $active_if_ring,
    ));

    $wp_customize->add_setting('cursor_hover_scale', array(
        'default'           => 1.5,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_hover_scale', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Hover Scale', 'fugu-theme'),
        'description'     => __('Scale multiplier when hovering over links.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 3,
            'step' => 0.1,
        ),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_animation_speed', array(
        'default'           => 200,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_animation_speed', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Animation Speed (ms)', 'fugu-theme'),
        'description'     => __('Lower values animate faster.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 50,
            'max'  => 500,
            'step' => 50,
        ),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_dot_inertia', array(
        'default'           => 0.15,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_dot_inertia', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Dot Inertia/Delay', 'fugu-theme'),
        'description'     => __('Smoothness of the dot following the pointer.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 0.5,
            'step' => 0.05,
        ),
        'active_callback' => $active_if_dot,
    ));

    $wp_customize->add_setting('cursor_ring_inertia', array(
        'default'           => 0.25,
        'sanitize_callback' => 'fugu_theme_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_ring_inertia', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Ring Inertia/Delay', 'fugu-theme'),
        'description'     => __('Smoothness of the ring following the pointer.', 'fugu-theme'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 0.5,
            'step' => 0.05,
        ),
        'active_callback' => $active_if_ring,
    ));

    $wp_customize->add_setting('cursor_blend_mode', array(
        'default'           => 'normal',
        'sanitize_callback' => 'fugu_theme_sanitize_cursor_blend_mode',
    ));
    $wp_customize->add_control('cursor_blend_mode', array(
        'type'            => 'select',
        'section'         => 'custom_cursor_section',
        'label'           => __('Blend Mode', 'fugu-theme'),
        'description'     => __('CSS blend mode for the cursor visuals.', 'fugu-theme'),
        'choices'         => array(
            'normal'     => __('Normal', 'fugu-theme'),
            'multiply'   => __('Multiply', 'fugu-theme'),
            'screen'     => __('Screen', 'fugu-theme'),
            'overlay'    => __('Overlay', 'fugu-theme'),
            'difference' => __('Difference', 'fugu-theme'),
            'exclusion'  => __('Exclusion', 'fugu-theme'),
        ),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_ring_fill_color', array(
        'default'           => 'rgba(0,0,0,0)',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'cursor_ring_fill_color',
        array(
            'section'         => 'custom_cursor_section',
            'label'           => __('Ring Fill Color', 'fugu-theme'),
            'description'     => __('Background fill color for the ring.', 'fugu-theme'),
            'active_callback' => $active_if_ring,
        )
    ));

    $wp_customize->add_setting('cursor_ring_fill_hover_color', array(
        'default'           => 'rgba(0,0,0,0.1)',
        'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'cursor_ring_fill_hover_color',
        array(
            'section'         => 'custom_cursor_section',
            'label'           => __('Ring Fill Hover Color', 'fugu-theme'),
            'description'     => __('Ring fill color when hovering interactive elements.', 'fugu-theme'),
            'active_callback' => $active_if_ring,
        )
    ));
}

add_action('wp_head', 'fugu_theme_header_position_css');
function fugu_theme_header_position_css() {
    $position = fugu_theme_sanitize_header_position(get_theme_mod('header_position', 'fixed'));

    if ($position === 'relative') {
        echo '<style id="header-position-css">header{position:relative!important;}</style>';
        return;
    }

    echo '<style id="header-position-css">header{position:fixed;z-index:999;width:100%;}</style>';
}

function fugu_theme_sanitize_checkbox($checked) {
    return (bool) $checked;
}

function fugu_theme_is_horizontal_separator_enabled($control) {
    $setting = $control->manager->get_setting('enable_horizontal_separator');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_theme_is_submenu_separator_enabled($control) {
    $setting = $control->manager->get_setting('enable_submenu_separator');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_theme_is_scroll_indicator_enabled($control) {
    $setting = $control->manager->get_setting('enable_scroll_indicator');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_theme_is_animate_on_scroll_enabled($control) {
    $setting = $control->manager->get_setting('enable_animate_on_scroll');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_theme_scroll_indicator_horizontal_offset_active($control) {
    if (!fugu_theme_is_scroll_indicator_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_indicator_horizontal_custom');
    return $custom ? !(bool) $custom->value() : true;
}

function fugu_theme_scroll_indicator_horizontal_custom_active($control) {
    if (!fugu_theme_is_scroll_indicator_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_indicator_horizontal_custom');
    return $custom ? (bool) $custom->value() : false;
}

function fugu_theme_scroll_indicator_vertical_offset_active($control) {
    if (!fugu_theme_is_scroll_indicator_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_indicator_vertical_custom');
    return $custom ? !(bool) $custom->value() : true;
}

function fugu_theme_scroll_indicator_vertical_custom_active($control) {
    if (!fugu_theme_is_scroll_indicator_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_indicator_vertical_custom');
    return $custom ? (bool) $custom->value() : false;
}

function fugu_theme_is_scroll_to_top_enabled($control) {
    $setting = $control->manager->get_setting('enable_scroll_to_top');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_theme_scroll_to_top_horizontal_offset_active($control) {
    if (!fugu_theme_is_scroll_to_top_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_to_top_horizontal_custom');
    return $custom ? !(bool) $custom->value() : true;
}

function fugu_theme_scroll_to_top_horizontal_custom_active($control) {
    if (!fugu_theme_is_scroll_to_top_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_to_top_horizontal_custom');
    return $custom ? (bool) $custom->value() : false;
}

function fugu_theme_scroll_to_top_vertical_offset_active($control) {
    if (!fugu_theme_is_scroll_to_top_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_to_top_vertical_custom');
    return $custom ? !(bool) $custom->value() : true;
}

function fugu_theme_scroll_to_top_vertical_custom_active($control) {
    if (!fugu_theme_is_scroll_to_top_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_to_top_vertical_custom');
    return $custom ? (bool) $custom->value() : false;
}

function fugu_theme_is_smooth_scrolling_enabled($control) {
    $setting = $control->manager->get_setting('enable_smooth_scrolling');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_theme_is_grid_line_enabled($control) {
    $setting = $control->manager->get_setting('grid_line_enable');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_theme_sanitize_scroll_indicator_horizontal($value) {
    $allowed = array('left', 'right');
    return in_array($value, $allowed, true) ? $value : 'right';
}

function fugu_theme_sanitize_scroll_indicator_horizontal_unit($value) {
    $allowed = array('px', '%', 'vw');
    return in_array($value, $allowed, true) ? $value : 'px';
}

function fugu_theme_sanitize_scroll_indicator_vertical_unit($value) {
    $allowed = array('px', '%', 'vh');
    return in_array($value, $allowed, true) ? $value : 'px';
}

function fugu_theme_sanitize_scroll_indicator_custom_value($value) {
    return sanitize_text_field($value);
}

function fugu_theme_sanitize_aos_easing($value) {
    $allowed = array(
        'linear',
        'ease',
        'ease-in',
        'ease-out',
        'ease-in-out',
        'ease-in-back',
        'ease-out-back',
        'ease-in-out-back',
    );

    return in_array($value, $allowed, true) ? $value : 'ease-in-out';
}

function fugu_theme_sanitize_scroll_to_top_style($value) {
    $allowed = array('circle', 'square', 'rounded');
    return in_array($value, $allowed, true) ? $value : 'circle';
}

function fugu_theme_sanitize_scroll_to_top_horizontal($value) {
    $allowed = array('left', 'right');
    return in_array($value, $allowed, true) ? $value : 'right';
}

function fugu_theme_sanitize_scroll_to_top_horizontal_unit($value) {
    $allowed = array('px', '%', 'vw');
    return in_array($value, $allowed, true) ? $value : 'px';
}

function fugu_theme_sanitize_scroll_to_top_vertical_unit($value) {
    $allowed = array('px', '%', 'vh');
    return in_array($value, $allowed, true) ? $value : 'px';
}

function fugu_theme_sanitize_scroll_to_top_custom_value($value) {
    return sanitize_text_field($value);
}

function fugu_theme_sanitize_css_dimension_value($value, $default) {
    $value = trim((string) $value);

    if ($value === '') {
        return $default;
    }

    $lower = strtolower($value);
    if (in_array($lower, array('auto', 'initial', 'inherit'), true)) {
        return $lower;
    }

    if (preg_match('/^calc\([0-9+*\/\-\. %vminvmaxrempxvhvw()]+\)$/i', $value)) {
        return $value;
    }

    if (preg_match('/^-?\d+(?:\.\d+)?(?:px|%|em|rem|vw|vh|vmin|vmax)?$/i', $value)) {
        return $value;
    }

    return $default;
}

function fugu_theme_sanitize_css_dimension_100($value) {
    return fugu_theme_sanitize_css_dimension_value($value, '100%');
}

function fugu_theme_sanitize_css_dimension_line($value) {
    return fugu_theme_sanitize_css_dimension_value($value, '1px');
}

function fugu_theme_sanitize_header_position($value) {
    $allowed = array('fixed', 'relative');
    return in_array($value, $allowed, true) ? $value : 'fixed';
}

function fugu_theme_sanitize_number_range($number, $setting) {
    if (!is_numeric($number)) {
        return $setting->default;
    }

    $number = floatval($number);

    $control = $setting->manager->get_control($setting->id);
    $attrs = $control ? $control->input_attrs : array();

    $min = isset($attrs['min']) ? floatval($attrs['min']) : $number;
    $max = isset($attrs['max']) ? floatval($attrs['max']) : $number;

    if ($number < $min || $number > $max) {
        return $setting->default;
    }

    if (isset($attrs['step'])) {
        $step = floatval($attrs['step']);
        if ($step > 0) {
            $number = round($number / $step) * $step;

            $step_string = (string) $attrs['step'];
            $precision = 0;
            if (strpos($step_string, '.') !== false) {
                $fraction = rtrim(substr($step_string, strpos($step_string, '.') + 1), '0');
                $precision = strlen($fraction);
            }

            if ($precision > 0) {
                $number = round($number, $precision);
            } else {
                $number = round($number);
            }
        }
    }

    return $number;
}

function fugu_theme_sanitize_transition_duration($value) {
    $value = absint($value);
    if ($value < 100) {
        $value = 100;
    }
    if ($value > 3000) {
        $value = 3000;
    }
    return $value;
}

function fugu_theme_sanitize_transition_animation($value) {
    $allowed = array('slide-up', 'fade');
    return in_array($value, $allowed, true) ? $value : 'slide-up';
}

function fugu_theme_sanitize_transition_position($value) {
    $allowed = array('above', 'under');
    return in_array($value, $allowed, true) ? $value : 'under';
}

function fugu_theme_sanitize_selectors($value) {
    return sanitize_textarea_field($value);
}

function fugu_theme_sanitize_cursor_style($value) {
    $allowed = array('dot', 'ring', 'both');
    return in_array($value, $allowed, true) ? $value : 'dot';
}

function fugu_theme_sanitize_cursor_blend_mode($value) {
    $allowed = array('normal', 'multiply', 'screen', 'overlay', 'difference', 'exclusion');
    return in_array($value, $allowed, true) ? $value : 'normal';
}

function fugu_theme_sanitize_color_alpha($color, $setting) {
    $color = trim($color);

    if ($color === '') {
        return $setting->default;
    }

    $hex = sanitize_hex_color($color);
    if ($hex) {
        return $hex;
    }

    $regex = '/^rgba?\(\s*(?:[01]?\d\d?|2[0-4]\d|25[0-5])\s*,\s*(?:[01]?\d\d?|2[0-4]\d|25[0-5])\s*,\s*(?:[01]?\d\d?|2[0-4]\d|25[0-5])\s*(?:,\s*(?:0|0?\.\d+|1(?:\.0+)?))?\s*\)$/';
    if (preg_match($regex, $color)) {
        return $color;
    }

    return $setting->default;
}

/**
 * Add body class for entrance animations
 */
add_filter('body_class', 'fugu_theme_entrance_body_class');
function fugu_theme_entrance_body_class($classes) {
    if (get_theme_mod('enable_page_transitions', false) && get_theme_mod('enable_page_transitions_entrance', true)) {
        $entrance_animation_type = get_theme_mod('page_transitions_entrance_animation', 'slide-up');
        $classes[] = $entrance_animation_type . '-entrance';
    }
    return $classes;
}

/**
 * Add header content via wp_body_open hook
 * This ensures it loads even when Elementor handles the template
 */
add_action('wp_body_open', 'fugu_theme_header_content', 1);
function fugu_theme_header_content() {
    // Header content can be added here if needed
}

/**
 * Add transition elements before scripts via wp_footer hook
 * Priority 1 ensures it loads before all scripts
 */
add_action('wp_footer', 'fugu_theme_footer_content', 1);
function fugu_theme_footer_content() {
    // Page transition elements
    if (get_theme_mod('enable_page_transitions', false)) : ?>
        <div aria-hidden="true" class="transition-pannel-bg initial-load"></div>
        <?php if (get_theme_mod('enable_page_transitions_borders', true)) : ?>
            <div aria-hidden="true" class="transition-borders-bg"></div>
        <?php endif; ?>
    <?php endif;
}

/**
 * Incluir Animate on Scroll plugin
 */
/**
 * Incluir Animate on Scroll plugin
 */
if (get_theme_mod('enable_animate_on_scroll', false)) {
    $aos_plugin = get_template_directory() . '/inc/animate-on-scroll/plugin.php';
    if (file_exists($aos_plugin)) {
        include_once $aos_plugin;
    }
    
    /**
     * Incluir Elementor Custom Class & Attributes Extension
     * Solo se carga cuando AOS está activado
     */
    $elementor_custom_class = get_template_directory() . '/inc/elementor-custom-class-attributes.php';
    if (file_exists($elementor_custom_class)) {
        include_once $elementor_custom_class;
    }
}

/**
 * Incluir Custom Post Types
 */
$custom_post_types = get_template_directory() . '/inc/custom-post-types.php';
if (file_exists($custom_post_types)) {
    include_once $custom_post_types;
}

/**
 * Load additional theme files.
 */
$fugu_theme_includes = array(
    '/inc/menu-separator-settings.php',
    '/inc/custom-cursor-settings.php',
    '/inc/scrollbar-customizer.php',
    '/inc/scroll-indicator.php',
    '/inc/scroll-to-top.php',
    '/inc/typography-settings.php',
    '/inc/featured-image-column.php',
    '/inc/cleanup-old-meta.php',
    '/inc/portfolio-shortcodes.php',
    '/inc/import-galgdr-municipios.php',
    '/inc/cleanup-municipio-provincias.php',
    '/inc/municipio-popup-support.php',
    '/inc/municipio-search-widget.php',
    '/inc/image-pan-zoom-shortcode.php',
);

foreach ($fugu_theme_includes as $relative_path) {
    $file = get_template_directory() . $relative_path;
    if (file_exists($file)) {
        include_once $file;
    }
}

/**
 * Output Grid Line CSS when enabled
 */
add_action('wp_head', 'fugu_theme_grid_line_styles');
function fugu_theme_grid_line_styles() {
    $grid_line_enable = get_theme_mod('grid_line_enable', false);
    
    if (!$grid_line_enable) {
        return;
    }

    // Get all grid line settings
    $line_color                = get_theme_mod('grid_line_line_color', '#eeeeee');
    $column_color              = get_theme_mod('grid_line_column_color', 'transparent');
    $columns                   = get_theme_mod('grid_line_columns', 12);
    $outline                   = get_theme_mod('grid_line_outline', false);
    $max_width                 = get_theme_mod('grid_line_max_width', '100%');
    $width_desktop             = get_theme_mod('grid_line_width_desktop', '100%');
    $width_tablet              = get_theme_mod('grid_line_width_tablet', '100%');
    $width_mobile_landscape    = get_theme_mod('grid_line_width_mobile_landscape', '100%');
    $width_mobile              = get_theme_mod('grid_line_width_mobile', '100%');
    $line_width                = get_theme_mod('grid_line_line_width', '1px');
    $direction                 = get_theme_mod('grid_line_direction', 90);
    $z_index                   = get_theme_mod('grid_line_z_index', 0);
    
    // Get breakpoints
    $breakpoint_desktop         = get_theme_mod('grid_line_breakpoint_desktop', 1024);
    $breakpoint_tablet          = get_theme_mod('grid_line_breakpoint_tablet', 768);
    $breakpoint_mobile_landscape = get_theme_mod('grid_line_breakpoint_mobile_landscape', 420);

    // Build outline styles
    $outline_style = '';
    if ($outline) {
        $outline_style = 'outline: ' . $line_width . ' solid ' . $line_color . ';';
    }

    ?>
    <style type="text/css">
        :root {
            --grid-line-color: <?php echo esc_attr($line_color); ?>;
            --grid-line-column-color: <?php echo esc_attr($column_color); ?>;
            --grid-line-columns: <?php echo (int) $columns; ?>;
            --grid-line-max-width: <?php echo esc_attr($max_width); ?>;
            --grid-line-thickness: <?php echo esc_attr($line_width); ?>;
            --grid-line-direction: <?php echo (int) $direction; ?>deg;
            --grid-line-z-index: <?php echo (int) $z_index; ?>;
        }
        
        /* Desktop */
        @media (min-width: <?php echo (int) $breakpoint_desktop; ?>px) {
            :root {
                --grid-line-width: <?php echo esc_attr($width_desktop); ?>;
            }
        }
        
        /* Tablet */
        @media (min-width: <?php echo (int) $breakpoint_tablet; ?>px) and (max-width: <?php echo ((int) $breakpoint_desktop - 1); ?>px) {
            :root {
                --grid-line-width: <?php echo esc_attr($width_tablet); ?>;
            }
        }
        
        /* Mobile Landscape */
        @media (min-width: <?php echo (int) $breakpoint_mobile_landscape; ?>px) and (max-width: <?php echo ((int) $breakpoint_tablet - 1); ?>px) {
            :root {
                --grid-line-width: <?php echo esc_attr($width_mobile_landscape); ?>;
            }
        }
        
        /* Mobile */
        @media (max-width: <?php echo ((int) $breakpoint_mobile_landscape - 1); ?>px) {
            :root {
                --grid-line-width: <?php echo esc_attr($width_mobile); ?>;
            }
        }
        
        body::before {
            content: "";
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            margin-right: auto;
            margin-left: auto;
            pointer-events: none;
            z-index: var(--grid-line-z-index, 0);
            min-height: 100vh;
            width: calc(var(--grid-line-width) - (2 * 0px));
            max-width: var(--grid-line-max-width, 100%);
            background-size: calc(100% + var(--grid-line-thickness, 1px)) 100%;
            background-image: repeating-linear-gradient(var(--grid-line-direction, 90deg), var(--grid-line-column-color, transparent), var(--grid-line-column-color, transparent) calc((100% / var(--grid-line-columns, 12)) - var(--grid-line-thickness, 1px)), var(--grid-line-color, #eee) calc((100% / var(--grid-line-columns, 12)) - var(--grid-line-thickness, 1px)), var(--grid-line-color, #eee) calc(100% / var(--grid-line-columns, 12)));
            <?php echo $outline_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        }
    </style>
    <?php
}
