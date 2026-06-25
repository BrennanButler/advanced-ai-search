<?php // phpcs:ignore
/**
 * Tests the Product Category Service
 *
 * @package WooSearch\Tests
 */

namespace WooSearch\Tests;

require_once __DIR__ . '/Factories/class-unittest-factory-for-product.php';

use WooSearch\Records\Services\ACF_Service;
use WooSearch\Records\Services\Meta_Service;
use WooSearch\Records\Services\Product_Category_Service;

use function PHPUnit\Framework\assertTrue;

use WP_UnitTestCase;
use WP_UnitTest_Factory_For_Post;
use WP_UnitTest_Generator_Sequence;

use WP_Term;

/**
 * TestProductCategoryService
 *
 * @group TestACFService
 */
class TestACFService extends WP_UnitTestCase {

	/**
	 * Test Product_Category_Service static method build_tree
	 *
	 * @return void
	 */
	public function testGetDataWithACF() {

		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$factory = new WP_UnitTest_Factory_For_Post();

		$post = $factory->create_and_get();

		acf_add_local_field_group(
			array(
				'key'      => 'group_custom_fields',
				'title'    => 'Custom Fields',
				'fields'   => array(
					array(
						'key'   => 'field_custom_text',
						'label' => 'Custom Text',
						'name'  => 'custom_text_field', // This is the meta_key
						'type'  => 'text',
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'post', // Apply to blog posts
						),
					),
				),
			)
		);

		update_field( 'custom_text_field', 'my-acf-value', $post->ID );

		$acf_service = new ACF_Service( $post );

		$acf_data = $acf_service->get_data();

		error_log( 'here is the acf data' );
		error_log( print_r( $acf_data, true ) );

		assertTrue(
			isset( $acf_data['custom_text_field'] ) && ( 'my-acf-value' === $acf_data['custom_text_field'] )
		);
	}
}
