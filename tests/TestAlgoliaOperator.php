<?php // phpcs:ignore
/**
 * Tests the Algolia operator and its operations upon Algolia indexes
 *
 * @file
 * @package WooSearch\Tests
 */

namespace WooSearch\Tests;

use Exception;
use WP_UnitTestCase;
use WooSearch\Engine\Operators\Algolia\Algolia_Operator;
use WooSearch\Indicies\Abstract_Record_Index;
use WooSearch\Indicies\PostType_Index;
use WooSearch\Indicies\Woo_Product_Index;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

/**
 * TestAlgoliaOperator
 *
 * @group TestAlgoliaOperator
 */
class TestAlgoliaOperator extends WP_UnitTestCase {

	/**
	 * Setup method
	 *
	 * @return void
	 */
	public function set_up(): void {
		Util::set_up();
	}

	/**
	 * Tear down method
	 *
	 * @return void
	 */
	public function tear_down(): void {
		Util::tear_down();
	}

	/**
	 * Test creating an instance of the Algolia_Operator class.
	 *
	 * @return void
	 * @covers WooSearch\Engine\Operators\Algolia\Algolia_Operator::get_instance
	 */
	public function testGetInstance() {
		$instance = Algolia_Operator::get_instance();
		assertInstanceOf( Algolia_Operator::class, $instance );
	}

	/**
	 * Test initialising an Algolia index with WordPress Posts.
	 *
	 * @return void
	 */
	public function testInitIndexWithWPPosts() {

		$instance = Algolia_Operator::get_instance();

		$test_index_name     = 'test_index';
		$index_prefix        = 'post';
		$post_type           = 'post';
		$forward_to_replicas = true;

		$index = new PostType_Index( $test_index_name, $index_prefix, $post_type, $forward_to_replicas );

		$instance->init_index( $index );

		$index_record_count = count( $instance->get_records( $index ) );

		assertTrue( Util::$totalTestPosts === $index_record_count );
	}

	/**
	 * Test initialising an Algolia index with Woocommerce Products.
	 *
	 * @return void
	 */
	public function testInitIndexWithWooProducts() {

		$instance = Algolia_Operator::get_instance();

		$test_index_name     = 'test_woo_product_index';
		$index_prefix        = 'product';
		$forward_to_replicas = true;

		$index = new Woo_Product_Index( $test_index_name, $index_prefix, $forward_to_replicas );

		$instance->init_index( $index );

		$index_record_count = count( $instance->get_records( $index ) );

		assertTrue( Util::$totalTestProducts === $index_record_count );
	}

	/**
	 * Test removing an index from Algolia
	 *
	 * @return void
	 */
	public function testRemoveIndex() {

		$instance = Algolia_Operator::get_instance();

		$test_index_name = 'test_woo_product_index';

		$instance->remove_index( $test_index_name );

		$no_results = false;

		try {

			$record = $instance->get_records( $test_index_name );

		} catch ( Exception $e ) {
			$no_results = true;
		}

		assertTrue( $no_results );
	}

	/**
	 * Test clearing (delete all records) from an index on Algolia
	 *
	 * @return void
	 */
	public function testClearIndex() {

		$instance = Algolia_Operator::get_instance();

		$test_index_name = 'test_index';

		$instance->clear_records( $test_index_name );

		$record_count = count( $instance->get_records( $test_index_name ) );

		assertTrue( 0 === $record_count );
	}

	/**
	 * Test saving records to an Algolia index.
	 *
	 * @return void
	 */
	public function testSaveRecords() {

		$instance = Algolia_Operator::get_instance();

		$test_index_name = 'test_index';

		$instance->clear_records( $test_index_name );

		$index = new PostType_Index( $test_index_name, 'post', 'post', true );

		$instance->save_records( $index, $index->fetch_records( 1 ) );

		$record_count = count( $instance->get_records( $test_index_name ) );

		assertTrue( Util::$totalTestPosts === $record_count );
	}

	/**
	 * Test delete one record
	 *
	 * @return void
	 */
	public function testDeleteRecordsOne() {

		$instance = Algolia_Operator::get_instance();

		$test_index_name = 'test_index';

		$index = new PostType_Index( $test_index_name, 'post', 'post', true );

		$instance->clear_records( $test_index_name );

		$instance->save_records( $index, $index->fetch_records( 1 ) );

		$record = $index->fetch_records( 1 )[0];

		$instance->delete_records(
			$index,
			array(
				$record->get_object_id(),
			)
		);

		$record_count = count( $instance->get_records( $test_index_name ) );

		assertTrue( ( Util::$totalTestPosts - 1 ) === $record_count );
	}

	/**
	 * Test delete many records.
	 *
	 * @return void
	 */
	public function testDeleteRecordsMany() {

		$instance = Algolia_Operator::get_instance();

		$test_index_name = 'test_index';

		$index = new PostType_Index( $test_index_name, 'post', 'post', true );

		$instance->clear_records( $test_index_name );

		$instance->save_records( $index, $index->fetch_records( 1 ) );

		$records = $index->fetch_records( 1 );

		$instance->delete_records(
			$index,
			array_map(
				function ( $record ) {
					return $record->get_object_id();
				},
				$records
			)
		);

		$record_count = count( $instance->get_records( $test_index_name ) );

		assertTrue( 0 === $record_count );
	}
}
