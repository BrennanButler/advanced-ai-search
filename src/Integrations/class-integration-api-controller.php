<?php

use WooSearch\Integrations\Index_Type_Integration;
use WooSearch\Integrations\Integration_Registry;
use WooSearch\Integrations\Record_Service_Integration_Interface;

class Integration_Api_Controller
{

	protected Index_Type_Integration | Record_Service_Integration_Interface $integration;

	public function __construct(Index_Type_Integration | Record_Service_Integration_Interface $integration)
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
