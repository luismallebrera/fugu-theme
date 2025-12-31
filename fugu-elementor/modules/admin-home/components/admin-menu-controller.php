<?php

namespace FuguTheme\Modules\AdminHome\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use FuguTheme\Includes\Script;
use FuguTheme\Includes\Utils;

class Admin_Menu_Controller {

	const MENU_PAGE_ICON = 'dashicons-plus-alt';
	const MENU_PAGE_POSITION = 59.9;
	const AI_SITE_PLANNER_SLUG = '-ai-site-planner';
	const THEME_BUILDER_SLUG = '-theme-builder';

	public function admin_menu(): void {
		add_menu_page(
			__( 'Fugu', 'fugu-elementor' ),
			__( 'Fugu', 'fugu-elementor' ),
			'manage_options',
			EHP_THEME_SLUG,
			[ $this, 'render_home' ],
			self::MENU_PAGE_ICON,
			self::MENU_PAGE_POSITION
		);

		add_submenu_page(
			EHP_THEME_SLUG,
			__( 'Home', 'fugu-elementor' ),
			__( 'Home', 'fugu-elementor' ),
			'manage_options',
			EHP_THEME_SLUG,
			[ $this, 'render_home' ]
		);

		do_action( 'fugu-plus-theme/admin-menu', EHP_THEME_SLUG );

		$theme_builder_slug = Utils::get_theme_builder_slug();
		add_submenu_page(
			EHP_THEME_SLUG,
			__( 'Theme Builder', 'fugu-elementor' ),
			__( 'Theme Builder', 'fugu-elementor' ),
			'manage_options',
			empty( $theme_builder_slug ) ? EHP_THEME_SLUG . self::THEME_BUILDER_SLUG : $theme_builder_slug,
			[ $this, 'render_home' ]
		);

		add_submenu_page(
			EHP_THEME_SLUG,
			__( 'AI Site Planner', 'fugu-elementor' ),
			__( 'AI Site Planner', 'fugu-elementor' ),
			'manage_options',
			EHP_THEME_SLUG . self::AI_SITE_PLANNER_SLUG,
			[ $this, 'render_home' ]
		);
	}

	public function render_home(): void {
		echo '<div id="ehe-admin-home"></div>';
	}

	public function redirect_menus(): void {
		$page = sanitize_key( filter_input( INPUT_GET, 'page', FILTER_UNSAFE_RAW ) );

		switch ( $page ) {
			case EHP_THEME_SLUG . self::AI_SITE_PLANNER_SLUG:
				wp_redirect( Utils::get_ai_site_planner_url() );
				exit;

			case EHP_THEME_SLUG . self::THEME_BUILDER_SLUG:
				wp_redirect( Utils::get_theme_builder_url() );
				exit;

			default:
				break;
		}
	}

	public function admin_enqueue_scripts() {
		$script = new Script(
			'fugu-elementor-menu',
		);

		$script->enqueue();
	}

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'redirect_menus' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
	}
}
