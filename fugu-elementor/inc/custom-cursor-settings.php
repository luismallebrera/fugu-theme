<?php
/**
 * Custom cursor front-end output.
 *
 * @package FuguElementor
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue custom cursor markup, styles, and scripts.
 */
function fugu_elementor_custom_cursor_enqueue() {
    if (!get_theme_mod('enable_custom_cursor', false)) {
        return;
    }

    $disable_on_mobile = get_theme_mod('disable_cursor_on_mobile', true);
    $hide_default = get_theme_mod('hide_default_cursor', true);
    $cursor_style = get_theme_mod('cursor_style', 'dot');
    $cursor_size = get_theme_mod('cursor_size', 8);
    $cursor_color = get_theme_mod('cursor_color', '#000000');
    $ring_size = get_theme_mod('cursor_ring_size', 40);
    $ring_border = get_theme_mod('cursor_ring_border', 2);
    $ring_fill = get_theme_mod('cursor_ring_fill_color', 'rgba(0,0,0,0)');
    $ring_fill_hover = get_theme_mod('cursor_ring_fill_hover_color', 'rgba(0,0,0,0.1)');
    $hover_scale = get_theme_mod('cursor_hover_scale', 1.5);
    $animation_speed = get_theme_mod('cursor_animation_speed', 200);
    $blend_mode = get_theme_mod('cursor_blend_mode', 'normal');
    $dot_inertia = get_theme_mod('cursor_dot_inertia', 0.15);
    $ring_inertia = get_theme_mod('cursor_ring_inertia', 0.25);

    $cursor_none = $hide_default ? 'cursor: none !important;' : '';

    ?>
    <style id="custom-cursor-styles">
    * {
        <?php echo $cursor_none; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    }

    .custom-cursor-dot {
        position: fixed;
        top: 0;
        left: 0;
        width: <?php echo esc_attr($cursor_size); ?>px;
        height: <?php echo esc_attr($cursor_size); ?>px;
        background-color: <?php echo esc_attr($cursor_color); ?>;
        border-radius: 50%;
        pointer-events: none;
        z-index: 99999;
        transition: transform <?php echo esc_attr($animation_speed); ?>ms ease, opacity <?php echo esc_attr($animation_speed); ?>ms ease;
        mix-blend-mode: <?php echo esc_attr($blend_mode); ?>;
    }

    .custom-cursor-ring {
        position: fixed;
        top: 0;
        left: 0;
        width: <?php echo esc_attr($ring_size); ?>px;
        height: <?php echo esc_attr($ring_size); ?>px;
        border: <?php echo esc_attr($ring_border); ?>px solid <?php echo esc_attr($cursor_color); ?>;
        background-color: <?php echo esc_attr($ring_fill); ?>;
        border-radius: 50%;
        pointer-events: none;
        z-index: 99998;
        transition: transform <?php echo esc_attr($animation_speed); ?>ms ease, opacity <?php echo esc_attr($animation_speed); ?>ms ease, background-color <?php echo esc_attr($animation_speed); ?>ms ease;
        mix-blend-mode: <?php echo esc_attr($blend_mode); ?>;
    }

    .custom-cursor-ring.hover {
        background-color: <?php echo esc_attr($ring_fill_hover); ?>;
        transform: scale(<?php echo esc_attr($hover_scale); ?>);
    }

    .custom-cursor-dot.hover {
        opacity: 0.7;
    }

    body.hide-cursor .custom-cursor-dot,
    body.hide-cursor .custom-cursor-ring {
        opacity: 0;
    }
    </style>
    <script id="custom-cursor-script">
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($disable_on_mobile) : ?>
        if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
            return;
        }
        <?php endif; ?>

        const cursorStyle = <?php echo wp_json_encode($cursor_style); ?>;
        const body = document.body;
        const dotInertia = <?php echo esc_js($dot_inertia); ?>;
        const ringInertia = <?php echo esc_js($ring_inertia); ?>;

        if (cursorStyle === 'dot' || cursorStyle === 'both') {
            const dot = document.createElement('div');
            dot.classList.add('custom-cursor-dot');
            body.appendChild(dot);
        }

        if (cursorStyle === 'ring' || cursorStyle === 'both') {
            const ring = document.createElement('div');
            ring.classList.add('custom-cursor-ring');
            body.appendChild(ring);
        }

        const dot = document.querySelector('.custom-cursor-dot');
        const ring = document.querySelector('.custom-cursor-ring');

        let mouseX = 0;
        let mouseY = 0;
        let dotX = 0;
        let dotY = 0;
        let ringX = 0;
        let ringY = 0;

        document.addEventListener('mousemove', function(e) {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        function animateCursor() {
            if (dot) {
                dotX += (mouseX - dotX) * (1 - dotInertia);
                dotY += (mouseY - dotY) * (1 - dotInertia);
                dot.style.left = (dotX - <?php echo esc_js($cursor_size / 2); ?>) + 'px';
                dot.style.top = (dotY - <?php echo esc_js($cursor_size / 2); ?>) + 'px';
            }

            if (ring) {
                ringX += (mouseX - ringX) * (1 - ringInertia);
                ringY += (mouseY - ringY) * (1 - ringInertia);
                ring.style.left = (ringX - <?php echo esc_js($ring_size / 2); ?>) + 'px';
                ring.style.top = (ringY - <?php echo esc_js($ring_size / 2); ?>) + 'px';
            }

            requestAnimationFrame(animateCursor);
        }

        animateCursor();

        const hoverElements = document.querySelectorAll('a, button, [onclick], input[type="button"], input[type="submit"]');

        hoverElements.forEach(function(el) {
            el.addEventListener('mouseenter', function() {
                if (dot) {
                    dot.classList.add('hover');
                }
                if (ring) {
                    ring.classList.add('hover');
                }
            });

            el.addEventListener('mouseleave', function() {
                if (dot) {
                    dot.classList.remove('hover');
                }
                if (ring) {
                    ring.classList.remove('hover');
                }
            });
        });

        document.addEventListener('mouseleave', function() {
            body.classList.add('hide-cursor');
        });

        document.addEventListener('mouseenter', function() {
            body.classList.remove('hide-cursor');
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'fugu_elementor_custom_cursor_enqueue');
