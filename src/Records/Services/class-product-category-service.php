<?php
/**
 * Category Service.
 *
 * @package WooSearch\Records\Services
 */

namespace WooSearch\Records\Services;

use WooSearch\Integrations\Record_Service_Integrations_Registry;

use Exception;
use WP_Term;

/**
 * Category_Service class.
 */
class Product_Category_Service implements Record_Service_Interface {

	/**
	 * The post id to perform this service on
	 *
	 * @var WC_Product
	 */
	protected $product_id;

	/**
	 * Constructor.
	 *
	 * @param WC_Product $product The post id to perform this service on.
	 */
	public function __construct( $product_id ) {
		$this->product_id = $product_id;
	}

	/**
	 * Raw data that this service generates.
	 *
	 * @return array
	 */
	public function get_data(): array {

		$product = wc_get_product( $this->product_id );

		$categories_ids = $product->get_category_ids();

		$categories = array_map(
			function ( $term_id ) {

				$term = get_term_by( 'id', $term_id, 'product_cat' );

				return array(
					'term_id'     => $term->term_id,
					'name'        => $term->name,
					'description' => $term->description,
					'parent'      => $term->parent,
					'count'       => $term->count,
				);
			},
			$categories_ids
		);

		$categories_hierarchy = self::build_category_hierarchy( $categories_ids );

		$categories_data = array(
			'hierarchy' => $categories_hierarchy,
			'data'      => $categories,
		);

		return $categories_data;
	}

	/**
	 * Build a tree from WP_Terms
	 *
	 * @param WP_Term $term The term to build a tree from.
	 * @param string  $hierarchy_str The hierarchy string.
	 * @return string
	 */
	public static function build_tree( WP_Term $term, string $hierarchy_str = '' ): string {

		if ( $term->parent > 0 ) {
			$parent_term = $term->parent;
			$parent_term = get_term_by( 'id', $parent_term, 'product_cat' );

			if ( ! $parent_term ) {
				throw new Exception( 'Either the taxonomy or term_id is invalid and build_tree failed due to this.' );
			}

			$hierarchy_str = self::build_tree( $parent_term, $hierarchy_str );
		}

		if ( '' === $hierarchy_str ) {
			return $term->name;
		}

		/**
		 * Allow developers to decide how the hierarchy_str looks.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_product_category_tree_hierarchy_str',
			$hierarchy_str . ' > ' . $term->name,
			$hierarchy_str,
			$term
		);
	}

	/**
	 * Build a category hierarchy
	 *
	 * @param array $categories An array of category IDs.
	 * @return array
	 */
	public static function build_category_hierarchy( array $categories ): array {
		$tree = array();

		foreach ( $categories as $category ) {

			$term = get_term_by( 'id', $category, 'product_cat' );

			if ( $term->parent ) {
				$sub_cats = self::build_tree( $term );

				$lvl = count( explode( ' > ', $sub_cats ) ) - 1;

				$key = 'lvl' . $lvl;

				if ( isset( $tree[ $key ] ) ) {

					if ( ! is_array( $tree[ $key ] ) ) {
						$current_value = $tree[ $key ];

						$tree[ $key ]   = array();
						$tree[ $key ][] = $current_value;
					}

					$tree[ $key ][] = $sub_cats;
				} else {
					$tree[ $key ] = $sub_cats;
				}
			} elseif ( isset( $tree['lvl0'] ) ) {

				if ( ! is_array( $tree['lvl0'] ) ) {
					$current_value = $tree['lvl0'];

					$tree['lvl0']   = array();
					$tree['lvl0'][] = $current_value;
				}

					$tree['lvl0'][] = $term->name;
			} else {
				$tree['lvl0'] = $term->name;
			}
		}

		asort( $tree );

		/**
		 * Allow developers to filter the category hierarchy tree (array)
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_product_category_hierarchy',
			$tree,
			$categories
		);
	}
}

add_action(
	'woo_search_register_record_service_integrations',
	function ( Record_Service_Integrations_Registry $record_service_integrations_registry ) {

		$record_service_integrations_registry->register(
			array(
				'slug'                => 'product-category-service',
				'name'                => 'Product Category Service',
				'description'         => 'Product Category Service',
				'service'             => Product_Category_Service::class,
				'index_type_supports' => array(
					'woo-index' => array(),
				),
			)
		);
	}
);
