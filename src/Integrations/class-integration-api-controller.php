<?php

use WooSearch\Integrations\Collection_Blueprint_Integration_Interface;
use WooSearch\Integrations\Integration_Registry;
use WooSearch\Integrations\Record_Service_Integration_Interface;

class Integration_Api_Controller
{

	protected Collection_Blueprint_Integration_Interface | Record_Service_Integration_Interface $integration;

	public function __construct(Collection_Blueprint_Integration_Interface | Record_Service_Integration_Interface $integration)
	{
		$this->integration = $integration;
	}

	public function register_routes(): void
	{
		add_action('rest_api_init', array(__CLASS__, '_register_routes'));
	}

	private function _register_routes(): void
	{
		/**
		 * 
		 */
		register_rest_route('advanced-ai-search/v1', '/author/(?P<id>\d+)', array(
			'methods' => 'GET',
			'callback' => 'my_awesome_func',
		));
	}
}
