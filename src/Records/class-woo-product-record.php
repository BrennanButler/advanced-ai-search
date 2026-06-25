<?php
/**
 * Woo Product Record
 *
 * @package WooSearch\Records
 */

namespace WooSearch\Records;

use WooSearch\Records\Services\Woo_Popular_Product_Service;
use WooSearch\Records\PostType_Record;
use WooSearch\Records\Record_Interface;
use WooSearch\Records\Services\Service_Container;
use WooSearch\Records\Services\Woo_Pricing_Service;
use WP_Post;

/**
 * Woo_Product_Record class.
 */
class Woo_Product_Record extends PostType_Record implements Record_Interface {

	/**
	 * Constructor.
	 *
	 * @param WP_Post|int $post The WP_Post or post Id.
	 * @param string      $prefix The record prefix.
	 */
	public function __construct( WP_Post|int $post, string $prefix = null, Service_Container $service_container = null ) {
		parent::__construct( $post, $prefix, 'product', $service_container );

		$post_id = gettype( $this->post ) === 'object' ? $this->post->ID : $this->post;
		$post    = gettype( $this->post ) === 'object' ? $this->post : get_post( $this->post );

		$product = wc_get_product( $post_id );

		$this->service_container->register(
			'woo_pricing_service',
			new Woo_Pricing_Service( $product )
		);

		$this->service_container->register(
			'woo_popular_product_service',
			new Woo_Popular_Product_Service( $post_id )
		);
	}

	/**
	 * Get the attributes for this record.
	 *
	 * @return array
	 */
	public static function getAttributes(): array {
		$post_type_attributes = PostType_Record::get_attributes();

		return array(
			...$post_type_attributes,
			'pricing',
			'popularity',
		);
	}

	/**
	 * Get attributes available for facceting
	 *
	 * @return array
	 */
	public static function get_attributes_available_for_faceting(): array {
		$post_type_attribute_facets = PostType_Record::get_attributes_available_for_faceting();

		return array(
			...$post_type_attribute_facets,
			'pricing',
			'popularity',
		);
	}

	/**
	 * Get the raw data for the operator.
	 *
	 * @return array
	 */
	public function get_data(): array {

		$pricing_service         = $this->service_container->get( 'woo_pricing_service' );
		$popular_product_service = $this->service_container->get( 'woo_popular_product_service' );

		$data = parent::get_data();

		$data['woo_pricing_service']         = $pricing_service->get_data();
		$data['woo_popular_product_service'] = $popular_product_service->get_data();

		return $data;
	}
}
