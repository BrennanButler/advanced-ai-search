<?php // phpcs:ignore
/**
 * Tests the Product Category Service
 *
 * @package WooSearch\Tests
 */

namespace WooSearch\Tests;

require_once __DIR__ . '/Factories/class-unittest-factory-for-product.php';

use WooSearch\Records\Services\Product_Category_Service;
use WooSearch\Tests\Factories\UnitTest_Factory_For_Product;

use function PHPUnit\Framework\assertTrue;

use WP_UnitTestCase;
use WP_UnitTest_Factory_For_Term;

use WP_Term;

/**
 * TestProductCategoryService
 *
 * @group TestProductCategoryService
 */
class TestProductCategoryService extends WP_UnitTestCase {

	/**
	 * Test Product_Category_Service get_data method
	 *
	 * @return void
	 */
	public function testGetData() {

		$factory = new UnitTest_Factory_For_Product();

		$products = $factory->create_many( 1 );

		$category_service = new Product_Category_Service( $products[0] );

		$category_data = $category_service->get_data();

		assertTrue(
			isset( $category_data['hierarchy'] ) && isset( $category_data['hierarchy'] )
		);
	}

	/**
	 * Test Product_Category_Service static method build_tree
	 *
	 * @return void
	 */
	public function testBuildTreeOneLevel() {
		$factory = self::factory()->term;

		$first_category = $factory->create_and_get(
			array(
				'name'     => 'category1',
				'parent'   => 0,
				'taxonomy' => 'product_cat',
			),
		);

		$tree = Product_Category_Service::build_tree( $first_category );

		assertTrue( 'category1' === $tree );
	}

	/**
	 * Test Product_Category_Service build_tree with two levels at the root node
	 *
	 * @return void
	 */
	public function testBuildTreeTwoLevelAtRoot() {
		$factory = self::factory()->term;

		$first_category = $factory->create_and_get(
			array(
				'name'     => 'category1',
				'parent'   => 0,
				'taxonomy' => 'product_cat',
			),
		);

		$second_category = $factory->create_and_get(
			array(
				'name'     => 'category2',
				'parent'   => $first_category->term_id,
				'taxonomy' => 'product_cat',
			)
		);

		$tree = Product_Category_Service::build_tree( $first_category );

		assertTrue( 'category1' === $tree );
	}

	/**
	 * Test Product_Category_Service build_tree with two levels at the second level node
	 *
	 * @return void
	 */
	public function testBuildTreeTwoLevelAtLevelTwo() {

		$factory = self::factory()->term;

		$first_category = $factory->create_and_get(
			array(
				'name'     => 'cat1',
				'parent'   => 0,
				'taxonomy' => 'product_cat',
			),
		);

		$second_category = $factory->create_and_get(
			array(
				'name'     => 'cat2',
				'parent'   => $first_category->term_id,
				'taxonomy' => 'product_cat',
			),
		);

		$tree = Product_Category_Service::build_tree( $second_category );

		$expected_string = $first_category->name . ' > ' . $second_category->name;

		assertTrue( $expected_string === $tree );
	}

	/**
	 * Test Product_Category_Service build_category_hierarchy method
	 *
	 * @return void
	 */
	public function testBuildCategoryHierarchy() {

		$factory = new WP_UnitTest_Factory_For_Term( $this, 'product_cat' );

		$first_category = $factory->create_and_get();

		$second_category = $factory->create_and_get(
			array(
				'parent' => $first_category->term_id,
			)
		);

		$hierarchy = Product_Category_Service::build_category_hierarchy( array( $first_category->term_id, $second_category->term_id ) );

		$expected_second_level = $first_category->name . ' > ' . $second_category->name;

		assertTrue(
			$hierarchy['lvl0'] === $first_category->name &&
			$hierarchy['lvl1'] === $expected_second_level,
		);
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testHierarchyStringFilter() {

		add_filter(
			'woo_search_product_category_tree_hierarchy_str',
			function ( $string, $hierarchy, WP_Term $term ) {
				return $hierarchy . ' | ' . $term->name;
			},
			10,
			3
		);

		$factory = self::factory()->term;

		$first_category = $factory->create_and_get(
			array(
				'name'     => 'cat1',
				'parent'   => 0,
				'taxonomy' => 'product_cat',
			),
		);

		$second_category = $factory->create_and_get(
			array(
				'name'     => 'cat2',
				'parent'   => $first_category->term_id,
				'taxonomy' => 'product_cat',
			),
		);

		$tree = Product_Category_Service::build_tree( $second_category );

		$expected_string = $first_category->name . ' | ' . $second_category->name;

		assertTrue( $expected_string === $tree );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testCategoryHierarchyFilter() {

		$test_string = 'test_filtered_lvl1';

		add_filter(
			'woo_search_product_category_hierarchy',
			function ( $tree, $categories ) use ( $test_string ) {
				$tree['lvl0'] = $test_string;

				return $tree;
			},
			10,
			3
		);

		$factory = self::factory()->term;

		$first_category = $factory->create_and_get(
			array(
				'name'     => 'cat1',
				'parent'   => 0,
				'taxonomy' => 'product_cat',
			),
		);

		$second_category = $factory->create_and_get(
			array(
				'name'     => 'cat2',
				'parent'   => $first_category->term_id,
				'taxonomy' => 'product_cat',
			),
		);

		$tree = Product_Category_Service::build_category_hierarchy(
			array(
				$first_category->term_id,
				$second_category->term_id,
			)
		);

		assertTrue( $test_string === $tree['lvl0'] );
	}
}
