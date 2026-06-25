<?php
/**
 * Abstract integration
 */


namespace WooSearch\Integrations;


use WP_REST_Request;


/**
 * Undocumented class
 */
abstract class Abstract_Collection_Blueprint_Integration implements Collection_Blueprint_Integration_Interface {

	protected string $slug;

	protected string $description;

	protected string $name;

	protected array $options = array();

	protected array $index_supports = array();

	protected string $index_class;

	public function get_slug(): string
	{
		return $this->slug;
	}

	public function get_name() : string {
		return $this->name;
	}

	public function get_description(): string
	{
		return $this->description;
	}

	public function get_options() : array {
		return $this->options;
	}

	public function index_supports(): array
	{
		return $this->index_supports;
	}

	public function get_index_class(): string
	{
		return $this->index_class;
	}

	public function register_rest_routes(): void
	{

		add_action( "index_type_integration_register_rest_routes_" . $this->slug, array($this, "register_fetch_records_route") );
	}
	
	protected function register_fetch_records_route() {
		register_rest_route(
			'advanced-ai-search/v1',
			'/integration/index/' . $this->slug . '/records/(?P<page>\d+)/(?P<per_page>\d+)',
			array(
				'methods' => 'GET',
				'callback' => array($this, '_fetch_records_route'),
				'permission_callback' => '__return_true'
			)
		);
	}

	protected function _fetch_records_route( WP_REST_Request $request ) {

		$page = $request->get_param('page');
		$per_page = $request->get_param('per_page');

		$index = new $this->index_class();

		return $index->fetch_records( intval($page), intval($per_page) );
	}
}
