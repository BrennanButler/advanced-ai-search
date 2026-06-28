<?php

use WooSearch\Admin\Pages\Admin_Page_Controller_Interface;
use WooSearch\WooSearch;
use WooSearch\Admin\Pages\Abstract_Page_Controller;
use WooSearch\DataCollection\Storage\WP_Collection_Storage;

class New_Data_Collection_Page_Controller extends Abstract_Page_Controller implements Admin_Page_Controller_Interface {

	public static function setup() {
		add_action('admin_menu', array(self::class, 'register_new_data_collection_page'));
	}

	public static function register_new_data_collection_page() {
		add_submenu_page(
			'woo-search-dashboard',
			'New Data Collection',
			'New Data Collection',
			'manage_options',
			'woo-search-new-data-collection',
			array(self::class, 'render_new_data_collection_page'),
			1
		);
	}

	public static function render_new_data_collection_page() {
		$integration_manager = WooSearch::get_instance()->get_integration_manager();
		$collection_blueprint_registry = $integration_manager->get_collection_blueprint_registry();
		$collection_blueprints = $collection_blueprint_registry->get_all();

		global $wpdb;

		$data_collection_storage = new WP_Collection_Storage( $wpdb );

		$data_collections = $data_collection_storage->get_all_collections();

		self::render_page(
			__DIR__ . '/../views/view-new-data-collection.php', 
			compact(
				array(
					'collection_blueprints',
					'data_collections'
				)
			)
		);
	}
}

New_Data_Collection_Page_Controller::setup();

/*
function render_data_collections_dashboard() {
	$integration_manager = WooSearch::get_instance()->get_integration_manager();
	$collection_blueprint_registry = $integration_manager->get_collection_blueprint_registry();
	$data_collections = $collection_blueprint_registry->get_all_data_collections();

	include __DIR__ . '/views/dashboard.php';
}*/