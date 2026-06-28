<?php
/**
 * An abstract collection blueprint
 *
 * @package WooSearch\CollectionBlueprints
 */

namespace WooSearch\CollectionBlueprints;

use Exception;
use WooSearch\CollectionBlueprints\Collection_Blueprint_Interface;

/**
 * Abstract collection blueprint
 */
#[\AllowDynamicProperties]
abstract class Abstract_Collection_Blueprint implements Collection_Blueprint_Interface {

	/**
	 * The name of the Collection Blueprint.
	 *
	 * @var string
	 */
	protected static string $name;

	/**
	 * The slug of the Collection Blueprint.
	 *
	 * @var string
	 */
	protected static string $slug;

	/**
	 * An array of Collection_Blueprint_Interface objects.
	 *
	 * @var array
	 */
	protected static array $replicas;

	/**
	 * Whether to forward settings to replicas.
	 *
	 * @var boolean
	 */
	protected static bool $forward_to_replicas;

	/**
	 * The record class.
	 *
	 * @var string
	 */
	protected static $record;

	/**
	 * The record prefix. This is used primarily for the unique objectId assigned to each record.
	 *
	 * @var string
	 */
	protected static string $record_prefix;

	protected static $collection_settings;

	/**
	 * Get the name of the collection blueprint.
	 *
	 * @return string
	 */
	public static function get_name(): string {
		return self::$name;
	}

	/**
	 * Get the slug of the collection blueprint.
	 *
	 * @return string
	 */
	public static function get_slug(): string {
		return self::$slug;
	}

	public static function get_collection_settings(): array {
		return self::$collection_settings;
	}
	/**
	 * Get the attributes that can be searched.
	 *
	 * @return array
	 */
	public static function get_searchable_attributes(): array {
		return self::$record::get_attributes();
	}

	/**
	 * Get the record prefix.
	 *
	 * @return string
	 */
	public static function get_record_prefix(): string {
		return self::$record_prefix;
	}

	/**
	 * Get the attributes used for faceting.
	 *
	 * @return array
	 */
	public static function get_attributes_for_faceting(): array {

		$attributes_available = self::$record::get_attributes_available_for_faceting();

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
	 * @param Collection_Blueprint_Interface $replica The replica index to add.
	 * @return void
	 */
	public static function add_replica( Collection_Blueprint_Interface $replica ): void {
		self::$replicas[] = $replica;
	}

	/**
	 * Get the replicas for this index.
	 *
	 * @return array
	 */
	public static function get_replicas(): array {
		return self::$replicas;
	}

	/**
	 * Whether to forward settings to replica blueprints.
	 *
	 * @return boolean
	 */
	public static function forward_to_replicas(): bool {
		return self::$forward_to_replicas;
	}
}
