<?php
/**
 * Thumbnail data source
 *
 * @package WooSearch\RecordModel\DataSource
 */

namespace WooSearch\RecordModel\DataSource;

use WooSearch\Integrations\Record_Service_Integrations_Registry;
use WooSearch\RecordModel\DataSource\Data_Source_Interface;
 
use WP_Post;

/**
 * Thumbnail_Data_Source class.
 */
class Thumbnail_Data_Source implements Data_Source_Interface {

	/**
	 * The WP_Post object for this service.
	 *
	 * @var WP_Post
	 */
	protected WP_Post $post;

	/**
	 * Constructor
	 *
	 * @param WP_Post $post The post for this service.
	 */
	public function __construct( WP_Post $post ) {
		$this->post = $post;
	}

	/**
	 * The raw data for the operator.
	 *
	 * @return array
	 */
	public function get_data(): array {
		$image_sizes = get_intermediate_image_sizes();

		$thumbnail = array();

		foreach ( $image_sizes as $size ) {
			$thumbnail[ $size ] = get_the_post_thumbnail_url( $this->post, $size );
		}

		return $thumbnail;
	}
}

add_action(
	'woo_search_register_record_service_integrations',
	function ( Record_Service_Integrations_Registry $record_service_integrations_registry ) {

		$record_service_integrations_registry->register(
			array(
				'slug'                => 'thumbnail-service',
				'name'                => 'Thumbnail Service',
				'description'         => 'Thumbnail Service',
				'service'             => Thumbnail_Data_Source::class,
				'index_type_supports' => array(
					'posttype-index' => array(),
					'woo-index' => array(),
				),
			)
		);
	}
);
