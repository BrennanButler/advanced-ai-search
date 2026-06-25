<?php
/**
 * ACF data source.
 *
 * @package WooSearch\RecordModel\DataSource
 */

namespace WooSearch\RecordModel\DataSource;

use WooSearch\RecordModel\DataSource\Data_Source_Interface;

use WooSearch\Integrations\Record_Service_Integrations_Registry;
use WP_Post;

/**
 * ACF_Data_Source class.
 */
class ACF_Data_Source implements Data_Source_Interface {

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

		$acf_fields = \get_fields( $this->post );

		return $acf_fields;
	}
}

add_action( 'woo_search_register_record_service_integrations', function ( Record_Service_Integrations_Registry $record_service_integrations_registry ) {

	$record_service_integrations_registry->register(
		array(
			'slug' => 'acf-record-service',
			'name' => 'ACF Record Service',
			'description' => 'ACF Record Service',
			'data_source' => ACF_Data_Source::class,
			'index_type_supports' => array(
				'posttype-index' => array(),
				'woo-index' => array(),
			),
		)
	);
});

