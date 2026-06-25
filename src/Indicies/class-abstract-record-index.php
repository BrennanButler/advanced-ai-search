<?php
/**
 * An abstract record Index
 *
 * @package WooSearch\Indicies
 */

namespace WooSearch\Indicies;

use Exception;
use WooSearch\Indicies\Record_Index_Interface;

/**
 * Abstract record index
 */
abstract class Abstract_Record_Index implements Record_Index_Interface {

	/**
	 * The name of the Index.
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * An array of RecordIndexInterface objects.
	 *
	 * @var array
	 */
	protected array $replicas;

	/**
	 * Whether to forward settings to replicas.
	 *
	 * @var boolean
	 */
	protected bool $forward_to_replicas;

	/**
	 * The record class.
	 *
	 * @var string
	 */
	protected string $record;

	/**
	 * The record prefix. This is used primarily for the unique objectId assigned to each record.
	 *
	 * @var string
	 */
	protected string $record_prefix;

	/**
	 * Undocumented function
	 *
	 * @param string  $name The name of the index.
	 * @param string  $record_prefix The record prefix.
	 * @param boolean $forward_to_replicas Whether to forward settings to replica indices.
	 * @param string  $record The record class.
	 * @throws Exception When record is not a class that exists.
	 */
	public function __construct( string $name, $record_prefix = 'index_', $forward_to_replicas = true, string $record = '' ) {

		if ( ! class_exists( $record ) ) {
			throw new Exception( "$record must be a class" ); // phpcs:ignore
		}

		$this->name                = $name;
		$this->record_prefix       = $record_prefix;
		$this->replicas            = array();
		$this->forward_to_replicas = $forward_to_replicas;
		$this->$record             = $record;
	}

	/**
	 * Get the name of the index.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Get the attributes that can be searched.
	 *
	 * @return array
	 */
	public function get_searchable_attributes(): array {
		return $this->record::get_attributes();
	}

	/**
	 * Get the record prefix.
	 *
	 * @return string
	 */
	public function get_record_prefix(): string {
		return $this->record_prefix;
	}

	/**
	 * Get the attributes used for faceting.
	 *
	 * @return array
	 */
	public function get_attributes_for_faceting(): array {

		$attributes_available = $this->record::get_attributes_available_for_faceting();

		/**
		 *
		 * Use the follow to configure
		 * unset($attributesAvailable["attribute_name"];
		 */

		return $attributes_available;
	}

	/**
	 * Add a replica to this index.
	 *
	 * @param Record_Index_Interface $replica The replica index to add.
	 * @return void
	 */
	public function add_replica( Record_Index_Interface $replica ): void {
		$this->replicas[] = $replica;
	}

	/**
	 * Get the replicas for this index.
	 *
	 * @return array
	 */
	public function get_replicas(): array {
		return $this->replicas;
	}

	/**
	 * Whether to forward settings to replica indicies.
	 *
	 * @return boolean
	 */
	public function forward_to_replicas(): bool {
		return $this->forward_to_replicas;
	}

	/**
	 * Validate the index.
	 *
	 * @return void
	 */
	public function validate_index() {

		$record = $this->fetch_records( 1, 1 )[0];

		$record::validate_record( $record );
	}
}
