<?php

namespace FuguTheme\Modules\AdminHome\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use FuguTheme\Modules\AdminHome\Rest\Admin_Config;
use FuguTheme\Modules\AdminHome\Rest\Promotions;
use FuguTheme\Modules\AdminHome\Rest\Theme_Settings;
use FuguTheme\Modules\AdminHome\Rest\Whats_New;

class Api_Controller {

	protected $endpoints = [];

	public function __construct() {
		$this->endpoints['promotions'] = new Promotions();
		$this->endpoints['admin-config'] = new Admin_Config();
		$this->endpoints['theme-settings'] = new Theme_Settings();
		$this->endpoints['whats-new'] = new Whats_New();
	}
}
