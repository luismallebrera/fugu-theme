<?php
/**
 * Plugin Name: Elementor AOS Integration
 * Description: Adds AOS (Animate On Scroll) controls to Elementor widgets using data attributes
 * Version: 2.0
 * Author: Copilot + luismallebrera
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function ecc_add_controls_with_aos( $element, $args ) {


    $element->add_control(
        'ecc_animation_type',
        [
            'label' => __( 'Animation Type', 'ecc' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                ''                => __( 'None', 'ecc' ),
                'fade'            => __( 'fade', 'ecc' ),
                'fade-up'         => __( 'fade-up', 'ecc' ),
                'fade-down'       => __( 'fade-down', 'ecc' ),
                'fade-left'       => __( 'fade-left', 'ecc' ),
                'fade-right'      => __( 'fade-right', 'ecc' ),
                'fade-up-right'   => __( 'fade-up-right', 'ecc' ),
                'fade-up-left'    => __( 'fade-up-left', 'ecc' ),
                'fade-down-right' => __( 'fade-down-right', 'ecc' ),
                'fade-down-left'  => __( 'fade-down-left', 'ecc' ),
                'flip-up'         => __( 'flip-up', 'ecc' ),
                'flip-down'       => __( 'flip-down', 'ecc' ),
                'flip-left'       => __( 'flip-left', 'ecc' ),
                'flip-right'      => __( 'flip-right', 'ecc' ),
                'slide-up'        => __( 'slide-up', 'ecc' ),
                'slide-down'      => __( 'slide-down', 'ecc' ),
                'slide-left'      => __( 'slide-left', 'ecc' ),
                'slide-right'     => __( 'slide-right', 'ecc' ),
                'zoom-in'         => __( 'zoom-in', 'ecc' ),
                'zoom-in-up'      => __( 'zoom-in-up', 'ecc' ),
                'zoom-in-down'    => __( 'zoom-in-down', 'ecc' ),
                'zoom-in-left'    => __( 'zoom-in-left', 'ecc' ),
                'zoom-in-right'   => __( 'zoom-in-right', 'ecc' ),
                'zoom-out'        => __( 'zoom-out', 'ecc' ),
                'zoom-out-up'     => __( 'zoom-out-up', 'ecc' ),
                'zoom-out-down'   => __( 'zoom-out-down', 'ecc' ),
                'zoom-out-left'   => __( 'zoom-out-left', 'ecc' ),
                'zoom-out-right'  => __( 'zoom-out-right', 'ecc' ),
                'clip-up'         => __( 'clip-up', 'ecc' ),
                'clip-up-fade'    => __( 'clip-up-fade', 'ecc' ),
                'fade-entrance'   => __( 'fade-entrance', 'ecc' ),
                'entrance-cool'   => __( 'entrance-cool', 'ecc' ),
                'clip-scale'      => __( 'clip-scale', 'ecc' ),

            ],
            'default' => '',
        ]
    );
    $element->add_control(
        'ecc_anchor_placement',
        [
            'label' => __( 'Anchor placements', 'ecc' ),
            'type'  => \Elementor\Controls_Manager::SELECT,
            'options' => [
                ''                => __( 'Default', 'ecc' ),
                'top-bottom'      => __( 'top-bottom', 'ecc' ),
                'top-center'      => __( 'top-center', 'ecc' ),
                'top-top'         => __( 'top-top', 'ecc' ),
                'center-bottom'   => __( 'center-bottom', 'ecc' ),
                'center-center'   => __( 'center-center', 'ecc' ),
                'center-top'      => __( 'center-top', 'ecc' ),
                'bottom-bottom'   => __( 'bottom-bottom', 'ecc' ),
                'bottom-center'   => __( 'bottom-center', 'ecc' ),
                'bottom-top'      => __( 'bottom-top', 'ecc' ),
            ],
            'default' => '',
        ]
    );
    $element->add_control(
        'ecc_easing_function',
        [
            'label' => __( 'Easing functions', 'ecc' ),
            'type'  => \Elementor\Controls_Manager::SELECT,
            'options' => [
                ''                => __( 'Default', 'ecc' ),
                'linear'          => __( 'linear', 'ecc' ),
                'easing-ease'     => __( 'ease', 'ecc' ),
                'easing-ease-in'  => __( 'ease-in', 'ecc' ),
                'easing-ease-out'        => __( 'ease-out', 'ecc' ),
                'easing-ease-in-out'     => __( 'ease-in-out', 'ecc' ),
                'easing-ease-in-back'    => __( 'ease-in-back', 'ecc' ),
                'easing-ease-out-back'   => __( 'ease-out-back', 'ecc' ),
                'easing-ease-in-out-back'=> __( 'ease-in-out-back', 'ecc' ),
                'easing-ease-in-sine'    => __( 'ease-in-sine', 'ecc' ),
                'easing-ease-out-sine'   => __( 'ease-out-sine', 'ecc' ),
                'easing-ease-in-out-sine'=> __( 'ease-in-out-sine', 'ecc' ),
                'easing-ease-in-quad'    => __( 'ease-in-quad', 'ecc' ),
                'easing-ease-out-quad'   => __( 'ease-out-quad', 'ecc' ),
                'easing-ease-in-out-quad'=> __( 'ease-in-out-quad', 'ecc' ),
                'easing-ease-in-cubic'   => __( 'ease-in-cubic', 'ecc' ),
                'easing-ease-out-cubic'  => __( 'ease-out-cubic', 'ecc' ),
                'easing-ease-in-out-cubic'=> __( 'ease-in-out-cubic', 'ecc' ),
                'easing-ease-in-quart'   => __( 'ease-in-quart', 'ecc' ),
                'easing-ease-out-quart'  => __( 'ease-out-quart', 'ecc' ),
                'easing-ease-in-out-quart'=> __( 'ease-in-out-quart', 'ecc' ),
                'easing-ease-out-slow-one'=> __( 'ease-out-slow-one', 'ecc' ),
                'easing-ease-out-slow-two'=> __( 'ease-out-slow-two', 'ecc' ),
            ],
            'default' => '',
        ]
    );
    $element->add_control(
        'ecc_duration',
        [
            'label' => __( 'Animation Duration', 'ecc' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [
                'px' => [
                    'min' => 100,
                    'max' => 3000,
                    'step' => 100,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'show_label' => true,
            'description' => __( 'Duration in ms', 'ecc' ),
        ]
    );
    $element->add_control(
        'ecc_delay',
        [
            'label' => __( 'Animation Delay', 'ecc' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [
                'px' => [
                    'min' => 100,
                    'max' => 3000,
                    'step' => 100,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'show_label' => true,
            'description' => __( 'Delay in ms', 'ecc' ),
        ]
    );
    $element->add_control(
        'ecc_custom_class',
        [
            'label' => __( 'Custom Class', 'ecc' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __( 'your-custom-class-here', 'ecc' ),
        ]
    );
    $element->add_control(
        'ecc_custom_attributes',
        [
            'label' => __( 'Custom Attributes', 'ecc' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __( 'data-example="value"', 'ecc' ),
        ]
    );
}

add_action( 'elementor/element/image/section_image/before_section_end', 'ecc_add_controls_with_aos', 10, 2 );
add_action( 'elementor/element/image-box/section_image/before_section_end', 'ecc_add_controls_with_aos', 10, 2 );
add_action( 'wp_loaded', function() {
    if ( did_action( 'elementor_pro/init' ) ) {
        add_action( 'elementor/element/theme-post-featured-image/section_image/before_section_end', 'ecc_add_controls_with_aos', 10, 2 );
    }
});
add_action( 'elementor/element/heading/section_title/before_section_end', 'ecc_add_controls_with_aos', 10, 2 );
add_action( 'elementor/element/button/section_button/before_section_end', 'ecc_add_controls_with_aos', 10, 2 );
add_action( 'elementor/element/section/section_advanced/before_section_end', 'ecc_add_controls_with_aos', 10, 2 );
add_action( 'elementor/element/column/section_advanced/before_section_end', 'ecc_add_controls_with_aos', 10, 2 );
add_action( 'elementor/element/container/section_layout/before_section_end', 'ecc_add_controls_with_aos', 10, 2 );


// -- IMAGE OUTPUT --
function ecc_add_image_custom_class_attributes( $html, $settings, $image_size_key, $image_key ) {
    $attributes = [];
    
    // Add AOS data attributes
    if ( ! empty( $settings['ecc_animation_type'] ) ) {
        $attributes[] = 'data-aos="' . esc_attr( $settings['ecc_animation_type'] ) . '"';
    }
    if ( ! empty( $settings['ecc_anchor_placement'] ) ) {
        $attributes[] = 'data-aos-anchor-placement="' . esc_attr( $settings['ecc_anchor_placement'] ) . '"';
    }
    if ( ! empty( $settings['ecc_easing_function'] ) ) {
        $easing_value = str_replace('easing-', '', $settings['ecc_easing_function']);
        $attributes[] = 'data-aos-easing=' . esc_attr( $easing_value );
    }
    if ( isset( $settings['ecc_duration']['size'] ) && $settings['ecc_duration']['size'] > 0 ) {
        $attributes[] = 'data-aos-duration="' . esc_attr( $settings['ecc_duration']['size'] ) . '"';
    }
    if ( isset( $settings['ecc_delay']['size'] ) && $settings['ecc_delay']['size'] > 0 ) {
        $attributes[] = 'data-aos-delay="' . esc_attr( $settings['ecc_delay']['size'] ) . '"';
    }
    
    // Add custom class if provided
    if ( ! empty( $settings['ecc_custom_class'] ) ) {
        if ( strpos( $html, 'class=' ) ) {
            $html = preg_replace( '/class="(.*)"/', 'class="' . esc_attr( $settings['ecc_custom_class'] ) . ' $1"', $html );
        } else {
            $html = preg_replace( '/src="(.*)"/', 'class="' . esc_attr( $settings['ecc_custom_class'] ) . '" src="$1"', $html );
        }
    }
    
    // Add AOS attributes to img tag
    if ( ! empty( $attributes ) ) {
        $attributes_string = implode( ' ', $attributes );
        $html = preg_replace( '/<img(.*?)/', '<img ' . $attributes_string . '$1', $html, 1 );
    }
    // Add custom attributes LAST
    if ( ! empty( $settings['ecc_custom_attributes'] ) ) {
        $attrs = explode( ' ', trim( $settings['ecc_custom_attributes'] ) );
        foreach ( $attrs as $attr ) {
            if ( strpos( $attr, '=' ) !== false ) {
                list( $attr_name, $attr_value ) = explode( '=', $attr, 2 );
                $attr_value = trim( $attr_value, '"' );
                if (strpos($html, $attr_name . '=') === false) {
                    $html = preg_replace( '/<img(.*?)\s/', '<img$1 ' . esc_attr($attr_name) . '="' . esc_attr($attr_value) . '" ', $html, 1 );
                }
            }
        }
    }
    return $html;
}
add_filter( 'elementor/image_size/get_attachment_image_html', 'ecc_add_image_custom_class_attributes', 10, 4 );

// -- HEADING AND BUTTON OUTPUT --
function ecc_add_heading_button_custom_class_attributes( $content, $widget ) {
    $settings = $widget->get_settings();
    
    if ( 'heading' === $widget->get_name() ) {
        $tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'];
    } elseif ( 'button' === $widget->get_name() ) {
        $tags = ['a', 'button'];
    } else {
        return $content;
    }
    
    $dom = new \DOMDocument();
    libxml_use_internal_errors(true);
    @$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
    
    foreach ( $tags as $tag ) {
        $elements = $dom->getElementsByTagName($tag);
        foreach ($elements as $el) {
            // Add custom class if provided
            if ( ! empty( $settings['ecc_custom_class'] ) ) {
                $existing_class = $el->getAttribute('class');
                $el->setAttribute('class', trim($existing_class . ' ' . $settings['ecc_custom_class']));
            }
            
            // Add AOS data attributes
            if ( ! empty( $settings['ecc_animation_type'] ) ) {
                $el->setAttribute('data-aos', $settings['ecc_animation_type']);
            }
            if ( ! empty( $settings['ecc_anchor_placement'] ) ) {
                $el->setAttribute('data-aos-anchor-placement', $settings['ecc_anchor_placement']);
            }
            if ( ! empty( $settings['ecc_easing_function'] ) ) {
                $easing_value = str_replace('easing-', '', $settings['ecc_easing_function']);
                $el->setAttribute('data-aos-easing', $easing_value);
            }
            if ( isset( $settings['ecc_duration']['size'] ) && $settings['ecc_duration']['size'] > 0 ) {
                $el->setAttribute('data-aos-duration', $settings['ecc_duration']['size']);
            }
            if ( isset( $settings['ecc_delay']['size'] ) && $settings['ecc_delay']['size'] > 0 ) {
                $el->setAttribute('data-aos-delay', $settings['ecc_delay']['size']);
            }
            
            // Add custom attributes
            if ( ! empty( $settings['ecc_custom_attributes'] ) ) {
                $attrs = explode( ' ', trim( $settings['ecc_custom_attributes'] ) );
                foreach ( $attrs as $attr ) {
                    if ( strpos( $attr, '=' ) !== false ) {
                        list( $attr_name, $attr_value ) = explode( '=', $attr, 2 );
                        $attr_value = trim( $attr_value, '"' );
                        if ( ! $el->hasAttribute($attr_name) ) {
                            $el->setAttribute( $attr_name, $attr_value );
                        }
                    }
                }
            }
        }
    }
    $content = $dom->saveHTML();
    return $content;
}
add_filter( 'elementor/widget/render_content', 'ecc_add_heading_button_custom_class_attributes', 10, 2 );

// -- SECTION/COLUMN/CONTAINER OUTPUT --
function ecc_render_section_column_container_attributes( $element ) {
    $settings = $element->get_settings_for_display();
    
    // Add custom class if provided
    if ( ! empty( $settings['ecc_custom_class'] ) ) {
        $element->add_render_attribute( '_wrapper', 'class', esc_attr( $settings['ecc_custom_class'] ) );
    }
    
    // Add AOS data attributes
    if ( ! empty( $settings['ecc_animation_type'] ) ) {
        $element->add_render_attribute( '_wrapper', 'data-aos', esc_attr( $settings['ecc_animation_type'] ) );
    }
    if ( ! empty( $settings['ecc_anchor_placement'] ) ) {
        $element->add_render_attribute( '_wrapper', 'data-aos-anchor-placement', esc_attr( $settings['ecc_anchor_placement'] ) );
    }
    if ( ! empty( $settings['ecc_easing_function'] ) ) {
        $easing_value = str_replace('easing-', '', $settings['ecc_easing_function']);
        $element->add_render_attribute( '_wrapper', 'data-aos-easing', esc_attr( $easing_value ) );
    }
    if ( isset( $settings['ecc_duration']['size'] ) && $settings['ecc_duration']['size'] > 0 ) {
        $element->add_render_attribute( '_wrapper', 'data-aos-duration', esc_attr( $settings['ecc_duration']['size'] ) );
    }
    if ( isset( $settings['ecc_delay']['size'] ) && $settings['ecc_delay']['size'] > 0 ) {
        $element->add_render_attribute( '_wrapper', 'data-aos-delay', esc_attr( $settings['ecc_delay']['size'] ) );
    }
    
    // Add custom attributes
    if ( ! empty( $settings['ecc_custom_attributes'] ) ) {
        $attrs = explode( ' ', trim( $settings['ecc_custom_attributes'] ) );
        foreach ( $attrs as $attr ) {
            if ( strpos( $attr, '=' ) !== false ) {
                list( $attr_name, $attr_value ) = explode( '=', $attr, 2 );
                $attr_value = trim( $attr_value, '"' );
                if ( ! isset( $element->get_render_attributes()['_wrapper'][$attr_name] ) ) {
                    $element->add_render_attribute( '_wrapper', $attr_name, esc_attr( $attr_value ) );
                }
            }
        }
    }
}
add_action('elementor/frontend/section/before_render', 'ecc_render_section_column_container_attributes');
add_action('elementor/frontend/column/before_render', 'ecc_render_section_column_container_attributes');
add_action('elementor/frontend/container/before_render', 'ecc_render_section_column_container_attributes');
