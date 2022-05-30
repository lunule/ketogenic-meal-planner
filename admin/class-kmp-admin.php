<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kmp
 * @subpackage Kmp/admin
 * @author     Codeable <info@codeable.io>
 */
class Kmp_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			$this->plugin_name . '-chosen',
			plugin_dir_url( __FILE__ ) . 'css/chosen.min.css',
			array(),
			false,
			'all'
		);
		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/kmp-admin.css',
			array($this->plugin_name . '-chosen'),
			false,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			$this->plugin_name . '-chosen',
			plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.min.js',
			array('jquery'),
			false,
			true
		);
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/kmp-admin.js',
			array($this->plugin_name . '-chosen'),
			false,
			true
		);
		wp_localize_script(
			$this->plugin_name,
			'kmp',
			array(
				// The nonce value used to test the Rest API call MUST BE 'wp_rest'.
				'restNonce' => wp_create_nonce('wp_rest'),
				'ajaxNonce' => wp_create_nonce('kmpajax'),
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'siteUrl'   => get_site_url(),
				'pluginUrl' => plugin_dir_url( dirname(__FILE__) ),
			)
		);
	}
}
