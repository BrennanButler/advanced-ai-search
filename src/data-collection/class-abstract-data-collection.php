<?php

namespace WooSearch\DataCollection;

use WooSearch\CollectionBlueprints\Collection_Blueprint_Interface;

use WooSearch\DataCollection\Data_Collection_Interface;

class Abstract_Data_Collection implements Data_Collection_Interface {

	/**
	 * The name of the data collection. This is used to identify the collection and should be unique.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The collection blueprint associated with this data collection.
	 *
	 * @var Collection_Blueprint_Interface
	 */
	protected $collection_blueprint;

	protected $collection_settings;

	/**
	 * Constructor for the abstract data collection.
	 *
	 * @param string $name The name of the data collection.
	 * @param Collection_Blueprint_Interface $collection_blueprint The collection blueprint associated with this data collection.
	 * @param array $collection_settings The settings for the data collection.
	 */
	public function __construct( string $name, Collection_Blueprint_Interface $collection_blueprint, $collection_settings = array() ) {
		$this->name = $name;
		$this->collection_blueprint = $collection_blueprint;
		$this->collection_settings = $collection_settings;
	}

	/**
	 * Get the name of the data collection.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Get the collection blueprint associated with this data collection.
	 *
	 * @return Collection_Blueprint_Interface
	 */
	public function get_collection_blueprint(): Collection_Blueprint_Interface {
		return $this->collection_blueprint;
	}
}