<?php

/**
 * The plugin entry file.
 *
 * Plugin Name:     commercial-algolia
 * Description:     Commercial Algolia
 * Text Domain:     commercial-algolia
 * Version:         0.0.1
 *
 * @package WooSearch
 */

namespace WooSearch;

defined('ABSPATH') || exit;

define('PLUGIN_ABSPATH_DIR', __DIR__);
define('PLUGIN_ABSPATH', __FILE__);

use WooSearch\Admin\Admin_Pages;
use WooSearch\Admin\Admin_React_App;
use WooSearch\Api\Api;
use WooSearch\Integrations\Integration_Manager;

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/record-model/data-source/data-sources.php';

require_once __DIR__ . '/src/collection-blueprints/class-posttype-data-collection.php';
require_once __DIR__ . '/src/collection-blueprints/class-woo-product-data-collection.php';

require_once __DIR__ . '/includes/helpers.php';

/**
 * Undocumented class
 */
class WooSearch
{

	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	private static $instance;

	/**
	 * Undocumented variable
	 *
	 * @var Integration_Manager
	 */
	private $integration_manager;

	/**
	 * Undocumented function
	 */
	private function __construct()
	{
		$this->integration_manager = Integration_Manager::get_instance();
	}

	/**
	 * Undocumented function
	 *
	 * @return WooSearch
	 */
	public static function get_instance()
	{

		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function init()
	{
		$this->integration_manager->init();
	}

	public function get_integration_manager()
	{
		return $this->integration_manager;
	}
}

add_action('plugins_loaded', function () {

	$woo_search = WooSearch::get_instance();
	$woo_search->init();

	Admin_Pages::setup_pages();
	Admin_React_App::setup_react_app();

	Api::setup();
}, 1);

function woo_search()
{
	return WooSearch::get_instance();
}

$GLOBALS['WP_IData'] = woo_search();


add_action("init", function () {
	register_post_type(
		'woo_search_indicies',
		array(
			'label'  => 'Woo Search Indicies',
			'public' => true,
		)
	);
});


function advanced_ai_search_register_blocks()
{

	$block_dir = __DIR__ . '/build/Blocks';
	$block_dirs = array_diff(
		scandir($block_dir),
		array('..', '.')
	);

	error_log("here are the block directories");
	error_log(print_r($block_dirs, true));

	foreach ($block_dirs as $block ) {

		$block_entry = $block_dir . '/' . $block;
		error_log("registering block " . $block_entry);
		
		$result = register_block_type($block_entry);

		error_log("result was");
		error_log(print_r($result, true));
	}
}

add_action('init', __NAMESPACE__ . '\\advanced_ai_search_register_blocks');

function enqueue_block_editor_assets() {

	$deps = require_once __DIR__ . '/build/admin.asset.php';
	
	wp_enqueue_script(
		'admin-script',
		plugin_dir_url(__FILE__) . '/build/admin.js',
		$deps['dependencies']
	);
}
add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_block_editor_assets');

require_once __DIR__ . '/wp-cli.php';
