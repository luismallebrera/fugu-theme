<?php
namespace SodaAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Entry List Widget
 *
 * @since 2.4.0
 */
class Entry_List_Widget extends Widget_Base {

	/**
	 * Track whether popup helper script was enqueued.
	 */
	private static $popup_script_enqueued = false;

	/**
	 * Track meta keys already registered for REST exposure per post type.
	 */
	private static $rest_meta_registry = [];

	/**
	 * Track whether the REST meta registry has been initialised for this request.
	 */
	private static $rest_meta_registry_initialized = false;

	/**
	 * Track whether the REST meta registry needs to be persisted.
	 */
	private static $rest_meta_registry_dirty = false;

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'soda-entry-list';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return __( 'Entry List', 'soda-addons' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Get widget categories.
	 */
	public function get_categories() {
		return [ 'soda-addons' ];
	}

	/**
	 * Get widget keywords.
	 */
	public function get_keywords() {
		return [ 'post', 'entry', 'list', 'blog', 'archive', 'query' ];
	}

	/**
	 * Register controls.
	 */
	protected function register_controls() {
		// Content Section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'soda-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' => __( 'Post Type', 'soda-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_types_options(),
				'default' => 'post',
			]
		);

		$this->add_control(
			'query_id',
			[
				'label' => __( 'Query ID', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Use this ID to customize the query via the elementor/query/{$query_id} filter.', 'soda-addons' ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Entries to Show', 'soda-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => __( 'Show Label', 'soda-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'label_text',
			[
				'label' => __( 'Label Text', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Leave empty to use post type label', 'soda-addons' ),
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator',
			[
				'label' => __( 'Separator', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => ', ',
				'placeholder' => ', ',
			]
		);

		$this->add_control(
			'link_entries',
			[
				'label' => __( 'Link Entries', 'soda-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => __( 'Link each entry to its single post.', 'soda-addons' ),
			]
		);

		$this->add_control(
			'link_type',
			[
				'label' => __( 'Link Type', 'soda-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'permalink' => __( 'Post Permalink', 'soda-addons' ),
					'popup_query' => __( 'Popup Anchor (Query String)', 'soda-addons' ),
					'popup_action' => __( 'Elementor Popup Action', 'soda-addons' ),
				],
				'default' => 'permalink',
				'condition' => [
					'link_entries' => 'yes',
				],
			]
		);

		$this->add_control(
			'popup_anchor',
			[
				'label' => __( 'Popup Anchor', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '#popup-7468',
				'description' => __( 'Anchor that triggers the popup (example: #popup-7468).', 'soda-addons' ),
				'condition' => [
					'link_entries' => 'yes',
					'link_type' => 'popup_query',
				],
			]
		);

		$this->add_control(
			'popup_query_param',
			[
				'label' => __( 'Query Parameter', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'municipio_id',
				'description' => __( 'Parameter name that receives the current entry ID.', 'soda-addons' ),
				'condition' => [
					'link_entries' => 'yes',
					'link_type' => 'popup_query',
				],
			]
		);

		$this->add_control(
			'popup_param_delivery',
			[
				'label' => __( 'Parameter Delivery', 'soda-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'query' => __( 'Query String', 'soda-addons' ),
					'data_attribute' => __( 'Data Attribute', 'soda-addons' ),
				],
				'default' => 'query',
				'condition' => [
					'link_entries' => 'yes',
					'link_type' => 'popup_query',
				],
			]
		);

		$this->add_control(
			'popup_action_id',
			[
				'label' => __( 'Popup ID', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '7468',
				'description' => __( 'Template ID of the Elementor popup to open.', 'soda-addons' ),
				'condition' => [
					'link_entries' => 'yes',
					'link_type' => 'popup_action',
				],
			]
		);

		$this->add_control(
			'popup_action_param_key',
			[
				'label' => __( 'Action Parameter Key', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'post',
				'description' => __( 'Key that receives the current entry ID in the action URL.', 'soda-addons' ),
				'condition' => [
					'link_entries' => 'yes',
					'link_type' => 'popup_action',
				],
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'Label HTML Tag', 'soda-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'div',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'popup_content_section',
			[
				'label' => __( 'Popup Content', 'soda-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'link_entries' => 'yes',
					'link_type' => [ 'popup_action', 'popup_query' ],
				],
			]
		);

		$this->add_control(
			'popup_dynamic_content',
			[
				'label' => __( 'Update Popup Content', 'soda-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'popup_selector_title',
			[
				'label' => __( 'Title Selector', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'CSS selector inside the popup to place the entry title (e.g. #popup-title or .popup-title).', 'soda-addons' ),
				'condition' => [
					'popup_dynamic_content' => 'yes',
				],
			]
		);

		$this->add_control(
			'popup_selector_content',
			[
				'label' => __( 'Content Selector', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'CSS selector to inject the full entry content.', 'soda-addons' ),
				'condition' => [
					'popup_dynamic_content' => 'yes',
				],
			]
		);

		$this->add_control(
			'popup_selector_excerpt',
			[
				'label' => __( 'Excerpt Selector', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'CSS selector to inject the entry excerpt.', 'soda-addons' ),
				'condition' => [
					'popup_dynamic_content' => 'yes',
				],
			]
		);

		$this->add_control(
			'popup_selector_featured_image',
			[
				'label' => __( 'Featured Image Selector', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'CSS selector for an <img> or container to receive the featured image.', 'soda-addons' ),
				'condition' => [
					'popup_dynamic_content' => 'yes',
				],
			]
		);

		$meta_repeater = new Repeater();
		$meta_repeater->add_control(
			'popup_meta_key',
			[
				'label' => __( 'Meta Key', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Name of the meta field to pull from the REST API (e.g. galgdr_asociado).', 'soda-addons' ),
			]
		);

		$meta_repeater->add_control(
			'popup_meta_selector',
			[
				'label' => __( 'Selector', 'soda-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'CSS selector inside the popup where the meta value should be injected.', 'soda-addons' ),
			]
		);

		$this->add_control(
			'popup_meta_fields',
			[
				'label' => __( 'Meta Fields', 'soda-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $meta_repeater->get_controls(),
				'title_field' => '{{{ popup_meta_key }}} → {{{ popup_meta_selector }}}',
				'prevent_empty' => false,
				'condition' => [
					'popup_dynamic_content' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Layout Section
		$this->start_controls_section(
			'layout_section',
			[
				'label' => __( 'Layout', 'soda-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'direction',
			[
				'label' => __( 'Direction', 'soda-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => __( 'Horizontal', 'soda-addons' ),
						'icon' => 'eicon-navigation-horizontal',
					],
					'column' => [
						'title' => __( 'Vertical', 'soda-addons' ),
						'icon' => 'eicon-navigation-vertical',
					],
				],
				'default' => 'row',
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__items' => 'display: flex; flex-direction: {{VALUE}}; flex-wrap: wrap;',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'soda-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Start', 'soda-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'soda-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => __( 'End', 'soda-addons' ),
						'icon' => 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => __( 'Justify', 'soda-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__items' => 'justify-content: {{VALUE}}; align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => __( 'Gap Between Items', 'soda-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100, 'step' => 1 ],
					'em' => [ 'min' => 0, 'max' => 10, 'step' => 0.1 ],
					'rem' => [ 'min' => 0, 'max' => 10, 'step' => 0.1 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 8 ],
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__items' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Label Style Section
		$this->start_controls_section(
			'label_style_section',
			[
				'label' => __( 'Label Style', 'soda-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'soda-addons' ),
				'selector' => '{{WRAPPER}} .soda-entry-list__label',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'soda-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'label_margin',
			[
				'label' => __( 'Margin', 'soda-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Items Style Section
		$this->start_controls_section(
			'items_style_section',
			[
				'label' => __( 'Items Style', 'soda-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'items_typography',
				'label' => __( 'Typography', 'soda-addons' ),
				'selector' => '{{WRAPPER}} .soda-entry-list__item',
			]
		);

		$this->start_controls_tabs( 'items_style_tabs' );

		// Normal State
		$this->start_controls_tab(
			'items_normal_tab',
			[
				'label' => __( 'Normal', 'soda-addons' ),
			]
		);

		$this->add_control(
			'items_color',
			[
				'label' => __( 'Color', 'soda-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__item' => 'color: {{VALUE}};',
					'{{WRAPPER}} .soda-entry-list__item a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'items_background',
				'label' => __( 'Background', 'soda-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .soda-entry-list__item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'items_border',
				'label' => __( 'Border', 'soda-addons' ),
				'selector' => '{{WRAPPER}} .soda-entry-list__item',
			]
		);

		$this->add_responsive_control(
			'items_border_radius',
			[
				'label' => __( 'Border Radius', 'soda-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'items_padding',
			[
				'label' => __( 'Padding', 'soda-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover State
		$this->start_controls_tab(
			'items_hover_tab',
			[
				'label' => __( 'Hover', 'soda-addons' ),
			]
		);

		$this->add_control(
			'items_color_hover',
			[
				'label' => __( 'Color', 'soda-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__item:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .soda-entry-list__item a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'items_background_hover',
				'label' => __( 'Background', 'soda-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .soda-entry-list__item:hover',
			]
		);

		$this->add_control(
			'items_border_color_hover',
			[
				'label' => __( 'Border Color', 'soda-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__item:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'items_transition',
			[
				'label' => __( 'Transition Duration', 'soda-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms' ],
				'range' => [
					's' => [ 'min' => 0, 'max' => 3, 'step' => 0.1 ],
					'ms' => [ 'min' => 0, 'max' => 3000, 'step' => 100 ],
				],
				'default' => [ 'unit' => 's', 'size' => 0.3 ],
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__item' => 'transition: all {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .soda-entry-list__item a' => 'transition: color {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Separator Style Section
		$this->start_controls_section(
			'separator_style_section',
			[
				'label' => __( 'Separator Style', 'soda-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'separator_typography',
				'label' => __( 'Typography', 'soda-addons' ),
				'selector' => '{{WRAPPER}} .soda-entry-list__separator',
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => __( 'Color', 'soda-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .soda-entry-list__separator' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get post types options.
	 */
	private function get_post_types_options() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options = [];

		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->labels->singular_name;
		}

		return $options;
	}

	/**
	 * Render widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$post_type = $settings['post_type'];
		$query_id = ! empty( $settings['query_id'] ) ? sanitize_key( $settings['query_id'] ) : '';
		$posts_per_page = isset( $settings['posts_per_page'] ) ? (int) $settings['posts_per_page'] : 5;
		if ( 0 === $posts_per_page ) {
			$posts_per_page = -1; // Allow showing all entries when set to zero
		}
		$show_label = $settings['show_label'] === 'yes';
		$label_text = ! empty( $settings['label_text'] ) ? $settings['label_text'] : '';
		$separator = $settings['separator'];
		$link_entries = $settings['link_entries'] === 'yes';
		$html_tag = $settings['html_tag'];
		$link_type = isset( $settings['link_type'] ) ? $settings['link_type'] : 'permalink';
		$popup_anchor = isset( $settings['popup_anchor'] ) ? $settings['popup_anchor'] : '';
		$popup_query_param = isset( $settings['popup_query_param'] ) ? $settings['popup_query_param'] : '';
		$popup_param_delivery = isset( $settings['popup_param_delivery'] ) ? $settings['popup_param_delivery'] : 'query';
		$popup_action_id = isset( $settings['popup_action_id'] ) ? $settings['popup_action_id'] : '';
		$popup_action_param_key = isset( $settings['popup_action_param_key'] ) ? $settings['popup_action_param_key'] : 'post';
		$popup_dynamic_content = isset( $settings['popup_dynamic_content'] ) && 'yes' === $settings['popup_dynamic_content'];
		$popup_selectors = [
			'title' => isset( $settings['popup_selector_title'] ) ? trim( $settings['popup_selector_title'] ) : '',
			'content' => isset( $settings['popup_selector_content'] ) ? trim( $settings['popup_selector_content'] ) : '',
			'excerpt' => isset( $settings['popup_selector_excerpt'] ) ? trim( $settings['popup_selector_excerpt'] ) : '',
			'featured_image' => isset( $settings['popup_selector_featured_image'] ) ? trim( $settings['popup_selector_featured_image'] ) : '',
		];
		$popup_selectors = array_filter( $popup_selectors );
		$popup_meta_fields = [];
		if ( ! empty( $settings['popup_meta_fields'] ) && is_array( $settings['popup_meta_fields'] ) ) {
			foreach ( $settings['popup_meta_fields'] as $meta_item ) {
				if ( empty( $meta_item['popup_meta_key'] ) || empty( $meta_item['popup_meta_selector'] ) ) {
					continue;
				}
				$meta_key = sanitize_key( $meta_item['popup_meta_key'] );
				$meta_selector = trim( $meta_item['popup_meta_selector'] );
				if ( empty( $meta_key ) || empty( $meta_selector ) ) {
					continue;
				}
				$popup_meta_fields[ $meta_key ] = $meta_selector;
			}
		}

		$post_type_object = get_post_type_object( $post_type );

		$popup_config = null;

		if ( in_array( $link_type, [ 'popup_action', 'popup_query' ], true ) ) {
			if ( 'popup_action' === $link_type ) {
				$popup_id_clean = preg_replace( '/[^0-9]/', '', $popup_action_id );
				$param_key = sanitize_key( $popup_action_param_key );
				if ( empty( $param_key ) ) {
					$param_key = 'post';
				}

				$popup_config = [
					'widgetId' => $this->get_id(),
					'popupId' => ! empty( $popup_id_clean ) ? (int) $popup_id_clean : null,
					'paramKey' => $param_key,
					'postType' => $post_type,
					'delivery' => 'action',
					'dynamic' => false,
				];
			} else {
				$anchor = ! empty( $popup_anchor ) ? $popup_anchor : '#popup';
				$param = sanitize_key( $popup_query_param );
				if ( empty( $param ) ) {
					$param = 'municipio_id';
				}
				$delivery_mode = in_array( $popup_param_delivery, [ 'query', 'data_attribute' ], true ) ? $popup_param_delivery : 'query';
				$popup_id_from_anchor = null;
				if ( preg_match( '/([0-9]+)/', $anchor, $matches ) ) {
					$popup_id_from_anchor = (int) $matches[1];
				}

				$popup_config = [
					'widgetId' => $this->get_id(),
					'popupId' => $popup_id_from_anchor,
					'paramKey' => $param,
					'postType' => $post_type,
					'delivery' => $delivery_mode,
					'dynamic' => false,
				];
			}

			if ( ! empty( $popup_meta_fields ) ) {
				$popup_config['meta'] = $popup_meta_fields;
				self::ensure_rest_meta_keys( $post_type, array_keys( $popup_meta_fields ) );
			}

			if ( $popup_dynamic_content && ( ! empty( $popup_selectors ) || ! empty( $popup_meta_fields ) ) && $post_type_object && ! empty( $post_type_object->show_in_rest ) ) {
				$rest_namespace = ! empty( $post_type_object->rest_namespace ) ? $post_type_object->rest_namespace : 'wp/v2';
				$rest_base = ! empty( $post_type_object->rest_base ) ? $post_type_object->rest_base : $post_type;
				$rest_route = rest_url( trailingslashit( sprintf( '%s/%s', trim( $rest_namespace, '/' ), trim( $rest_base, '/' ) ) ) );

				$popup_config['dynamic'] = true;
				$popup_config['restUrl'] = esc_url_raw( $rest_route );
				$popup_config['selectors'] = $popup_selectors;
			}

			$popup_configuration_script = 'window.SodaEntryListPopupConfig = window.SodaEntryListPopupConfig || {}; window.SodaEntryListPopupConfig["' . esc_js( $this->get_id() ) . '"] = ' . wp_json_encode( $popup_config ) . ';';
			wp_add_inline_script( 'elementor-frontend', $popup_configuration_script, 'after' );

			self::enqueue_popup_script();
		}

		$args = [
			'post_type' => $post_type,
			'posts_per_page' => $posts_per_page,
			'ignore_sticky_posts' => true,
			'post_status' => 'publish',
			'no_found_rows' => true,
		];

		$query = new \WP_Query();
		foreach ( $args as $key => $value ) {
			$query->set( $key, $value );
		}

		if ( $query_id ) {
			$query->set( 'query_id', $query_id );
			do_action_ref_array( 'elementor/query/' . $query_id, [ &$query, $this ] );
		}

		$query->query( $query->query_vars );

		if ( ! $query->have_posts() ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="soda-entry-list__message">' . __( 'No entries found for this query.', 'soda-addons' ) . '</div>';
			}
			return;
		}

		if ( empty( $label_text ) && $post_type_object ) {
			$label_text = $post_type_object->labels->name;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'soda-entry-list' );

		if ( $query_id ) {
			$this->add_render_attribute( 'wrapper', 'data-query-id', $query_id );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( $show_label && ! empty( $label_text ) ) : ?>
				<<?php echo esc_attr( $html_tag ); ?> class="soda-entry-list__label">
					<?php echo esc_html( $label_text ); ?>
				</<?php echo esc_attr( $html_tag ); ?>>
			<?php endif; ?>

			<div class="soda-entry-list__items">
				<?php
				$posts_count = $query->post_count;
				$counter = 0;

				while ( $query->have_posts() ) :
					$query->the_post();
					$counter++;
					?>
					<span class="soda-entry-list__item">
						<?php if ( $link_entries ) : ?>
							<?php
							$post_id = get_the_ID();
							$link_url = '';
							$link_extra_attributes = [];

							if ( 'popup_query' === $link_type ) {
								$anchor = ! empty( $popup_anchor ) ? $popup_anchor : '#popup';
								$param = sanitize_key( $popup_query_param );
								if ( empty( $param ) ) {
									$param = 'municipio_id';
								}

								$link_url = $anchor;

								$delivery_mode = in_array( $popup_param_delivery, [ 'query', 'data_attribute' ], true ) ? $popup_param_delivery : 'query';
								$popup_id_from_anchor = null;
								if ( preg_match( '/([0-9]+)/', $anchor, $matches ) ) {
									$popup_id_from_anchor = (int) $matches[1];
								}

								if ( 'data_attribute' === $delivery_mode ) {
									$data_attr_name = 'data-' . str_replace( '_', '-', $param );
									$link_extra_attributes[ $data_attr_name ] = absint( $post_id );
								}

								$link_extra_attributes['data-soda-entry-link'] = 'yes';
								$link_extra_attributes['data-soda-entry-widget'] = $this->get_id();
								$link_extra_attributes['data-soda-post-id'] = absint( $post_id );
								$link_extra_attributes['data-soda-entry-delivery'] = $delivery_mode;
								$link_extra_attributes['data-soda-param-key'] = $param;
								if ( 'query' === $delivery_mode ) {
									$link_extra_attributes['data-soda-query-param'] = $param;
								}

								if ( $popup_id_from_anchor ) {
									$link_extra_attributes['data-elementor-open-popup'] = 'yes';
									$link_extra_attributes['data-elementor-popup-id'] = $popup_id_from_anchor;
									$link_extra_attributes['data-elementor-popup-settings'] = wp_json_encode(
										[
											'id' => $popup_id_from_anchor,
											$param => absint( $post_id ),
										]
									);
								}
							} elseif ( 'popup_action' === $link_type ) {
								$popup_id_clean = preg_replace( '/[^0-9]/', '', $popup_action_id );
								$param_key = sanitize_key( $popup_action_param_key );
								if ( empty( $param_key ) ) {
									$param_key = 'post';
								}
								if ( ! empty( $popup_id_clean ) ) {
									$link_url = '#';
									$link_extra_attributes['data-elementor-open-popup'] = 'yes';
									$link_extra_attributes['data-elementor-popup-id'] = absint( $popup_id_clean );
									$popup_settings = [
										'id' => absint( $popup_id_clean ),
										$param_key => absint( $post_id ),
									];
									$link_extra_attributes['data-elementor-popup-settings'] = wp_json_encode( $popup_settings );
									$link_extra_attributes['data-soda-entry-link'] = 'yes';
									$link_extra_attributes['data-soda-entry-widget'] = $this->get_id();
									$link_extra_attributes['data-soda-post-id'] = absint( $post_id );
									$link_extra_attributes['data-soda-entry-delivery'] = 'action';
									$link_extra_attributes['data-soda-param-key'] = $param_key;
									self::enqueue_popup_script();
								}
							}

							if ( empty( $link_url ) ) {
								$link_url = get_permalink( $post_id );
							}
							?>
							<?php
							$link_attributes = [ 'href' => $link_url ];
							foreach ( $link_extra_attributes as $attr_key => $attr_value ) {
								$link_attributes[ $attr_key ] = $attr_value;
							}
							$attributes_output = [];
							foreach ( $link_attributes as $attr_key => $attr_value ) {
								$attributes_output[] = sprintf( '%s="%s"', esc_attr( $attr_key ), esc_attr( $attr_value ) );
							}
							?>
							<a <?php echo implode( ' ', $attributes_output ); ?>>
								<?php echo esc_html( get_the_title() ); ?>
							</a>
						<?php else : ?>
							<?php echo esc_html( get_the_title() ); ?>
						<?php endif; ?>
					</span>
					<?php if ( $counter < $posts_count && ! empty( $separator ) ) : ?>
						<span class="soda-entry-list__separator"><?php echo esc_html( $separator ); ?></span>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		</div>
		<?php

		wp_reset_postdata();
	}

	/**
	 * Bootstrap the REST meta registry from the persisted option.
	 */
	public static function bootstrap_rest_meta_registry() {
		if ( self::$rest_meta_registry_initialized ) {
			return;
		}

		self::$rest_meta_registry_initialized = true;

		$stored_registry = get_option( 'soda_entry_list_rest_meta_registry', [] );
		if ( is_array( $stored_registry ) ) {
			self::$rest_meta_registry = $stored_registry;
		}

		foreach ( array_keys( self::$rest_meta_registry ) as $post_type ) {
			add_filter( 'rest_prepare_' . $post_type, [ __CLASS__, 'inject_configured_meta' ], 10, 3 );
		}

		add_action( 'shutdown', [ __CLASS__, 'persist_rest_meta_registry' ] );
	}

	/**
	 * Persist the REST meta registry when it has been modified.
	 */
	public static function persist_rest_meta_registry() {
		if ( ! self::$rest_meta_registry_initialized || ! self::$rest_meta_registry_dirty ) {
			return;
		}

		update_option( 'soda_entry_list_rest_meta_registry', self::$rest_meta_registry, false );
		self::$rest_meta_registry_dirty = false;
	}

	/**
	 * Ensure selected meta keys are exposed through the REST API for the given post type.
	 *
	 * @param string $post_type Post type slug.
	 * @param array  $meta_keys Meta keys to expose.
	 */
	public static function register_popup_meta_keys( $post_type, array $meta_keys ) {
		self::ensure_rest_meta_keys( $post_type, $meta_keys );
	}

	/**
	 * Ensure the popup helper script is enqueued only once per request.
	 */
	public static function enqueue_popup_script() {
		if ( self::$popup_script_enqueued ) {
			return;
		}

		$script = <<<'JS'
(function(){
	if (window.SodaEntryListPopupInit) {
		return;
	}

	window.SodaEntryListPopupInit = true;
	window.SodaEntryListPopupConfig = window.SodaEntryListPopupConfig || {};
	window.SodaEntryListPopupCache = window.SodaEntryListPopupCache || {};

	function decodeEntities(str) {
		var textarea = document.createElement('textarea');
		textarea.innerHTML = str;
		return textarea.value;
	}

	function applyData(config, data) {
		if (!data) {
			return;
		}

		var selectors = config.selectors || {};

		if (selectors.title) {
			document.querySelectorAll(selectors.title).forEach(function(element){
				element.textContent = data.title && data.title.rendered ? decodeEntities(data.title.rendered) : '';
			});
		}

		if (selectors.excerpt) {
			document.querySelectorAll(selectors.excerpt).forEach(function(element){
				element.innerHTML = data.excerpt && data.excerpt.rendered ? data.excerpt.rendered : '';
			});
		}

		if (selectors.content) {
			document.querySelectorAll(selectors.content).forEach(function(element){
				element.innerHTML = data.content && data.content.rendered ? data.content.rendered : '';
			});
		}

		if (selectors.featured_image) {
			var imageUrl = '';
			if (data._embedded && data._embedded['wp:featuredmedia'] && data._embedded['wp:featuredmedia'][0] && data._embedded['wp:featuredmedia'][0].source_url) {
				imageUrl = data._embedded['wp:featuredmedia'][0].source_url;
			}

			if (imageUrl) {
				document.querySelectorAll(selectors.featured_image).forEach(function(element){
					if (element.tagName === 'IMG') {
						element.src = imageUrl;
					} else {
						element.style.backgroundImage = 'url(' + imageUrl + ')';
					}
				});
			}
		}

		var metaSelectors = config.meta || {};
		Object.keys(metaSelectors).forEach(function(metaKey){
			var selector = metaSelectors[metaKey];
			if (!selector) {
				return;
			}

			var value = '';
			if (data.meta && typeof data.meta === 'object' && data.meta.hasOwnProperty(metaKey)) {
				value = data.meta[metaKey];
			} else if (data.hasOwnProperty(metaKey)) {
				value = data[metaKey];
			} else if (data.acf && typeof data.acf === 'object' && data.acf.hasOwnProperty(metaKey)) {
				value = data.acf[metaKey];
			}

			if (Array.isArray(value)) {
				value = value.join(', ');
			} else if (value && typeof value === 'object') {
				if (Object.prototype.hasOwnProperty.call(value, 'rendered')) {
					value = value.rendered;
				} else {
					value = '';
				}
			}

			if (value === null || typeof value === 'undefined') {
				value = '';
			}

			document.querySelectorAll(selector).forEach(function(element){
				if ('value' in element) {
					element.value = value;
				} else {
					element.textContent = value;
				}
			});
		});
	}

	window.SodaEntryListPopupApplyData = applyData;
	window.SodaEntryListPopupUpdateContent = updatePopupContent;

	function showPopupWithConfig(config, postId, baseSettings) {
		if (!config || !postId) {
			return null;
		}

		var popupSettings = {};
		if (baseSettings && typeof baseSettings === 'object') {
			try {
				popupSettings = JSON.parse(JSON.stringify(baseSettings));
			} catch (error) {
				popupSettings = Object.assign({}, baseSettings);
			}
		}

		var paramKey = config.paramKey || 'post';
		if (!popupSettings.id && config.popupId) {
			popupSettings.id = config.popupId;
		}

		if (!popupSettings.id) {
			return null;
		}

		if (config.delivery === 'query' && paramKey) {
			try {
				var urlObject = new URL(window.location.href);
				urlObject.searchParams.set(paramKey, postId);
				window.history.replaceState({}, '', urlObject.href);
			} catch (error) {
				var parts = window.location.href.split('#');
				var base = parts[0];
				var hash = parts.length > 1 ? '#' + parts.slice(1).join('#') : '';
				var pattern = new RegExp('([?&])' + paramKey + '=([^&#]*)');
				if (pattern.test(base)) {
					base = base.replace(pattern, '$1' + paramKey + '=' + postId);
				} else if (base.indexOf('?') !== -1) {
					base = base + '&' + paramKey + '=' + postId;
				} else {
					base = base + '?' + paramKey + '=' + postId;
				}

				window.history.replaceState({}, '', base + hash);
			}
		}

		popupSettings[paramKey] = postId;
		popupSettings.post = postId;
		popupSettings.postId = postId;
		popupSettings.post_id = postId;

		if (!popupSettings.dynamic || typeof popupSettings.dynamic !== 'object') {
			popupSettings.dynamic = {};
		}

		popupSettings.dynamic[paramKey] = postId;
		popupSettings.dynamic.post = postId;
		popupSettings.dynamic.postId = postId;
		popupSettings.dynamic.post_id = postId;

		if (typeof elementorProFrontend !== 'undefined' && elementorProFrontend.modules && elementorProFrontend.modules.popup) {
			elementorProFrontend.modules.popup.showPopup({ id: popupSettings.id, settings: popupSettings });
		}

		if (config.dynamic) {
			updatePopupContent(config, postId);
		}

		return popupSettings;
	}

	window.SodaEntryListPopupShowConfig = showPopupWithConfig;
	window.SodaEntryListPopupOpen = function(widgetId, postId, baseSettings){
		if (!widgetId) {
			return null;
		}
		var config = window.SodaEntryListPopupConfig[widgetId] || null;
		if (!config) {
			return null;
		}
		return showPopupWithConfig(config, postId, baseSettings || {});
	};

	function updatePopupContent(config, postId) {
		if (!config || !config.restUrl) {
			return;
		}

		var cacheKey = config.postType + ':' + postId;
		if (window.SodaEntryListPopupCache[cacheKey]) {
			applyData(config, window.SodaEntryListPopupCache[cacheKey]);
			return;
		}

		var url = config.restUrl + postId + '?_embed=1';
		fetch(url, { credentials: 'same-origin' })
			.then(function(response){
				if (!response.ok) {
					throw new Error('Request failed');
				}
				return response.json();
			})
			.then(function(data){
				window.SodaEntryListPopupCache[cacheKey] = data;
				applyData(config, data);
			})
			.catch(function(){
				// Silent failure.
			});
	}

	document.addEventListener('click', function(event){
		var trigger = event.target.closest('[data-soda-entry-link]');
		if (!trigger) {
			return;
		}

		var widgetId = trigger.getAttribute('data-soda-entry-widget') || '';
		var postId = parseInt(trigger.getAttribute('data-soda-post-id'), 10);

		if (!widgetId || !postId) {
			return;
		}

		var config = window.SodaEntryListPopupConfig[widgetId] || {};
		var deliveryMode = config.delivery || trigger.getAttribute('data-soda-entry-delivery') || 'query';
		var paramKey = config.paramKey || trigger.getAttribute('data-soda-param-key') || 'post';

		if (deliveryMode === 'data_attribute' && paramKey) {
			var attrName = 'data-' + paramKey.replace(/_/g, '-');
			trigger.setAttribute(attrName, postId);
		} else if (deliveryMode === 'query' && paramKey) {
			try {
				var urlObject = new URL(window.location.href);
				urlObject.searchParams.set(paramKey, postId);
				window.history.replaceState({}, '', urlObject.href);
			} catch (error) {
				var parts = window.location.href.split('#');
				var base = parts[0];
				var hash = parts.length > 1 ? '#' + parts.slice(1).join('#') : '';
				var pattern = new RegExp('([?&])' + paramKey + '=([^&#]*)');
				if (pattern.test(base)) {
					base = base.replace(pattern, '$1' + paramKey + '=' + postId);
				} else if (base.indexOf('?') !== -1) {
					base = base + '&' + paramKey + '=' + postId;
				} else {
					base = base + '?' + paramKey + '=' + postId;
				}
				window.history.replaceState({}, '', base + hash);
			}
		}

		var popupSettings = {};
		var settingsAttr = trigger.getAttribute('data-elementor-popup-settings');

		if (settingsAttr) {
			try {
				popupSettings = JSON.parse(settingsAttr);
			} catch (error) {
				popupSettings = {};
			}
		}

		var popupId = popupSettings.id || config.popupId;

		if (!popupId) {
			var popupIdAttr = trigger.getAttribute('data-elementor-popup-id');
			if (popupIdAttr) {
				popupId = parseInt(popupIdAttr, 10);
			} else {
				var href = trigger.getAttribute('href') || '';
				var match = href.match(/popup=([0-9]+)/);
				if (match) {
					popupId = parseInt(match[1], 10);
				}
			}
		}

		if (popupId) {
			popupSettings.id = popupId;
		}

		if (!paramKey) {
			paramKey = 'post';
		}

		event.preventDefault();

		showPopupWithConfig(config, postId, popupSettings);
	});
})();
JS;

		wp_add_inline_script( 'elementor-frontend', $script, 'after' );
		self::$popup_script_enqueued = true;
	}

	/**
	 * Ensure selected meta keys are exposed through the REST API for the given post type.
	 *
	 * @param string $post_type Post type slug.
	 * @param array  $meta_keys Meta keys to expose.
	 */
	private static function ensure_rest_meta_keys( $post_type, array $meta_keys ) {
		self::bootstrap_rest_meta_registry();

		$meta_keys = array_filter( array_unique( $meta_keys ) );
		if ( empty( $meta_keys ) ) {
			return;
		}

		if ( ! isset( self::$rest_meta_registry[ $post_type ] ) ) {
			self::$rest_meta_registry[ $post_type ] = [];
			add_filter( 'rest_prepare_' . $post_type, [ __CLASS__, 'inject_configured_meta' ], 10, 3 );
		}

		$combined_keys = array_values( array_unique( array_merge( self::$rest_meta_registry[ $post_type ], $meta_keys ) ) );
		if ( $combined_keys !== self::$rest_meta_registry[ $post_type ] ) {
			self::$rest_meta_registry[ $post_type ] = $combined_keys;
			self::$rest_meta_registry_dirty = true;
		}
	}

	/**
	 * Append configured meta keys to the REST API response.
	 *
	 * @param \WP_REST_Response $response Response object.
	 * @param \WP_Post          $post     Current post.
	 * @param \WP_REST_Request  $request  Request object.
	 *
	 * @return \WP_REST_Response
	 */
	public static function inject_configured_meta( $response, $post, $request ) {
		$post_type = isset( $post->post_type ) ? $post->post_type : ''; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( empty( $post_type ) || empty( self::$rest_meta_registry[ $post_type ] ) ) {
			return $response;
		}

		$data = $response->get_data();
		if ( ! isset( $data['meta'] ) || ! is_array( $data['meta'] ) ) {
			$data['meta'] = [];
		}

		foreach ( self::$rest_meta_registry[ $post_type ] as $meta_key ) {
			$meta_value = get_post_meta( $post->ID, $meta_key, false );
			if ( empty( $meta_value ) ) {
				$data['meta'][ $meta_key ] = '';
				continue;
			}

			if ( count( $meta_value ) === 1 ) {
				$meta_value = $meta_value[0];
			}
			$data['meta'][ $meta_key ] = $meta_value;
		}

		$response->set_data( $data );

		return $response;
	}
}

	Entry_List_Widget::bootstrap_rest_meta_registry();
