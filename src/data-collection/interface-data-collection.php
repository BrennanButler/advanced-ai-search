<?php

namespace WooSearch\DataCollection;

use WooSearch\CollectionBlueprints\Collection_Blueprint_Interface;

interface Data_Collection_Interface {

	/**
	 * The name of the data collection. This is used to identify the collection and should be unique.
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Get the collection blueprint associated with this data collection.
	 *
	 * @return Collection_Blueprint_Interface
	 */
	public function get_collection_blueprint(): Collection_Blueprint_Interface;

}