<?php
/**
 * Image Pan & Zoom Shortcode
 *
 * Usage: [image_pan_zoom image="URL" zoom_min="1" zoom_max="3" zoom_step="0.1" height="600"]
 */

if (!defined('ABSPATH')) {
    exit;
}

function fugu_elementor_image_pan_zoom_shortcode($atts) {
    $atts = shortcode_atts([
        'image' => '',
        'zoom_min' => '1',
        'zoom_max' => '3',
        'zoom_step' => '0.1',
        'height' => '600',
        'width' => '100%',
        'mouse_wheel' => 'true',
        'drag' => 'true',
        'controls' => 'true',
        'controls_position' => 'top-right'
    ], $atts);
    
    if (empty($atts['image'])) {
        return '<p>Image URL required</p>';
    }
    
    static $instance = 0;
    $instance++;
    $id = 'image-pan-zoom-' . $instance;
    
    $controls_html = '';
    if ($atts['controls'] === 'true') {
        $position_class = 'ipz-controls-' . esc_attr($atts['controls_position']);
        $controls_html = '
            <div class="ipz-controls ' . $position_class . '">
                <button class="ipz-btn ipz-zoom-in" data-target="' . $id . '">+</button>
                <button class="ipz-btn ipz-zoom-out" data-target="' . $id . '">−</button>
                <button class="ipz-btn ipz-reset" data-target="' . $id . '">⟲</button>
            </div>';
    }
    
    $output = sprintf(
        '<div class="image-pan-zoom-container" style="width: %s; height: %spx; position: relative; overflow: hidden; cursor: %s; background: #f0f0f0;">
            <img id="%s" src="%s" alt="Pan & Zoom Image" 
                data-zoom-min="%s" 
                data-zoom-max="%s" 
                data-zoom-step="%s"
                data-mouse-wheel="%s"
                data-drag="%s"
                style="max-width: none; position: absolute; transform-origin: 0 0;">
            %s
        </div>',
        esc_attr($atts['width']),
        esc_attr($atts['height']),
        $atts['drag'] === 'true' ? 'grab' : 'default',
        esc_attr($id),
        esc_url($atts['image']),
        esc_attr($atts['zoom_min']),
        esc_attr($atts['zoom_max']),
        esc_attr($atts['zoom_step']),
        esc_attr($atts['mouse_wheel']),
        esc_attr($atts['drag']),
        $controls_html
    );
    
    return $output;
}
add_shortcode('image_pan_zoom', 'fugu_elementor_image_pan_zoom_shortcode');

function fugu_elementor_enqueue_image_pan_zoom_assets() {
    wp_enqueue_style(
        'fugu-elementor-image-pan-zoom',
        get_template_directory_uri() . '/css/image-pan-zoom.css',
        [],
        '1.0.0'
    );
    
    wp_enqueue_script(
        'fugu-elementor-image-pan-zoom',
        get_template_directory_uri() . '/js/image-pan-zoom.js',
        [],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'fugu_elementor_enqueue_image_pan_zoom_assets');
