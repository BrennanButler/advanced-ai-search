<?php

namespace WooSearch\Integrations;

use WooSearch\Indicies\Index_Type_Registry;
use WooSearch\Integrations\Index_Type_Integration;
use WooSearch\Integrations\Collection_Blueprint_Integrations_Registry;
use WooSearch\Integrations\Integration_Registry;
use WooSearch\Integrations\Record_Service_Integration_Interface;
use WooSearch\Integrations\Record_Service_Integrations_Registry;

/**
 * Undocumented class
 */
class Integration_Manager {

	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	private static $instance;

	/**
	 * Undocumented variable
	 *
	 * @var Integration_Registry
	 */
	private Integration_Registry $integration_registry;

	/**
	 * Undocumented variable
	 *
	 * @var Record_Service_Integrations_Registry
	 */
	private Record_Service_Integrations_Registry $record_service_registry;

	/**
	 * Undocumented variable
	 *
	 * @var Collection_Blueprint_Integrations_Registry
	 */
	private Collection_Blueprint_Integrations_Registry $collection_blueprint_registry;

	/**
	 * Undocumented function
	 */
	private function __construct() {

		$this->integration_registry = new Integration_Registry();

		$this->record_service_registry = new Record_Service_Integrations_Registry();

		$this->collection_blueprint_registry = new Collection_Blueprint_Integrations_Registry();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function init() {

		/**
		 * @since 1.0
		 */
		do_action( 'woo_search_register_integrations', $this->integration_registry );

		$this->setup_collection_blueprints();
		$this->setup_record_services();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function setup_collection_blueprints() {

		/**
		 * @since 1.0
		 */
		do_action( 'woo_search_register_collection_blueprints', $this->collection_blueprint_registry );

		foreach ( $this->integration_registry as $integration ) {

			if ( $integration instanceof Collection_Blueprint_Integration_Interface ) {
				$this->collection_blueprint_registry->register( $integration );
			}
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function setup_record_services() {

		do_action( 'woo_search_register_record_service_integrations', $this->record_service_registry );

		foreach ( $this->integration_registry as $integration ) {

			if ( $integration instanceof Record_Service_Integration_Interface ) {
				$this->record_service_registry->register( $integration );
			}
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return Collection_Blueprint_Integrations_Registry
	 */
	public function get_collection_blueprint_registry() {
		return $this->collection_blueprint_registry;
	}

	/**
	 * Undocumented function
	 *
	 * @return Record_Service_Integrations_Registry
	 */
	public function get_record_service_registry() {
		return $this->record_service_registry;
	}

	public function get_third_party_integration_registry() {
		return $this->integration_registry;
	}
}
