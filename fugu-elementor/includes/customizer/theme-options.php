<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('customize_register', 'fugu_elementor_customize_header_settings', 15);
function fugu_elementor_customize_header_settings($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('header_settings_section')) {
        $wp_customize->add_section('header_settings_section', array(
            'title'    => __('Header Settings', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 47,
        ));
    }

    $wp_customize->add_setting('header_position', array(
        'default'           => 'fixed',
        'sanitize_callback' => 'fugu_elementor_sanitize_header_position',
    ));
    $wp_customize->add_control('header_position', array(
        'type'        => 'radio',
        'section'     => 'header_settings_section',
        'label'       => __('Header Position', 'fugu-elementor'),
        'description' => __('Choose whether the header should be fixed or relative.', 'fugu-elementor'),
        'choices'     => array(
            'fixed'    => __('Fixed', 'fugu-elementor'),
            'relative' => __('Relative', 'fugu-elementor'),
        ),
    ));

    $wp_customize->add_setting('enable_scroll_class', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_scroll_class', array(
        'type'        => 'checkbox',
        'section'     => 'header_settings_section',
        'label'       => __('Enable Scroll Class', 'fugu-elementor'),
        'description' => __('Add a "scroll" class to body after scrolling past threshold.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('scroll_class_threshold', array(
        'default'           => 100,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_class_threshold', array(
        'type'            => 'number',
        'section'         => 'header_settings_section',
        'label'           => __('Scroll Threshold (px)', 'fugu-elementor'),
        'description'     => __('Amount of pixels to scroll before adding the scroll class.', 'fugu-elementor'),
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

add_action('customize_register', 'fugu_elementor_customize_custom_post_types', 20);
function fugu_elementor_customize_custom_post_types($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('custom_post_types_section')) {
        $wp_customize->add_section('custom_post_types_section', array(
            'title'    => __('Custom Post Types', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 70,
        ));
    }

    $wp_customize->add_setting('enable_portfolio_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_portfolio_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Portfolio', 'fugu-elementor'),
        'description' => __('Enable Portfolio custom post type with categories and tags.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('enable_proyectos_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_proyectos_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Proyectos', 'fugu-elementor'),
        'description' => __('Enable Proyectos custom post type with categories and tags.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('enable_noticias_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_noticias_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Noticias', 'fugu-elementor'),
        'description' => __('Enable Noticias custom post type with categories and tags.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('enable_noticias_slider_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_noticias_slider_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Noticias Slider', 'fugu-elementor'),
        'description' => __('Enable Noticias Slider custom post type for news slider.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('enable_galgdr_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_galgdr_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable GDR & Municipios', 'fugu-elementor'),
        'description' => __('Enable GDR and Municipios custom post types with Provincia taxonomy.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('enable_perfil_contratante_cpt', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_perfil_contratante_cpt', array(
        'type'        => 'checkbox',
        'section'     => 'custom_post_types_section',
        'label'       => __('Enable Perfil Contratante', 'fugu-elementor'),
        'description' => __('Enable Perfil Contratante custom post type with GDR taxonomy.', 'fugu-elementor'),
    ));
}

add_action('customize_register', 'fugu_elementor_customize_menu_separators', 25);
function fugu_elementor_customize_menu_separators($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('menu_separator_section')) {
        $wp_customize->add_section('menu_separator_section', array(
            'title'    => __('Menu Separators', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 31,
        ));
    }

    $wp_customize->add_setting('enable_horizontal_separator', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_horizontal_separator', array(
        'type'        => 'checkbox',
        'section'     => 'menu_separator_section',
        'label'       => __('Enable Horizontal Menu Separator', 'fugu-elementor'),
        'description' => __('Add vertical separator between horizontal menu items.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('horizontal_separator_color', array(
        'default'           => '#dddddd',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'horizontal_separator_color',
        array(
            'section'         => 'menu_separator_section',
            'label'           => __('Horizontal Separator Color', 'fugu-elementor'),
            'active_callback' => 'fugu_elementor_is_horizontal_separator_enabled',
        )
    ));

    $wp_customize->add_setting('horizontal_separator_width', array(
        'default'           => 1,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('horizontal_separator_width', array(
        'type'            => 'number',
        'section'         => 'menu_separator_section',
        'label'           => __('Horizontal Separator Width', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_horizontal_separator_enabled',
    ));

    $wp_customize->add_setting('horizontal_separator_height', array(
        'default'           => 60,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('horizontal_separator_height', array(
        'type'            => 'number',
        'section'         => 'menu_separator_section',
        'label'           => __('Horizontal Separator Height (%)', 'fugu-elementor'),
        'description'     => __('Height as percentage of menu item height.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 20,
            'max'  => 100,
            'step' => 5,
        ),
        'active_callback' => 'fugu_elementor_is_horizontal_separator_enabled',
    ));

    $wp_customize->add_setting('horizontal_separator_rotation', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('horizontal_separator_rotation', array(
        'type'            => 'number',
        'section'         => 'menu_separator_section',
        'label'           => __('Horizontal Separator Rotation (degrees)', 'fugu-elementor'),
        'description'     => __('Rotate the separator line.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => -45,
            'max'  => 45,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_horizontal_separator_enabled',
    ));

    $wp_customize->add_setting('enable_submenu_separator', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_submenu_separator', array(
        'type'        => 'checkbox',
        'section'     => 'menu_separator_section',
        'label'       => __('Enable Submenu Separator', 'fugu-elementor'),
        'description' => __('Add horizontal separator between submenu items.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('submenu_separator_color', array(
        'default'           => '#dddddd',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'submenu_separator_color',
        array(
            'section'         => 'menu_separator_section',
            'label'           => __('Submenu Separator Color', 'fugu-elementor'),
            'active_callback' => 'fugu_elementor_is_submenu_separator_enabled',
        )
    ));

    $wp_customize->add_setting('submenu_separator_width', array(
        'default'           => 1,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('submenu_separator_width', array(
        'type'            => 'number',
        'section'         => 'menu_separator_section',
        'label'           => __('Submenu Separator Width (Thickness)', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_submenu_separator_enabled',
    ));
}

add_action('customize_register', 'fugu_elementor_customize_scroll_indicator', 30);
function fugu_elementor_customize_scroll_indicator($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('scroll_indicator_section')) {
        $wp_customize->add_section('scroll_indicator_section', array(
            'title'    => __('Scroll Indicator', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 50,
        ));
    }

    $wp_customize->add_setting('enable_scroll_indicator', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_scroll_indicator', array(
        'type'        => 'checkbox',
        'section'     => 'scroll_indicator_section',
        'label'       => __('Enable Scroll Indicator', 'fugu-elementor'),
        'description' => __('Show a vertical scroll progress indicator.', 'fugu-elementor'),
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
            'label'           => __('Indicator Color', 'fugu-elementor'),
            'active_callback' => 'fugu_elementor_is_scroll_indicator_enabled',
        )
    ));

    $wp_customize->add_setting('scroll_indicator_width', array(
        'default'           => 4,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_width', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Indicator Width (px)', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 20,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal', array(
        'default'           => 'right',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_indicator_horizontal',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal', array(
        'type'            => 'radio',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Horizontal Position', 'fugu-elementor'),
        'choices'         => array(
            'left'  => __('Left', 'fugu-elementor'),
            'right' => __('Right', 'fugu-elementor'),
        ),
        'active_callback' => 'fugu_elementor_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal_offset', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal_offset', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Horizontal Offset', 'fugu-elementor'),
        'description'     => __('Distance from the edge.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_scroll_indicator_horizontal_offset_active',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_indicator_horizontal_unit',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal_unit', array(
        'type'            => 'radio',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Horizontal Unit', 'fugu-elementor'),
        'choices'         => array(
            'px' => 'px',
            '%'  => '%',
            'vw' => 'vw',
        ),
        'active_callback' => 'fugu_elementor_scroll_indicator_horizontal_offset_active',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal_custom', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal_custom', array(
        'type'            => 'checkbox',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Use Custom Horizontal Value', 'fugu-elementor'),
        'description'     => __('Enable to use calc() or custom CSS values.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_horizontal_custom_value', array(
        'default'           => 'calc(2% - 2px)',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_indicator_custom_value',
    ));
    $wp_customize->add_control('scroll_indicator_horizontal_custom_value', array(
        'type'            => 'text',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Custom Horizontal Value', 'fugu-elementor'),
        'description'     => __('Example: calc(2% - 2px) or 5vw.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_scroll_indicator_horizontal_custom_active',
    ));

    $wp_customize->add_setting('scroll_indicator_vertical_offset', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_vertical_offset', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Vertical Position', 'fugu-elementor'),
        'description'     => __('Distance from bottom.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_scroll_indicator_vertical_offset_active',
    ));

    $wp_customize->add_setting('scroll_indicator_vertical_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_indicator_vertical_unit',
    ));
    $wp_customize->add_control('scroll_indicator_vertical_unit', array(
        'type'            => 'radio',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Vertical Unit', 'fugu-elementor'),
        'choices'         => array(
            'px' => 'px',
            '%'  => '%',
            'vh' => 'vh',
        ),
        'active_callback' => 'fugu_elementor_scroll_indicator_vertical_offset_active',
    ));

    $wp_customize->add_setting('scroll_indicator_vertical_custom', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('scroll_indicator_vertical_custom', array(
        'type'            => 'checkbox',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Use Custom Vertical Value', 'fugu-elementor'),
        'description'     => __('Enable to use calc() or custom CSS values.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_vertical_custom_value', array(
        'default'           => 'calc(50vh - 50px)',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_indicator_custom_value',
    ));
    $wp_customize->add_control('scroll_indicator_vertical_custom_value', array(
        'type'            => 'text',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Custom Vertical Value', 'fugu-elementor'),
        'description'     => __('Example: calc(50vh - 50px) or 10%.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_scroll_indicator_vertical_custom_active',
    ));

    $wp_customize->add_setting('scroll_indicator_height', array(
        'default'           => 100,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_height', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Indicator Height (px)', 'fugu-elementor'),
        'description'     => __('Total height of the indicator bar.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 50,
            'max'  => 500,
            'step' => 10,
        ),
        'active_callback' => 'fugu_elementor_is_scroll_indicator_enabled',
    ));

    $wp_customize->add_setting('scroll_indicator_bg_color', array(
        'default'           => 'rgba(49, 60, 89, 0.1)',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'scroll_indicator_bg_color',
        array(
            'section'         => 'scroll_indicator_section',
            'label'           => __('Background Color', 'fugu-elementor'),
            'description'     => __('Background track color.', 'fugu-elementor'),
            'active_callback' => 'fugu_elementor_is_scroll_indicator_enabled',
        )
    ));

    $wp_customize->add_setting('scroll_indicator_border_radius', array(
        'default'           => 10,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_indicator_border_radius', array(
        'type'            => 'number',
        'section'         => 'scroll_indicator_section',
        'label'           => __('Border Radius (px)', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_scroll_indicator_enabled',
    ));
}

add_action('customize_register', 'fugu_elementor_customize_animate_on_scroll', 32);
function fugu_elementor_customize_animate_on_scroll($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('animate_on_scroll_section')) {
        $wp_customize->add_section('animate_on_scroll_section', array(
            'title'    => __('Animate on Scroll', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 52,
        ));
    }

    $wp_customize->add_setting('enable_animate_on_scroll', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_animate_on_scroll', array(
        'type'        => 'checkbox',
        'section'     => 'animate_on_scroll_section',
        'label'       => __('Enable Animate on Scroll', 'fugu-elementor'),
        'description' => __('Enable the animate on scroll plugin functionality.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('aos_duration', array(
        'default'           => 800,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('aos_duration', array(
        'type'            => 'number',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Animation Duration (ms)', 'fugu-elementor'),
        'description'     => __('Duration of animations in milliseconds.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 100,
            'max'  => 3000,
            'step' => 50,
        ),
        'active_callback' => 'fugu_elementor_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_easing', array(
        'default'           => 'ease-in-out',
        'sanitize_callback' => 'fugu_elementor_sanitize_aos_easing',
    ));
    $wp_customize->add_control('aos_easing', array(
        'type'            => 'select',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Animation Easing', 'fugu-elementor'),
        'description'     => __('Easing function for animations.', 'fugu-elementor'),
        'choices'         => array(
            'linear'          => __('Linear', 'fugu-elementor'),
            'ease'            => __('Ease', 'fugu-elementor'),
            'ease-in'         => __('Ease In', 'fugu-elementor'),
            'ease-out'        => __('Ease Out', 'fugu-elementor'),
            'ease-in-out'     => __('Ease In Out', 'fugu-elementor'),
            'ease-in-back'    => __('Ease In Back', 'fugu-elementor'),
            'ease-out-back'   => __('Ease Out Back', 'fugu-elementor'),
            'ease-in-out-back' => __('Ease In Out Back', 'fugu-elementor'),
        ),
        'active_callback' => 'fugu_elementor_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_once', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('aos_once', array(
        'type'            => 'checkbox',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Animate Once', 'fugu-elementor'),
        'description'     => __('Animation occurs only once when scrolling down.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_mirror', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('aos_mirror', array(
        'type'            => 'checkbox',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Mirror Animation', 'fugu-elementor'),
        'description'     => __('Animate elements when scrolling past them.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_offset', array(
        'default'           => 120,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('aos_offset', array(
        'type'            => 'number',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Animation Offset (px)', 'fugu-elementor'),
        'description'     => __('Offset from the original trigger point.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 500,
            'step' => 10,
        ),
        'active_callback' => 'fugu_elementor_is_animate_on_scroll_enabled',
    ));

    $wp_customize->add_setting('aos_disable_mobile', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('aos_disable_mobile', array(
        'type'            => 'checkbox',
        'section'         => 'animate_on_scroll_section',
        'label'           => __('Disable on Mobile', 'fugu-elementor'),
        'description'     => __('Disable animations on mobile devices for better performance.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_animate_on_scroll_enabled',
    ));
}

add_action('customize_register', 'fugu_elementor_customize_smooth_scrolling', 33);
function fugu_elementor_customize_smooth_scrolling($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('smooth_scrolling_section')) {
        $wp_customize->add_section('smooth_scrolling_section', array(
            'title'    => __('Smooth Scrolling', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 53,
        ));
    }

    $wp_customize->add_setting('enable_smooth_scrolling', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_smooth_scrolling', array(
        'type'        => 'checkbox',
        'section'     => 'smooth_scrolling_section',
        'label'       => __('Enable Smooth Scrolling', 'fugu-elementor'),
        'description' => __('Enable Lenis smooth scrolling on your website.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('smooth_scrolling_disable_wheel', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('smooth_scrolling_disable_wheel', array(
        'type'            => 'checkbox',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Disable Mouse Wheel', 'fugu-elementor'),
        'description'     => __('Disable smooth scrolling for mouse wheel.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_anchor_links', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('smooth_scrolling_anchor_links', array(
        'type'            => 'checkbox',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Smooth Anchor Links', 'fugu-elementor'),
        'description'     => __('Enable smooth scrolling for anchor links.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_gsap', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('smooth_scrolling_gsap', array(
        'type'            => 'checkbox',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Synchronize with GSAP/ScrollTrigger', 'fugu-elementor'),
        'description'     => __('Enable GSAP ScrollTrigger synchronization.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_anchor_offset', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('smooth_scrolling_anchor_offset', array(
        'type'            => 'number',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Smooth Anchor Link Offset (px)', 'fugu-elementor'),
        'description'     => __('Offset for smooth anchor links in pixels.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 500,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_lerp', array(
        'default'           => 0.07,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('smooth_scrolling_lerp', array(
        'type'            => 'number',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Linear Interpolation (lerp) Intensity', 'fugu-elementor'),
        'description'     => __('Between 0 and 1. Lower = smoother. Set to 0 to use duration instead.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1,
            'step' => 0.01,
        ),
        'active_callback' => 'fugu_elementor_is_smooth_scrolling_enabled',
    ));

    $wp_customize->add_setting('smooth_scrolling_duration', array(
        'default'           => 1.2,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('smooth_scrolling_duration', array(
        'type'            => 'number',
        'section'         => 'smooth_scrolling_section',
        'label'           => __('Duration of Scroll Animation (seconds)', 'fugu-elementor'),
        'description'     => __('Set lerp to 0 to use this value.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 5,
            'step' => 0.1,
        ),
        'active_callback' => 'fugu_elementor_is_smooth_scrolling_enabled',
    ));
}

add_action('customize_register', 'fugu_elementor_customize_scroll_to_top', 35);
function fugu_elementor_customize_scroll_to_top($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('scroll_to_top_section')) {
        $wp_customize->add_section('scroll_to_top_section', array(
            'title'    => __('Scroll to Top Button', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 55,
        ));
    }

    $wp_customize->add_setting('enable_scroll_to_top', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_scroll_to_top', array(
        'type'        => 'checkbox',
        'section'     => 'scroll_to_top_section',
        'label'       => __('Enable Scroll to Top Button', 'fugu-elementor'),
        'description' => __('Show a button to scroll back to the top of the page.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('scroll_to_top_style', array(
        'default'           => 'circle',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_to_top_style',
    ));
    $wp_customize->add_control('scroll_to_top_style', array(
        'type'            => 'radio',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Button Style', 'fugu-elementor'),
        'choices'         => array(
            'circle'  => __('Circle', 'fugu-elementor'),
            'square'  => __('Square', 'fugu-elementor'),
            'rounded' => __('Rounded', 'fugu-elementor'),
        ),
        'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_size', array(
        'default'           => 50,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_size', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Button Size (px)', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 30,
            'max'  => 100,
            'step' => 5,
        ),
        'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_bg_color', array(
        'default'           => '#313C59',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'scroll_to_top_bg_color',
        array(
            'section'         => 'scroll_to_top_section',
            'label'           => __('Background Color', 'fugu-elementor'),
            'choices'         => array('alpha' => true),
            'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
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
            'label'           => __('Icon Color', 'fugu-elementor'),
            'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
        )
    ));

    $wp_customize->add_setting('scroll_to_top_hover_bg_color', array(
        'default'           => '#1e2640',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'scroll_to_top_hover_bg_color',
        array(
            'section'         => 'scroll_to_top_section',
            'label'           => __('Hover Background Color', 'fugu-elementor'),
            'choices'         => array('alpha' => true),
            'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
        )
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal', array(
        'default'           => 'right',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_to_top_horizontal',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal', array(
        'type'            => 'radio',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Horizontal Position', 'fugu-elementor'),
        'choices'         => array(
            'left'  => __('Left', 'fugu-elementor'),
            'right' => __('Right', 'fugu-elementor'),
        ),
        'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal_offset', array(
        'default'           => 30,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal_offset', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Horizontal Offset', 'fugu-elementor'),
        'description'     => __('Distance from edge.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_scroll_to_top_horizontal_offset_active',
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_to_top_horizontal_unit',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal_unit', array(
        'type'            => 'radio',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Horizontal Unit', 'fugu-elementor'),
        'choices'         => array(
            'px' => 'px',
            '%'  => '%',
            'vw' => 'vw',
        ),
        'active_callback' => 'fugu_elementor_scroll_to_top_horizontal_offset_active',
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal_custom', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal_custom', array(
        'type'            => 'checkbox',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Use Custom Horizontal Value', 'fugu-elementor'),
        'description'     => __('Enable to use calc() or custom CSS values.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_horizontal_custom_value', array(
        'default'           => 'calc(2% - 2px)',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_to_top_custom_value',
    ));
    $wp_customize->add_control('scroll_to_top_horizontal_custom_value', array(
        'type'            => 'text',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Custom Horizontal Value', 'fugu-elementor'),
        'description'     => __('Example: calc(2% - 2px) or 5vw.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_scroll_to_top_horizontal_custom_active',
    ));

    $wp_customize->add_setting('scroll_to_top_vertical_offset', array(
        'default'           => 30,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_vertical_offset', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Vertical Position', 'fugu-elementor'),
        'description'     => __('Distance from bottom.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_scroll_to_top_vertical_offset_active',
    ));

    $wp_customize->add_setting('scroll_to_top_vertical_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_to_top_vertical_unit',
    ));
    $wp_customize->add_control('scroll_to_top_vertical_unit', array(
        'type'            => 'radio',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Vertical Unit', 'fugu-elementor'),
        'choices'         => array(
            'px' => 'px',
            '%'  => '%',
            'vh' => 'vh',
        ),
        'active_callback' => 'fugu_elementor_scroll_to_top_vertical_offset_active',
    ));

    $wp_customize->add_setting('scroll_to_top_vertical_custom', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('scroll_to_top_vertical_custom', array(
        'type'            => 'checkbox',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Use Custom Vertical Value', 'fugu-elementor'),
        'description'     => __('Enable to use calc() or custom CSS values.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_vertical_custom_value', array(
        'default'           => 'calc(5vh - 10px)',
        'sanitize_callback' => 'fugu_elementor_sanitize_scroll_to_top_custom_value',
    ));
    $wp_customize->add_control('scroll_to_top_vertical_custom_value', array(
        'type'            => 'text',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Custom Vertical Value', 'fugu-elementor'),
        'description'     => __('Example: calc(5vh - 10px) or 3%.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_scroll_to_top_vertical_custom_active',
    ));

    $wp_customize->add_setting('scroll_to_top_show_after', array(
        'default'           => 300,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_show_after', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Show After Scroll (px)', 'fugu-elementor'),
        'description'     => __('Show button after scrolling X pixels.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 100,
            'max'  => 1000,
            'step' => 50,
        ),
        'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_animation_speed', array(
        'default'           => 300,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_animation_speed', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Animation Speed (ms)', 'fugu-elementor'),
        'description'     => __('Animation speed in milliseconds.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 100,
            'max'  => 1000,
            'step' => 50,
        ),
        'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
    ));

    $wp_customize->add_setting('scroll_to_top_zindex', array(
        'default'           => 9999,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('scroll_to_top_zindex', array(
        'type'            => 'number',
        'section'         => 'scroll_to_top_section',
        'label'           => __('Z-Index', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 999999,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_scroll_to_top_enabled',
    ));
}

add_action('customize_register', 'fugu_elementor_customize_scrollbar', 40);
function fugu_elementor_customize_scrollbar($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('fugu_elementor_scrollbar')) {
        $wp_customize->add_section('fugu_elementor_scrollbar', array(
            'title'    => __('Scrollbar', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 160,
        ));
    }

    $wp_customize->add_setting('wp_scrollbar_width', array(
        'default'           => 4,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('wp_scrollbar_width', array(
        'type'        => 'number',
        'section'     => 'fugu_elementor_scrollbar',
        'label'       => __('Scrollbar Width (px)', 'fugu-elementor'),
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 64,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('wp_scrollbar_track_color', array(
        'default'           => '#dddddd',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'wp_scrollbar_track_color',
        array(
            'section' => 'fugu_elementor_scrollbar',
            'label'   => __('Scrollbar Track Color', 'fugu-elementor'),
            'choices' => array('alpha' => true),
        )
    ));

    $wp_customize->add_setting('wp_scrollbar_thumb_color', array(
        'default'           => '#ff0000',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'wp_scrollbar_thumb_color',
        array(
            'section' => 'fugu_elementor_scrollbar',
            'label'   => __('Scrollbar Thumb Color', 'fugu-elementor'),
            'choices' => array('alpha' => true),
        )
    ));

    $wp_customize->add_setting('wp_scrollbar_thumb_hover_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'wp_scrollbar_thumb_hover_color',
        array(
            'section'     => 'fugu_elementor_scrollbar',
            'label'       => __('Scrollbar Thumb (html.scrolling) Color', 'fugu-elementor'),
            'description' => __('Color when html has the "scrolling" class applied.', 'fugu-elementor'),
            'choices'     => array('alpha' => true),
        )
    ));
}

add_action('customize_register', 'fugu_elementor_customize_grid_line', 45);
function fugu_elementor_customize_grid_line($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('grid_line_section')) {
        $wp_customize->add_section('grid_line_section', array(
            'title'    => __('Grid Line Overlay', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 60,
        ));
    }

    $wp_customize->add_setting('grid_line_enable', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('grid_line_enable', array(
        'type'        => 'checkbox',
        'section'     => 'grid_line_section',
        'label'       => __('Enable Grid Line', 'fugu-elementor'),
        'description' => __('Display a grid line overlay on the page.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('grid_line_breakpoint_desktop', array(
        'default'           => 1024,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_breakpoint_desktop', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Desktop Min Width', 'fugu-elementor'),
        'description'     => __('Minimum width for desktop (default: 1024px).', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 3000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_breakpoint_tablet', array(
        'default'           => 768,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_breakpoint_tablet', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Tablet Min Width', 'fugu-elementor'),
        'description'     => __('Minimum width for tablet (default: 768px).', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 3000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_breakpoint_mobile_landscape', array(
        'default'           => 420,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_breakpoint_mobile_landscape', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Mobile Landscape Min Width', 'fugu-elementor'),
        'description'     => __('Minimum width for mobile landscape (default: 420px).', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 3000,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_line_color', array(
        'default'           => '#eeeeee',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'grid_line_line_color',
        array(
            'section'         => 'grid_line_section',
            'label'           => __('Line Color', 'fugu-elementor'),
            'description'     => __('Color of the grid lines.', 'fugu-elementor'),
            'choices'         => array('alpha' => true),
            'active_callback' => 'fugu_elementor_is_grid_line_enabled',
        )
    ));

    $wp_customize->add_setting('grid_line_column_color', array(
        'default'           => 'transparent',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'grid_line_column_color',
        array(
            'section'         => 'grid_line_section',
            'label'           => __('Column Color', 'fugu-elementor'),
            'description'     => __('Background color between lines.', 'fugu-elementor'),
            'choices'         => array('alpha' => true),
            'active_callback' => 'fugu_elementor_is_grid_line_enabled',
        )
    ));

    $wp_customize->add_setting('grid_line_columns', array(
        'default'           => 12,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_columns', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Number of Columns', 'fugu-elementor'),
        'description'     => __('Number of grid columns to display.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 24,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_outline', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('grid_line_outline', array(
        'type'            => 'checkbox',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Outline', 'fugu-elementor'),
        'description'     => __('Draw an outline around the grid overlay.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_max_width', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_elementor_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_max_width', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Max Width', 'fugu-elementor'),
        'description'     => __('Maximum width of the grid overlay (supports px, %, vw, calc).', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_width_desktop', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_elementor_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_width_desktop', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Width (Desktop)', 'fugu-elementor'),
        'description'     => __('Width of the grid container on desktop.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_width_tablet', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_elementor_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_width_tablet', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Width (Tablet)', 'fugu-elementor'),
        'description'     => __('Width of the grid container on tablet.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_width_mobile_landscape', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_elementor_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_width_mobile_landscape', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Width (Mobile Landscape)', 'fugu-elementor'),
        'description'     => __('Width of the grid container on mobile landscape.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_width_mobile', array(
        'default'           => '100%',
        'sanitize_callback' => 'fugu_elementor_sanitize_css_dimension_100',
    ));
    $wp_customize->add_control('grid_line_width_mobile', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Grid Width (Mobile)', 'fugu-elementor'),
        'description'     => __('Width of the grid container on mobile.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_line_width', array(
        'default'           => '1px',
        'sanitize_callback' => 'fugu_elementor_sanitize_css_dimension_line',
    ));
    $wp_customize->add_control('grid_line_line_width', array(
        'type'            => 'text',
        'section'         => 'grid_line_section',
        'label'           => __('Line Width', 'fugu-elementor'),
        'description'     => __('Thickness of each grid line.', 'fugu-elementor'),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_direction', array(
        'default'           => 90,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_direction', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Line Direction (degrees)', 'fugu-elementor'),
        'description'     => __('Angle of the grid lines.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => -360,
            'max'  => 360,
            'step' => 15,
        ),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));

    $wp_customize->add_setting('grid_line_z_index', array(
        'default'           => 0,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('grid_line_z_index', array(
        'type'            => 'number',
        'section'         => 'grid_line_section',
        'label'           => __('Z-Index', 'fugu-elementor'),
        'description'     => __('Stacking order of the grid overlay.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => -9999,
            'max'  => 9999,
            'step' => 1,
        ),
        'active_callback' => 'fugu_elementor_is_grid_line_enabled',
    ));
}

add_action('customize_register', 'fugu_elementor_customize_page_transitions', 20);
function fugu_elementor_customize_page_transitions($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('page_transitions_section')) {
        $wp_customize->add_section('page_transitions_section', array(
            'title'    => __('Page Transitions', 'fugu-elementor'),
            'panel'    => 'theme_options',
            'priority' => 55,
        ));
    }

    $wp_customize->add_setting('enable_page_transitions', array(
        'default'           => false,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_page_transitions', array(
        'type'        => 'checkbox',
        'section'     => 'page_transitions_section',
        'label'       => __('Enable Page Transitions', 'fugu-elementor'),
        'description' => __('Enable smooth transitions between page navigation.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('page_transitions_duration', array(
        'default'           => 900,
        'sanitize_callback' => 'fugu_elementor_sanitize_transition_duration',
    ));
    $wp_customize->add_control('page_transitions_duration', array(
        'type'        => 'number',
        'section'     => 'page_transitions_section',
        'label'       => __('Transition Duration (ms)', 'fugu-elementor'),
        'description' => __('Duration of the transition animation in milliseconds.', 'fugu-elementor'),
        'input_attrs' => array(
            'min'  => 100,
            'max'  => 3000,
            'step' => 50,
        ),
    ));

    $wp_customize->add_setting('page_transitions_exit_animation', array(
        'default'           => 'slide-up',
        'sanitize_callback' => 'fugu_elementor_sanitize_transition_animation',
    ));
    $wp_customize->add_control('page_transitions_exit_animation', array(
        'type'        => 'select',
        'section'     => 'page_transitions_section',
        'label'       => __('Exit Animation Type', 'fugu-elementor'),
        'description' => __('Choose the animation style when leaving a page.', 'fugu-elementor'),
        'choices'     => array(
            'slide-up' => __('Slide', 'fugu-elementor'),
            'fade'     => __('Fade', 'fugu-elementor'),
        ),
    ));

    $wp_customize->add_setting('page_transitions_entrance_animation', array(
        'default'           => 'slide-up',
        'sanitize_callback' => 'fugu_elementor_sanitize_transition_animation',
    ));
    $wp_customize->add_control('page_transitions_entrance_animation', array(
        'type'        => 'select',
        'section'     => 'page_transitions_section',
        'label'       => __('Entrance Animation Type', 'fugu-elementor'),
        'description' => __('Choose the animation style when entering a page.', 'fugu-elementor'),
        'choices'     => array(
            'slide-up' => __('Slide', 'fugu-elementor'),
            'fade'     => __('Fade', 'fugu-elementor'),
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
            'label'       => __('Panel Color', 'fugu-elementor'),
            'description' => __('Background color of the transition panel.', 'fugu-elementor'),
        )
    ));

    $wp_customize->add_setting('enable_page_transitions_borders', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_page_transitions_borders', array(
        'type'        => 'checkbox',
        'section'     => 'page_transitions_section',
        'label'       => __('Enable Transition Borders', 'fugu-elementor'),
        'description' => __('Enable border frame animation during page transitions.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('enable_page_transitions_entrance', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_page_transitions_entrance', array(
        'type'        => 'checkbox',
        'section'     => 'page_transitions_section',
        'label'       => __('Enable Entrance Animation', 'fugu-elementor'),
        'description' => __('Enable entrance animation on page load.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('page_transitions_position', array(
        'default'           => 'under',
        'sanitize_callback' => 'fugu_elementor_sanitize_transition_position',
    ));
    $wp_customize->add_control('page_transitions_position', array(
        'type'        => 'radio',
        'section'     => 'page_transitions_section',
        'label'       => __('Transition Position', 'fugu-elementor'),
        'description' => __('Choose whether transitions appear above or under page content.', 'fugu-elementor'),
        'choices'     => array(
            'above' => __('Above Page Content', 'fugu-elementor'),
            'under' => __('Under Page Content', 'fugu-elementor'),
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
            'label'           => __('Borders Color', 'fugu-elementor'),
            'description'     => __('Background color of the transition borders.', 'fugu-elementor'),
            'active_callback' => function($control) {
                $setting = $control->manager->get_setting('enable_page_transitions_borders');
                return $setting ? (bool) $setting->value() : true;
            },
        )
    ));

    $wp_customize->add_setting('page_transitions_selectors', array(
        'default'           => '.menu li a, .elementor-widget-image > a, .soda-post-nav-next a, .soda-post-nav-prev a',
        'sanitize_callback' => 'fugu_elementor_sanitize_selectors',
    ));
    $wp_customize->add_control('page_transitions_selectors', array(
        'type'        => 'textarea',
        'section'     => 'page_transitions_section',
        'label'       => __('Click Selectors', 'fugu-elementor'),
        'description' => __('CSS selectors for elements that trigger transitions (comma-separated).', 'fugu-elementor'),
    ));
}

add_action('customize_register', 'fugu_elementor_customize_custom_cursor', 25);
function fugu_elementor_customize_custom_cursor($wp_customize) {
    if (!$wp_customize->get_panel('theme_options')) {
        $wp_customize->add_panel('theme_options', array(
            'priority'    => 10,
            'title'       => __('Theme Options', 'fugu-elementor'),
            'description' => __('General theme options', 'fugu-elementor'),
        ));
    }

    if (!$wp_customize->get_section('custom_cursor_section')) {
        $wp_customize->add_section('custom_cursor_section', array(
            'title'    => __('Custom Cursor', 'fugu-elementor'),
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
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_custom_cursor', array(
        'type'        => 'checkbox',
        'section'     => 'custom_cursor_section',
        'label'       => __('Enable Custom Cursor', 'fugu-elementor'),
        'description' => __('Replace default cursor with a modern custom cursor.', 'fugu-elementor'),
    ));

    $wp_customize->add_setting('hide_default_cursor', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('hide_default_cursor', array(
        'type'            => 'checkbox',
        'section'         => 'custom_cursor_section',
        'label'           => __('Hide Default Cursor', 'fugu-elementor'),
        'description'     => __('Hide the system cursor when the custom cursor is active.', 'fugu-elementor'),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('disable_cursor_on_mobile', array(
        'default'           => true,
        'sanitize_callback' => 'fugu_elementor_sanitize_checkbox',
    ));
    $wp_customize->add_control('disable_cursor_on_mobile', array(
        'type'            => 'checkbox',
        'section'         => 'custom_cursor_section',
        'label'           => __('Disable on Mobile/Touch Devices', 'fugu-elementor'),
        'description'     => __('Automatically disable the custom cursor on touch devices.', 'fugu-elementor'),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_style', array(
        'default'           => 'dot',
        'sanitize_callback' => 'fugu_elementor_sanitize_cursor_style',
    ));
    $wp_customize->add_control('cursor_style', array(
        'type'            => 'radio',
        'section'         => 'custom_cursor_section',
        'label'           => __('Cursor Style', 'fugu-elementor'),
        'choices'         => array(
            'dot'  => __('Dot', 'fugu-elementor'),
            'ring' => __('Ring', 'fugu-elementor'),
            'both' => __('Dot + Ring', 'fugu-elementor'),
        ),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_size', array(
        'default'           => 8,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_size', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Cursor Dot Size (px)', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 4,
            'max'  => 30,
            'step' => 1,
        ),
        'active_callback' => $active_if_dot,
    ));

    $wp_customize->add_setting('cursor_color', array(
        'default'           => '#000000',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'cursor_color',
        array(
            'section'         => 'custom_cursor_section',
            'label'           => __('Cursor Color', 'fugu-elementor'),
            'active_callback' => $active_if_enabled,
        )
    ));

    $wp_customize->add_setting('cursor_ring_size', array(
        'default'           => 40,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_ring_size', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Ring Size (px)', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 20,
            'max'  => 80,
            'step' => 1,
        ),
        'active_callback' => $active_if_ring,
    ));

    $wp_customize->add_setting('cursor_ring_border', array(
        'default'           => 2,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_ring_border', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Ring Border Width (px)', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 5,
            'step' => 1,
        ),
        'active_callback' => $active_if_ring,
    ));

    $wp_customize->add_setting('cursor_hover_scale', array(
        'default'           => 1.5,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_hover_scale', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Hover Scale', 'fugu-elementor'),
        'description'     => __('Scale multiplier when hovering over links.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 1,
            'max'  => 3,
            'step' => 0.1,
        ),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_animation_speed', array(
        'default'           => 200,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_animation_speed', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Animation Speed (ms)', 'fugu-elementor'),
        'description'     => __('Lower values animate faster.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 50,
            'max'  => 500,
            'step' => 50,
        ),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_dot_inertia', array(
        'default'           => 0.15,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_dot_inertia', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Dot Inertia/Delay', 'fugu-elementor'),
        'description'     => __('Smoothness of the dot following the pointer.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 0.5,
            'step' => 0.05,
        ),
        'active_callback' => $active_if_dot,
    ));

    $wp_customize->add_setting('cursor_ring_inertia', array(
        'default'           => 0.25,
        'sanitize_callback' => 'fugu_elementor_sanitize_number_range',
    ));
    $wp_customize->add_control('cursor_ring_inertia', array(
        'type'            => 'number',
        'section'         => 'custom_cursor_section',
        'label'           => __('Ring Inertia/Delay', 'fugu-elementor'),
        'description'     => __('Smoothness of the ring following the pointer.', 'fugu-elementor'),
        'input_attrs'     => array(
            'min'  => 0,
            'max'  => 0.5,
            'step' => 0.05,
        ),
        'active_callback' => $active_if_ring,
    ));

    $wp_customize->add_setting('cursor_blend_mode', array(
        'default'           => 'normal',
        'sanitize_callback' => 'fugu_elementor_sanitize_cursor_blend_mode',
    ));
    $wp_customize->add_control('cursor_blend_mode', array(
        'type'            => 'select',
        'section'         => 'custom_cursor_section',
        'label'           => __('Blend Mode', 'fugu-elementor'),
        'description'     => __('CSS blend mode for the cursor visuals.', 'fugu-elementor'),
        'choices'         => array(
            'normal'     => __('Normal', 'fugu-elementor'),
            'multiply'   => __('Multiply', 'fugu-elementor'),
            'screen'     => __('Screen', 'fugu-elementor'),
            'overlay'    => __('Overlay', 'fugu-elementor'),
            'difference' => __('Difference', 'fugu-elementor'),
            'exclusion'  => __('Exclusion', 'fugu-elementor'),
        ),
        'active_callback' => $active_if_enabled,
    ));

    $wp_customize->add_setting('cursor_ring_fill_color', array(
        'default'           => 'rgba(0,0,0,0)',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'cursor_ring_fill_color',
        array(
            'section'         => 'custom_cursor_section',
            'label'           => __('Ring Fill Color', 'fugu-elementor'),
            'description'     => __('Background fill color for the ring.', 'fugu-elementor'),
            'active_callback' => $active_if_ring,
        )
    ));

    $wp_customize->add_setting('cursor_ring_fill_hover_color', array(
        'default'           => 'rgba(0,0,0,0.1)',
        'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'cursor_ring_fill_hover_color',
        array(
            'section'         => 'custom_cursor_section',
            'label'           => __('Ring Fill Hover Color', 'fugu-elementor'),
            'description'     => __('Ring fill color when hovering interactive elements.', 'fugu-elementor'),
            'active_callback' => $active_if_ring,
        )
    ));
}

add_action('wp_head', 'fugu_elementor_header_position_css');
function fugu_elementor_header_position_css() {
    $position = fugu_elementor_sanitize_header_position(get_theme_mod('header_position', 'fixed'));

    if ($position === 'relative') {
        echo '<style id="header-position-css">header{position:relative!important;}</style>';
        return;
    }

    echo '<style id="header-position-css">header{position:fixed;z-index:999;width:100%;}</style>';
}

function fugu_elementor_sanitize_checkbox($checked) {
    return (bool) $checked;
}

function fugu_elementor_is_horizontal_separator_enabled($control) {
    $setting = $control->manager->get_setting('enable_horizontal_separator');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_elementor_is_submenu_separator_enabled($control) {
    $setting = $control->manager->get_setting('enable_submenu_separator');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_elementor_is_scroll_indicator_enabled($control) {
    $setting = $control->manager->get_setting('enable_scroll_indicator');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_elementor_is_animate_on_scroll_enabled($control) {
    $setting = $control->manager->get_setting('enable_animate_on_scroll');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_elementor_scroll_indicator_horizontal_offset_active($control) {
    if (!fugu_elementor_is_scroll_indicator_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_indicator_horizontal_custom');
    return $custom ? !(bool) $custom->value() : true;
}

function fugu_elementor_scroll_indicator_horizontal_custom_active($control) {
    if (!fugu_elementor_is_scroll_indicator_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_indicator_horizontal_custom');
    return $custom ? (bool) $custom->value() : false;
}

function fugu_elementor_scroll_indicator_vertical_offset_active($control) {
    if (!fugu_elementor_is_scroll_indicator_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_indicator_vertical_custom');
    return $custom ? !(bool) $custom->value() : true;
}

function fugu_elementor_scroll_indicator_vertical_custom_active($control) {
    if (!fugu_elementor_is_scroll_indicator_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_indicator_vertical_custom');
    return $custom ? (bool) $custom->value() : false;
}

function fugu_elementor_is_scroll_to_top_enabled($control) {
    $setting = $control->manager->get_setting('enable_scroll_to_top');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_elementor_scroll_to_top_horizontal_offset_active($control) {
    if (!fugu_elementor_is_scroll_to_top_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_to_top_horizontal_custom');
    return $custom ? !(bool) $custom->value() : true;
}

function fugu_elementor_scroll_to_top_horizontal_custom_active($control) {
    if (!fugu_elementor_is_scroll_to_top_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_to_top_horizontal_custom');
    return $custom ? (bool) $custom->value() : false;
}

function fugu_elementor_scroll_to_top_vertical_offset_active($control) {
    if (!fugu_elementor_is_scroll_to_top_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_to_top_vertical_custom');
    return $custom ? !(bool) $custom->value() : true;
}

function fugu_elementor_scroll_to_top_vertical_custom_active($control) {
    if (!fugu_elementor_is_scroll_to_top_enabled($control)) {
        return false;
    }

    $custom = $control->manager->get_setting('scroll_to_top_vertical_custom');
    return $custom ? (bool) $custom->value() : false;
}

function fugu_elementor_is_smooth_scrolling_enabled($control) {
    $setting = $control->manager->get_setting('enable_smooth_scrolling');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_elementor_is_grid_line_enabled($control) {
    $setting = $control->manager->get_setting('grid_line_enable');
    return $setting ? (bool) $setting->value() : false;
}

function fugu_elementor_sanitize_scroll_indicator_horizontal($value) {
    $allowed = array('left', 'right');
    return in_array($value, $allowed, true) ? $value : 'right';
}

function fugu_elementor_sanitize_scroll_indicator_horizontal_unit($value) {
    $allowed = array('px', '%', 'vw');
    return in_array($value, $allowed, true) ? $value : 'px';
}

function fugu_elementor_sanitize_scroll_indicator_vertical_unit($value) {
    $allowed = array('px', '%', 'vh');
    return in_array($value, $allowed, true) ? $value : 'px';
}

function fugu_elementor_sanitize_scroll_indicator_custom_value($value) {
    return sanitize_text_field($value);
}

function fugu_elementor_sanitize_aos_easing($value) {
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

function fugu_elementor_sanitize_scroll_to_top_style($value) {
    $allowed = array('circle', 'square', 'rounded');
    return in_array($value, $allowed, true) ? $value : 'circle';
}

function fugu_elementor_sanitize_scroll_to_top_horizontal($value) {
    $allowed = array('left', 'right');
    return in_array($value, $allowed, true) ? $value : 'right';
}

function fugu_elementor_sanitize_scroll_to_top_horizontal_unit($value) {
    $allowed = array('px', '%', 'vw');
    return in_array($value, $allowed, true) ? $value : 'px';
}

function fugu_elementor_sanitize_scroll_to_top_vertical_unit($value) {
    $allowed = array('px', '%', 'vh');
    return in_array($value, $allowed, true) ? $value : 'px';
}

function fugu_elementor_sanitize_scroll_to_top_custom_value($value) {
    return sanitize_text_field($value);
}

function fugu_elementor_sanitize_css_dimension_value($value, $default) {
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

function fugu_elementor_sanitize_css_dimension_100($value) {
    return fugu_elementor_sanitize_css_dimension_value($value, '100%');
}

function fugu_elementor_sanitize_css_dimension_line($value) {
    return fugu_elementor_sanitize_css_dimension_value($value, '1px');
}

function fugu_elementor_sanitize_header_position($value) {
    $allowed = array('fixed', 'relative');
    return in_array($value, $allowed, true) ? $value : 'fixed';
}

function fugu_elementor_sanitize_number_range($number, $setting) {
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

function fugu_elementor_sanitize_transition_duration($value) {
    $value = absint($value);
    if ($value < 100) {
        $value = 100;
    }
    if ($value > 3000) {
        $value = 3000;
    }
    return $value;
}

function fugu_elementor_sanitize_transition_animation($value) {
    $allowed = array('slide-up', 'fade');
    return in_array($value, $allowed, true) ? $value : 'slide-up';
}

function fugu_elementor_sanitize_transition_position($value) {
    $allowed = array('above', 'under');
    return in_array($value, $allowed, true) ? $value : 'under';
}

function fugu_elementor_sanitize_selectors($value) {
    return sanitize_textarea_field($value);
}

function fugu_elementor_sanitize_cursor_style($value) {
    $allowed = array('dot', 'ring', 'both');
    return in_array($value, $allowed, true) ? $value : 'dot';
}

function fugu_elementor_sanitize_cursor_blend_mode($value) {
    $allowed = array('normal', 'multiply', 'screen', 'overlay', 'difference', 'exclusion');
    return in_array($value, $allowed, true) ? $value : 'normal';
}

function fugu_elementor_sanitize_color_alpha($color, $setting) {
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
add_filter('body_class', 'fugu_elementor_entrance_body_class');
function fugu_elementor_entrance_body_class($classes) {
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
add_action('wp_body_open', 'fugu_elementor_header_content', 1);
function fugu_elementor_header_content() {
    // Header content can be added here if needed
}

/**
 * Add transition elements before scripts via wp_footer hook
 * Priority 1 ensures it loads before all scripts
 */
add_action('wp_footer', 'fugu_elementor_footer_content', 1);
function fugu_elementor_footer_content() {
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
$fugu_elementor_includes = array(
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

foreach ($fugu_elementor_includes as $relative_path) {
    $file = get_template_directory() . $relative_path;
    if (file_exists($file)) {
        include_once $file;
    }
}

/**
 * Output Grid Line CSS when enabled
 */
add_action('wp_head', 'fugu_elementor_grid_line_styles');
function fugu_elementor_grid_line_styles() {
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
