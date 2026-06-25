<?php
/**
 * Woo Index for the management of indicies that are in sync for search.
 *
 * @package WooSearch\Admin
 */

namespace WooSearch\Admin;

use Exception;

class Woo_Index {

	protected int $post_id;

	public function __construct( $post_id ) {
		$this->post_id = $post_id;
	}

	public function get_name() {
		return get_the_title( $this->post_id );
	}
	
	public function get_index_type() {
		return get_post_meta(
			$this->post_id,
			'index_type',
			true
		);
	}

	public function set_index_type( $index_type ) {
		update_post_meta(
			$this->post_id,
			'index_type',
			$index_type
		);
	}

	public function get_record_service_integrations() {
		return get_post_meta(
			$this->post_id,
			'record_service_integrations',
			true
		);
	}

	public function set_record_service_integrations( $integrations ) {
		update_post_meta(
			$this->post_id,
			'record_service_integrations',
			$integrations
		);
	}

	public function get_id() {
		return $this->post_id;
	}

	public static function create ( string $name, string $index_type, $record_services = array() ) {

		$post_id = wp_insert_post(
			array(
				'post_type'  => 'woo_search_indicies',
				'post_title'  => $name,
				'post_status' => 'publish',
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			throw new Exception( 'There was an error inserting a new Woo Index' );
		}

		update_post_meta(
			$post_id,
			'index_type',
			$index_type
		);

		update_post_meta(
			$post_id,
			'record_service_integrations',
			$record_services
		);

		return $post_id;
	}
}
