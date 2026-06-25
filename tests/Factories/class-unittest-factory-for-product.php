<?php


namespace WooSearch\Tests\Factories;

use WP_UnitTest_Factory_For_Thing;
use WP_UnitTest_Generator_Sequence;
use WC_Product_Simple;


class UnitTest_Factory_For_Product extends WP_UnitTest_Factory_For_Thing {

	public function __construct( $type = 'simple', $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = array(
			'name'   => new WP_UnitTest_Generator_Sequence( 'Product title %s' ),
			'short_description' => new WP_UnitTest_Generator_Sequence( 'Product excerpt %s' ),
			'regular_price'    => 12.99,
			'type'         => $type,
		);
	}

	/**
	 * Creates a post object.
	 *
	 * @since UT (3.7.0)
	 * @since 6.2.0 Returns a WP_Error object on failure.
	 *
	 * @param array $args Array with elements for the post.
	 *
	 * @return int|WP_Error The post ID on success, WP_Error object on failure.
	 */
	public function create_object( $args ) {

		$product = null;

		switch ( $args['type'] ) {
			case 'simple':
				$product = new WC_Product_Simple();
				break;
			case 'external':
				$product = new WC_Product_External();
				break;
			case 'grouped':
					$product = new WC_Product_Grouped();
				break;
			case 'variable':
				$product = new WC_Product_Variable();
				break;

			default:
				throw new Exception( "Product type not supported" );

		}

		$product->set_name( $args['name'] );
		$product->set_regular_price( $args['regular_price'] );
		$product->set_short_description( $args['regular_price'] );

		return $product->save();
	}

	/**
	 * Updates an existing post object.
	 *
	 * @since UT (3.7.0)
	 * @since 6.2.0 Returns a WP_Error object on failure.
	 *
	 * @param int   $post_id ID of the post to update.
	 * @param array $fields  Post data.
	 *
	 * @return int|WP_Error The post ID on success, WP_Error object on failure.
	 */
	public function update_object( $post_id, $fields ) {
		$fields['ID'] = $post_id;
		return wp_update_post( $fields, true );
	}

	/**
	 * Retrieves a post by a given ID.
	 *
	 * @since UT (3.7.0)
	 *
	 * @param int $post_id ID of the post to retrieve.
	 *
	 * @return WP_Post|null WP_Post object on success, null on failure.
	 */
	public function get_object_by_id( $id ) {
		return wc_get_product( $id );
	}
}
