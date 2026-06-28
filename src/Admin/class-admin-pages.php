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

	public static function init() {

		add_action(
			'admin_menu',
			array(self::class, 'setup_pages')
		);

	}

	public static function setup_pages() {
		
		$controllers_directory = __DIR__ . '/pages/controllers/';
		$controllers = scandir($controllers_directory, SCANDIR_SORT_ASCENDING);

		foreach ( $controllers as $controller ) {
			if ( '.php' === substr( $controller, -4 ) ) {
				require_once $controllers_directory . $controller;
			}
		}
	}
}
