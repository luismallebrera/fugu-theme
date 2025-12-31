<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Customizer controls for header & footer.
 *
 * @return void
 */
function fugu_customizer_register( $wp_customize ) {
	require_once get_template_directory() . '/includes/customizer/customizer-action-links.php';

	$wp_customize->add_section(
		'fugu-options',
		[
			'title' => esc_html__( 'Header & Footer', 'fugu-elementor' ),
			'capability' => 'edit_theme_options',
		]
	);

	$wp_customize->add_setting(
		'fugu-header-footer',
		[
			'sanitize_callback' => false,
			'transport' => 'refresh',
		]
	);

	$wp_customize->add_control(
		new FuguElementor\Includes\Customizer\Fugu_Customizer_Action_Links(
			$wp_customize,
			'fugu-header-footer',
			[
				'section' => 'fugu-options',
				'priority' => 20,
			]
		)
	);
}
add_action( 'customize_register', 'fugu_customizer_register' );

/**
 * Register Customizer controls for Elementor Pro upsell.
 *
 * @return void
 */
function fugu_customizer_register_elementor_pro_upsell( $wp_customize ) {
	if ( function_exists( 'elementor_pro_load_plugin' ) ) {
		return;
	}

	require_once get_template_directory() . '/includes/customizer/customizer-upsell.php';

	$wp_customize->add_section(
		new FuguElementor\Includes\Customizer\Fugu_Customizer_Upsell(
			$wp_customize,
			'fugu-upsell-elementor-pro',
			[
				'heading' => esc_html__( 'Customize your entire website with Elementor Pro', 'fugu-elementor' ),
				'description' => esc_html__( 'Build and customize every part of your website, including Theme Parts with Elementor Pro.', 'fugu-elementor' ),
				'button_text' => esc_html__( 'Upgrade Now', 'fugu-elementor' ),
				'button_url' => 'https://elementor.com/pro/?utm_source=fugu-theme-customize&utm_campaign=gopro&utm_medium=wp-dash',
				'priority' => 999999,
			]
		)
	);
}
add_action( 'customize_register', 'fugu_customizer_register_elementor_pro_upsell' );

/**
 * Enqueue Customizer CSS.
 *
 * @return void
 */
function fugu_customizer_styles() {
	wp_enqueue_style(
		'fugu-elementor-customizer',
		FUGU_THEME_STYLE_URL . 'customizer.css',
		[],
		FUGU_ELEMENTOR_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'fugu_customizer_styles' );
