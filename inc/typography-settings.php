<?php
/**
 * Native typography settings and CSS output.
 *
 * @package Fugu_Theme
 */

if (!defined('ABSPATH')) {
	exit;
}

add_action('customize_register', 'fugu_theme_register_typography_settings', 20);
function fugu_theme_register_typography_settings($wp_customize) {
	$defaults   = fugu_theme_get_typography_defaults();
	$items      = fugu_theme_get_typography_items();
	$properties = fugu_theme_get_typography_property_config();

	if (!$wp_customize->get_panel('theme_options')) {
		$wp_customize->add_panel('theme_options', array(
			'priority'    => 10,
			'title'       => __('Theme Options', 'fugu-theme'),
			'description' => __('General theme options', 'fugu-theme'),
		));
	}

	if (!$wp_customize->get_section('typography_section')) {
		$wp_customize->add_section('typography_section', array(
			'title'       => __('Typography', 'fugu-theme'),
			'panel'       => 'theme_options',
			'priority'    => 30,
			'description' => __('Configura fuentes base y estilos globales.', 'fugu-theme'),
		));
	}

	$priority = 5;

	foreach ($items as $key => $item) {
		foreach ($item['properties'] as $property) {
			if (!isset($properties[$property])) {
				continue;
			}

			$setting_id = 'typography_' . $key . '_' . $property;
			$default    = isset($defaults[$key][$property]) ? $defaults[$key][$property] : '';
			$config     = $properties[$property];

			$wp_customize->add_setting($setting_id, array(
				'default'           => $default,
				'sanitize_callback' => $config['sanitize_callback'],
				'transport'         => 'refresh',
			));

			$control_args = array(
				'label'    => sprintf($config['label'], $item['label']),
				'section'  => 'typography_section',
				'priority' => $priority,
				'type'     => $config['type'],
			);

			if ($property === 'font_family' && !empty($item['description'])) {
				$control_args['description'] = $item['description'];
			}

			if (!empty($config['choices'])) {
				$control_args['choices'] = $config['choices'];
			}

			if (!empty($config['input_attrs'])) {
				$control_args['input_attrs'] = $config['input_attrs'];
			}

			if ($config['type'] === 'color') {
				$wp_customize->add_control(new WP_Customize_Color_Control(
					$wp_customize,
					$setting_id,
					$control_args
				));
			} else {
				$wp_customize->add_control($setting_id, $control_args);
			}

			$priority++;
		}
	}
}

add_action('wp_head', 'fugu_theme_output_typography_styles', 30);
function fugu_theme_output_typography_styles() {
	$defaults  = fugu_theme_get_typography_defaults();
	$items     = fugu_theme_get_typography_items();
	$selectors = fugu_theme_get_typography_selectors();
	$css       = array();

	foreach ($items as $key => $item) {
		if (!isset($selectors[$key])) {
			continue;
		}

		$rules = array();

		foreach ($item['properties'] as $property) {
			$default = isset($defaults[$key][$property]) ? $defaults[$key][$property] : '';
			$value   = fugu_theme_get_typography_value($key, $property, $default);

			if ($value === '' || $value === $default) {
				continue;
			}

			switch ($property) {
				case 'font_family':
					if (strtolower($value) === 'inherit') {
						break;
					}
					$rules[] = 'font-family:' . esc_attr($value) . ';';
					break;
				case 'font_weight':
					$rules[] = 'font-weight:' . esc_attr($value) . ';';
					break;
				case 'font_size':
					$rules[] = 'font-size:' . esc_attr($value) . ';';
					break;
				case 'line_height':
					$rules[] = 'line-height:' . esc_attr($value) . ';';
					break;
				case 'letter_spacing':
					$rules[] = 'letter-spacing:' . esc_attr($value) . ';';
					break;
				case 'color':
					$rules[] = 'color:' . esc_attr($value) . ';';
					break;
				case 'text_transform':
					$rules[] = 'text-transform:' . esc_attr($value) . ';';
					break;
			}
		}

		if (!empty($rules)) {
			$css[] = $selectors[$key] . '{' . implode('', $rules) . '}';
		}
	}

	if (!empty($css)) {
		echo '<style id="elementor-blank-typography">' . implode('', $css) . '</style>';
	}
}

