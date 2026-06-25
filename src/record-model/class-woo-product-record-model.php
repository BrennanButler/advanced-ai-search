<?php
/**
 * Woo Product Record
 *
 * @package WooSearch\RecordModel
 */

namespace WooSearch\RecordModel;

use WooSearch\RecordModel\DataSource\Woo_Popular_Product_Data_Source;
use WooSearch\RecordModel\PostType_Record_Model;

use WooSearch\RecordModel\Record_Model_Interface;

use WooSearch\RecordModel\DataSource\Data_Source_Registry;
use WooSearch\RecordModel\DataSource\Woo_Pricing_Data_Source;
use WP_Post;

/**
 * Woo_Product_Record_Model class.
 */
class Woo_Product_Record_Model extends PostType_Record_Model implements Record_Model_Interface {

	/**
	 * Constructor.
	 *
	 * @param WP_Post|int $post The WP_Post or post Id.
	 * @param string      $prefix The record prefix.
	 */
	public function __construct( WP_Post|int $post, string $prefix = null, Data_Source_Registry $data_source_registry ) {
		parent::__construct( $post, $prefix, 'product', $data_source_registry );

		$post_id = gettype( $this->post ) === 'object' ? $this->post->ID : $this->post;
		$post    = gettype( $this->post ) === 'object' ? $this->post : get_post( $this->post );

		$product = \wc_get_product( $post_id );

		$this->data_source_registry->register_data_sources(
			array(
				'woo_pricing_service' => new Woo_Pricing_Data_Source( $product ),
			)
		);

		$this->data_source_registry->register_data_sources(
			array(
				'woo_popular_product_service' => new Woo_Popular_Product_Data_Source( $post_id ),
			)
		);
	}

	/**
	 * Get the attributes for this record.
	 *
	 * @return array
	 */
	public static function getAttributes(): array {
		$post_type_attributes = PostType_Record_Model::get_attributes();

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
		$post_type_attribute_facets = PostType_Record_Model::get_attributes_available_for_faceting();

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

		$pricing_service         = $this->data_source_registry->get( 'woo_pricing_service' );
		$popular_product_service = $this->data_source_registry->get( 'woo_popular_product_service' );

		$data = parent::get_data();

		$data['woo_pricing_service']         = $pricing_service->get_data();
		$data['woo_popular_product_service'] = $popular_product_service->get_data();

		return $data;
	}
}
