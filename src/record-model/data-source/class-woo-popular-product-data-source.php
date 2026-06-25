<?php
/**
 * Woo popular product data source.
 *
 * @package WooSearch\RecordModel\DataSource
 */

namespace WooSearch\RecordModel\DataSource;

use WooSearch\Integrations\Record_Service_Integrations_Registry;

/**
 * Woo_Popular_Product_Data_Source class.
 */
class Woo_Popular_Product_Data_Source implements Data_Source_Interface {

	/**
	 * The product id
	 *
	 * @var integer
	 */
	protected int $product_id;

	/**
	 * The constructor.
	 *
	 * @param integer $product_id The product id.
	 */
	public function __construct( int $product_id ) {
		$this->product_id = $product_id;
	}

	/**
	 * Get the raw data for the operator.
	 *
	 * @return array
	 */
	public function get_data(): array {
		global $wpdb;

		// Query to get the total quantity ordered for the given product ID.

		$prepared_statement = $wpdb->prepare(
			"
            SELECT SUM(oim_qty.meta_value) 
            FROM {$wpdb->prefix}woocommerce_order_items AS oi
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS oim_id
                ON oi.order_item_id = oim_id.order_item_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS oim_qty
                ON oi.order_item_id = oim_qty.order_item_id
            WHERE oim_id.meta_key = '_product_id' 
                AND oim_id.meta_value = %d
                AND oim_qty.meta_key = '_qty'
                AND oi.order_item_type = 'line_item'
        	",
			$this->product_id,
		);

		// Get the total quantity ordered for this product ID.
		$total_quantity = $wpdb->get_var( $prepared_statement ); // phpcs:ignore

		return array(
			'count' =>
			/**
			 * Allow developers to filter the product popularity number provided by this service.
			 *
			 * @since 1.0.0
			 */
			apply_filters(
				'woo_search_popular_product_service_product_popularity',
				(int) $total_quantity,
				$this->product_id
			),
		);
	}
}

add_action(
	'woo_search_register_record_service_integrations',
	function ( Record_Service_Integrations_Registry $record_service_integrations_registry ) {

		$record_service_integrations_registry->register(
			array(
				'slug'                => 'woo-product-service',
				'name'                => 'Woo product Service',
				'description'         => 'Woo product Service',
				'service'             => Woo_Popular_Product_Data_Source::class,
				'index_type_supports' => array(
					'woo-index' => array(),
				),
			)
		);
	}
);
