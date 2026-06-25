<?php
/**
 * Api
 *
 * @package WooSearch\Api
 */

namespace WooSearch\Api;

use WooSearch\Admin\Woo_Index;
use WooSearch\Integrations\Index_Type_Integration;
use WooSearch\Integrations\Integration_Interface;
use WooSearch\Integrations\Record_Service_Integration_Interface;
use WooSearch\WooSearch;

/**
 * Api
 */
class Api {

	public static function setup() {
		add_action(
			'rest_api_init',
			function () {


				register_rest_route(
					'/woo-search/v1',
					'/index-integrations/',
					array(
						'methods'  => 'GET',
						'callback' => array(self::class, 'index_integrations'),
					)
				);

				register_rest_route(
					'/woo-search/v1',
					'/index-types/',
					array(
						'methods'  => 'GET',
						'callback' => array(self::class, 'index_types'),
					)
				);

				register_rest_route(
					'/woo-search/v1',
					'/record-services/',
					array(
						'methods'  => 'GET',
						'callback' => array(self::class, 'record_services'),
					)
				);


				register_rest_route(
					'/woo-search/v1',
					'/indicies/',
					array(
						'methods'  => 'GET',
						'callback' => array(self::class, 'indicies_get'),
					)
				);

				register_rest_route(
					'/woo-search/v1',
					'/indicies/',
					array(
						'methods'  => 'POST',
						'callback' => array(self::class, 'indicies_create'),
					)
				);


			}
		);
	}

	public static function index_integrations() {
		$woo_search = WooSearch::get_instance();

		$index_integration_registry = $woo_search->get_integration_manager()->get_index_type_registry();
		$integrations = $index_integration_registry->get_all();

		return array_map(
			function ( Index_Type_Integration|array $integration ) {

				if ( ! is_array( $integration ) ) {
					return array(
						'name' => $integration->get_name(),
						'slug' => $integration->get_slug(),
						'description' => $integration->get_description(),
						'options' => $integration->get_options()
					);
				}

				return array(
					'name' => $integration['name'],
					'slug' => $integration['slug'],
					'description' => $integration['description'],
					'options' => $integration['options']
				);
			},
			$integrations
		);
	}

	public static function record_service_integrations() {
		$woo_search = WooSearch::get_instance();

		$record_service_registry = $woo_search->get_integration_manager()->get_record_service_registry();
		$integrations = $record_service_registry->get_all();

		return array_map(
			function ( Index_Type_Integration_Interface|array $integration ) {

				if ( ! is_array( $integration ) ) {
					return array(
						'name' => $integration->get_name(),
						'slug' => $integration->get_slug(),
						'description' => $integration->get_description(),
						'options' => $integration->get_options()
					);
				}

				return array(
					'name' => $integration['name'],
					'slug' => $integration['slug'],
					'description' => $integration['description'],
					'options' => $integration['options']
				);
			},
			$integrations
		);
	}

	public static function index_types() {
		$woo_search = WooSearch::get_instance();

		$integration_manager = $woo_search->get_integration_manager();

		$index_type_registry = $integration_manager->get_index_type_registry();

		$index_types = $index_type_registry->get_all();

		$types = array(
			...array_map(
				function( $integration ) {

					if ( is_array( $integration ) ) {
						return array(
							"slug" => $integration['slug'],
							'name' => $integration['name'],
							'description' => $integration['description'],
							'options' => $integration['options'],
						);
					}

					$record_class = $integration->get_index_class()::get_record();
					$attributes = $record_class::get_attributes();

					return array(
						"slug" => $integration->get_slug(),
						'name' => $integration->get_name(),
						'description' => $integration->get_description(),
						'options' => $integration->get_options(),
						'attributes' => $attributes
					);
				},
				$index_types
			),
		);

		return $types;
	}

	public static function record_services() {
		$woo_search = WooSearch::get_instance();

		$record_service_registry = $woo_search->get_integration_manager()->get_record_service_registry();

		$integrations = $record_service_registry->get_all();

		$record_services = array(
			...array_map(
				function( $integration ) {

					if ( is_array( $integration ) ) {
						return array(
							"slug" => $integration['slug'],
							'name' => $integration['name'],
							'description' => $integration['description'],
							'index_type_supports' => $integration['index_type_supports'],
							'options' => $integration['options'],
						);
					}

					return array(
						"slug" => $integration->get_slug(),
						'name' => $integration->get_name(),
						'description' => $integration->get_description(),
						'index_type_supports' => $integration->index_supports(),
						'options' => $integration->get_options()
					);
				},
				$integrations
			),
		);

		return $record_services;
	}

	public static function indicies_get() {
		
		$indicies = get_posts(
			array(
				'post_type' => 'woo_search_indicies',
				'limit' => '-1'
			)
		);

		$indicies = array_map(
			function( $post_index ) {

				$index = new Woo_Index( $post_index->ID );

				return array(
					'id'                          => $index->get_id(),
					'name'                        => $index->get_name(),
					'index_type'                  => $index->get_index_type(),
					'record_service_integrations' => $index->get_record_service_integrations(),
				);
			},
			$indicies
		);

		return $indicies;
	}

	public static function indicies_create( $request ) {
		$index_name = $request['name'];
		$index_type = $request['index_type'];
		$record_services = $request['record_services'];

		$post_id = Woo_Index::create( $index_name, $index_type, $record_services );

		return array(
			"message" => "success"
		);
	}
}
