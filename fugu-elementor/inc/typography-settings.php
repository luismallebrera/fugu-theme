<?php
/**
 * Native typography settings and CSS output.
 *
 * @package FuguElementor
 */

if (!defined('ABSPATH')) {
	exit;
}

add_action('customize_register', 'fugu_elementor_register_typography_settings', 20);
function fugu_elementor_register_typography_settings($wp_customize) {
	$defaults   = fugu_elementor_get_typography_defaults();
	$items      = fugu_elementor_get_typography_items();
	$properties = fugu_elementor_get_typography_property_config();

	if (!$wp_customize->get_panel('theme_options')) {
		$wp_customize->add_panel('theme_options', array(
			'priority'    => 10,
			'title'       => __('Theme Options', 'fugu-elementor'),
			'description' => __('General theme options', 'fugu-elementor'),
		));
	}

	if (!$wp_customize->get_section('typography_section')) {
		$wp_customize->add_section('typography_section', array(
			'title'       => __('Typography', 'fugu-elementor'),
			'panel'       => 'theme_options',
			'priority'    => 30,
			'description' => __('Configura fuentes base y estilos globales.', 'fugu-elementor'),
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

add_action('wp_head', 'fugu_elementor_output_typography_styles', 30);
function fugu_elementor_output_typography_styles() {
	$defaults  = fugu_elementor_get_typography_defaults();
	$items     = fugu_elementor_get_typography_items();
	$selectors = fugu_elementor_get_typography_selectors();
	$css       = array();

	foreach ($items as $key => $item) {
		if (!isset($selectors[$key])) {
			continue;
		}

		$rules = array();

		foreach ($item['properties'] as $property) {
			$default = isset($defaults[$key][$property]) ? $defaults[$key][$property] : '';
			$value   = fugu_elementor_get_typography_value($key, $property, $default);

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

function fugu_elementor_get_typography_defaults() {
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

function fugu_elementor_get_typography_items() {
	return array(
		'body'  => array(
			'label'       => __('Body Text', 'fugu-elementor'),
			'description' => __('Afecta al cuerpo del sitio, párrafos y texto genérico.', 'fugu-elementor'),
			'properties'  => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'links' => array(
			'label'      => __('Links', 'fugu-elementor'),
			'properties' => array('font_family', 'font_weight', 'color'),
		),
		'h1'    => array(
			'label'      => __('Heading 1 (H1)', 'fugu-elementor'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h2'    => array(
			'label'      => __('Heading 2 (H2)', 'fugu-elementor'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h3'    => array(
			'label'      => __('Heading 3 (H3)', 'fugu-elementor'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h4'    => array(
			'label'      => __('Heading 4 (H4)', 'fugu-elementor'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h5'    => array(
			'label'      => __('Heading 5 (H5)', 'fugu-elementor'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'h6'    => array(
			'label'      => __('Heading 6 (H6)', 'fugu-elementor'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color'),
		),
		'menu'  => array(
			'label'      => __('Menu Items', 'fugu-elementor'),
			'properties' => array('font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'color', 'text_transform'),
		),
	);
}

function fugu_elementor_get_typography_property_config() {
	return array(
		'font_family'    => array(
			'label'             => __('%s Font Family', 'fugu-elementor'),
			'type'              => 'text',
			'sanitize_callback' => 'fugu_elementor_sanitize_font_family',
			'input_attrs'       => array('placeholder' => 'inherit'),
		),
		'font_weight'    => array(
			'label'             => __('%s Font Weight', 'fugu-elementor'),
			'type'              => 'select',
			'sanitize_callback' => 'fugu_elementor_sanitize_font_weight',
			'choices'           => fugu_elementor_get_font_weight_choices(),
		),
		'font_size'      => array(
			'label'             => __('%s Font Size', 'fugu-elementor'),
			'type'              => 'text',
			'sanitize_callback' => 'fugu_elementor_sanitize_typography_dimension',
			'input_attrs'       => array('placeholder' => '16px'),
		),
		'line_height'    => array(
			'label'             => __('%s Line Height', 'fugu-elementor'),
			'type'              => 'text',
			'sanitize_callback' => 'fugu_elementor_sanitize_typography_dimension',
			'input_attrs'       => array('placeholder' => '1.3'),
		),
		'letter_spacing' => array(
			'label'             => __('%s Letter Spacing', 'fugu-elementor'),
			'type'              => 'text',
			'sanitize_callback' => 'fugu_elementor_sanitize_typography_dimension',
			'input_attrs'       => array('placeholder' => '0'),
		),
		'color'          => array(
			'label'             => __('%s Color', 'fugu-elementor'),
			'type'              => 'color',
			'sanitize_callback' => 'fugu_elementor_sanitize_color_alpha',
		),
		'text_transform' => array(
			'label'             => __('%s Text Transform', 'fugu-elementor'),
			'type'              => 'select',
			'sanitize_callback' => 'fugu_elementor_sanitize_text_transform',
			'choices'           => array(
				'none'       => __('None', 'fugu-elementor'),
				'uppercase'  => __('Uppercase', 'fugu-elementor'),
				'lowercase'  => __('Lowercase', 'fugu-elementor'),
				'capitalize' => __('Capitalize', 'fugu-elementor'),
			),
		),
	);
}

function fugu_elementor_get_font_weight_choices() {
	return array(
		'300' => __('300 (Light)', 'fugu-elementor'),
		'400' => __('400 (Regular)', 'fugu-elementor'),
		'500' => __('500 (Medium)', 'fugu-elementor'),
		'600' => __('600 (Semi Bold)', 'fugu-elementor'),
		'700' => __('700 (Bold)', 'fugu-elementor'),
		'800' => __('800 (Extra Bold)', 'fugu-elementor'),
		'900' => __('900 (Black)', 'fugu-elementor'),
	);
}

function fugu_elementor_get_typography_selectors() {
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

function fugu_elementor_get_typography_value($key, $property, $default) {
	return get_theme_mod('typography_' . $key . '_' . $property, $default);
}

function fugu_elementor_sanitize_font_family($value, $setting) {
	$value = trim(sanitize_text_field($value));

	if ($value === '') {
		return isset($setting->default) ? $setting->default : 'inherit';
	}

	return $value;
}

function fugu_elementor_sanitize_font_weight($value, $setting) {
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

function fugu_elementor_sanitize_text_transform($value, $setting) {
	$value   = strtolower(trim((string) $value));
	$allowed = array('none', 'uppercase', 'lowercase', 'capitalize');

	if (!in_array($value, $allowed, true)) {
		return isset($setting->default) ? $setting->default : 'none';
	}

	return $value;
}

function fugu_elementor_sanitize_typography_dimension($value, $setting) {
	$default = isset($setting->default) ? $setting->default : '';
	return fugu_elementor_sanitize_css_dimension_value($value, $default);
}
