<?php

namespace WooSearch\Admin;

use WooSearch\Indicies\Record_Index_Interface;

class Managed_Index {

	private wpdb $wpdb;
	
	private string $table_name;

	public function __construct( $wpdb ) {
		$this->wpdb = $wpdb;

		$this->table_name = "intelligent_data_managed_index";
	}

	public function insert_managed_index( $label, Record_Index_Interface $index_type ) {
		$inserted = $this->wpdb->insert(
			$this->table_name,
			[
				'label' => $label,
				'index_type' => $index_type->get_name()
			],
			[
				'%s',
				'%s'
			]
		);

		if ( !$inserted ) {
			return false;
		}

		return (int) $this->wpdb->insert_id;
	}

	public function get_managed_index( $id ) {
		$row = $this->wpdb->get_row(
			$this->wpdb->prepare(
				"SELECT * FROM {$this->table_name} WHERE id = %d",
				$id
			)
		);

		return $row ?: null;
	}

	public function get_all() {
		return $this->wpdb->get_results(
			"SELECT * FROM {$this->table_name} ORDER BY created_at DESC"
		);
	}

	public function delete( $id ) {
		return (bool) $this->wpdb->delete(
			$this->table_name,
			[ 'id' => $id ],
			[ '%d' ]
		);
	}
}