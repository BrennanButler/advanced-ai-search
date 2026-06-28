<?php
/**
 * Service container used for registering attribute retrieval services for records.
 *
 * @package WooSearch\RecordModel\DataSource
 */

namespace WooSearch\RecordModel\DataSource;

use WooSearch\Integrations\Record_Service_Integrations_Registry;

use Exception;

/**
 * Data_Source_Registry class is used for registering attribute retrieval services for records.
 */
class Data_Source_Registry {

	/**
	 * An array of registered services.
	 *
	 * @var array
	 */
	protected array $services = array();

	/**
	 * Register a single data source service.
	 *
	 * @param string $name The name of the service.
	 * @param mixed  $service The service instance.
	 * @return void
	 */
	public function register( string $name, $service ) {
		$this->services[ $name ] = $service;
	}

	/**
	 * Register multiple services at once.
	 *
	 * @param array $services An array of services in a key value format where the key denotes the name of the service.
	 * @return void
	 */
	public function register_data_sources( array $services ) {

		foreach ( $services as $name => $service ) {
			$this->services[ $name ] = $service;
		}
	}

	/**
	 * Get a service by its name.
	 *
	 * @param string $name The service name.
	 * @return mixed
	 * @throws Exception When service is not found in array of registered services.
	 */
	public function get( string $name ): mixed {

		if ( isset( $this->services[ $name ] ) ) {
			return $this->services[ $name ];
		}

		throw new Exception( 'Service ' . esc_html( $name ) . ' not found.' );
	}

	public function get_all_sources_slugs(): array {
		return array_keys( $this->services );
	}
}