<?php

namespace WooSearch\Integrations;

use WooSearch\Indicies\PostType_Index;
use WooSearch\Indicies\Woo_Product_Index;
use WooSearch\Indicies\Index_Type_Registry;
use WooSearch\Integrations\Index_Type_Integration;
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
	 * @var Index_Type_Registry
	 */
	private Index_Type_Integrations_Registry $index_type_registry;

	/**
	 * Undocumented function
	 */
	private function __construct() {

		$this->integration_registry = new Integration_Registry();

		$this->record_service_registry = new Record_Service_Integrations_Registry();

		$this->index_type_registry = new Index_Type_Integrations_Registry();
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

		$this->setup_index_types();
		$this->setup_record_services();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function setup_index_types() {

		/**
		 * @since 1.0
		 */
		do_action( 'woo_search_register_index_type_integrations', $this->index_type_registry );

		foreach ( $this->integration_registry as $integration ) {

			if ( $integration instanceof Index_Type_Integration ) {
				$this->index_type_registry->register( $integration );
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
	 * @return Index_Type_Integrations_Registry
	 */
	public function get_index_type_registry() {
		return $this->index_type_registry;
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
