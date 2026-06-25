<?php
/**
 * Tags Service
 *
 * @package WooSearch\Records\Services
 */

namespace WooSearch\Records\Services;

use WooSearch\Integrations\Record_Service_Integrations_Registry;

use WP_Post;

/**
 * Tags_Service class.
 */
class Tags_Service {

	/**
	 * The WP_Post for this service
	 *
	 * @var WP_Post
	 */
	protected WP_Post $post;

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post The post for this service.
	 */
	public function __construct( WP_Post $post ) {
		$this->post = $post;
	}

	/**
	 * The raw data for the operator.
	 *
	 * @return array
	 */
	public function get_data(): array {
		$tags = get_the_tags( $this->post );

		$tags_data = array();

		if ( false !== $tags ) {
			$tags_data = array_map(
				function ( $term ) {
					return array(
						'term_id'     => $term->term_id,
						'name'        => $term->name,
						'description' => $term->description,
						'parent'      => $term->parent,
						'count'       => $term->count,
					);
				},
				$tags
			);
		}

		return $tags_data;
	}
}

add_action(
	'woo_search_register_record_service_integrations',
	function ( Record_Service_Integrations_Registry $record_service_integrations_registry ) {

		$record_service_integrations_registry->register(
			array(
				'slug'                => 'tags-service',
				'name'                => 'Tags Service',
				'description'         => 'Tags Service',
				'service'             => Tags_Service::class,
				'index_type_supports' => array(
					'posttype-index' => array(),
					'woo-index' => array(),
				),
			)
		);
	}
);
