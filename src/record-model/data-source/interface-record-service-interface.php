<?php
/**
 * Data Source Interface.
 *
 * @package WooSearch\RecordModel\DataSource
 */

namespace WooSearch\RecordModel\DataSource;

interface Data_Source_Interface {

	/**
	 * Get the data that this service is providing
	 *
	 * @return array
	 */
	public function get_data(): array;
}
