<?php

namespace FuguTheme\Modules\AdminHome\Rest;

use FuguTheme\Includes\Utils;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Promotions extends Rest_Base {

	public function get_promotions() {
		$action_links_data = [];

		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) && Utils::is_elementor_active() ) {
			$action_links_data[] = [
				'type' => 'go-pro',
				'image' => FUGU_THEME_IMAGES_URL . 'go-pro.svg',
				'url' => 'https://go.elementor.com/fugu-upgrade-epro/',
				'alt' => __( 'Elementor Pro', 'fugu-elementor' ),
				'title' => __( 'Bring your vision to life', 'fugu-elementor' ),
				'messages' => [
					__( 'Get complete design flexibility for your website with Elementor Pro’s advanced tools and premium features.', 'fugu-elementor' ),
				],
				'button' => __( 'Upgrade Now', 'fugu-elementor' ),
				'upgrade' => true,
				'features' => [
					__( 'Popup Builder', 'fugu-elementor' ),
					__( 'Custom Code & CSS', 'fugu-elementor' ),
					__( 'E-commerce Features', 'fugu-elementor' ),
					__( 'Collaborative Notes', 'fugu-elementor' ),
					__( 'Form Submission', 'fugu-elementor' ),
					__( 'Form Integrations', 'fugu-elementor' ),
					__( 'Customs Attribute', 'fugu-elementor' ),
					__( 'Role Manager', 'fugu-elementor' ),
				],
			];
		}

		if (
			! defined( 'ELEMENTOR_IMAGE_OPTIMIZER_VERSION' ) &&
			! defined( 'IMAGE_OPTIMIZATION_VERSION' )
		) {
			$action_links_data[] = [
				'type' => 'go-image-optimizer',
				'image' => FUGU_THEME_IMAGES_URL . 'image-optimizer.svg',
				'url' => Utils::get_plugin_install_url( 'image-optimization' ),
				'alt' => __( 'Elementor Image Optimizer', 'fugu-elementor' ),
				'title' => '',
				'messages' => [
					__( 'Optimize Images.', 'fugu-elementor' ),
					__( 'Reduce Size.', 'fugu-elementor' ),
					__( 'Improve Speed.', 'fugu-elementor' ),
					__( 'Try Image Optimizer for free', 'fugu-elementor' ),
				],
				'button' => __( 'Install', 'fugu-elementor' ),
				'width' => 72,
				'height' => 'auto',
				'target' => '_self',
				'backgroundImage' => FUGU_THEME_IMAGES_URL . 'image-optimization-bg.svg',
			];
		}

		if ( ! defined( 'SEND_VERSION' ) ) {
			$action_links_data[] = [
				'type' => 'go-send',
				'image' => FUGU_THEME_IMAGES_URL . 'send-logo.gif',
				'backgroundColor' => '#EFEFFF',
				'url' => Utils::get_plugin_install_url( 'send-app' ),
				'alt' => __( 'Send', 'fugu-elementor' ),
				'title' => '',
				'target' => '_self',
				'messages' => [
					__( 'Connect any website to automated Email & SMS workflows in a click with Send.', 'fugu-elementor' ),
				],
				'button' => __( 'Install', 'fugu-elementor' ),
				'buttonBgColor' => '#524CFF',
				'width' => 72,
				'height' => 'auto',
			];
		} elseif (
			! defined( 'ELEMENTOR_AI_VERSION' ) &&
			Utils::is_elementor_installed()
		) {
			$action_links_data[] = [
				'type' => 'go-ai',
				'image' => FUGU_THEME_IMAGES_URL . 'ai.png',
				'url' => 'https://go.elementor.com/fugu-site-planner',
				'alt' => __( 'Elementor AI', 'fugu-elementor' ),
				'title' => __( 'Elementor AI', 'fugu-elementor' ),
				'messages' => [
					__( 'Boost creativity with Elementor AI. Craft & enhance copy, create custom CSS & Code, and generate images to elevate your website.', 'fugu-elementor' ),
				],
				'button' => __( 'Let\'s Go', 'fugu-elementor' ),
			];
		}

		return rest_ensure_response( [ 'links' => $action_links_data ] );
	}

	public function register_routes() {
		register_rest_route(
			self::ROUTE_NAMESPACE,
			'/promotions',
			[
				'methods' => WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_promotions' ],
				'permission_callback' => [ $this, 'permission_callback' ],
			]
		);
	}
}
