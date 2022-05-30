<?php

/**
 * The admin Ajax request class
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/public
 */
class Kmp_Admin_Ajax {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function ajax_cb_get_pages() {

		// @note - nonce verification not working
		/*
		$nonce = $_GET['ajaxNonce'];
		if ( ! wp_verify_nonce( $nonce, 'kmpajax' ) ) {
			die();
		}
		*/

		// we will pass post IDs and titles to this array
		$return = array();

		// you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
		$search_results = new WP_Query( array(
			's'						=> $_GET['q'], // the search query
			'post_status' 			=> 'publish',  // if you don't want drafts to be returned
			'ignore_sticky_posts' 	=> 1,
			'posts_per_page' 		=> 50,         // how much to show at once
			'post_type' 			=> 'page',
		));

		if ( $search_results->have_posts() ) {
			while ( $search_results->have_posts() ) {
				$search_results->the_post();
				// shorten the title a little
				$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
				$return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
			}
		}
		wp_reset_query();
		echo json_encode( $return );
		die;
	}

	public function ajax_cb_get_wc_memberships() {

		// @note - nonce verification not working
		/*
		$nonce = $_GET['ajaxNonce'];
		if ( ! wp_verify_nonce( $nonce, 'kmpajax' ) ) {
			die();
		}
		*/

		// we will pass post IDs and titles to this array
		$return = array();

		// you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
		$search_results = new WP_Query( array(
			's'						=> $_GET['q'], 		// the search query
			'post_status' 			=> 'publish', 		// if you don't want drafts to be returned
			'ignore_sticky_posts' 	=> 1,
			'posts_per_page' 		=> 50, 				// how much to show at once
			'post_type' 			=> 'wc_membership_plan',
		));

		if( $search_results->have_posts() ) {
			while( $search_results->have_posts() ) {

				$search_results->the_post();
				// shorten the title a little
				$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
				$return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
			}
		}
		wp_reset_query();
		echo json_encode( $return );
		die;
	}
}
