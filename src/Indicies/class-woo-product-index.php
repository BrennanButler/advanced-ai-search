<?php
/**
 * Woocommerce Product Index.
 *
 * @package WooSearch\Indicies
 */

namespace WooSearch\Indicies;

use WooSearch\Records\Woo_Product_Record;
use WooSearch\Records\PostTypeRecord;
use WooSearch\Indicies\Abstract_Record_Index;
use WooSearch\Integrations\Index_Type_Integrations_Registry;

/**
 * Woo_Product_Index handles the index abstraction of WooCommerce products.
 */
class Woo_Product_Index extends Abstract_Record_Index {

	/**
	 * Constructor.
	 *
	 * @param string  $name The name of the index.
	 * @param string  $record_prefix The record prefix.
	 * @param boolean $forward_to_replicas Whether to forward the settings onto replica indicies.
	 */
	public function __construct( string $name, string $record_prefix = 'product_', bool $forward_to_replicas = true ) {
		parent::__construct( $name, $record_prefix, true, Woo_Product_Record::class );

		$this->record        = Woo_Product_Record::class;
		$this->record_prefix = $record_prefix;

		// Add replica indicies here.
	}

	/**
	 * Undocumented function
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
			'desc(popularity)',

		);
	}

	/**
	 * Get the searchable attributes for this index
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
		return Woo_Product_Record::class;
	}

	/**
	 * Fetch the records internally for this index.
	 *
	 * @param integer $page The page for search.
	 * @param integer $per_page The amount of records per page.
	 * @return array
	 */
	public function fetch_records( int $page, $per_page = 100 ): array {

		$records = new \WP_Query(
			array(
				'posts_per_page' => $per_page,
				'paged'          => $page,
				'post_type'      => 'product',

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

add_action( 'woo_search_register_index_type_integrations', function ( Index_Type_Integrations_Registry $index_type_registry ) {

	$index_type_registry->register(
		array(
			'slug' => 'woo-index',
			'name' => 'Woocommerce Product',
			'description' => 'Woo product index type description',
			'class' => Woo_Product_Index::class,
			'options' => array(),
		)
	);
});