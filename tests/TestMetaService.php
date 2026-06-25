<?php // phpcs:ignore
/**
 * Tests the Product Category Service
 *
 * @package WooSearch\Tests
 */

namespace WooSearch\Tests;

require_once __DIR__ . '/Factories/class-unittest-factory-for-product.php';

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
 * @group TestProductCategoryService
 */
class TestMetaService extends WP_UnitTestCase {

	/**
	 * Test Product_Category_Service static method build_tree
	 *
	 * @return void
	 */
	public function testGetDataWithMeta() {

		$factory = new WP_UnitTest_Factory_For_Post();

		$post = $factory->create_and_get(
			array(
				'post_status' => 'publish',
				'post_title' => 'Post title',
				'post_content' => 'Post content',
				'post_excerpt' => 'Post content',
				'post_type' => 'post',
				'meta_input' => array(
					'test_meta_key' => 'test_meta_value',
				),
			)
		);

		$meta_service = new Meta_Service( $post );

		$meta_data = $meta_service->get_data();

		error_log("here is the post meta data");
		error_log( print_r($meta_data, true));

		assertTrue(
			isset( $meta_data['test_meta_key'] ) && ( 'test_meta_value' === $meta_data['test_meta_key'] )
		);
	}
}
