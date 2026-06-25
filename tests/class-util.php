<?php
/**
 * Util class for testing
 *
 * @package WooSearch\Tests
 */

namespace WooSearch\Tests;

/**
 * Util class
 */
class Util {

	static $totalTestPosts = 2;

	static $totalTestProducts = 1;

	/**
	 * Common setup code for tests
	 *
	 * @return void
	 */
	public static function set_up() {
		$user = wp_insert_user(
			array(
				'user_login' => 'test_user',
				'user_pass' => '123'
			)
		);

		if ( is_wp_error( $user ) ) {
			error_log( 'there was an error creating the user - ' . $user->get_error_message() );
		}

		$parent = wp_insert_category(
			array(
				'cat_name' => 'Parent category',
			),
			true
		);

		if ( is_wp_error( $parent ) ) {
			error_log( $parent->get_error_message() );
		}

		wp_add_post_tags(
			$parent,
			array(
				'Tag1',
				'Tag2',
			)
		);

		$child = wp_insert_category(
			array(
				'cat_name'        => 'Child category',
				'category_parent' => $parent,
			),
			true
		);

		if ( is_wp_error( $child ) ) {
			error_log( $child->get_error_message() );
		}

		wp_add_post_tags(
			$child,
			array(
				'Tag1',
				'Tag2',
			)
		);

		$categoryIds = array(
			$parent,
			$child,
		);

		error_log( 'here is the category Ids' );
		error_log( print_r( $categoryIds, true ) );

		$post_arr = array(
			'post_title'   => 'Test post',
			'post_content' => 'Test post content',
			'post_status'  => 'publish',
			'post_author'  => $user,
			'post_type'    => 'post',
		);

		$postParent = wp_insert_post( $post_arr, true );

		wp_set_post_categories( $postParent, $categoryIds );

		$post_arr = array(
			'post_title'   => 'Test post child',
			'post_content' => 'Test post content',
			'post_parent'  => $postParent,
			'post_status'  => 'publish',
			'post_author'  => $user,
			'post_type'    => 'post',
		);

		$post = wp_insert_post( $post_arr, true );

		if ( is_wp_error( $post ) ) {
			error_log( 'there was an error inserting the child post' );
		}
		wp_set_post_categories( $post, $categoryIds );

		error_log( 'here is the post object for post ' . $post );
		$post = wp_get_post_categories( $post );
		error_log( print_r( $post, true ) );

		$post_arr = array(
			'post_title'   => 'Test product',
			'post_content' => 'This is my test product',
			'post_status'  => 'publish',
			'post_author'  => $user,
			'post_type'    => 'product',
		);

		$productId = wp_insert_post( $post_arr, true );

		update_post_meta( $productId, '_price', '1.99' );
		update_post_meta( $productId, '_regular_price', '2.99' );
		update_post_meta( $productId, '_sale_price', '0.99' );
	}

	/**
	 * Common teardown code for tests
	 *
	 * @return void
	 */
	public static function tear_down() {
		$allposts = get_posts(
			array(
				'post_type'   => 'post',
				'numberposts' => -1,
			)
		);
		foreach ( $allposts as $eachpost ) {
			wp_delete_post( $eachpost->ID, true );
		}

		$allposts = get_posts(
			array(
				'post_type'   => 'product',
				'numberposts' => -1,
			)
		);
		foreach ( $allposts as $eachpost ) {
			wp_delete_post( $eachpost->ID, true );
		}

		$parentTerm = get_term_by( 'name', 'Parent category', 'category' );
		wp_delete_category( $parentTerm->term_id );

		$childTerm = get_term_by( 'name', 'Child category', 'category' );
		wp_delete_category( $childTerm->term_id );

		$user = get_user_by( 'login', 'test_user' );
		wp_delete_user( $user->ID );
	}
}
