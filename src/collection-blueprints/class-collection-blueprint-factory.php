<?php

namespace WooSearch\CollectionBlueprints;

use WooSearch\WooSearch;

use WooSearch\CollectionBlueprints\Collection_Blueprint_Interface;

use Exception;

class Collection_Blueprint_Factory {

	/**
	 * Create a collection blueprint instance based on the provided slug.
	 *
	 * @param string $slug The slug of the collection blueprint to create.
	 *
	 * @return Collection_Blueprint_Interface|Exception The created collection blueprint instance or an exception if the slug is not registered.
	 */
	public static function create_collection_blueprint( string $slug ): Collection_Blueprint_Interface {
		$integration_manager = WooSearch::get_instance()->get_integration_manager();

		$collection_blueprint_class = $integration_manager->get_collection_blueprint_registry()->get( $slug );

		return $collection_blueprint_class;
	}
}