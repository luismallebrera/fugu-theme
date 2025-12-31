<?php
/**
 * Scroll indicator front-end output.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output Scroll Indicator CSS and JavaScript
 */
function fugu_elementor_scroll_indicator_output() {
    if (!get_theme_mod('enable_scroll_indicator', false)) {
        return;
    }

    $color = get_theme_mod('scroll_indicator_color', '#313C59');
    $bg_color = get_theme_mod('scroll_indicator_bg_color', 'rgba(49, 60, 89, 0.1)');
    $width = get_theme_mod('scroll_indicator_width', 4);
    $height = get_theme_mod('scroll_indicator_height', 100);
    $horizontal = get_theme_mod('scroll_indicator_horizontal', 'right');
    $border_radius = get_theme_mod('scroll_indicator_border_radius', 10);
    
    $use_horizontal_custom = get_theme_mod('scroll_indicator_horizontal_custom', false);
    $use_vertical_custom = get_theme_mod('scroll_indicator_vertical_custom', false);
    
    if ($use_horizontal_custom) {
        $horizontal_value = get_theme_mod('scroll_indicator_horizontal_custom_value', 'calc(2% - 2px)');
        $position_style = $horizontal === 'left' 
            ? "left: {$horizontal_value};" 
            : "right: {$horizontal_value};";
    } else {
        $horizontal_offset = get_theme_mod('scroll_indicator_horizontal_offset', 0);
        $horizontal_unit = get_theme_mod('scroll_indicator_horizontal_unit', 'px');
        $position_style = $horizontal === 'left' 
            ? "left: {$horizontal_offset}{$horizontal_unit};" 
            : "right: {$horizontal_offset}{$horizontal_unit};";
    }
    
    if ($use_vertical_custom) {
        $vertical_value = get_theme_mod('scroll_indicator_vertical_custom_value', 'calc(50vh - 50px)');
    } else {
        $vertical_offset = get_theme_mod('scroll_indicator_vertical_offset', 0);
        $vertical_unit = get_theme_mod('scroll_indicator_vertical_unit', 'px');
        $vertical_value = $vertical_offset . $vertical_unit;
    }

    ?>
    <style id="scroll-indicator-css">
        .scroll-indicator {
            position: fixed;
            bottom: <?php echo esc_attr($vertical_value); ?>;
            <?php echo $position_style; ?>
            width: <?php echo esc_attr($width); ?>px;
            height: <?php echo esc_attr($height); ?>px;
            background-color: <?php echo esc_attr($bg_color); ?>;
            border-radius: <?php echo esc_attr($border_radius); ?>px;
            z-index: 9999;
            overflow: hidden;
        }
        .scroll-indicator-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0%;
            background-color: <?php echo esc_attr($color); ?>;
            border-radius: <?php echo esc_attr($border_radius); ?>px;
            transition: height 0.1s ease-out;
        }
    </style>
    <script id="scroll-indicator-script">
    document.addEventListener('DOMContentLoaded', function() {
        const indicator = document.createElement('div');
        indicator.className = 'scroll-indicator';
        
        const progress = document.createElement('div');
        progress.className = 'scroll-indicator-progress';
        
        indicator.appendChild(progress);
        document.body.appendChild(indicator);
        
        function updateScrollIndicator() {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            const scrollableHeight = documentHeight - windowHeight;
            const scrollPercentage = (scrollTop / scrollableHeight) * 100;
            
            progress.style.height = Math.min(scrollPercentage, 100) + '%';
        }
        
        updateScrollIndicator();
        window.addEventListener('scroll', updateScrollIndicator, { passive: true });
        window.addEventListener('resize', updateScrollIndicator, { passive: true });
    });
    </script>
    <?php
}
add_action('wp_footer', 'fugu_elementor_scroll_indicator_output');
