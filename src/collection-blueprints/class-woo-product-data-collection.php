<?php
/**
 * Woocommerce Product Collection Blueprint.
 *
 * @package WooSearch\CollectionBlueprints
 */

namespace WooSearch\CollectionBlueprints;

use WooSearch\CollectionBlueprints\Abstract_Collection_Blueprint;
use WooSearch\CollectionBlueprints\Collection_Blueprint_Interface;
use WooSearch\Records\Woo_Product_Record;
use WooSearch\Integrations\Collection_Blueprint_Integrations_Registry;

use WooSearch\Integrations\Abstract_Collection_Blueprint_Integration;
use WooSearch\Integrations\Collection_Blueprint_Integration_Interface;

use WP_REST_Request;

/**
 * Woo_Product_Collection_Blueprint class for Woocommerce product data collections.
 */
class Woo_Product_Collection_Blueprint extends Abstract_Collection_Blueprint implements Collection_Blueprint_Interface {

	/**
	 * Constructor.
	 *
	 * @param string  $name The name of the collection blueprint.
	 * @param string  $record_prefix The record prefix.
	 * @param boolean $forward_to_replicas Whether to forward the settings onto replica collection blueprints.
	 */
	public function __construct( string $name, string $record_prefix = 'product_', bool $forward_to_replicas = true ) {
		parent::__construct( $name, $record_prefix, true, Woo_Product_Record::class );

		$this->record        = Woo_Product_Record::class;
		$this->record_prefix = $record_prefix;

		// Add replica indicies here.
	}

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	public function get_ranking(): array {
		return array(
			'desc(post_date)',
			'typo',
			'geo',
			'words',
			'filters',
			'proximity',
			'attribute',
			'exact',
			'desc(popularity)',

		);
	}

	/**
	 * Get the searchable attributes for this collection blueprint.
	 *
	 * @return array
	 */
	public function get_searchable_attributes(): array {

		$attributes = $this->record::get_attributes();

		$attributes_to_remove = array(
			'has_excerpt',
			'is_published',
			'post_parent',
			'wp_id',
			'post_status',
			'comment_status',
		);

		$attributes = array_diff( $attributes, $attributes_to_remove );

		return array_values( $attributes );
	}

	/**
	 * Get the record class.
	 *
	 * @return string
	 */
	public static function get_record(): string {
		return Woo_Product_Record::class;
	}

	/**
	 * Fetch the records internally for this collection blueprint.
	 *
	 * @param integer $page The page for search.
	 * @param integer $per_page The amount of records per page.
	 * @return array
	 */
	public function fetch_records( int $page, $per_page = 100 ): array {

		$records = new \WP_Query(
			array(
				'posts_per_page' => $per_page,
				'paged'          => $page,
				'post_type'      => 'product',

			)
		);

		if ( ! $records->have_posts() ) {
			return array();
		}

		return array_map(
			function ( $post ) {

				$record = $this->get_record();

				return new $record( $post, $this->get_record_prefix() );
			},
			$records->posts
		);
	}
}

class Woo_Product_Collection_Blueprint_Integration extends Abstract_Collection_Blueprint_Integration implements Collection_Blueprint_Integration_Interface {

	public function __construct()
	{
		$this->slug = "post_type_index";
		$this->name = "Post type index";
		$this->description = "My description";
		$this->index_class = PostType_Collection_Blueprint::class;
	}

	public function register_rest_routes(): void
	{
		parent::register_rest_routes();

		remove_action("index_type_integration_register_rest_routes_post_type_index", "register_fetch_records_route");

		register_rest_route(
			'advanced-ai-search/v1',
			'/integration/index/' . $this->slug . '/records/(?P<post_type>\w+)/(?P<page>\d+)/(?P<per_page>\d+)',
			array(
				'methods' => 'GET',
				'callback' => array($this, '_fetch_post_type_records_route'),
				'permission_callback' => '__return_true'
			)
		);
	}

	protected function _fetch_post_type_records_route( WP_REST_Request $request ) {
		$page = $request->get_param('page');
		$per_page = $request->get_param('per_page');
		$post_type = $request->get_param("post_type");

		$index = new $this->index_class( $post_type );

		return $index->fetch_records( intval($page), intval($per_page) );
	}
}

add_action( 'woo_search_register_collection_blueprints', function ( Collection_Blueprint_Integrations_Registry $collection_blueprint_registry ) {
	
	$collection_blueprint_registry->register(
		new Woo_Product_Collection_Blueprint_Integration()
	);
});