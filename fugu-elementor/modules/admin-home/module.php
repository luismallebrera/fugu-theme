<?php

namespace FuguTheme\Modules\AdminHome;

use FuguTheme\Includes\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * class Module
 *
 * @package FuguPlus
 * @subpackage FuguPlusModules
 */
class Module extends Module_Base {
	/**
	 * @inheritDoc
	 */
	public static function get_name(): string {
		return 'admin-home';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_component_ids(): array {
		return [
			'Admin_Menu_Controller',
			'Scripts_Controller',
			'Api_Controller',
			'Ajax_Handler',
			'Conversion_Banner',
			'Admin_Top_Bar',
			'Settings_Controller',
			'Notificator',
			'Finder',
		];
	}
}
