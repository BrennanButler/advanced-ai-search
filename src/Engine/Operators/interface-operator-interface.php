<?php
/**
 * Operator interface
 *
 * @package WooSearch\Engine\Operators
 */

namespace WooSearch\Engine\Operators;

use WooSearch\Indicies\Record_Index_Interface;

interface Operator_Interface {

	/**
	 * Get the name of the operator.
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Get an instance (singleton) of an OperatorInterface object.
	 *
	 * @return OperatorInterface
	 */
	public static function get_instance();

	/**
	 * Initialize a index with an Operator.
	 *
	 * @param Record_Index_Interface $index The index to initialise.
	 * @return void
	 */
	public function init_index( Record_Index_Interface $index );

	/**
	 * Remove an index with an Operator.
	 *
	 * @param Record_Index_Interface|string $index The index to remove.
	 * @return void
	 */
	public function remove_index( Record_Index_Interface|string $index );

	/**
	 * Save records with an Operator
	 *
	 * @param Record_Index_Interface $index The index to save records to.
	 * @param Array                  $records The records to save.
	 * @return void
	 */
	public function save_records( Record_Index_Interface $index, array $records );

	/**
	 * Delete records from an index
	 *
	 * @param Record_Index_Interface $index The index to remove records from.
	 * @param Array                  $records The records to remove.
	 * @return void
	 */
	public function delete_records( Record_Index_Interface $index, array $records );

	/**
	 * Clear (empty) records from an index
	 *
	 * @param Record_Index_Interface|string $index The index to clear records from.
	 * @return void
	 */
	public function clear_records( Record_Index_Interface|string $index );

	/**
	 * Get records from an index.
	 *
	 * @param Record_Index_Interface|string $index The index to get records from.
	 * @return Array
	 */
	public function get_records( Record_Index_Interface|string $index ): array;

	/**
	 * Validate an index.
	 *
	 * @param Record_Index_Interface $index The index to validate.
	 * @return void
	 */
	public static function validate_index( Record_Index_Interface $index );
}
