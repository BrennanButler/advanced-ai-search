<?php
/**
 * Abstract record class.
 *
 * @package WooSearch\RecordModel
 */

namespace WooSearch\RecordModel;

use WooSearch\Records\Attributes\AttributeInterface;
use WooSearch\RecordModel\Record_Model_Interface;
use WooSearch\RecordModel\DataSource\Data_Source_Registry;

use Exception;

/**
 * AbstractRecord
 *
 * Demonstrates the minimum implementation of the Record_Model_Interface
 */
abstract class Abstract_Record_Model implements Record_Model_Interface {

	/**
	 * A unique identifier for a particular record
	 *
	 * @var string
	 */
	public string $object_id;

	/**
	 * An array of record attributes and their values
	 *
	 * @var Array
	 */
	protected array $data;

	protected static Data_Source_Registry $data_source_registry;

	public function __construct() {
		self::$data_source_registry = new Data_Source_Registry();
	
	}
	/**
	 * Return the unique object identifier of a record.
	 *
	 * @return string
	 */
	public function get_object_id(): string {
		return $this->object_id;
	}

	public static function get_attributes(): array {
		return self::$data_source_registry->get_all_sources_slugs();
	}

	/**
	 * Validate a record. Throws an exception at runtime if the record fails validation
	 *
	 * @param Record_Model_Interface $record The record to validate.
	 * @return void
	 */
	public static function validate_record( Record_Model_Interface $record ) {
		self::validate_attributes( $record );
		self::validate_object_id( $record );
	}

	/**
	 * Validate the attributes of a record. Throws an exception in runtime
	 *
	 * @param Record_Model_Interface $record The record to validate.
	 * @return void
	 * @throws Exception When the record contains attributes not specified in the record class.
	 */
	public static function validate_attributes( Record_Model_Interface $record ): void {

		$attributes = $record->get_attributes();
		$data       = $record->get_data();

		$data_keys = array_keys( $data );

		if ( sort( $data_keys ) !== sort( $attributes ) ) {
			throw new Exception( 'Your record contains attributes not specified in the Record class' );
		}
	}

	/**
	 * Validate the objectId
	 *
	 * @param Record_Model_Interface $record The record to validate.
	 * @return void
	 * @throws Exception When object IDs contain spaces.
	 */
	public static function validate_object_id( Record_Model_Interface $record ) {
		$object_id = $record->get_object_id();

		if ( str_contains( $object_id, ' ' ) ) {
			throw new Exception( 'Object IDs must not contain spaces' );
		}
	}
}
