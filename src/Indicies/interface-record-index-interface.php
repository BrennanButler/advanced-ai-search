<?php
/**
 * Record index interface.
 *
 * @package WooSearch\Indicies
 */

namespace WooSearch\Indicies;

interface Record_Index_Interface {

	/**
	 * Get the name of the index.
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Get the prefix used on all records in this index.
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
	 * Whether the settings of this index are forwarded to it's replicas.
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
	 * Add a replica index to this index.
	 *
	 * @param Record_Index_Interface $replica The replica index to add.
	 * @return void
	 */
	public function add_replica( Record_Index_Interface $replica ): void;

	/**
	 * Get replicas associated with this index.
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
	 * Get the record class that will handle the records for this index
	 *
	 * @return string
	 */
	public static function get_record(): string;

	/**
	 * Validate the index.
	 *
	 * @return void
	 */
	public function validate_index();
}
