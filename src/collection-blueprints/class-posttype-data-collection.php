<?php
/**
 * PostType collection blueprint for core and custom WordPress post types.
 *
 * @package WooSearch\CollectionBlueprints
 */

namespace WooSearch\CollectionBlueprints;

use WooSearch\Integrations\Abstract_Collection_Blueprint_Integration;
use WooSearch\RecordModel\PostType_Record_Model;
use WooSearch\Integrations\Collection_Blueprint_Integration_Interface;
use WooSearch\Integrations\Collection_Blueprint_Integrations_Registry;
use WP_REST_Request;

/**
 * PostType_Collection_Blueprint class for core and custom WordPress post type data collections.
 */
class PostType_Collection_Blueprint extends Abstract_Collection_Blueprint implements Collection_Blueprint_Interface {

	/**
	 * The post type to used for the collection.
	 *
	 * @var string
	 */
	protected static string $post_type;

	/**
	 * Constructor.
	 *
	 * @param string  $name The name of the collection blueprint.
	 * @param string  $record_prefix The record prefix.
	 * @param string  $post_type The post type to use for this collection blueprint.
	 * @param boolean $forward_to_replicas Whether to forward the settings to replica collection blueprints.
	 */
	public static function init( $blueprint_settings ): void {

		self::$post_type     = 'post';

		if ( isset( $blueprint_settings['post_type'] ) ) {
			self::$post_type = $blueprint_settings['post_type'];
		}

		self::$record        = new PostType_Record_Model();
		self::$record_prefix = 'wp_';
	}

	/**
	 * Get the ranking of this index for search.
	 *
	 * @return array
	 */
	public static function get_ranking(): array {
		return array(
			'desc(post_date)',
			'typo',
			'geo',
			'words',
			'filters',
			'proximity',
			'attribute',
			'exact',
			'custom',
		);
	}

	/**
	 * Get the searchable attributes for this index.
	 *
	 * @return array
	 */
	public static function get_searchable_attributes(): array {

		$attributes = self::$record::get_attributes();

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
		return PostType_Record_Model::class;
	}

	/**
	 * Fetch records internally for this index.
	 *
	 * @param integer $page The page for search.
	 * @param integer $per_page The amount of records to retrieve per page.
	 * @return array
	 */
	public static function fetch_records( int $page, $per_page = 100 ): array {

		$records = new \WP_Query(
			array(
				'posts_per_page' => $per_page,
				'paged'          => $page,
				'post_type'      => self::$post_type,

			)
		);

		if ( ! $records->have_posts() ) {
			return array();
		}

		return array_map(
			function ( $post ) {

				$record = self::get_record();

				return new $record( $post, self::get_record_prefix() );
			},
			$records->posts
		);
	}
}


class PostType_Collection_Blueprint_Integration extends Abstract_Collection_Blueprint_Integration implements Collection_Blueprint_Integration_Interface {

	public function __construct()
	{
		$this->slug = "post_type_index";
		$this->name = "Post type index";
		$this->description = "My description";

		
		$this->index_class = PostType_Collection_Blueprint::class;
	}

	public function get_blueprint_settings(): array {

		$registered_post_types = get_post_types( array(), 'objects' );

		// Remove woocommerce product post type as we handle that seperately.
		unset( $registered_post_types['product'] );

		return array(
			array(
				'key' => 'post_type',
				'label' => 'Post Type',
				'type' => 'select',
				'description' => 'The post type to use for this collection blueprint.',
				'default' => 'post',
				'required' => true,
				'options' => array_map(
					function ( $post_type ) {
						return array(
							'value' => $post_type->name,
							'label' => $post_type->label,
						);
					},
					$registered_post_types
				)
			)
		);
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
		new PostType_Collection_Blueprint_Integration()
	);
});

