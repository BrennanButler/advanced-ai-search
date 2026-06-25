<?php
/**
 * Integration registry
 *
 * @package WooSearch\Integrations
 */

namespace WooSearch\Integrations;

use Exception;
use Iterator;

class Integration_Registry implements Iterator {

	private $position = 0;

	protected array $integrations = array();

	public function __construct() {
		$this->position = 0;
	}

	public function rewind(): void
	{
		$this->position = 0;
	}

	public function current(): mixed
	{
		return $this->integrations[$this->position];
	}

	public function key(): mixed
	{
		return $this->position;
	}

	public function next(): void
	{
		++$this->position;
	}

	public function valid(): bool
	{
		return isset( $this->integrations[$this->position] );
	}

	
	public function register( Collection_Blueprint_Integration_Interface|Record_Service_Integration_Interface $integration ) {
		$this->integrations[] = $integration;
	}

	public function get( string $slug ) {

		$filtered_integrations = array_filter(
			$this->integrations,
			function ( $integration ) use ( $slug ) {
				return $slug === $integration->get_slug();
			}
		);

		if ( count( $filtered_integrations ) < 1 ) {
			throw new Exception( "Attempted to retrieve a collection blueprint that doesn't exist" );
		}

		// We should only expect one result.
		$type = $filtered_integrations[0];

		return $type;
	}

	public function get_all() {
		return $this->integrations;
	}
}
