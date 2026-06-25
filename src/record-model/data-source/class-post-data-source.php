<?php
/**
 * Post data source.
 *
 * @package WooSearch\RecordModel\DataSource
 */

namespace WooSearch\RecordModel\DataSource;

use WooSearch\RecordModel\DataSource\Data_Source_Interface;

use WooSearch\Integrations\Record_Service_Integrations_Registry;

use WP_Post;

/**
 * Post_Data_Source class.
 */
class Post_Data_Source implements Data_Source_Interface {

	/**
	 * The WP_Post object
	 *
	 * @var WP_Post
	 */
	protected WP_Post $post;

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post The post this service will use.
	 */
	public function __construct( WP_Post $post ) {
		$this->post = $post;
	}

	/**
	 * Get the raw data for the operator.
	 *
	 * @return array
	 */
	public function get_data(): array {

		$excerpt = $this->get_excerpt();

		return array(
			'post_title'         => $this->get_post_title(),
			'post_url'           => $this->get_post_url(),
			'post_author_name'   => $this->get_post_author_name(),
			'excerpt'            => $excerpt,
			'has_excerpt'        => '' !== $excerpt,
			'word_count'         => $this->get_word_count(),
			'post_date'          => $this->get_post_date(),
			'post_status'        => $this->get_post_status(),
			'comment_status'     => $this->get_comment_status(),
			'post_modified_date' => $this->get_post_modified_date(),
		);
	}

	/**
	 * Get the post title.
	 *
	 * @return string
	 */
	public function get_post_title(): string {

		/**
		 * Allow develpers to filter the post data service's post title.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_post_data_post_title',
			get_the_title( $this->post ),
			$this->post
		);
	}

	/**
	 * Get the post url.
	 *
	 * @return string
	 */
	public function get_post_url(): string {

		/**
		 * Allow develpers to filter the post data service's post url.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_post_data_post_url',
			get_post_permalink( $this->post ),
			$this->post
		);
	}

	/**
	 * Get the post author name
	 *
	 * @return string
	 */
	public function get_post_author_name(): string {

		$author_name = get_user_by( 'ID', $this->post->post_author )->display_name;

		/**
		 * Allow develpers to filter the post data service's post author name.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_post_data_post_author_name',
			$author_name,
			$this->post
		);
	}

	/**
	 * Get the excerpt
	 *
	 * @return string
	 */
	public function get_excerpt(): string {

		/**
		 * Allow develpers to filter the post data service's post excerpt.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_post_data_post_excerpt',
			get_the_excerpt( $this->post->ID ),
			$this->post
		);
	}

	/**
	 * Get the word count
	 *
	 * @return int
	 */
	public function get_word_count(): int {

		$word_count = count(
			explode(
				' ',
				apply_filters( 'the_content', $this->post->post_content ) // phpcs:ignore
			)
		);

		return intval(
			/**
			 * Allow develpers to filter the post data service's post word count.
			 *
			 * @since 1.0.0
			 */
			apply_filters(
				'woo_search_post_data_post_word_count',
				$word_count,
				$this->post
			)
		);
	}

	/**
	 * Get post date
	 *
	 * @return string
	 */
	public function get_post_date(): string {

		/**
		 * Allow develpers to filter the post data service's post post date.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_post_data_post_date',
			$this->post->post_date,
			$this->post
		);
	}

	/**
	 * Get post status
	 *
	 * @return string
	 */
	public function get_post_status(): string {

		/**
		 * Allow develpers to filter the post data service's post status.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_post_data_post_status',
			$this->post->post_status,
			$this->post
		);
	}

	/**
	 * Get comment status
	 *
	 * @return string
	 */
	public function get_comment_status(): string {

		/**
		 * Allow develpers to filter the post data service's post comment status.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_post_data_comment_status',
			$this->post->comment_status,
			$this->post
		);
	}

	/**
	 * Get post modified date
	 *
	 * @return string
	 */
	public function get_post_modified_date(): string {

		/**
		 * Allow develpers to filter the post data service's post modified date.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_post_data_post_modified_date',
			$this->post->post_modified,
			$this->post
		);
	}

	/**
	 * Get post parent.
	 *
	 * @return WP_Post|null
	 */
	public function get_post_parent(): WP_Post|null {

		/**
		 * Allow develpers to filter the post data service's post parent.
		 *
		 * @since 1.0.0
		 */
		return apply_filters(
			'woo_search_post_data_post_parent',
			get_post_parent( $this->post ),
			$this->post
		);
	}
}

add_action(
	'woo_search_register_record_service_integrations',
	function ( Record_Service_Integrations_Registry $record_service_integrations_registry ) {

		$record_service_integrations_registry->register(
			array(
				'slug'                => 'post-data-service',
				'name'                => 'Post data Service',
				'description'         => 'Post data Service',
				'service'             => Post_Data_Source::class,
				'index_type_supports' => array(
					'posttype-index' => array(),
					'woo-index' => array(),
				),
			)
		);
	}
);
