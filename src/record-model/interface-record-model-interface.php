<?php
/**
 * Record Interface.
 *
 * @package WooSearch\RecordModel
 */

namespace WooSearch\RecordModel;

interface Record_Model_Interface {

	/**
	 * Get a record's unique object ID.
	 *
	 * @return string
	 */
	public function get_object_id(): string;

	/**
	 * Get the available attributes for a record.
	 *
	 * @return array
	 */
	public static function get_attributes(): array;


	/**
	 * Get the attributes available for faceting
	 *
	 * @return array
	 */
	public static function get_attributes_available_for_faceting(): array;

	/**
	 * Get the raw data for a record.
	 *
	 * @return array
	 */
	public function get_data(): array;

	/**
	 * Validate a record
	 *
	 * @param Record_Model_Interface $record The record to validate.
	 * @return void
	 */
	public static function validate_record( Record_Model_Interface $record );

	/**
	 * Validate attributes.
	 *
	 * @param Record_Model_Interface $record The record to validate.
	 * @return void
	 */
	public static function validate_attributes( Record_Model_Interface $record ): void;

	/**
	 * Validate object ID.
	 *
	 * @param Record_Model_Interface $record The record to validate.
	 * @return void
	 */
	public static function validate_object_id( Record_Model_Interface $record );
}
