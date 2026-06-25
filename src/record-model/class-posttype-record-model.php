<?php
/**
 * Post Type Record
 *
 * @package WooSearch\Records
 */

namespace WooSearch\RecordModel;

use WooSearch\RecordModel\Abstract_Record_Model;

use WooSearch\RecordModel\DataSource\Data_Source_Registry;

use WooSearch\RecordModel\Record_Model_Interface;

use WooSearch\RecordModel\DataSource\Category_Data_Source;
use WooSearch\RecordModel\DataSource\Post_Data_Source;

use WooSearch\RecordModel\DataSource\Tags_Data_Source;
use WooSearch\RecordModel\DataSource\Thumbnail_Data_Source;
use WooSearch\RecordModel\DataSource\Reading_Time_Data_Source;

use WP_Post;

/**
 * PostTypeRecord class defines a record type that can handle any core and custom post types
 */
class PostType_Record_Model extends Abstract_Record_Model implements Record_Model_Interface {

	/**
	 * The WP_Post object or the post ID
	 *
	 * @var WP_Post|int
	 */
	protected $post;

	/**
	 * The post type to use for this index.
	 *
	 * @var string
	 */
	protected string $post_type;

	/**
	 * The record prefix.
	 *
	 * @var string
	 */
	protected string $prefix;

	/**
	 * The service container for this index. This is where integration can register services and additions to a record.
	 *
	 * @var Data_Source_Registry
	 */
	protected Data_Source_Registry $data_source_registry;

	/**
	 * Undocumented function
	 *
	 * @param WP_Post|integer   $post The WP_Post object or postId to form the record.
	 * @param string|null       $prefix The record prefix.
	 * @param string            $post_type The record post type.
	 * @param Data_Source_Registry $data_source_registry The record data source registry.
	 */
	public function __construct( WP_Post|int $post, string $prefix = null, string $post_type = 'post', Data_Source_Registry $data_source_registry ) {
		$this->post      = $post;
		$this->post_type = $post_type;
		$this->prefix    = $prefix;

		$post_id = gettype( $this->post ) === 'object' ? $this->post->ID : $this->post;
		$post    = gettype( $this->post ) === 'object' ? $this->post : get_post( $this->post );

		/**
		 * "woo_search_posttype_record_{$post_type}_objectid" allows developers to filter the object id for a specific post type.
		 *
		 * @since 1.0.0
		 */
		$this->object_id = apply_filters(
			"woo_search_posttype_record_{$post_type}_objectid",
			$prefix ? "{$prefix}_{$post_id}" : "{$post_type}_{$post_id}",
			array(
				$post_type,
				$post_id,
			)
		);

		$this->data_source_registry = $data_source_registry;

		$post_data_service = new Post_Data_Source( $post );

		$this->data_source_registry->register_data_sources(
			array(
				'post_data_service'    => $post_data_service,
				'categories_service'   => new Category_Data_Source( $post_id ),
				'reading_time_service' => new Reading_Time_Data_Source( $post_data_service->get_word_count() ),
				'tags_service'         => new Tags_Data_Source( $post ),
				'thumbnail_service'    => new Thumbnail_Data_Source( $post ),
			)
		);
	}

	/**
	 * Get the attributes for this record.
	 *
	 * @return array
	 */
	public static function get_attributes(): array {

		$default_attributes = array(
			'wp_id',
			'title',
			'post_date',
			'post_status',
			'is_published',
			'comment_status',
			'post_modified',
			'post_parent',
			'author',
			'excerpt',
			'has_excerpt',
			'categories',
			'tags',
		);

		return $default_attributes;
	}

	/**
	 * Get the attributes available for faceting
	 *
	 * @return array
	 */
	public static function get_attributes_available_for_faceting(): array {

		$default_facets = array(
			'categories.hierarchy',
			'tags.name',
		);

		return $default_facets;
	}

	/**
	 * Get the raw data needed for the Operator
	 *
	 * @return array
	 */
	public function get_data(): array {

		$post_id = gettype( $this->post ) === 'object' ? $this->post->ID : $this->post;

		$post_data_service    = $this->data_source_registry->get( 'post_data_service' );
		$categories_service   = $this->data_source_registry->get( 'categories_service' );
		$tag_service          = $this->data_source_registry->get( 'tags_service' );
		$thumbnail_service    = $this->data_source_registry->get( 'thumbnail_service' );
		$reading_time_service = $this->data_source_registry->get( 'reading_time_service' );

		$post_parent_data = array();
		$post_parent      = $post_data_service->get_post_parent();

		if ( null !== $post_parent ) {
			$post_parent_record = new self( $post_parent, $this->prefix, $this->post_type, $this->data_source_registry );

			$post_parent_data = $post_parent_record->get_data();
		}

		$record_data = array(
			'objectID' => $this->object_id,
			'wp_id'    => $post_id,
		);

		/**
		 * Add the data built by the enabled services.
		 */

		$record_data = array(
			...$record_data,
			'post_data_service'    => $post_data_service->get_data(),
			'categories_service'   => $categories_service->get_data(),
			'tags_service'         => $tag_service->get_data(),
			'thumbnail_service'    => $thumbnail_service->get_data(),
			'reading_time_service' => $reading_time_service->get_data(),
		);

		// Add the post parent data back into the post_data_service.
		$record_data['post_data_service'] = array(
			...$record_data['post_data_service'],
			'post_parent' => $post_parent_data,
		);

		// TODO: Load the enabled services from integrations for Index.

		return $record_data;
	}
}
