<?php

/**
 * Class handling the custom Rest API endpoint.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/admin
 */

class Kmp_Admin_Rest_Api {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function register_custom_rest_routes() {
		register_rest_route( 'kmp/v2', '/meals/', array(
			'methods' 		=> 'GET',
			'callback' 		=> array( $this, 'get_meals' ),
			'permission_callback' => '__return_true',
			'show_in_index' => false,
		));
		register_rest_route('kmp/v2', '/uid/', [
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_uid' ),
			'permission_callback' => '__return_true',
			'show_in_index' => false,
		]);
	}

	function get_meals() {
		global $wpdb;
		$list = $wpdb->get_results( " SELECT DISTINCT kmpid, name FROM ".$wpdb->prefix . "kmp_meal_data " );
		return $list;

	}

	function get_uid($data) {
		$user_is_platinum_member = 0;
		if (class_exists( 'woocommerce' ) && is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' )) {
			$user_memberships_Arr = wc_memberships_get_user_active_memberships();
			$plan_ids_Arr = [];
			foreach ( $user_memberships_Arr as $m ) {
				$plan_ids_Arr[] = $m->plan_id;
			}
			if ( in_array( PLATINUM_MEMBERSHIP_ID , $plan_ids_Arr ) ) {
				$user_is_platinum_member = 1;
			}
		}
		$data = array(
			'uid'  => get_current_user_id(),
			'plat' => $user_is_platinum_member
		);
		$response = new WP_REST_Response($data, 200);
		$response->set_headers(['Cache-Control' => 'must-revalidate, no-cache, no-store, private']);
		return $response;
	}
}
