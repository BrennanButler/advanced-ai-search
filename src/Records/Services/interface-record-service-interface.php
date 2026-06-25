<?php
/**
 * Record Service Interface.
 *
 * @package WooSearch\Records\Services
 */

namespace WooSearch\Records\Services;

interface Record_Service_Interface {

	/**
	 * Get the data that this service is providing
	 *
	 * @return array
	 */
	public function get_data(): array;
}
