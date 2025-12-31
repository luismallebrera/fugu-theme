<?php
/**
 * Scroll to top front-end output.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output Scroll to Top CSS and JavaScript
 */
function fugu_elementor_scroll_to_top_output() {
    if (!get_theme_mod('enable_scroll_to_top', false)) {
        return;
    }

    $style = get_theme_mod('scroll_to_top_style', 'circle');
    $size = get_theme_mod('scroll_to_top_size', 50);
    $bg_color = get_theme_mod('scroll_to_top_bg_color', '#313C59');
    $icon_color = get_theme_mod('scroll_to_top_icon_color', '#ffffff');
    $hover_bg = get_theme_mod('scroll_to_top_hover_bg_color', '#1e2640');
    $horizontal = get_theme_mod('scroll_to_top_horizontal', 'right');
    $horizontal_offset = get_theme_mod('scroll_to_top_horizontal_offset', 30);
    $horizontal_unit = get_theme_mod('scroll_to_top_horizontal_unit', 'px');
    $horizontal_custom = get_theme_mod('scroll_to_top_horizontal_custom', false);
    $horizontal_custom_value = get_theme_mod('scroll_to_top_horizontal_custom_value', 'calc(2% - 2px)');
    $vertical_offset = get_theme_mod('scroll_to_top_vertical_offset', 30);
    $vertical_unit = get_theme_mod('scroll_to_top_vertical_unit', 'px');
    $vertical_custom = get_theme_mod('scroll_to_top_vertical_custom', false);
    $vertical_custom_value = get_theme_mod('scroll_to_top_vertical_custom_value', 'calc(5vh - 10px)');
    $show_after = get_theme_mod('scroll_to_top_show_after', 300);
    $animation_speed = get_theme_mod('scroll_to_top_animation_speed', 300);
    $zindex = get_theme_mod('scroll_to_top_zindex', 9999);

    $border_radius = '50%';
    if ($style === 'square') {
        $border_radius = '0';
    } elseif ($style === 'rounded') {
        $border_radius = '8px';
    }

    if ($horizontal_custom) {
        $horizontal_position = $horizontal_custom_value;
    } else {
        $horizontal_position = $horizontal_offset . $horizontal_unit;
    }

    if ($vertical_custom) {
        $vertical_position = $vertical_custom_value;
    } else {
        $vertical_position = $vertical_offset . $vertical_unit;
    }

    $position_style = $horizontal === 'left' 
        ? "left: {$horizontal_position};" 
        : "right: {$horizontal_position}";

    ?>
    <style id="scroll-to-top-css">
        .scroll-to-top {
            position: fixed;
            bottom: <?php echo esc_attr($vertical_position); ?>;
            <?php echo $position_style; ?>;
            width: <?php echo esc_attr($size); ?>px;
            height: <?php echo esc_attr($size); ?>px;
            background-color: <?php echo esc_attr($bg_color); ?>;
            color: <?php echo esc_attr($icon_color); ?>;
            border: none;
            border-radius: <?php echo esc_attr($border_radius); ?>;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: <?php echo esc_attr($zindex); ?>;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        .scroll-to-top:hover {
            background-color: <?php echo esc_attr($hover_bg); ?>;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }
        .scroll-to-top svg {
            width: <?php echo esc_attr($size * 0.5); ?>px;
            height: <?php echo esc_attr($size * 0.5); ?>px;
            fill: currentColor;
        }
    </style>
    <script id="scroll-to-top-script">
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.createElement('button');
        button.className = 'scroll-to-top';
        button.setAttribute('aria-label', 'Scroll to top');
        button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/></svg>';
        document.body.appendChild(button);
        
        const showAfter = <?php echo esc_js($show_after); ?>;
        const animationSpeed = <?php echo esc_js($animation_speed); ?>;
        
        const easing = (x) => {
            return x === 0
                ? 0
                : x === 1
                ? 1
                : x < 0.5
                ? Math.pow(2, 20 * x - 10) / 2
                : (2 - Math.pow(2, -20 * x + 10)) / 2;
        };
        
        let lenisInstance = null;
        
        setTimeout(function() {
            if (typeof window.lenis !== 'undefined') {
                lenisInstance = window.lenis;
                console.log('Lenis detected and ready');
            }
        }, 100);
        
        function toggleButton() {
            const scrollPos = lenisInstance ? lenisInstance.scroll : window.pageYOffset;
            if (scrollPos > showAfter) {
                button.classList.add('visible');
            } else {
                button.classList.remove('visible');
            }
        }
        
        button.addEventListener('click', function() {
            if (lenisInstance) {
                lenisInstance.scrollTo(0, {
                    duration: animationSpeed / 1000,
                    easing: easing
                });
            } else {
                const scrollDuration = animationSpeed;
                const scrollStep = -window.pageYOffset / (scrollDuration / 15);
                
                const scrollInterval = setInterval(function() {
                    if (window.pageYOffset !== 0) {
                        window.scrollBy(0, scrollStep);
                    } else {
                        clearInterval(scrollInterval);
                    }
                }, 15);
            }
        });
        
        toggleButton();
        
        setTimeout(function() {
            if (lenisInstance) {
                lenisInstance.on('scroll', toggleButton);
            } else {
                window.addEventListener('scroll', toggleButton, { passive: true });
            }
        }, 150);
    });
    </script>
    <?php
}
add_action('wp_footer', 'fugu_elementor_scroll_to_top_output');
