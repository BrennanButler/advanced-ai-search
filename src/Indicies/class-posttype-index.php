<?php
/**
 * PostType Index for core and custom WordPress post types.
 *
 * @package WooSearch\Indicies
 */

namespace WooSearch\Indicies;

use  WooSearch\Integrations\Abstract_Index_Type_Integration;
use WooSearch\Records\PostType_Record;
use WooSearch\Indicies\Abstract_Record_Index;
use WooSearch\Integrations\Index_Type_Integration_Interface;
use WooSearch\Integrations\Index_Type_Integrations_Registry;
use WP_REST_Request;

/**
 * PostType_Index class for core and custom WordPress post type Indicies.
 */
class PostType_Index extends Abstract_Record_Index implements Record_Index_Interface {

	/**
	 * The post type to use for this index.
	 *
	 * @var string
	 */
	protected string $post_type;

	/**
	 * Constructor.
	 *
	 * @param string  $name The name of the index.
	 * @param string  $record_prefix The record prefix.
	 * @param string  $post_type The post type to use for this index.
	 * @param boolean $forward_to_replicas Whether to forward the settings to replica indicies.
	 */
	public function __construct( string $post_type = 'post', string $name = "wp_posts", string $record_prefix = 'wp_', bool $forward_to_replicas = true ) {
		parent::__construct( $name, $record_prefix, true, PostType_Record::class );

		$this->post_type     = $post_type;
		$this->record        = PostType_Record::class;
		$this->record_prefix = $record_prefix;
	}

	/**
	 * Get the ranking of this index for search.
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
			'custom',
		);
	}

	/**
	 * Get the searchable attributes for this index.
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
		return PostType_Record::class;
	}

	/**
	 * Fetch records internally for this index.
	 *
	 * @param integer $page The page for search.
	 * @param integer $per_page The amount of records to retrieve per page.
	 * @return array
	 */
	public function fetch_records( int $page, $per_page = 100 ): array {

		$records = new \WP_Query(
			array(
				'posts_per_page' => $per_page,
				'paged'          => $page,
				'post_type'      => $this->post_type,

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


class PostType_Index_Integration extends Abstract_Index_Type_Integration implements Index_Type_Integration_Interface {

	public function __construct()
	{
		$this->slug = "post_type_index";
		$this->name = "Post type index";
		$this->description = "My description";
		$this->index_class = PostType_Index::class;
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

add_action( 'woo_search_register_index_type_integrations', function ( Index_Type_Integrations_Registry $index_type_registry ) {
	/*
	$index_type_registry->register(
		array(
			'slug' => 'posttype-index',
			'name' => 'Post Type Index',
			'description' => 'Post type index type description',
			'class' => PostType_Index::class,
			'options' => array(
				array(
					"slug" => "post-type",
					"label" => "Post type",
					"type" => "text",
					"placeholder" => "post type placeholder",
				),
			)
		)
	);*/

	$index_type_registry->register(
		new PostType_Index_Integration()
	);
});

