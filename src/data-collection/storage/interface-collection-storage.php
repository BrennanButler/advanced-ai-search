<?php

namespace WooSearch\DataCollection\Storage;

use WooSearch\DataCollection\Data_Collection_Interface;

use Exception;

interface Collection_Storage_Interface {

	/**
	 * Save a data collection.
	 *
	 * @param Data_Collection_Interface $collection The data collection to save.
	 * @return int The ID of the saved data collection.
	 */
	public function save_collection( Data_Collection_Interface $collection ): int;

	/**
	 * Get a data collection by its ID.
	 *
	 * @param int $id The ID of the data collection to retrieve.
	 * @return Data_Collection_Interface|null The data collection if found, null otherwise.
	 */
	public function get_collection( int $id ): ?Data_Collection_Interface;

	/**
	 * Get all data collections.
	 *
	 * @return array The list of all data collections.
	 */
	public function get_all_collections(): array;
}