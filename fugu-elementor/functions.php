<?php
/**
 * Theme functions and definitions
 *
 * @package FuguElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'FUGU_ELEMENTOR_VERSION', '3.4.5' );
define( 'EHP_THEME_SLUG', 'fugu-elementor' );

define( 'FUGU_THEME_PATH', get_template_directory() );
define( 'FUGU_THEME_URL', get_template_directory_uri() );
define( 'FUGU_THEME_ASSETS_PATH', FUGU_THEME_PATH . '/assets/' );
define( 'FUGU_THEME_ASSETS_URL', FUGU_THEME_URL . '/assets/' );
define( 'FUGU_THEME_SCRIPTS_PATH', FUGU_THEME_ASSETS_PATH . 'js/' );
define( 'FUGU_THEME_SCRIPTS_URL', FUGU_THEME_ASSETS_URL . 'js/' );
define( 'FUGU_THEME_STYLE_PATH', FUGU_THEME_ASSETS_PATH . 'css/' );
define( 'FUGU_THEME_STYLE_URL', FUGU_THEME_ASSETS_URL . 'css/' );
define( 'FUGU_THEME_IMAGES_PATH', FUGU_THEME_ASSETS_PATH . 'images/' );
define( 'FUGU_THEME_IMAGES_URL', FUGU_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'fugu_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function fugu_elementor_setup() {
		if ( is_admin() ) {
			fugu_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'fugu_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'fugu-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'fugu-elementor' ) ] );
		}

		if ( apply_filters( 'fugu_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'fugu_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
					'navigation-widgets',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'assets/css/editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'fugu_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'fugu_elementor_setup' );

function fugu_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'fugu_theme_version';
	// The theme version saved in the database.
	$fugu_theme_db_version = get_option( $theme_version_option_name );

	// If the 'fugu_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $fugu_theme_db_version || version_compare( $fugu_theme_db_version, FUGU_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, FUGU_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'fugu_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function fugu_elementor_display_header_footer() {
		$fugu_elementor_header_footer = true;

		return apply_filters( 'fugu_elementor_header_footer', $fugu_elementor_header_footer );
	}
}

if ( ! function_exists( 'fugu_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function fugu_elementor_scripts_styles() {
		if ( apply_filters( 'fugu_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'fugu-elementor',
				FUGU_THEME_STYLE_URL . 'reset.css',
				[],
				FUGU_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'fugu_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'fugu-elementor-theme-style',
				FUGU_THEME_STYLE_URL . 'theme.css',
				[],
				FUGU_ELEMENTOR_VERSION
			);
		}

		if ( fugu_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'fugu-elementor-header-footer',
				FUGU_THEME_STYLE_URL . 'header-footer.css',
				[],
				FUGU_ELEMENTOR_VERSION
			);
		}

		wp_enqueue_script( 'wp-util' );

		if ( get_theme_mod( 'enable_scroll_class', false ) ) {
			wp_enqueue_script(
				'fugu-elementor-scroll-class',
				get_template_directory_uri() . '/js/scroll-class.js',
				[ 'jquery' ],
				FUGU_ELEMENTOR_VERSION,
				true
			);

			wp_localize_script(
				'fugu-elementor-scroll-class',
				'fuguElementorScrollClass',
				[
					'threshold' => intval( get_theme_mod( 'scroll_class_threshold', 100 ) ),
				]
			);
		}

		if ( get_theme_mod( 'enable_smooth_scrolling', false ) ) {
			wp_enqueue_script( 'lenis', 'https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js', [], '1.0.42', true );

			if ( get_theme_mod( 'smooth_scrolling_gsap', false ) ) {
				wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], '3.12.5', true );
				wp_enqueue_script( 'scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', [ 'gsap' ], '3.12.5', true );
			}

			wp_enqueue_script(
				'fugu-elementor-smooth-scrolling',
				get_template_directory_uri() . '/js/smooth-scrolling.js',
				[ 'lenis' ],
				FUGU_ELEMENTOR_VERSION,
				true
			);

			wp_localize_script(
				'fugu-elementor-smooth-scrolling',
				'fuguElementorSmoothScrollingParams',
				[
					'smoothWheel'  => get_theme_mod( 'smooth_scrolling_disable_wheel', false ) ? 0 : 1,
					'anchorOffset' => intval( get_theme_mod( 'smooth_scrolling_anchor_offset', 0 ) ),
					'lerp'         => floatval( get_theme_mod( 'smooth_scrolling_lerp', 0.07 ) ),
					'duration'     => floatval( get_theme_mod( 'smooth_scrolling_duration', 1.2 ) ),
					'anchorLinks'  => get_theme_mod( 'smooth_scrolling_anchor_links', false ),
					'gsapSync'     => get_theme_mod( 'smooth_scrolling_gsap', false ),
				]
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'fugu_elementor_scripts_styles' );

if ( ! function_exists( 'fugu_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function fugu_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'fugu_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'fugu_elementor_register_elementor_locations' );

if ( ! function_exists( 'fugu_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function fugu_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'fugu_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'fugu_elementor_content_width', 0 );

if ( ! function_exists( 'fugu_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function fugu_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'fugu_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'fugu_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

// Theme Customizer options and helpers
require get_template_directory() . '/includes/customizer/theme-options.php';

if ( ! function_exists( 'fugu_elementor_customizer' ) ) {
	// Customizer controls
	function fugu_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! fugu_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'fugu_elementor_customizer' );

if ( ! function_exists( 'fugu_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function fugu_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'fugu_elementor_page_title', 'fugu_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `fugu_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'fugu_elementor_body_open' ) ) {
	function fugu_elementor_body_open() {
		wp_body_open();
	}
}

require FUGU_THEME_PATH . '/theme.php';

FuguTheme\Theme::instance();
