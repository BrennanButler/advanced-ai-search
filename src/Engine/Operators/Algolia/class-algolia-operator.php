<?php
/**
 * Algolia operator that manages Indicies and their records.
 *
 * @package WooSearch\Engine\Operators\Algolia
 */

namespace WooSearch\Engine\Operators\Algolia;

use WooSearch\Engine\Operators\Abstract_Operator;
use WooSearch\Engine\Operators\Operator_Interface;
use WooSearch\Indicies\Record_Index_Interface;
use Exception;

use Algolia\AlgoliaSearch\Api\SearchClient;

use WooSearch\Logging\Logger;

/**
 * Algolia operator manages Indicies and their records using Algolia's platform.
 */
class Algolia_Operator extends Abstract_Operator implements Operator_Interface {

	/**
	 * The name of the operator.
	 */
	public const NAME = 'AlgoliaOperator';

	/**
	 * A reference to an instance of this singleton object.
	 *
	 * @var Operator_Interface
	 */
	private static $instance;

	/**
	 * An Algolia SearchClient instance.
	 *
	 * @var SearchClient
	 */
	private SearchClient $algolia;

	/**
	 * Private constructor.
	 */
	private function __construct() {
		// Dissallow direction construction.
	}

	/**
	 * Get an instance of AlgoliaOperator (one allowed only).
	 *
	 * @return Operator_Interface
	 */
	public static function get_instance(): Operator_Interface {
		if ( null === self::$instance ) {
			self::$instance = new self();

			$write_key = get_option( 'algolia-write-key' );
			$app_id    = get_option( 'algolia-application-id' );

			error_log( 'here is the algolia credentials' );
			error_log( $write_key );
			error_log( $app_id );

			self::$instance->algolia = SearchClient::create( $app_id, $write_key );
		}

		return self::$instance;
	}

	/**
	 * Get the name of the operator
	 *
	 * @return string
	 */
	public function get_name(): string {
		return self::NAME;
	}

	/**
	 * Initialize a Record Index.
	 *
	 * @param Record_Index_Interface $index The Record Index to be initialized.
	 * @return void
	 * @throws Exception Throws exception upon failure of configuring the Index with Algolia.
	 */
	public function init_index( Record_Index_Interface $index ) {

		parent::init_index( $index );

		$replicas = $index->get_replicas();

		if ( count( $replicas ) > 0 ) {

			foreach ( $replicas as $replica ) {

				$response = $this->algolia->setSettings(
					$index->get_name(),
					array(
						'ranking' => $index->get_ranking(),
					)
				);

				Logger::debugLog(
					print_r( $response, true ),
					'Attempting to init the replica index ' . $index
				);
			}

			$replicas = array_map(
				function ( $replica ) {
					return $replica->getName();
				},
				$index->get_replicas()
			);
		}

		$this->clear_records( $index );

		$response = $this->algolia->setSettings(
			$index->get_name(),
			array(
				'attributesForFaceting' => $index->get_attributes_for_faceting(),
				'searchableAttributes'  => $index->get_searchable_attributes(),
				'replicas'              => $replicas,
				'ranking'               => $index->get_ranking(),
				'queryType'             => 'prefixAll',
				'typoTolerance'         => 'min',
			),
			true
		);

		if ( ! isset( $response['updatedAt'] ) || ! isset( $response['taskID'] ) ) {
			throw new Exception( 'Algolia was unable to initialise your index ' . $index->get_name() ); // phpcs:ignore
		}

		$this->algolia->waitForTask(
			$index->get_name(),
			$response['taskID']
		);

		$page = 1;

		do {

			$records = $index->fetch_records( $page );

			if ( count( $records ) < 1 ) {
				break;
			}

			++$page;

			$this->save_records( $index, $records );

		} while ( true );

		Logger::debugLog(
			print_r( $response, true ),
			'Attempting to init the index ' . $index->get_name()
		);
	}

