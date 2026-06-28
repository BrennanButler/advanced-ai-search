<?php

use WooSearch\Admin\Pages\Admin_Page_Controller_Interface;
use WooSearch\WooSearch;
use WooSearch\Admin\Pages\Abstract_Page_Controller;
use WooSearch\DataCollection\Storage\WP_Collection_Storage;

class Dashboard_Page_Controller extends Abstract_Page_Controller implements Admin_Page_Controller_Interface {

	public static function setup() {
		add_action('admin_menu', array(self::class, 'register_dashboard_page'));
	}

	public static function register_dashboard_page() {
		add_menu_page(
			'Woo Search',
			'Woo Search',
			'manage_options',
			'woo-search-dashboard',
			array(self::class, 'render_dashboard_page'),
			'dashicons-search',
			2
		);
	}

	public static function render_dashboard_page() {
		$integration_manager = WooSearch::get_instance()->get_integration_manager();
		$collection_blueprint_registry = $integration_manager->get_collection_blueprint_registry();
		$collection_blueprints = $collection_blueprint_registry->get_all();

		global $wpdb;

		$data_collection_storage = new WP_Collection_Storage( $wpdb );
		$data_collections = $data_collection_storage->get_all_collections();

		self::render_page(
			__DIR__ . '/../views/view-dashboard.php', 
			compact(
				array(
					'collection_blueprints',
					'data_collections'
				)
			)
		);
	}
}

Dashboard_Page_Controller::setup();

/*
function render_data_collections_dashboard() {
	$integration_manager = WooSearch::get_instance()->get_integration_manager();
	$collection_blueprint_registry = $integration_manager->get_collection_blueprint_registry();
	$data_collections = $collection_blueprint_registry->get_all_data_collections();

	include __DIR__ . '/views/dashboard.php';
}*/