function fugu_theme_get_typography_defaults() {
	return array(
		'body'  => array(
			'font_family'    => 'inherit',
			'font_weight'    => '400',
			'font_size'      => '16px',
			'line_height'    => '1.6',
			'letter_spacing' => '0',
			'color'          => '#333333',
		),
		'links' => array(
			'font_family' => 'inherit',
			'font_weight' => '400',
			'color'       => '#0073aa',
		),
		'h1'    => array(
			'font_family'    => 'inherit',
			'font_weight'    => '700',
			'font_size'      => '2.5rem',
			'line_height'    => '1.2',
			'letter_spacing' => '0',
			'color'          => '#111111',
		),
		'h2'    => array(
			'font_family'    => 'inherit',
			'font_weight'    => '700',
			'font_size'      => '2rem',
			'line_height'    => '1.3',
			'letter_spacing' => '0',
			'color'          => '#111111',
		),
		'h3'    => array(
			'font_family'    => 'inherit',
			'font_weight'    => '700',
			'font_size'      => '1.75rem',
			'line_height'    => '1.3',
			'letter_spacing' => '0',
			'color'          => '#111111',
		),
		'h4'    => array(
			'font_family'    => 'inherit',
			'font_weight'    => '700',
			'font_size'      => '1.5rem',
			'line_height'    => '1.4',
			'letter_spacing' => '0',
			'color'          => '#111111',
		),
		'h5'    => array(
			'font_family'    => 'inherit',
			'font_weight'    => '700',
			'font_size'      => '1.25rem',
			'line_height'    => '1.4',
			'letter_spacing' => '0',
			'color'          => '#111111',
		),
		'h6'    => array(
			'font_family'    => 'inherit',
			'font_weight'    => '700',
			'font_size'      => '1rem',
			'line_height'    => '1.4',
			'letter_spacing' => '0',
			'color'          => '#111111',
		),
		'menu'  => array(
			'font_family'    => 'inherit',
			'font_weight'    => '400',
			'font_size'      => '16px',
			'line_height'    => '1.5',
			'letter_spacing' => '0',
			'color'          => '#333333',
			'text_transform' => 'none',
		),
	);
}

