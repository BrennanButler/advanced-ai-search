<?php
/**
 * Woo Pricing Service
 *
 * @package WooSearch\Records\Services
 */

namespace WooSearch\Records\Services;

use WooSearch\Integrations\Record_Service_Integrations_Registry;

use WC_Product;

/**
 * Woo_Pricing_service class.
 */
class Woo_Pricing_Service {

	/**
	 * The product for this service.
	 *
	 * @var WC_Product
	 */
	protected WC_Product $product;

	/**
	 * Constructor.
	 *
	 * @param WC_Product $product The woo product object.
	 */
	public function __construct( WC_Product $product ) {
		$this->product = $product;
	}

	/**
	 * The raw data for the operator.
	 *
	 * @return array
	 */
	public function get_data(): array {

		return array(
			// We want the pre-processed price.
			'price'         => get_post_meta( $this->product->get_id(), '_price', true ),
			'regular_price' => get_post_meta( $this->product->get_id(), '_regular_price', true ),
			'sale_price'    => get_post_meta( $this->product->get_id(), '_sale_price', true ),
		);
	}
}

add_action(
	'woo_search_register_record_service_integrations',
	function ( Record_Service_Integrations_Registry $record_service_integrations_registry ) {

		$record_service_integrations_registry->register(
			array(
				'slug'                => 'woo-pricing-service',
				'name'                => 'Woo pricing Service',
				'description'         => 'Woo pricing Service',
				'service'             => Woo_Pricing_Service::class,
				'index_type_supports' => array(
					'woo-index' => array(),
				),
			)
		);
	}
);
