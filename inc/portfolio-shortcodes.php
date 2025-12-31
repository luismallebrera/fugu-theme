<?php
/**
 * Portfolio Shortcodes
 * Custom shortcodes for portfolio custom fields
 *
 * @package Fugu_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode to display portfolio_title_color
 * Usage: [portfolio_title_color]
 */
function fugu_theme_portfolio_title_color_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => get_the_ID(),
		'default' => 'light',
	), $atts );
	
	$post_id = absint( $atts['post_id'] );
	$title_color = get_post_meta( $post_id, 'portfolio_title_color', true );
	
	if ( empty( $title_color ) ) {
		$title_color = $atts['default'];
	}
	
	return esc_html( $title_color );
}
add_shortcode( 'portfolio_title_color', 'fugu_theme_portfolio_title_color_shortcode' );

/**
 * Shortcode to display portfolio_title_color as CSS class
 * Usage: [portfolio_title_color_class]
 * Output: title-dark or title-light
 */
function fugu_theme_portfolio_title_color_class_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => get_the_ID(),
		'default' => 'light',
		'prefix'  => 'title-',
	), $atts );
	
	$post_id = absint( $atts['post_id'] );
	$title_color = get_post_meta( $post_id, 'portfolio_title_color', true );
	
	if ( empty( $title_color ) ) {
		$title_color = $atts['default'];
	}
	
	return esc_attr( $atts['prefix'] . $title_color );
}
add_shortcode( 'portfolio_title_color_class', 'fugu_theme_portfolio_title_color_class_shortcode' );

/**
 * Shortcode to display portfolio_title_color as hex color
 * Usage: [portfolio_title_color_hex]
 * Output: #313C59 or #ffffff
 */
function fugu_theme_portfolio_title_color_hex_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => get_the_ID(),
		'default' => 'light',
	), $atts );
	
	$post_id = absint( $atts['post_id'] );
	$title_color = get_post_meta( $post_id, 'portfolio_title_color', true );
	
	if ( empty( $title_color ) ) {
		$title_color = $atts['default'];
	}
	
	// Convert to hex color
	$hex_color = ( $title_color === 'light' ) ? '#ffffff' : '#313C59';
	
	return esc_attr( $hex_color );
}
add_shortcode( 'portfolio_title_color_hex', 'fugu_theme_portfolio_title_color_hex_shortcode' );

/**
 * Shortcode to conditionally display content based on title color
 * Usage: [if_title_color color="dark"]Content for dark[/if_title_color]
 */
function fugu_theme_if_title_color_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'post_id' => get_the_ID(),
		'color'   => 'dark',
	), $atts );
	
	$post_id = absint( $atts['post_id'] );
	$title_color = get_post_meta( $post_id, 'portfolio_title_color', true );
	
	if ( empty( $title_color ) ) {
		$title_color = 'light';
	}
	
	// Only show content if color matches
	if ( $title_color === $atts['color'] ) {
		return do_shortcode( $content );
	}
	
	return '';
}
add_shortcode( 'if_title_color', 'fugu_theme_if_title_color_shortcode' );
