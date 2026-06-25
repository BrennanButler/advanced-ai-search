<?php
/**
 * Admin pages setup for the plugin.
 *
 * @package WooSearch\Admin
 */

namespace WooSearch\Admin;

use function PHPSTORM_META\map;

/**
 * Admin_Pages
 */
class Admin_Pages {

	static function setup_pages() {

		add_action(
			'admin_menu',
			function () {
				self::setup_toplevel_pages();
				self::setup_submenu_pages();
			}
		);

	}

	static function setup_toplevel_pages() {

		\add_menu_page(
			'WooSearch',
			'WooSearch',
			'manage_options',
			'woo-search',
			array(self::class, 'woo_search_page'),
			plugins_url( '' ),
			6
		);
	}

	public static function woo_search_page() {
		printf(
			'<h1>WooSearch</h1>
			<div id="woo-search-react-app"></div>'
		);
	}

	static function setup_submenu_pages() {

		\add_submenu_page(
			'woo-search',
			"custom edit page",
			'',
			'manage_options',
			'woo-search-manage-index',
			array(self::class, 'woo_manage_index'),
			-1
		);

		\add_submenu_page(
			'woo-search',
			'Woo Search Settings',
			'Settings',
			'manage_options',
			'woo-search-settings',
			array(self::class, 'woo_search_settings')
		);
	}

	public static function woo_manage_index() {
		printf(
			'<h1>WooSearch Manage index</h1>
			<div id="woo-search-react-app"></div>'
		);
	}

	public static function woo_search_settings() {
		printf(
			'<h1>WooSearch settings</h1>
			<div id="woo-search-react-app"></div>'
		);
	}
}
