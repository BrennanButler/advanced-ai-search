<?php
/**
 * Service container used for registering attribute retrieval services for records.
 *
 * @package WooSearch\Records\Services
 */

namespace WooSearch\Records\Services;

use WooSearch\Integrations\Record_Service_Integrations_Registry;

use Exception;

/**
 * Service_Container class is used for registering attribute retrieval services for records.
 */
class Service_Container {

	/**
	 * An array of registered services.
	 *
	 * @var array
	 */
	protected array $services = array();

	/**
	 * Register a single service.
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
	public function register_services( array $services ) {

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
}