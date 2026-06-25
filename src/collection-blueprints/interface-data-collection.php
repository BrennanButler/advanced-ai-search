<?php
/**
 * Record index interface.
 *
 * @package WooSearch\DataCollections
 */

namespace WooSearch\CollectionBlueprints;

interface Collection_Blueprint_Interface {

	/**
	 * Get the name of the collection blueprint.
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Get the prefix used on all records in this collection blueprint.
	 *
	 * @return string
	 */
	public function get_record_prefix(): string;

	/**
	 * Get an array of attributes that will be used for faceting.
	 *
	 * @return array
	 */
	public function get_attributes_for_faceting(): array;

	/**
	 * Whether the settings of this collection blueprint are forwarded to its replicas.
	 *
	 * @return boolean
	 */
	public function forward_to_replicas(): bool;

	/**
	 * Get the searchable attributes.
	 *
	 * @return array
	 */
	public function get_searchable_attributes(): array;

	/**
	 * Get the ranking of each attribute for search.
	 *
	 * @return array
	 */
	public function get_ranking(): array;

	/**
	 * Add a replica index to this collection blueprint.
	 *
	 * @param Collection_Blueprint_Interface $replica The replica collection blueprint to add.
	 * @return void
	 */
	public function add_replica( Collection_Blueprint_Interface $replica ): void;

	/**
	 * Get replicas associated with this collection blueprint.
	 *
	 * @return array
	 */
	public function get_replicas(): array;

	/**
	 * Fetch records for this index (internally)
	 *
	 * @param integer $page The page number for the search results.
	 * @param integer $per_page The amount of results to return.
	 * @return array
	 */
	public function fetch_records( int $page, int $per_page = 100 ): array;

	/**
	 * Get the record class that will handle the records for this collection blueprint
	 *
	 * @return string
	 */
	public static function get_record(): string;

	/**
	 * Validate the collection blueprint.
	 *
	 * @return void
	 */
	public function validate_index();
}