function fugu_theme_get_typography_items() {
	return array(
		'body'  => array(
			'label'       => __('Body Text', 'fugu-theme'),
			'description' => __('Afecta al cuerpo del sitio, párrafos y texto genérico.', 'fugu-theme'),
			'properties'  => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'links' => array(
			'label'      => __('Links', 'fugu-theme'),
			'properties' => array('font_family', 'font_weight', 'color'),
		),
		'h1'    => array(
			'label'      => __('Heading 1 (H1)', 'fugu-theme'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h2'    => array(
			'label'      => __('Heading 2 (H2)', 'fugu-theme'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h3'    => array(
			'label'      => __('Heading 3 (H3)', 'fugu-theme'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h4'    => array(
			'label'      => __('Heading 4 (H4)', 'fugu-theme'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h5'    => array(
			'label'      => __('Heading 5 (H5)', 'fugu-theme'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h6'    => array(
			'label'      => __('Heading 6 (H6)', 'fugu-theme'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'menu'  => array(
			'label'      => __('Menu Items', 'fugu-theme'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color', 'text_transform'),
		),
	);
}

function fugu_theme_get_typography_property_config() {
	return array(
		'font_family'    => array(
			'label'             => __('%s Font Family', 'fugu-theme'),
			'type'              => 'text',
			'sanitize_callback' => 'fugu_theme_sanitize_font_family',
			'input_attrs'       => array('placeholder' => 'inherit'),
		),
		'font_weight'    => array(
			'label'             => __('%s Font Weight', 'fugu-theme'),
			'type'              => 'select',
			'sanitize_callback' => 'fugu_theme_sanitize_font_weight',
			'choices'           => fugu_theme_get_font_weight_choices(),
		),
		'font_size'      => array(
			'label'             => __('%s Font Size', 'fugu-theme'),
			'type'              => 'text',
			'sanitize_callback' => 'fugu_theme_sanitize_typography_dimension',
			'input_attrs'       => array('placeholder' => '16px'),
		),
		'line_height'    => array(
			'label'             => __('%s Line Height', 'fugu-theme'),
			'type'              => 'text',
			'sanitize_callback' => 'fugu_theme_sanitize_typography_dimension',
			'input_attrs'       => array('placeholder' => '1.3'),
		),
		'letter_spacing' => array(
			'label'             => __('%s Letter Spacing', 'fugu-theme'),
			'type'              => 'text',
			'sanitize_callback' => 'fugu_theme_sanitize_typography_dimension',
			'input_attrs'       => array('placeholder' => '0'),
		),
		'color'          => array(
			'label'             => __('%s Color', 'fugu-theme'),
			'type'              => 'color',
			'sanitize_callback' => 'fugu_theme_sanitize_color_alpha',
		),
		'text_transform' => array(
			'label'             => __('%s Text Transform', 'fugu-theme'),
			'type'              => 'select',
			'sanitize_callback' => 'fugu_theme_sanitize_text_transform',
			'choices'           => array(
				'none'       => __('None', 'fugu-theme'),
				'uppercase'  => __('Uppercase', 'fugu-theme'),
				'lowercase'  => __('Lowercase', 'fugu-theme'),
				'capitalize' => __('Capitalize', 'fugu-theme'),
			),
		),
	);
}

function fugu_theme_get_font_weight_choices() {
	return array(
		'300' => __('300 (Light)', 'fugu-theme'),
		'400' => __('400 (Regular)', 'fugu-theme'),
		'500' => __('500 (Medium)', 'fugu-theme'),
		'600' => __('600 (Semi Bold)', 'fugu-theme'),
		'700' => __('700 (Bold)', 'fugu-theme'),
		'800' => __('800 (Extra Bold)', 'fugu-theme'),
		'900' => __('900 (Black)', 'fugu-theme'),
	);
}

function fugu_theme_get_typography_selectors() {
	return array(
		'body'  => 'body, p',
		'links' => 'a',
		'h1'    => 'h1',
		'h2'    => 'h2',
		'h3'    => 'h3',
		'h4'    => 'h4',
		'h5'    => 'h5',
		'h6'    => 'h6',
		'menu'  => '.menu-item, .menu-item a, nav a, .nav-menu a',
	);
}

function fugu_theme_get_typography_value($key, $property, $default) {
	return get_theme_mod('typography_' . $key . '_' . $property, $default);
}

function fugu_theme_sanitize_font_family($value, $setting) {
	$value = trim(sanitize_text_field($value));

	if ($value === '') {
		return isset($setting->default) ? $setting->default : 'inherit';
	}

	return $value;
}

function fugu_theme_sanitize_font_weight($value, $setting) {
	$value = strtolower(trim((string) $value));

	if ($value === 'regular' || $value === 'normal') {
		$value = '400';
	}

	if ($value === 'bold') {
		$value = '700';
	}

	$allowed = array('300', '400', '500', '600', '700', '800', '900');

	if (!in_array($value, $allowed, true)) {
		return isset($setting->default) ? $setting->default : '400';
	}

	return $value;
}

function fugu_theme_sanitize_text_transform($value, $setting) {
	$value   = strtolower(trim((string) $value));
	$allowed = array('none', 'uppercase', 'lowercase', 'capitalize');

	if (!in_array($value, $allowed, true)) {
		return isset($setting->default) ? $setting->default : 'none';
	}

	return $value;
}

function fugu_theme_sanitize_typography_dimension($value, $setting) {
	$default = isset($setting->default) ? $setting->default : '';
	return fugu_theme_sanitize_css_dimension_value($value, $default);
}
