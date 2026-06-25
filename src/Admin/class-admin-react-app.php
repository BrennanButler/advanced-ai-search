<?php
/**
 * Admin React App
 */

namespace WooSearch\Admin;

/**
 * Undocumented class
 */
class Admin_React_App {

	public static function setup_react_app() {

		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue_assets' ) );
	}

	public static function enqueue_assets( $hook_suffix ) {

		if ( ! str_contains( $hook_suffix, 'woo-search' ) ) {
			error_log("suffix is wrong " . $hook_suffix);
			return;
		}
		error_log("we have enqueue");

		
		$asset_file = PLUGIN_ABSPATH_DIR . '/build/admin.asset.php';
		$url = plugin_dir_url(PLUGIN_ABSPATH);

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_script(
			'woo-search-admin-react-app',
			$url . '/build/admin.js',
			$asset['dependencies'],
			null
		);

		wp_enqueue_style(
			"woo-search-admin-react-app-styles",
			$url . '/build/admin.css',
			array( 'wp-components' ),
			$asset['version']
		);
	}
}
