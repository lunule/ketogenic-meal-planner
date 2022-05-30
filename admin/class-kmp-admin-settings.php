<?php

/**
 * The admin settings class.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/admin
 */
class Kmp_Admin_Settings {

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
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	public function add_menu_items() {
		add_menu_page(
			'Ketogenic Meal Planner - Plugin Settings',             // Page title
			'Ketogenic Meal Planner',                               // Menu title
			'edit_others_posts',                                    // Capability
			'edit.php?post_type=kmp-meal',                          // Menu slug
			// WE DON'T NEED A CALLBACK FUNCTION HERE - we show the custom post type edit
			// screen instead
			//array( $this, 'submenu_cb_options_plugin_settings' ), // Callback function
			'',                                                     // Callback function
			'dashicons-carrot',                                     // Icon URL
			30                                                      // Position
		);
		add_submenu_page(
			'edit.php?post_type=kmp-meal',                          // Parent Menu Slug
			'All Meals',                                            // Page title
			'All Meals',                                            // Menu title
			'edit_others_posts',                                    // Capability
			'edit.php?post_type=kmp-meal',                          // Menu slug
			''                                                      // Callback function
		);
		add_submenu_page(
			'edit.php?post_type=kmp-meal',                          // Parent Menu Slug
			'Add New Meal',                                         // Page title
			'Add New Meal',                                         // Menu title
			'edit_others_posts',                                    // Capability
			'post-new.php?post_type=kmp-meal',                      // Menu slug
			''                                                      // Callback function
		);
		add_submenu_page(
			'edit.php?post_type=kmp-meal',                          // Parent Menu Slug
			'All Submissions',                                      // Page title
			'All Submissions',                                      // Menu title
			'edit_others_posts',                                    // Capability
			'edit.php?post_type=kmp-submission',                    // Menu slug
			''                                                      // Callback function
		);
		add_submenu_page(
			'edit.php?post_type=kmp-meal',                          // Parent Menu Slug
			'Add New Submission',                                   // Page title
			'Add New Submission',                                   // Menu title
			'edit_others_posts',                                    // Capability
			'post-new.php?post_type=kmp-submission',                // Menu slug
			''                                                      // Callback function
		);
		add_submenu_page(
			'edit.php?post_type=kmp-meal',                          // Parent Menu Slug
			'Ketogenic Meal Planner - Plugin Settings',             // Page title
			'Plugin Settings',                                      // Menu title
			'edit_others_posts',                                    // Capability
			'kmp-plugin-settings',                                  // Menu slug
			array( $this, 'submenu_cb_options_plugin_settings' )    // Callback function
		);
		/*
		add_submenu_page(
			'kmp-meals',
			'Ketogenic Meal Planner - Meals',
			'Meals',
			'edit_others_posts',
			'kmp-meals',
			array( $this, 'submenu_cb_options_meals' )
		);
		*/
		add_submenu_page(
			'edit.php?post_type=kmp-meal',
			'Ketogenic Meal Planner - Import',
			'Import',
			'edit_others_posts',
			'kmp-import',
			array( $this, 'submenu_cb_options_import' )
		);

		/**
		 * The below unset can be used ONLY if you want the parent menu item to link to the
		 * FIRST NON-DUPLICATE submenu item.
		 *
		 * SO, in our case, it can't be used, as we want the parent item to link to the Meals
		 * subitem - which is the SECOND NON-DUPLICATE submenu item - in this specific case
		 * the duplicate item should be removed with CSS. (Using JS to actually REMOVE the
		 * item would be totally superfluous, as the existence of the hidden duplicate item
		 * doesn't mean any security concern, neither the user can have any reason to
		 * experiment/play with it. )
		 */
		//unset($GLOBALS['submenu']['kmp-meals'][0]);

	}

	public function add_settings_sections() {
		/**
		 * add_settings_section just creates a section with the second parameter as the
		 * section title (can be empty), the section being used in and AS THE LAST PARAMETER
		 * OF the add_settings_field() function - this means it's required, BUT THE SECOND
		 * AND THE THIRD PARAMETERS CAN BE EMPTY, as they don't add anything special, and as
		 * the section title and the callback function's output can be added directly to the
		 * view file's HTML. FOR INSTANCE, check the /admin/views/view-kmp-plugin-settings.php
		 * file.
		 */
		add_settings_section(
			'kmp_settings_section_plugin_settings',         // ID of the settings section
			'',                                             // Title of the section
			'',                                             // Callback function that fills the section
			'kmp-plugin-settings'                           // The slug-name of the settings page on which to show the section.
		);
		/*
		add_settings_section(
			'kmp_settings_section_meals',
			'List of Meals',
			array( $this, 'section_cb_meals' ),
			'kmp-meals'
		);
		*/
		add_settings_section(
			'kmp_settings_section_import',
			'Import CSV',
			array( $this, 'section_cb_import' ),
			'kmp-import'
		);
	}

