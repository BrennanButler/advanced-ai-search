<?php
/**
 * Category Data Source.
 *
 * @package WooSearch\RecordModel\DataSource
 */

namespace WooSearch\RecordModel\DataSource;

use WooSearch\Integrations\Record_Service_Integrations_Registry;
use WooSearch\RecordModel\DataSource\Data_Source_Interface;
use WP_Term;

/**
 * Category_Data_Source class.
 */
class Category_Data_Source implements Data_Source_Interface {

	/**
	 * The post id to perform this service on
	 *
	 * @var integer
	 */
	protected int $post_id;

	/**
	 * Constructor.
	 *
	 * @param integer $post_id The post id to perform this service on.
	 */
	public function __construct( int $post_id ) {
		$this->post_id = $post_id;
	}

	/**
	 * Raw data that this service generates.
	 *
	 * @return array
	 */
	public function get_data(): array {
		$categories_ids = wp_get_post_categories(
			$this->post_id
		);

		$categories = array_map(
			function ( $term_id ) {

				$term = get_term_by( 'id', $term_id, 'category' );

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

		if ( $term->parent ) {
			$parent_term = $term->parent;
			$parent_term = get_term_by( 'id', $parent_term, 'category' );

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
			'woo_search_category_tree_hierarchy_str',
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

			$term = get_term_by( 'id', $category, 'category' );

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
			'woo_search_category_hierarchy',
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
				'slug'                => 'category-service',
				'name'                => 'Category Service',
				'description'         => 'Category Service',
				'data_source'         => Category_Data_Source::class,
				'index_type_supports' => array(
					'posttype-index' => array(),
				),
			)
		);
	}
);
