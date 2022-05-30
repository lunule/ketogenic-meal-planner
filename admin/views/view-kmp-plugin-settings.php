<?php
/**
 * Displays the UI for editing information with the Ketogenic Meal Planner Plugin
 * Dashboard's Plugin Settings page.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/admin
 */
?>

<div class="wrap">

	<h1>Ketogenic Meal Planner <span>Plugin Settings</span></h1>

	<section class="kmp-section kmp-section--plugin_settings__main">

		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'kmp_option_group_plugin_settings' );  // Group otion ID

				// do_settings_section requires a PAGE SLUG, not a SECTION SLUG, and
				// it outputs all the sections "binded" to a specific plugin page, and not a
				// specific section.
				do_settings_sections( 'kmp-plugin-settings' ); // ID of the page on which the options will be displayed

				submit_button();
			?>
		</form>

	</section>

</div><!-- .wrap -->