	/**
	 * Remove an index from Algolia.
	 *
	 * @param Record_Index_Interface|string $index The index to remove. Either a RecordIndexInterface object or the name of the index.
	 * @return void
	 * @throws Exception When an index name provided is invalid.
	 */
	public function remove_index( Record_Index_Interface|string $index ) {

		if ( $index instanceof Record_Index_Interface ) {

			$response = $this->algolia->deleteIndex(
				$index->get_name()
			);

			Logger::debugLog(
				print_r( $response, true ),
				'Attempting to remove index ' . $index
			);

			$this->algolia->waitForTask(
				$index->get_name(),
				$response['taskID']
			);

		} elseif ( 'string' === gettype( $index ) ) {

			// Perform some validation.
			if ( str_contains( $index, ' ' ) ) {
				throw new Exception( 'Index name must not contain any spaces' );
			}

			$response = $this->algolia->deleteIndex(
				$index
			);

			Logger::debugLog(
				print_r( $response, true ),
				'Attempting to remove index ' . $index
			);
			$this->algolia->waitForTask(
				$index,
				$response['taskID']
			);

		}
	}

	/**
	 * Save records to an index.
	 *
	 * @param Record_Index_Interface $index The index you want to save records to.
	 * @param array                  $records An array of records and their attributes to save.
	 * @return void
	 */
	public function save_records( Record_Index_Interface $index, array $records ) {

		foreach ( $records as $record ) {
			$response = $this->algolia->addOrUpdateObject(
				$index->get_name(),
				$record->get_object_id(),
				$record->get_data()
			);

			$this->algolia->waitForTask(
				$index->get_name(),
				$response['taskID']
			);
		}
	}

	/**
	 * Delete records from an index
	 *
	 * @param Record_Index_Interface $index The index to delete records from.
	 * @param array                  $records The records to delete.
	 * @return void
	 */
	public function delete_records( Record_Index_Interface $index, array $records ) {

		// Create a batch request for multiple deletions.
		$response = $this->algolia->batch(
			$index->get_name(),
			array(
				'requests' => array_map(
					function ( $object_id ) {
						return array(
							'action' => 'deleteObject',
							'body'   => array(
								'objectID' => $object_id,
							),
						);
					},
					$records
				),
			)
		);

		Logger::debugLog(
			print_r( $response, true ),
			'Attempting to remove specified records from string index ' . $index->get_name()
		);

		$this->algolia->waitForTask(
			$index->get_name(),
			$response['taskID']
		);
	}

	/**
	 * Get records from an index.
	 *
	 * @param Record_Index_Interface|string $index The index to retrieve records from.
	 * @return array
	 * @throws Exception When algolia is unable to provide records.
	 */
	public function get_records( Record_Index_Interface|string $index ): array {

		if ( $index instanceof Record_Index_Interface ) {
			$objects = $this->algolia->browse(
				$index->get_name()
			);
		} else {
			$objects = $this->algolia->browse(
				$index
			);
		}

		if ( ! isset( $objects['hits'] ) ) {
			throw new Exception( 'There was an error retrieving objects from Algolia - ' . esc_html( $objects['message'] ) );
		}

		error_log( 'here are the hits' );
		error_log( print_r( $objects['hits'], true ) );

		return $objects['hits'];
	}

	/**
	 * Clear (empty) an index of records.
	 *
	 * @param Record_Index_Interface|string $index The index to clear.
	 * @return void
	 * @throws Exception When an invalid index name is provided.
	 */
	public function clear_records( Record_Index_Interface|string $index ) {

		if ( $index instanceof Record_Index_Interface ) {

			$response = $this->algolia->clearObjects(
				$index->get_name()
			);

			$this->algolia->waitForTask(
				$index->get_name(),
				$response['taskID']
			);

			Logger::debugLog(
				print_r( $response, true ),
				'Attempting to remove all records from index ' . $index->get_name()
			);
		} elseif ( gettype( $index ) === 'string' ) {

			// Perform some validation.
			if ( str_contains( $index, ' ' ) ) {
				throw new Exception( 'Index name must not contain any spaces' );
			}

			$response = $this->algolia->clearObjects(
				$index
			);

			$this->algolia->waitForTask(
				$index,
				$response['taskID']
			);

			Logger::debugLog(
				print_r( $response, true ),
				'Attempting to remove all records from string index ' . $index
			);
		}
	}
}