	// This is the SUBMENU PAGE CONTENT CALLBACK FUNCTION - this is the one
	// where you use do_settings_section([page-slug]) WHICH OUTPUTS THE CONTENT
	// DEFINED IN THE CALLBACK FUNCTIONS OF ALL ._SECTIONS_. BINDED TO THIS
	// SPECIFIC PLUGIN PAGE
	public function submenu_cb_options_plugin_settings($args) {
		include_once KMP_ADMIN_DIR_PATH . 'views/view-kmp-plugin-settings.php';
	}

	/*
	public function submenu_cb_options_meals($args) {
		// ...
	}
	*/

	public function submenu_cb_options_import($args) {

		/**
		 * TESTING THE SETTINGS API
		 * ========================================================================================
		 */

		// do_settings_section requires a PAGE SLUG, not a SECTION SLUG - and actually,
		// it outputs oll the sections "binded" to a specific plugin page, and not a
		// specific section.
		do_settings_sections( 'kmp-import' );

		/**
		 * TESTING THE CSV IMPORTER
		 * ========================================================================================
		 */
		// Import CSV
		if ( isset($_POST['butimport']) ) :

			// File extension
			$extension = pathinfo( $_FILES['import_file']['name'], PATHINFO_EXTENSION );

			// If file extension is 'csv'
			if ( !empty( $_FILES['import_file']['name'] ) && $extension == 'csv' ) :

				$totalInserted = 0;

				// Open file in read mode
				$csvFile = fopen( $_FILES['import_file']['tmp_name'], 'r' );

				fgetcsv( $csvFile ); // Skipping header row

				// Read file
				while ( ( $csvData = fgetcsv($csvFile) ) !== FALSE ) :

					$csvData = array_map("utf8_encode", $csvData);

					// Row column length
					$dataLen = count($csvData);

					// var_dump($dataLen);

					// Skip row if length != 19
					if ( !($dataLen == 19) ) continue;

					// Assign value to variables

					$calorierange       = trim($csvData[0]);
					$kmpid              = trim($csvData[1]);
					$meal               = trim($csvData[2]);
					$name               = trim($csvData[3]);
					$ingredients        = trim($csvData[4]);
					$calories           = trim($csvData[5]);
					$fat                = trim($csvData[6]);
					$carbs              = trim($csvData[7]);
					$fiber              = trim($csvData[8]);
					$netcarbs           = trim($csvData[9]);
					$protein            = trim($csvData[10]);

					// Do not trim boolean values.
					$vegetarian         = $csvData[11];
					$vegan              = $csvData[12];
					$carnivore          = $csvData[13];
					$dairyfree          = $csvData[14];
					$eggfree            = $csvData[15];
					$beginnerfriendly   = $csvData[16];
					$advanced           = $csvData[17];
					$url                = trim($csvData[18]);

					$chck_args_Arr = array(
						'posts_per_page' => -1,
						'post_type'      => 'kmp-meal',
						'meta_query'     => array(
							array(
								'key'    => 'kmp-cf-kmpid',
								'value'  => $kmpid,
							),
						),
					);
					$chck_posts_Arr = get_posts( $chck_args_Arr );

					// If there's no record with the current .csv row's kmp id, the import
					// is greenlighted
					if ( count( $chck_posts_Arr ) == 0 ) :

						/**
						 * CAREFUL. Here you DON'T QUERY FOR AN $id VALUE.
						 * Also, the CSV file SHOULDN'T HAVE SUCH COLUMN EITHER,
						 * BUT THE DATABASE TABLE SHOULD.
						 */

						// Check some of the row values if empty or not - e.g. a record where
						// the calorierang/kmpid/meal/name data is missing is certainly an
						// incorrect row/record.
						if (
							!empty($calorierange) &&
							!empty($kmpid) &&
							!empty($meal) &&
							!empty($name)
						) :

							// insert the post and set the category
							$post_id = wp_insert_post(array (
								'post_type' => 'kmp-meal',
								'post_title' => $name,
								'post_content' => $ingredients,
								'post_status' => 'publish',
								'comment_status' => 'closed',   // if you prefer
								'ping_status' => 'closed',      // if you prefer
							));

							$vegetarian = ( '0' == intval($vegetarian) ) ? 0 : 1;
							$vegan      = ( '0' == intval($vegan) ) ? 0 : 1;
							$carnivore  = ( '0' == intval($carnivore) ) ? 0 : 1;
							$dairyfree  = ( '0' == intval($dairyfree) ) ? 0 : 1;
							$eggfree    = ( '0' == intval($eggfree) ) ? 0 : 1;
							$beginner   = ( '0' == intval($beginner) ) ? 0 : 1;
							$advanced   = ( '0' == intval($advanced) ) ? 0 : 1;

							if ($post_id) :
								update_post_meta( $post_id, 'kmp-cf-calorierange', $calorierange );
								update_post_meta( $post_id, 'kmp-cf-kmpid', $kmpid );
								update_post_meta( $post_id, 'kmp-cf-mealtype', strtolower($meal) );
								update_post_meta( $post_id, 'kmp-cf-calories', $calories );
								update_post_meta( $post_id, 'kmp-cf-fat', $fat );
								update_post_meta( $post_id, 'kmp-cf-carbs', $carbs );
								update_post_meta( $post_id, 'kmp-cf-fiber', $fiber );
								update_post_meta( $post_id, 'kmp-cf-netcarbs', $netcarbs );
								update_post_meta( $post_id, 'kmp-cf-protein', $protein );
								update_post_meta( $post_id, 'kmp-cf-vegetarian', $vegetarian );
								update_post_meta( $post_id, 'kmp-cf-vegan', $vegan );
								update_post_meta( $post_id, 'kmp-cf-carnivore', $carnivore );
								update_post_meta( $post_id, 'kmp-cf-dairyfree', $dairyfree );
								update_post_meta( $post_id, 'kmp-cf-eggfree', $eggfree );
								update_post_meta( $post_id, 'kmp-cf-beginner', $beginnerfriendly );
								update_post_meta( $post_id, 'kmp-cf-advanced', $advanced );
								update_post_meta( $post_id, 'kmp-cf-url', $url );
							endif;

							if ( $wpdb->insert_id > 0 ) :
								$totalInserted++;
							endif;

						endif;

					endif;

				endwhile;

				echo "<h3 style='color: green;'>Total record Inserted : " . $totalInserted . "</h3>";

			else :

				echo "<h3 style='color: red;'>Invalid Extension</h3>";

			endif;

		endif;

		?>

		<!-- Form -->
		<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>
			<input type="file" name="import_file" >
			<input type="submit" name="butimport" value="Import">
		</form>

		<h2>All Entries</h2>

		<div class="kmp-import-table-wrap">

			<!-- Record List -->
			<table border="1">

				<thead>

					<tr>

						<?php
						/**
						 * CAREFUL. HERE, YOU SHOULD CREATE A TH FOR THE DATABASE TABLE'S
						 * AUTO-INCREMENT ID COLUMN.
						 * ---
						 * A REMINDER: the .CSV file SHOULDN'T have an ID column, but the database
						 * table SHOULD.
						 */
						?>

						<th>ID</th>
						<th>Calorie Range</th>
						<th>KMP ID</th>
						<th>Meal</th>
						<th>Name</th>
						<th>Ingredients</th>
						<th>Calories</th>
						<th>Fat</th>
						<th>Carbs</th>
						<th>Fiber</th>
						<th>Net Carbs</th>
						<th>Protein</th>
						<th>Vegetarian</th>
						<th>Vegan</th>
						<th>Carnivore</th>
						<th>Dairy-Free</th>
						<th>Egg-Free</th>
						<th>Beginner Friendly</th>
						<th>Advanced</th>
						<th>URL(s)</th>

					</tr>

				</thead>

				<tbody>

					<?php
					// Fetch records
					$meal_query_args_Arr = array(
						'post_type'      => 'kmp-meal',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
					);
					$meal_posts_Arr = get_posts( $meal_query_args_Arr );

					if ( count($meal_posts_Arr) > 0 ) :

						$count = 0;

						foreach ( $meal_posts_Arr as $post ) :

							$id                 = $post->ID;
							$calorierange       = get_post_meta($post->ID, 'kmp-cf-calorierange')[0];
							$kmpid              = get_post_meta($post->ID, 'kmp-cf-kmpid')[0];
							$meal               = get_post_meta($post->ID, 'kmp-cf-mealtype')[0];
							$name               = $post->post_title;
							$ingredients        = $post->post_content;
							$calories           = get_post_meta($post->ID, 'kmp-cf-calories')[0];
							$fat                = get_post_meta($post->ID, 'kmp-cf-fat')[0];
							$carbs              = get_post_meta($post->ID, 'kmp-cf-carbs')[0];
							$fiber              = get_post_meta($post->ID, 'kmp-cf-fiber')[0];
							$netcarbs           = get_post_meta($post->ID, 'kmp-cf-netcarbs')[0];
							$protein            = get_post_meta($post->ID, 'kmp-cf-protein')[0];
							$vegetarian         = get_post_meta($post->ID, 'kmp-cf-vegetarian')[0];
							$vegan              = get_post_meta($post->ID, 'kmp-cf-vegan')[0];
							$carnivore          = get_post_meta($post->ID, 'kmp-cf-carnivore')[0];
							$dairyfree          = get_post_meta($post->ID, 'kmp-cf-dairyfree')[0];
							$eggfree            = get_post_meta($post->ID, 'kmp-cf-eggfree')[0];
							$beginnerfriendly   = get_post_meta($post->ID, 'kmp-cf-beginner')[0];
							$advanced           = get_post_meta($post->ID, 'kmp-cf-advanced')[0];
							$url                = get_post_meta($post->ID, 'kmp-cf-url')[0];
							?>

							<tr>

								<?php
								/**
								 * CAREFUL. HERE, YOU SHOULD CREATE A TD FOR THE DATABASE TABLE'S
								 * AUTO-INCREMENT ID COLUMN.
								 * ---
								 * A REMINDER: the .CSV file SHOULDN'T have an ID column, but the
								 * database table SHOULD.
								 */
								?>

								<td><?php echo $id; ?></td>
								<td><?php echo $calorierange; ?></td>
								<td><?php echo $kmpid; ?></td>
								<td><?php echo $meal; ?></td>
								<td><?php echo $name; ?></td>
								<td><?php echo $ingredients; ?></td>
								<td><?php echo $calories; ?></td>
								<td><?php echo $fat; ?></td>
								<td><?php echo $carbs; ?></td>
								<td><?php echo $fiber; ?></td>
								<td><?php echo $netcarbs; ?></td>
								<td><?php echo $protein; ?></td>
								<td><?php echo $vegetarian; ?></td>
								<td><?php echo $vegan; ?></td>
								<td><?php echo $carnivore; ?></td>
								<td><?php echo $dairyfree; ?></td>
								<td><?php echo $eggfree; ?></td>
								<td><?php echo $beginnerfriendly; ?></td>
								<td><?php echo $advanced; ?></td>
								<td><?php echo $url; ?></td>

							</tr>

						<?php
						endforeach;

					else :

						echo "<tr><td colspan='20'>No record found</td></tr>";

					endif;
					?>

				</tbody>

			</table>

		</div>

	<?php
	}

