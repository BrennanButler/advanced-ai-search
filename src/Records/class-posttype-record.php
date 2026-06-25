<?php
/**
 * Post Type Record
 *
 * @package WooSearch\Records
 */

namespace WooSearch\Records;

use WooSearch\Records\Services\Reading_Time_Service;
use WooSearch\Records\Abstract_Record;

use WooSearch\Records\Record_Interface;
use WooSearch\Records\Services\Category_Service;
use WooSearch\Records\Services\Post_Data_Service;
use WooSearch\Records\Services\Service_Container;
use WooSearch\Records\Services\Tags_Service;
use WooSearch\Records\Services\Thumbnail_Service;
use WP_Post;

/**
 * PostTypeRecord class defines a record type that can handle any core and custom post types
 */
class PostType_Record extends Abstract_Record implements Record_Interface {

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
	 * @var Service_Container
	 */
	protected Service_Container $service_container;

	/**
	 * Undocumented function
	 *
	 * @param WP_Post|integer   $post The WP_Post object or postId to form the record.
	 * @param string|null       $prefix The record prefix.
	 * @param string            $post_type The record post type.
	 * @param Service_Container $service_container The record service container.
	 */
	public function __construct( WP_Post|int $post, string $prefix = null, string $post_type = 'post', Service_Container $service_container = null ) {
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

		$this->service_container = $service_container ? $service_container : new Service_Container();

		$post_data_service = new Post_Data_Service( $post );

		$this->service_container->register_services(
			array(
				'post_data_service'    => $post_data_service,
				'categories_service'   => new Category_Service( $post_id ),
				'reading_time_service' => new Reading_Time_Service( $post_data_service->get_word_count() ),
				'tags_service'         => new Tags_Service( $post ),
				'thumbnail_service'    => new Thumbnail_Service( $post ),
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

		$post_data_service    = $this->service_container->get( 'post_data_service' );
		$categories_service   = $this->service_container->get( 'categories_service' );
		$tag_service          = $this->service_container->get( 'tags_service' );
		$thumbnail_service    = $this->service_container->get( 'thumbnail_service' );
		$reading_time_service = $this->service_container->get( 'reading_time_service' );

		$post_parent_data = array();
		$post_parent      = $post_data_service->get_post_parent();

		if ( null !== $post_parent ) {
			$post_parent_record = new self( $post_parent, $this->prefix, $this->post_type );

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
