<?php
/**
 * Thumbnail service
 *
 * @package WooSearch\Records\Services
 */

namespace WooSearch\Records\Services;

use WooSearch\Integrations\Record_Service_Integrations_Registry;
use WP_Post;

/**
 * Thumbnail_Service class.
 */
class Meta_Service {

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

		/**
		 * We want to return the name/key of the field and its value
		 */

		$meta_keys = get_post_custom_keys( $this->post->ID );

		if ( null === $meta_keys ) {
			return array();
		}

		/**
		 * If the ACF plugin is enabled we want to remove this meta data from this output.
		 */
		if ( function_exists( 'get_fields' ) ) {

			$acf_fields = get_fields( $this->post->ID );

			if ( $acf_fields ) {
				foreach ( $acf_fields as $name => $value ) {

					if ( in_array( $name, $meta_keys, true ) ) {
						unset( $meta_keys[ $name ] );
					}
				}
			}
		}

		$data = array();

		foreach ( $meta_keys as $key ) {

			$meta_value = get_post_meta( $this->post->ID, $key, true );

			$data[ $key ] = $meta_value ? $meta_value : null; // Force null.
		}

		return $data;
	}
}

add_action(
	'woo_search_register_record_service_integrations',
	function ( Record_Service_Integrations_Registry $record_service_integrations_registry ) {

		$record_service_integrations_registry->register(
			array(
				'slug'                => 'meta-service',
				'name'                => 'Meta Service',
				'description'         => 'Meta Service',
				'service'             => Meta_Service::class,
				'index_type_supports' => array(
					'posttype-index' => array(),
					'woo-index' => array(),
				),
			)
		);
	}
);