	/**
	 * This is A SECTION CALLBACK FUNCTION - the content specified here will be displayed on the
	 * plugin page ONLY IF do_settings_sections([page-id]) IS USED IN THE SUBMENU PAGE/OPTIONS
	 * PAGE CALLBACK FUNCTION WITH THE ID (SLUG) OF THE SAME SUBMENU/OPTIONS PAGE USED IN THIS
	 * SPECIFIC SECTION'S add_settings_section() DECLARATION.
	 *
	 * THIS SPECIFIC SECTION DOESN'T OUTPUT ANYTHING, AS IT HAS BEEN DEACTIVATED IN THE RELATED
	 * add_settings_section() CALL.
	 */
	public function section_cb_plugin_settings($args) {
		/**
		 * TESTING THE SECTION CALLBACK FUNCTION'S $args PARAMETER
		 * ========================================================================================
		 */
		/*echo str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' .     var_export($args, true) . ' ?>', true ) ); */
	}

	/*
	public function section_cb_meals($args) {
		// TESTING THE SECTION CALLBACK FUNCTION'S $args PARAMETER
		echo str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' .     var_export($args, true) . ' ?>', true ) );
	}
	*/

	public function section_cb_import($args) {
		/**
		 * TESTING THE SECTION CALLBACK FUNCTION'S $args PARAMETER
		 * ========================================================================================
		 */
		/*echo str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' .     var_export($args, true) . ' ?>', true ) );*/
	}
}
