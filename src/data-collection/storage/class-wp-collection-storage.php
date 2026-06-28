<?php

namespace WooSearch\DataCollection\Storage;

use WooSearch\DataCollection\Storage\Collection_Storage_Interface;
use WooSearch\DataCollection\Data_Collection_Interface;
use WooSearch\CollectionBlueprints\Collection_Blueprint_Factory;
use WooSearch\DataCollection\Abstract_Data_Collection;

use Exception;

class WP_Collection_Storage implements Collection_Storage_Interface {

	protected $wpdb;

	public function __construct( $wpdb ) {
		$this->wpdb = $wpdb;
	}

	public function setup_storage(): void {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table_name      = $this->wpdb->prefix . 'data_collections';
		$charset_collate = $this->wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(191) NOT NULL,
			blueprint_slug VARCHAR(191) NOT NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY blueprint_slug (blueprint_slug)
		) {$charset_collate};";

		dbDelta( $sql );

		$table_name      = $this->wpdb->prefix . 'data_collection_settings';

		$sql = "CREATE TABLE {$table_name} (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	data_collection_id BIGINT UNSIGNED NOT NULL,
	setting_key VARCHAR(191) NOT NULL,
	setting_value LONGTEXT NULL,

	PRIMARY KEY  (id),

	UNIQUE KEY data_collection_setting_unique (data_collection_id, setting_key),

	KEY data_collection_id (data_collection_id),
	KEY setting_key (setting_key)
) {$charset_collate};";

			dbDelta( $sql );
	}

	/**
	 * Save a data collection.
	 *
	 * @param Data_Collection_Interface $collection The data collection to save.
	 * @return int The ID of the saved data collection.
	 */
	public function save_collection( Data_Collection_Interface $collection ): int {
		
		$this->wpdb->insert(
			$this->wpdb->prefix . 'data_collections',
			[
				'name' => $collection->get_name(),
				'blueprint' => $collection->get_collection_blueprint()->get_slug(),
			]
		);

		return $this->wpdb->insert_id;
	}

	/**
	 * Get a data collection by its ID.
	 *
	 * @param int $id The ID of the data collection to retrieve.
	 * @return Data_Collection_Interface|null The data collection if found, null otherwise.
	 */
	public function get_collection( int $id ): ?Data_Collection_Interface {
		$result = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->wpdb->prefix . "data_collections WHERE id = %d", $id ) );

		if ( ! $result ) {
			return null;
		}

		$blueprint = Collection_Blueprint_Factory::create_collection_blueprint( $result->blueprint );

		return new Abstract_Data_Collection(
			$result->name,
			$blueprint
		);
	}

	/**
	 * Get all data collections.
	 *
	 * @return array The list of all data collections.
	 */
	public function get_all_collections(): array {
		$results = $this->wpdb->get_results( "SELECT 
		dc.*,
		JSON_OBJECTAGG(cbm.setting_key, cbm.setting_value) AS blueprint_settings
	FROM " . $this->wpdb->prefix . 'data_collections' . " dc
	LEFT JOIN " . $this->wpdb->prefix . 'data_collection_settings' . " cbm
		ON cbm.data_collection_id = dc.id
	GROUP BY dc.id", ARRAY_A );

		return array_map(
			function ( $result ) {
				$blueprint_settings = json_decode( $result['blueprint_settings'], true );

				

				$blueprint = Collection_Blueprint_Factory::create_collection_blueprint( $result['blueprint'] );

				return new Abstract_Data_Collection(
					$result['name'],
					$blueprint,
					$blueprint_settings
				);
			},
			$results
		);
	}
}