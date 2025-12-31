<?php

namespace FuguTheme\Modules\AdminHome\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Finder {

	public function add_fugu_theme_finder_entry( $categories_data ) {
		if ( isset( $categories_data['site'] ) && isset( $categories_data['site']['items'] ) ) {
			$categories_data['site']['items']['fugu-elementor-home'] = [
				'title' => esc_html__( 'Fugu Theme Home', 'fugu-elementor' ),
				'icon' => 'paint-brush',
				'url' => admin_url( 'admin.php?page=fugu-elementor' ),
				'keywords' => [ 'theme', 'fugu', 'home', 'plus', '+' ],
			];
		}

		return $categories_data;
	}

	public function __construct() {
		add_filter( 'elementor/finder/categories', [ $this, 'add_fugu_theme_finder_entry' ] );
	}
}
