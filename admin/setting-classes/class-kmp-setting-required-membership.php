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

class Kmp_Setting_Required_Membership extends Kmp_Admin_Settings implements Kmp_Setting {

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
			'kmp_field_required_membership',                // The ID of the settings field
			'Membership(s) Granting Full App Access',       // The name of the field of setting(s)
			array( $this, 'display' ),
			'kmp-plugin-settings',                          // ID of the page on which to display these fields
			'kmp_settings_section_plugin_settings'          // The ID of the setting section
		);
		register_setting(
			'kmp_option_group_plugin_settings',             // Group of options to display these fields
			'kmp_field_required_membership',                // Name of options = the ID of the settings field
			array( $this, 'sanitize' )                      // Sanitization function
		);
	}

	public function display() {

		// Now grab the options based on what we're looking for
		$rm_opt 		= get_option( 'kmp_field_required_membership' );
		$rm_cleanval 	= isset( $rm_opt ) ? $rm_opt : '';

		//echo "<input id='kmp_field_notification_email' name='kmp_field_notification_email' size='100' type='email' value='{$rm_cleanval}' />";

		$search_results = get_posts( array(
			'post_status' 			=> 'publish',
			'posts_per_page' 		=> -1,
			'post_type' 			=> 'wc_membership_plan',
		));

		$opt_val_Arr = explode(',', $rm_cleanval);

		ob_start(); ?>

			<select name="kfrm_pseudo" id="kfrm_pseudo" multiple>

				<?php
				if( !empty($search_results) ) :

					foreach ( $search_results as $post ) : ?>

						<option value="<?php echo $post->ID; ?>" <?php if ( in_array($post->ID, $opt_val_Arr) ) echo 'selected="selected"'; ?>><?php echo $post->post_title; ?></option>

					<?php
					endforeach;

				endif;
				?>

			</select>

			<input type="hidden" id="kmp_field_required_membership" name="kmp_field_required_membership" value="<?php echo $rm_cleanval; ?>" />

		<?php
		wp_reset_query();
		echo ob_get_clean();
	}

	public function sanitize( $input ) {
		// var_dump($input);
		// Sanitize the information
		$new_input 	= $input;
		return $new_input;
	}
}
