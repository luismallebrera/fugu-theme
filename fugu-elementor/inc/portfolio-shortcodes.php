<?php
/**
 * Portfolio Shortcodes
 * Custom shortcodes for portfolio custom fields
 *
 * @package FuguElementor
 */

if (!defined('ABSPATH')) {
	exit;
}

function fugu_elementor_portfolio_title_color_shortcode($atts) {
	$atts = shortcode_atts(array(
		'post_id' => get_the_ID(),
		'default' => 'light',
	), $atts);
	
	$post_id = absint($atts['post_id']);
	$title_color = get_post_meta($post_id, 'portfolio_title_color', true);
	
	if (empty($title_color)) {
		$title_color = $atts['default'];
	}
	
	return esc_html($title_color);
}
add_shortcode('portfolio_title_color', 'fugu_elementor_portfolio_title_color_shortcode');

function fugu_elementor_portfolio_title_color_class_shortcode($atts) {
	$atts = shortcode_atts(array(
		'post_id' => get_the_ID(),
		'default' => 'light',
		'prefix'  => 'title-',
	), $atts);
	
	$post_id = absint($atts['post_id']);
	$title_color = get_post_meta($post_id, 'portfolio_title_color', true);
	
	if (empty($title_color)) {
		$title_color = $atts['default'];
	}
	
	return esc_attr($atts['prefix'] . $title_color);
}
add_shortcode('portfolio_title_color_class', 'fugu_elementor_portfolio_title_color_class_shortcode');

function fugu_elementor_portfolio_title_color_hex_shortcode($atts) {
	$atts = shortcode_atts(array(
		'post_id' => get_the_ID(),
		'default' => 'light',
	), $atts);
	
	$post_id = absint($atts['post_id']);
	$title_color = get_post_meta($post_id, 'portfolio_title_color', true);
	
	if (empty($title_color)) {
		$title_color = $atts['default'];
	}
	
	$hex_color = ($title_color === 'light') ? '#ffffff' : '#313C59';
	
	return esc_attr($hex_color);
}
add_shortcode('portfolio_title_color_hex', 'fugu_elementor_portfolio_title_color_hex_shortcode');

function fugu_elementor_if_title_color_shortcode($atts, $content = null) {
	$atts = shortcode_atts(array(
		'post_id' => get_the_ID(),
		'color'   => 'dark',
	), $atts);
	
	$post_id = absint($atts['post_id']);
	$title_color = get_post_meta($post_id, 'portfolio_title_color', true);
	
	if (empty($title_color)) {
		$title_color = 'light';
	}
	
	if ($title_color === $atts['color']) {
		return do_shortcode($content);
	}
	
	return '';
}
add_shortcode('if_title_color', 'fugu_elementor_if_title_color_shortcode');
