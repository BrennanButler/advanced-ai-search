<?php

namespace WooSearch\DataCollection\Storage;

use WooSearch\DataCollection\Storage\Collection_Storage_Interface;
use WooSearch\DataCollection\Data_Collection_Interface;
use WooSearch\CollectionBlueprints\Collection_Blueprint_Factory;
use WooSearch\DataCollection\Abstract_Data_Collection;

use Exception;

class WP_Collection_Storage implements Collection_Storage_Interface {

	protected $wpdb;

	public function __construct( $wpdb ) {
		$this->wpdb = $wpdb;
	}

	/**
	 * Save a data collection.
	 *
	 * @param Data_Collection_Interface $collection The data collection to save.
	 * @return int The ID of the saved data collection.
	 */
	public function save_collection( Data_Collection_Interface $collection ): int {
		
		$this->wpdb->insert(
			'wp_data_collections',
			[
				'name' => $collection->get_name(),
				'blueprint' => $collection->get_collection_blueprint()->get_slug(),
			]
		);

		return $this->wpdb->insert_id;
	}

	/**
	 * Get a data collection by its ID.
	 *
	 * @param int $id The ID of the data collection to retrieve.
	 * @return Data_Collection_Interface|null The data collection if found, null otherwise.
	 */
	public function get_collection( int $id ): ?Data_Collection_Interface {
		$result = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM wp_data_collections WHERE id = %d", $id ) );

		if ( ! $result ) {
			return null;
		}

		$blueprint = Collection_Blueprint_Factory::create_collection_blueprint( $result->blueprint );

		return new Abstract_Data_Collection(
			$result->name,
			$blueprint
		);
	}

	/**
	 * Get all data collections.
	 *
	 * @return array The list of all data collections.
	 */
	public function get_all_collections(): array {
		$results = $this->wpdb->get_results( "SELECT * FROM wp_data_collections" );

		return array_map(
			function ( $result ) {
				$blueprint = Collection_Blueprint_Factory::create_collection_blueprint( $result->blueprint );

				return new Abstract_Data_Collection(
					$result->name,
					$blueprint
				);
			},
			$results
		);
	}
}