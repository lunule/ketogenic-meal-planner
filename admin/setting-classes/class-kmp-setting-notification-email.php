<?php
/**
 * Registers the setting, and handles the associated field's front-end display and sanitization.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/admin
 */

class Kmp_Setting_Notification_Email extends Kmp_Admin_Settings implements Kmp_Setting {

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


	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function register() {
		add_settings_field(
			'kmp_field_notification_email',         // The ID of the settings field
			'Submission Notification Email',        // The name of the field of setting(s)
			array( $this, 'display' ),
			'kmp-plugin-settings',                  // ID of the page on which to display these fields
			'kmp_settings_section_plugin_settings'  // The ID of the setting section
		);
		register_setting(
			'kmp_option_group_plugin_settings',     // Group of options to display these fields
			'kmp_field_notification_email',         // Name of options = the ID of the settings field
			array( $this, 'sanitize' )              // Sanitization function
		);
	}

	public function display() {
		// Now grab the options based on what we're looking for
		$ne_opt 		= get_option( 'kmp_field_notification_email' );
		$ne_cleanval 	= isset( $ne_opt ) ? $ne_opt : '';
		echo "<input id='kmp_field_notification_email' name='kmp_field_notification_email' size='100' type='email' value='{$ne_cleanval}' />";
	}

	public function sanitize( $input ) {
		// Sanitize the information
		$new_input 	= sanitize_email( $input );
		return $new_input;
	}
}
