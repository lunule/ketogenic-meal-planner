<?php

/**
 * Metabox class.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/admin
 * @author     Codeable <info@codeable.io>
 */

class Kmp_Admin_Metaboxes {

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
	 * Register the metaboxes.
	 *
	 * @since    1.0.0
	 */
	public function add_metaboxes() {
		add_meta_box(
			'kmp_meal_metabox',                                 // id
			__( 'Meal Settings', 'kmp' ),                       // title
			array( $this, 'render_kmp_meal_metabox' ),          // callback
			'kmp-meal',                                         // screen
			'advanced',                                         // context
			'high',                                             // priority
			array( 'foo' => 'foo_val', 'bar' => 'bar_val' ),    // args - here for testing and debugging purposes only
		);
		add_meta_box(
			'kmp_submission_metabox',                           // id
			__( 'Submission Values', 'kmp' ),                   // title
			array( $this, 'render_kmp_submission_metabox' ),    // callback
			'kmp-submission',                                   // screen
			'advanced',                                         // context
			'high',                                             // priority
			array( 'foo' => 'foo_val', 'bar' => 'bar_val' ),    // args - here for testing and debugging purposes only
		);
	}

	public function render_kmp_meal_metabox($post, $args) {
		// $args = the args specified as the last parameter of add_meta_box()
		/*echo str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' .     var_export($args, true) . ' ?>', true ) );*/

		wp_nonce_field( basename( __FILE__ ), 'kmp_mb_nonce' );
		$post_stored_meta = get_post_meta( $post->ID );
		?>

		<div class="kmp-cf-group-container kcgc--meal-identifiers">

			<div class="kmp-flex-container">

				<div class="kmp-flex-item kmpid">

					<p class="mb-0">
						<label for="kmp-cf-kmpid" class="kmp-mb-label"><?php _e( 'KMP ID', 'kmp' )?></label>
						<input
							type="text"
							name="kmp-cf-kmpid"
							id="kmp-cf-kmpid"
							class="kmp-mb-input kmp-mb-input--text"
							minlength="2"
							maxlength="10"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-kmpid'] ) ) echo $post_stored_meta['kmp-cf-kmpid'][0]; ?>"
						/>
					</p>

				</div>

				<div class="kmp-flex-item calorierange">

					<p class="mb-0">
						<label for="kmp-cf-calorierange" class="kmp-mb-label"><?php _e( 'Calorie Range', 'kmp' )?></label>
						<input
							type="number"
							name="kmp-cf-calorierange"
							id="kmp-cf-calorierange"
							class="kmp-mb-input kmp-mb-input--number"
							min="200"
							max="3000"
							step="100"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-calorierange'] ) ) echo $post_stored_meta['kmp-cf-calorierange'][0]; ?>"
						/>
					</p>

				</div>

				<div class="kmp-flex-item mealtype">

					<p class="mb-0">

						<label for="kmp-cf-mealtype" class="kmp-mb-label"><?php _e( 'Meal Type', 'kmp' )?></label>
						<select
							name="kmp-cf-mealtype"
							id="kmp-cf-mealtype"
						>
							<option value="novalue" selected>Select meal type</option>
							<option value="breakfast" <?php if ( isset ( $post_stored_meta['kmp-cf-mealtype'] ) ) selected( $post_stored_meta['kmp-cf-mealtype'][0], 'breakfast' ); ?>><?php _e( 'Breakfast', 'kmp' )?></option>
							<option value="lunch" <?php if ( isset ( $post_stored_meta['kmp-cf-mealtype'] ) ) selected( $post_stored_meta['kmp-cf-mealtype'][0], 'lunch' ); ?>><?php _e( 'Lunch', 'kmp' )?></option>
							<option value="dinner" <?php if ( isset ( $post_stored_meta['kmp-cf-mealtype'] ) ) selected( $post_stored_meta['kmp-cf-mealtype'][0], 'dinner' ); ?>><?php _e( 'Dinner', 'kmp' )?></option>
							<option value="snack" <?php if ( isset ( $post_stored_meta['kmp-cf-mealtype'] ) ) selected( $post_stored_meta['kmp-cf-mealtype'][0], 'snack' ); ?>><?php _e( 'Snack', 'kmp' )?></option>
							<option value="dessert" <?php if ( isset ( $post_stored_meta['kmp-cf-mealtype'] ) ) selected( $post_stored_meta['kmp-cf-mealtype'][0], 'dessert' ); ?>><?php _e( 'Dessert', 'kmp' )?></option>
						</select>

					</p>

				</div>

			</div>

		</div>

		<div class="kmp-cf-group-container kcgc--meal-data">

			<div class="kmp-flex-container kmp-flex-container--wrap">

				<div class="kmp-flex-item calories">

					<p>
						<label for="kmp-cf-calories" class="kmp-mb-label"><?php _e( 'Calories', 'kmp' )?></label>
						<input
							type="number"
							name="kmp-cf-calories"
							id="kmp-cf-calories"
							class="kmp-mb-input kmp-mb-input--number"
							min="100"
							max="3000"
							step="1"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-calories'] ) ) echo $post_stored_meta['kmp-cf-calories'][0]; ?>"
						/>
					</p>

				</div>

				<div class="kmp-flex-item fat">

					<p>
						<label for="kmp-cf-fat" class="kmp-mb-label"><?php _e( 'Fat', 'kmp' )?></label>
						<input
							type="number"
							name="kmp-cf-fat"
							id="kmp-cf-fat"
							class="kmp-mb-input kmp-mb-input--number"
							min="0"
							max="200"
							step=".1"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-fat'] ) ) echo $post_stored_meta['kmp-cf-fat'][0]; ?>"
						/>
					</p>

				</div>

				<div class="kmp-flex-item carbs">

					<p class="mb-0">
						<label for="kmp-cf-carbs" class="kmp-mb-label"><?php _e( 'Carbs', 'kmp' )?></label>
						<input
							type="number"
							name="kmp-cf-carbs"
							id="kmp-cf-carbs"
							class="kmp-mb-input kmp-mb-input--number"
							min="0"
							max="200"
							step=".1"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-carbs'] ) ) echo $post_stored_meta['kmp-cf-carbs'][0]; ?>"
						/>
					</p>

				</div>

				<div class="kmp-flex-item fiber">

					<p>
						<label for="kmp-cf-fiber" class="kmp-mb-label"><?php _e( 'Fiber', 'kmp' )?></label>
						<input
							type="number"
							name="kmp-cf-fiber"
							id="kmp-cf-fiber"
							class="kmp-mb-input kmp-mb-input--number"
							min="0"
							max="200"
							step=".1"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-fiber'] ) ) echo $post_stored_meta['kmp-cf-fiber'][0]; ?>"
						/>
					</p>

				</div>

				<div class="kmp-flex-item netcarbs">

					<p class="mb-0">
						<label for="kmp-cf-netcarbs" class="kmp-mb-label"><?php _e( 'Net Carbs', 'kmp' )?></label>
						<input
							type="number"
							name="kmp-cf-netcarbs"
							id="kmp-cf-netcarbs"
							class="kmp-mb-input kmp-mb-input--number"
							min="0"
							max="200"
							step=".1"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-netcarbs'] ) ) echo $post_stored_meta['kmp-cf-netcarbs'][0]; ?>"
						/>
					</p>

				</div>

				<div class="kmp-flex-item protein">

					<p class="mb-0">
						<label for="kmp-cf-protein" class="kmp-mb-label"><?php _e( 'Protein', 'kmp' )?></label>
						<input
							type="number"
							name="kmp-cf-protein"
							id="kmp-cf-protein"
							class="kmp-mb-input kmp-mb-input--number"
							min="0"
							max="200"
							step=".1"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-protein'] ) ) echo $post_stored_meta['kmp-cf-protein'][0]; ?>"
						/>
					</p>

				</div>

			</div>

		</div>

		<div class="kmp-cf-group-container kcgc--meal-categories">

			<div class="kmp-flex-container kmp-flex-container--wrap">

				<div class="kmp-flex-item vegetarian">

					<p>

						<label for="kmp-cf-vegetarian" class="kmp-mb-label kmp-mb-label--checkbox">

							<input
								type="checkbox"
								name="kmp-cf-vegetarian"
								id="kmp-cf-vegetarian"
								value="0"
								<?php if ( isset ( $post_stored_meta['kmp-cf-vegetarian'] ) ) checked( $post_stored_meta['kmp-cf-vegetarian'][0], '1' ); ?>
							/>

							<span><?php _e( 'Vegetarian', 'kmp' )?></span>

						</label>

					</p>

				</div>

				<div class="kmp-flex-item vegan">

					<p>

						<label for="kmp-cf-vegan" class="kmp-mb-label kmp-mb-label--checkbox">

							<input
								type="checkbox"
								name="kmp-cf-vegan"
								id="kmp-cf-vegan"
								value="0"
								<?php if ( isset ( $post_stored_meta['kmp-cf-vegan'] ) ) checked( $post_stored_meta['kmp-cf-vegan'][0], '1' ); ?>
							/>

							<span><?php _e( 'Vegan', 'kmp' )?></span>

						</label>

					</p>

				</div>

				<div class="kmp-flex-item carnivore">

					<p>

						<label for="kmp-cf-carnivore" class="kmp-mb-label kmp-mb-label--checkbox">

							<input
								type="checkbox"
								name="kmp-cf-carnivore"
								id="kmp-cf-carnivore"
								value="0"
								<?php if ( isset ( $post_stored_meta['kmp-cf-carnivore'] ) ) checked( $post_stored_meta['kmp-cf-carnivore'][0], '1' ); ?>
							/>

							<span><?php _e( 'Carnivore', 'kmp' )?></span>

						</label>

					</p>

				</div>

				<div class="kmp-flex-item dairyfree">

					<p>

						<label for="kmp-cf-dairyfree" class="kmp-mb-label kmp-mb-label--checkbox">

							<input
								type="checkbox"
								name="kmp-cf-dairyfree"
								id="kmp-cf-dairyfree"
								value="0"
								<?php if ( isset ( $post_stored_meta['kmp-cf-dairyfree'] ) ) checked( $post_stored_meta['kmp-cf-dairyfree'][0], '1' ); ?>
							/>

							<span><?php _e( 'Dairy-Free', 'kmp' )?></span>

						</label>

					</p>

				</div>

				<div class="kmp-flex-item eggfree">

					<p class="mb-0">

						<label for="kmp-cf-eggfree" class="kmp-mb-label kmp-mb-label--checkbox">

							<input
								type="checkbox"
								name="kmp-cf-eggfree"
								id="kmp-cf-eggfree"
								value="0"
								<?php if ( isset ( $post_stored_meta['kmp-cf-eggfree'] ) ) checked( $post_stored_meta['kmp-cf-eggfree'][0], '1' ); ?>
							/>

							<span><?php _e( 'Egg-Free', 'kmp' )?></span>

						</label>

					</p>

				</div>

				<div class="kmp-flex-item beginner">

					<p class="mb-0">

						<label for="kmp-cf-beginner" class="kmp-mb-label kmp-mb-label--checkbox">

							<input
								type="checkbox"
								name="kmp-cf-beginner"
								id="kmp-cf-beginner"
								value="0"
								<?php if ( isset ( $post_stored_meta['kmp-cf-beginner'] ) ) checked( $post_stored_meta['kmp-cf-beginner'][0], '1' ); ?>
							/>

							<span><?php _e( 'Beginner', 'kmp' )?></span>

						</label>

					</p>

				</div>

				<div class="kmp-flex-item advanced">

					<p class="mb-0">

						<label for="kmp-cf-advanced" class="kmp-mb-label kmp-mb-label--checkbox">

							<input
								type="checkbox"
								name="kmp-cf-advanced"
								id="kmp-cf-advanced"
								value="0"
								<?php if ( isset ( $post_stored_meta['kmp-cf-advanced'] ) ) checked( $post_stored_meta['kmp-cf-advanced'][0], '1' ); ?>
							/>

							<span><?php _e( 'Advanced', 'kmp' )?></span>

						</label>

					</p>

				</div>

			</div>

		</div>

		<div class="kmp-cf-group-container kcgc--meal-url">

			<div class="kmp-flex-container">

				<div class="kmp-flex-item kmp-flex-item--fullwidth url">

					<p>
						<label for="kmp-cf-url" class="kmp-mb-label"><?php _e( 'Meal URL', 'kmp' )?></label>
						<textarea
							type="text"
							name="kmp-cf-url"
							id="kmp-cf-url"
							class="kmp-mb-input kmp-mb-input--text"
							minlength="10"
							rows="2"
						><?php if ( isset ( $post_stored_meta['kmp-cf-url'] ) ) echo $post_stored_meta['kmp-cf-url'][0]; ?></textarea>
					</p>

					<div class="kmp-mb-notice">

						<p>You can specify here the URL of the recipe post corresponding to this specific meal. If a featured image is specified for the recipe post, the meal planner app will pull this image and display it in the meal card.</p>

						<p class="mb-0">Please specify one recipe post URL only - even if multiple urls are specified here, the app pulls the image based on the first url, and ignores the other one(s).</p>

					</div>

				</div>

			</div>

		</div>

		<?php
	}

	public function save_kmp_meal_metabox($post_id) {

		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'kmp_mb_nonce' ] ) && wp_verify_nonce( $_POST[ 'kmp_mb_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}

		if (
				isset( $_POST[ 'kmp-cf-kmpid' ] ) &&
				( strlen( $_POST[ 'kmp-cf-kmpid' ] ) >= 2 ) &&
				( strlen( $_POST[ 'kmp-cf-kmpid' ] ) <= 10 )
		) {
			update_post_meta( $post_id, 'kmp-cf-kmpid', sanitize_text_field( $_POST[ 'kmp-cf-kmpid' ] ) );
		}

		if (
				isset( $_POST[ 'kmp-cf-calorierange' ] ) &&
				is_numeric( $_POST[ 'kmp-cf-calorierange' ] ) &&
				( $_POST[ 'kmp-cf-calorierange' ] >= 200 ) &&
				( $_POST[ 'kmp-cf-calorierange' ] <= 3000 )
		) {
			update_post_meta( $post_id, 'kmp-cf-calorierange', absint( $_POST[ 'kmp-cf-calorierange' ] ) );
		}

		$mealtype_vals_Arr = array(
			'breakfast',
			'lunch',
			'dinner',
			'snack',
			'dessert',
		);
		if (
				isset( $_POST[ 'kmp-cf-mealtype' ] ) &&
				in_array( $_POST[ 'kmp-cf-mealtype' ], $mealtype_vals_Arr )
		) {
			update_post_meta( $post_id, 'kmp-cf-mealtype', sanitize_text_field( $_POST[ 'kmp-cf-mealtype' ] ) );
		}

		if (
				isset( $_POST[ 'kmp-cf-calories' ] ) &&
				is_numeric( $_POST[ 'kmp-cf-calories' ] ) &&
				( $_POST[ 'kmp-cf-calories' ] >= 100 ) &&
				( $_POST[ 'kmp-cf-calories' ] <= 3000 )
		) {
			update_post_meta( $post_id, 'kmp-cf-calories', absint( $_POST[ 'kmp-cf-calories' ] ) );
		}

		$opt_name_suff_1_Arr = array(
			'fat',
			'fiber',
			'carbs',
			'netcarbs',
			'protein',
		);
		foreach ( $opt_name_suff_1_Arr as $suffix ) {
			if (
					isset( $_POST[ 'kmp-cf-' . $suffix ] ) &&
					is_numeric( $_POST[ 'kmp-cf-' . $suffix ] ) &&
					( $_POST[ 'kmp-cf-' . $suffix ] >= 0 ) &&
					( $_POST[ 'kmp-cf-' . $suffix ] <= 200 )
			) {
				update_post_meta( $post_id, 'kmp-cf-' . $suffix, absint( $_POST[ 'kmp-cf-' . $suffix ] ) );
			}
		}

		$opt_name_suff_2_Arr = array(
			'vegetarian',
			'vegan',
			'carnivore',
			'dairyfree',
			'eggfree',
			'beginner',
			'advanced',
		);
		foreach ( $opt_name_suff_2_Arr as $suffix ) {

			if ( isset( $_POST[ 'kmp-cf-' . $suffix ] ) ) {
				update_post_meta( $post_id, 'kmp-cf-' . $suffix, '1' );
			} else {
				update_post_meta( $post_id, 'kmp-cf-' . $suffix, '0' );
			}

		}

		if ( isset( $_POST[ 'kmp-cf-url' ] ) ) {
			update_post_meta( $post_id, 'kmp-cf-url', $_POST[ 'kmp-cf-url' ] );
		}
	}

	public function kmp_meal_remove_postlist_columns_seo( $columns ) {
		unset($columns['wpseo-score']);
		unset($columns['wpseo-score-readability']);
		unset($columns['wpseo-title']);
		unset($columns['wpseo-metadesc']);
		unset($columns['wpseo-focuskw']);
		unset($columns['wpseo-links']);
		unset($columns['wpseo-linked']);
		return $columns;
	}

	public function kmp_meal_remove_postlist_columns_cs( $response, $posttype ){
		if ( 'kmp-meal' == $posttype ) {
			return false;
		}
		return $response;
	}

	public function kmp_meal_add_postlist_columns($columns) {
		$columns['kmpid'] 			= __( 'KMP ID', 'kmp' );
		$columns['mealtype'] 		= __( 'Meal Type', 'kmp' );
		$columns['calorierange'] 	= __( 'Calorie Range', 'kmp' );
		return $columns;
	}

	public function kmp_meal_set_new_postlist_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'kmpid' :
				echo get_post_meta( $post_id , 'kmp-cf-kmpid' , true );
				break;
			case 'mealtype' :
				echo get_post_meta( $post_id , 'kmp-cf-mealtype' , true );
				break;
			case 'calorierange' :
				echo get_post_meta( $post_id , 'kmp-cf-calorierange' , true );
				break;
		}
	}

	public function kmp_meal_make_new_postlist_columns_sortable( $columns ) {

		$columns['kmpid'] = 'kmpid';
		$columns['mealtype'] = 'mealtype';
		$columns['calorierange'] = 'calorierange';

		//To make a column 'un-sortable' remove it from the array
		//unset($columns['date']);

		return $columns;
	}

	public function kmp_meal_new_postlist_columns_custom_ordering( $query ) {
		if ( ! is_admin() ) {
			return;
		}
		$orderby = $query->get( 'orderby');
		if ( 'kmpid' == $orderby ) {
			$meta_query = array(
				'relation' => 'OR',
				array(
					'key' => 'kmp-cf-kmpid',
					'compare' => 'NOT EXISTS', // see note above
				),
				array(
					'key' => 'kmp-cf-kmpid',
				),
			);
			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value' );
			//$query->set( 'meta_type', 'NUMERIC' );
		}
	}

	public function kmp_meal_remove_metaboxes( $post ) {
		global $wp_meta_boxes;
		remove_meta_box( 'wpseo_meta', 'kmp-meal', 'normal' );
		remove_meta_box( 'wc-memberships-post-memberships-data', 'kmp-meal', 'normal' );
		remove_meta_box( 'crp_metabox', 'kmp-meal', 'advanced' );
		remove_meta_box( 'customsidebars-mb', 'kmp-meal', 'side' );
		//remove_meta_box( 'submitdiv', 'cpt-slug', 'side' );
		//add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'cpt-slug', 'normal', 'low' );
	}

	public function kmp_meal_metabox_order( $order ) {
		return array(
			'normal'  	=> join( ",", array(
				'kmp_meal_metabox',
			)),
			'side'     => '',
			'advanced' => '',
		);
	}

	public function render_kmp_submission_metabox($post, $args) {
		// $args = the args specified as the last parameter of add_meta_box()
		/*echo str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' .     var_export($args, true) . ' ?>', true ) );*/

		wp_nonce_field( basename( __FILE__ ), 'kmp_mb_nonce' );
		$post_stored_meta = get_post_meta( $post->ID );

		/*$now = new DateTime('now', new DateTimeZone('America/New_York') );
		echo $now->format('m-d-Y H:i:s');*/
		?>

		<div class="kmp-cf-group-container kcgc--maindata">

			<div class="kmp-flex-container">

				<div class="kmp-flex-item datetime">

					<p class="mb-0">
						<label for="kmp-cf-kmpid" class="kmp-mb-label"><?php _e( 'Submission Date and Time', 'kmp' )?></label>
						<input
							type="datetime"
							name="kmp-cf-datetime"
							id="kmp-cf-datetime"
							class="kmp-mb-input kmp-mb-input--datetime"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-datetime'] ) ) echo $post_stored_meta['kmp-cf-datetime'][0]; ?>"
						/>
					</p>

					<div class="kmp-mb-notice">

						<p class="mb-0">In <code>MM-DD-YYYY hh:mm:ss</code> format</p>

					</div>

				</div>

				<div class="kmp-flex-item caltarget">

					<p class="mb-0">
						<label for="kmp-cf-caltarget" class="kmp-mb-label"><?php _e( 'How many calories you\'d like your interactive sliding meal plan to follow?', 'kmp' )?></label>
						<input
							type="number"
							name="kmp-cf-caltarget"
							id="kmp-cf-caltarget"
							class="kmp-mb-input kmp-mb-input--number"
							min="100"
							max="3000"
							step="100"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-caltarget'] ) ) echo $post_stored_meta['kmp-cf-caltarget'][0]; ?>"
						/>
					</p>

				</div>

				<div class="kmp-flex-item mealsnumber">

					<p class="mb-0">

						<label for="kmp-cf-mealsnumber" class="kmp-mb-label"><?php _e( 'How many full meals do you prefer to eat per day?', 'kmp' )?></label>

						<select
							name="kmp-cf-mealsnumber"
							id="kmp-cf-mealsnumber"
						>
							<option value="novalue" selected>Select number of meals</option>
							<option value="1" <?php if ( isset ( $post_stored_meta['kmp-cf-mealsnumber'] ) ) selected( $post_stored_meta['kmp-cf-mealsnumber'][0], '1' ); ?>><?php _e( 'One', 'kmp' )?></option>
							<option value="2" <?php if ( isset ( $post_stored_meta['kmp-cf-mealsnumber'] ) ) selected( $post_stored_meta['kmp-cf-mealsnumber'][0], '2' ); ?>><?php _e( 'Two', 'kmp' )?></option>
							<option value="3" <?php if ( isset ( $post_stored_meta['kmp-cf-mealsnumber'] ) ) selected( $post_stored_meta['kmp-cf-mealsnumber'][0], '3' ); ?>><?php _e( 'Three', 'kmp' )?></option>
							<option value="4" <?php if ( isset ( $post_stored_meta['kmp-cf-mealsnumber'] ) ) selected( $post_stored_meta['kmp-cf-mealsnumber'][0], '4' ); ?>><?php _e( 'Four', 'kmp' )?></option>
						</select>
					</p>

				</div>

			</div>

			<div class="kmp-flex-container">

				<div class="kmp-flex-item email">

					<p class="mb-0">
						<label for="kmp-cf-email" class="kmp-mb-label"><?php _e( 'Email', 'kmp' )?></label>
						<input
							type="email"
							name="kmp-cf-email"
							id="kmp-cf-email"
							class="kmp-mb-input kmp-mb-input--email"
							minlength="2"
							maxlength="200"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-email'] ) ) echo $post_stored_meta['kmp-cf-email'][0]; ?>"
						/>
					</p>

				</div>

				<div class="kmp-flex-item phone">

					<p class="mb-0">
						<label for="kmp-cf-phone" class="kmp-mb-label"><?php _e( 'Phone', 'kmp' )?></label>
						<input
							type="text"
							name="kmp-cf-phone"
							id="kmp-cf-phone"
							class="kmp-mb-input kmp-mb-input--text"
							minlength="2"
							maxlength="200"
							value="<?php if ( isset ( $post_stored_meta['kmp-cf-phone'] ) ) echo $post_stored_meta['kmp-cf-phone'][0]; ?>"
						/>
					</p>

				</div>

			</div>

		</div>

		<div class="kmp-cf-group-container kcgc--propselections">

			<div class="kmp-flex-container kmp-flex-container--wrap">

				<div class="kmp-flex-item fasting mb-10">

					<p>

						<label for="kmp-cf-fasting" class="kmp-mb-label"><?php _e( 'Do you practice intermittent fasting?', 'kmp' )?></label>

						<select
							name="kmp-cf-fasting"
							id="kmp-cf-fasting"
						>
							<option value="novalue" selected>Select number of meals</option>
							<option value="yes" <?php if ( isset ( $post_stored_meta['kmp-cf-fasting'] ) ) selected( $post_stored_meta['kmp-cf-fasting'][0], 'yes' ); ?>><?php _e( 'Yes', 'kmp' )?></option>
							<option value="no" <?php if ( isset ( $post_stored_meta['kmp-cf-fasting'] ) ) selected( $post_stored_meta['kmp-cf-fasting'][0], 'no' ); ?>><?php _e( 'No', 'kmp' )?></option>
						</select>
					</p>

				</div>

				<div class="kmp-flex-item adddessert mb-10">

					<p>

						<label for="kmp-cf-adddessert" class="kmp-mb-label"><?php _e( 'Do you want your meal plan to include a snack or a dessert?', 'kmp' )?></label>

						<select
							name="kmp-cf-adddessert"
							id="kmp-cf-adddessert"
						>
							<option value="novalue" selected>Dessert vs Snack</option>
							<option value="dessert" <?php if ( isset ( $post_stored_meta['kmp-cf-adddessert'] ) ) selected( $post_stored_meta['kmp-cf-adddessert'][0], 'dessert' ); ?>><?php _e( 'Dessert', 'kmp' )?></option>
							<option value="snack" <?php if ( isset ( $post_stored_meta['kmp-cf-adddessert'] ) ) selected( $post_stored_meta['kmp-cf-adddessert'][0], 'snack' ); ?>><?php _e( 'Snack', 'kmp' )?></option>
							<option value="no" <?php if ( isset ( $post_stored_meta['kmp-cf-adddessert'] ) ) selected( $post_stored_meta['kmp-cf-adddessert'][0], 'no' ); ?>><?php _e( 'None', 'kmp' )?></option>
						</select>
					</p>

				</div>

				<div class="kmp-flex-item mealcomplexity mb-10">

					<p>

						<label for="kmp-cf-mealcomplexity" class="kmp-mb-label"><?php _e( 'Do you prefer beginner-friendly, simple meals or more complex recipes?', 'kmp' )?></label>

						<select
							name="kmp-cf-mealcomplexity"
							id="kmp-cf-mealcomplexity"
						>
							<option value="novalue" selected>Select meal complexity</option>
							<option value="beginner" <?php if ( isset ( $post_stored_meta['kmp-cf-mealcomplexity'] ) ) selected( $post_stored_meta['kmp-cf-mealcomplexity'][0], 'beginner' ); ?>><?php _e( 'Beginner', 'kmp' )?></option>
							<option value="advanced" <?php if ( isset ( $post_stored_meta['kmp-cf-mealcomplexity'] ) ) selected( $post_stored_meta['kmp-cf-mealcomplexity'][0], 'advanced' ); ?>><?php _e( 'Advanced', 'kmp' )?></option>
							<option value="no" <?php if ( isset ( $post_stored_meta['kmp-cf-mealcomplexity'] ) ) selected( $post_stored_meta['kmp-cf-mealcomplexity'][0], 'no' ); ?>><?php _e( 'No Preference', 'kmp' )?></option>
						</select>
					</p>

				</div>

				<div class="kmp-flex-item diettype1">

					<p class="mb-0">

						<label for="kmp-cf-diettype1" class="kmp-mb-label"><?php _e( 'Do you have any dietary preferences?', 'kmp' )?></label>

						<select
							name="kmp-cf-diettype1"
							id="kmp-cf-diettype1"
						>
							<option value="novalue" selected>Select dietary preference</option>
							<option value="vegetarian" <?php if ( isset ( $post_stored_meta['kmp-cf-diettype1'] ) ) selected( $post_stored_meta['kmp-cf-diettype1'][0], 'vegetarian' ); ?>><?php _e( 'Vegetarian', 'kmp' )?></option>
							<option value="vegan" <?php if ( isset ( $post_stored_meta['kmp-cf-diettype1'] ) ) selected( $post_stored_meta['kmp-cf-diettype1'][0], 'vegan' ); ?>><?php _e( 'Vegan', 'kmp' )?></option>
							<option value="carnivore" <?php if ( isset ( $post_stored_meta['kmp-cf-diettype1'] ) ) selected( $post_stored_meta['kmp-cf-diettype1'][0], 'carnivore' ); ?>><?php _e( 'Carnivore', 'kmp' )?></option>
							<option value="no" <?php if ( isset ( $post_stored_meta['kmp-cf-diettype1'] ) ) selected( $post_stored_meta['kmp-cf-diettype1'][0], 'no' ); ?>><?php _e( 'None', 'kmp' )?></option>
						</select>
					</p>

				</div>

				<div class="kmp-flex-item diettype2">

					<p class="mb-0">

						<label for="kmp-cf-diettype2" class="kmp-mb-label"><?php _e( 'If you don\'t have any dietary preferences, would you like any of the
						following included in with your standard meal plan?', 'kmp' )?></label>

						<select
							name="kmp-cf-diettype2"
							id="kmp-cf-diettype2"
						>
							<option value="novalue" selected>Select dietary preference</option>
							<option value="vegan" <?php if ( isset ( $post_stored_meta['kmp-cf-diettype2'] ) ) selected( $post_stored_meta['kmp-cf-diettype2'][0], 'vegan' ); ?>><?php _e( 'Vegan', 'kmp' )?></option>
							<option value="carnivore" <?php if ( isset ( $post_stored_meta['kmp-cf-diettype2'] ) ) selected( $post_stored_meta['kmp-cf-diettype2'][0], 'carnivore' ); ?>><?php _e( 'Carnivore', 'kmp' )?></option>
							<option value="no" <?php if ( isset ( $post_stored_meta['kmp-cf-diettype2'] ) ) selected( $post_stored_meta['kmp-cf-diettype2'][0], 'no' ); ?>><?php _e( 'None', 'kmp' )?></option>
						</select>
					</p>

				</div>

			</div>

		</div>

		<div class="kmp-cf-group-container kcgc--sensitivities">

			<div class="sensitivities mb-20">

				<p>

					<label for="kmp-cf-sensitivities" class="kmp-mb-label"><?php _e( 'Do you have any food sensitivities or allergies?', 'kmp' )?></label>

					<select
						name="kmp-cf-sensitivities"
						id="kmp-cf-sensitivities"
					>
						<option value="novalue" selected>Select sensitivity</option>
						<option value="dairy-free" <?php if ( isset ( $post_stored_meta['kmp-cf-sensitivities'] ) ) selected( $post_stored_meta['kmp-cf-sensitivities'][0], 'dairy-free' ); ?>><?php _e( 'Dairy-Free', 'kmp' )?></option>
						<option value="egg-free" <?php if ( isset ( $post_stored_meta['kmp-cf-sensitivities'] ) ) selected( $post_stored_meta['kmp-cf-sensitivities'][0], 'egg-free' ); ?>><?php _e( 'Egg-Free', 'kmp' )?></option>
						<option value="other" <?php if ( isset ( $post_stored_meta['kmp-cf-sensitivities'] ) ) selected( $post_stored_meta['kmp-cf-sensitivities'][0], 'other' ); ?>><?php _e( 'Other', 'kmp' )?></option>
						<option value="no" <?php if ( isset ( $post_stored_meta['kmp-cf-sensitivities'] ) ) selected( $post_stored_meta['kmp-cf-sensitivities'][0], 'no' ); ?>><?php _e( 'None', 'kmp' )?></option>
					</select>
				</p>

			</div>

			<div class="sensitivitiesother">

				<p class="mb-0">
					<label for="kmp-cf-sensitivitiesother" class="kmp-mb-label"><?php _e( 'What other food sensitivities do you have?', 'kmp' )?></label>
					<textarea
						name="kmp-cf-sensitivitiesother"
						id="kmp-cf-sensitivitiesother"
						class="kmp-mb-input kmp-mb-input--text"
						minlength="10"
						rows="3"
					><?php if ( isset ( $post_stored_meta['kmp-cf-sensitivitiesother'] ) ) echo $post_stored_meta['kmp-cf-sensitivitiesother'][0]; ?></textarea>
				</p>

			</div>

		</div>

		<?php
	}

	public function save_kmp_submission_metabox($post_id) {

		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'kmp_mb_nonce' ] ) && wp_verify_nonce( $_POST[ 'kmp_mb_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}

		// Text field or textarea
		if (
			isset( $_POST[ 'kmp-cf-sensitivitiesother' ] ) &&
			( strlen( $_POST[ 'kmp-cf-sensitivitiesother' ] ) >= 10 ) &&
			( strlen( $_POST[ 'kmp-cf-sensitivitiesother' ] ) <= 1000 )
		) {
			update_post_meta( $post_id, 'kmp-cf-sensitivitiesother', sanitize_text_field( $_POST[ 'kmp-cf-sensitivitiesother' ] ) );
		}

		// Datetime
		if ( isset( $_POST[ 'kmp-cf-datetime' ] ) ) {
			$datetime = $_POST[ 'kmp-cf-datetime' ];
			if ( $this->verify_date( $datetime ) ) {
				update_post_meta( $post_id, 'kmp-cf-datetime', sanitize_text_field( $datetime ) );
			}
		}

		// Email
		if ( isset( $_POST[ 'kmp-cf-email' ] ) ) {
			$email = $_POST[ 'kmp-cf-email' ];
			if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				update_post_meta( $post_id, 'kmp-cf-email', sanitize_email( $_POST[ 'kmp-cf-email' ] ) );
			}
		}

		// Number
		if (
			isset( $_POST[ 'kmp-cf-caltarget' ] ) &&
			is_numeric( $_POST[ 'kmp-cf-caltarget' ] ) &&
			( $_POST[ 'kmp-cf-caltarget' ] >= 200 ) &&
			( $_POST[ 'kmp-cf-caltarget' ] <= 3000 )
		) {
			update_post_meta( $post_id, 'kmp-cf-caltarget', absint( $_POST[ 'kmp-cf-caltarget' ] ) );
		}


		/**
		 * Selects
		 */

		/*
										INPUT TYPE				VAL/SAN 	MIN/MAX
		addDessert 			string 		select 					in_array 	desser|snack|no
		calTarget 			string 		number 					absint
		dietType1 			string 		select 					in_array 	vegetarian|vegan|carnivore|no
		dietType2 			string 		select					in_array 	vegan|carnivore|no
		email 				string 		input[type="email"]		EMAIL  		8 chars <= x <= 200 chars
		fasting 			string 		select					in_array 	yes|no
		mealComplexity 		string 		select		 			in_array 	beginner|advanced|no
		mealsNumber 		string 		select 					in_array 	1|2|3|4
		firstName 			string 		input[type="text"]		in_array 	2 chars <= x <= 50 chars
		lastName 	 		string 		input[type="text"]		in_array 	2 chars <= x <= 50 chars
		sensitivities 		string 		select					in_array 	dairy-free|egg-free|other|no
		sensitivitiesOther 	string 		textarea				in_array 	10 chars <= x <= 1000 chars
		yourGoals 			string 		textarea				in_array 	10 chars <= x <= 1000 chars
		---
		submissionTime 		date		input[type="text"] 		date + time today + now

		===================================================================================================

		TITLE = firstName + lastName
		CONTENT = yoarGoals

		FIELD GROUP 1: MAIN DATA

		submissionTime 			Date and Time of Submission
		calTarget 				How many calories you'd like your interactive sliding meal plan to follow?
		mealsNumber 			How many full meals do you prefer to eat per day?

		FIELD GROUP 2: MEAL PROPERTY SELECTIONS

		Subgroup1:
			fasting 			Do you practice intermittent fasting?
			addDessert 			Do you want your meal plan to include a snack or a dessert?
			mealComplexity 	 	Do you prefer beginner-friendly, simple meals or more complex recipes?

		Subgroup2:
			dietType1 			Do you have any dietary preferences?
			dietType2			If you don't have any dietary preferences, would you like any of the
								following included in with your standard meal plan?


		FIELD GROUP 3: SENSITIVITIES

		sensitivities 		Do you have any food sensitivities or allergies?
		sensitivityOther 	What other food sensitivities do you have?

		*/

		$mealsnumber_vals_Arr = array(
			'1',
			'2',
			'3',
			'4',
		);
		if (
			isset( $_POST[ 'kmp-cf-mealsnumber' ] ) &&
			in_array( $_POST[ 'kmp-cf-mealsnumber' ], $mealsnumber_vals_Arr )
		) {
			update_post_meta( $post_id, 'kmp-cf-mealsnumber', sanitize_text_field( $_POST[ 'kmp-cf-mealsnumber' ] ) );
		}

		$adddessert_vals_Arr = array(
			'dessert',
			'snack',
			'no',
		);
		if (
			isset( $_POST[ 'kmp-cf-adddessert' ] ) &&
			in_array( $_POST[ 'kmp-cf-adddessert' ], $adddessert_vals_Arr )
		) {
			update_post_meta( $post_id, 'kmp-cf-adddessert', sanitize_text_field( $_POST[ 'kmp-cf-adddessert' ] ) );
		}

		$diettype1_vals_Arr = array(
			'vegetarian',
			'vegan',
			'carnivore',
			'no',
		);
		if (
			isset( $_POST[ 'kmp-cf-diettype1' ] ) &&
			in_array( $_POST[ 'kmp-cf-diettype1' ], $diettype1_vals_Arr )
		) {
			update_post_meta( $post_id, 'kmp-cf-diettype1', sanitize_text_field( $_POST[ 'kmp-cf-diettype1' ] ) );
		}

		$diettype2_vals_Arr = array(
			'vegan',
			'carnivore',
			'no',
		);
		if (
			isset( $_POST[ 'kmp-cf-diettype2' ] ) &&
			in_array( $_POST[ 'kmp-cf-diettype2' ], $diettype2_vals_Arr )
		) {
			update_post_meta( $post_id, 'kmp-cf-diettype2', sanitize_text_field( $_POST[ 'kmp-cf-diettype2' ] ) );
		}

		$fasting_vals_Arr = array(
			'yes',
			'no',
		);

		if (
			isset( $_POST[ 'kmp-cf-fasting' ] ) &&
			in_array( $_POST[ 'kmp-cf-fasting' ], $fasting_vals_Arr )
		) {
			update_post_meta( $post_id, 'kmp-cf-fasting', sanitize_text_field( $_POST[ 'kmp-cf-fasting' ] ) );
		}

		$mealcomplexity_vals_Arr = array(
			'beginner',
			'advanced',
			'no',
		);
		if (
			isset( $_POST[ 'kmp-cf-mealcomplexity' ] ) &&
			in_array( $_POST[ 'kmp-cf-mealcomplexity' ], $mealcomplexity_vals_Arr )
		) {
			update_post_meta( $post_id, 'kmp-cf-mealcomplexity', sanitize_text_field( $_POST[ 'kmp-cf-mealcomplexity' ] ) );
		}

		$sensitivities_vals_Arr = array(
			'dairy-free',
			'egg-free',
			'no',
		);
		if (
			isset( $_POST[ 'kmp-cf-sensitivities' ] ) &&
			in_array( $_POST[ 'kmp-cf-sensitivities' ], $sensitivities_vals_Arr )
		) {
			update_post_meta( $post_id, 'kmp-cf-sensitivities', sanitize_text_field( $_POST[ 'kmp-cf-sensitivities' ] ) );
		}

		// Phone
		if ( isset( $_POST[ 'kmp-cf-phone' ] ) ) {
			$phone = preg_replace('/[^0-9+-]/', '', $_POST['kmp-cf-phone']);
			update_post_meta( $post_id, 'kmp-cf-phone', sanitize_text_field( $phone ) );
		}
	}

	public function kmp_submission_remove_postlist_columns_seo( $columns ) {
		unset($columns['wpseo-score']);
		unset($columns['wpseo-score-readability']);
		unset($columns['wpseo-title']);
		unset($columns['wpseo-metadesc']);
		unset($columns['wpseo-focuskw']);
		unset($columns['wpseo-links']);
		unset($columns['wpseo-linked']);
		return $columns;
	}

	public function kmp_submission_remove_postlist_columns_cs( $response, $posttype ){
		if ( 'kmp-submission' == $posttype ) {
			return false;
		}
		return $response;
	}

	public function kmp_submission_add_postlist_columns($columns) {
		$columns['email'] 			= __( 'Email', 'kmp' );
		$columns['datetime'] 		= __( 'Submission Date & Time', 'kmp' );
		return $columns;
	}

	public function kmp_submission_set_new_postlist_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'email' :
				echo get_post_meta( $post_id , 'kmp-cf-email' , true );
				break;
			case 'datetime' :
				echo get_post_meta( $post_id , 'kmp-cf-datetime' , true );
				break;
		}
	}


	public function kmp_submission_make_new_postlist_columns_sortable( $columns ) {
		$columns['email']    = 'email';
		$columns['datetime'] = 'datetime';

		// To make a column 'un-sortable' remove it from the array
		// unset($columns['date']);

		return $columns;
	}

	public function kmp_submission_new_postlist_columns_custom_ordering( $query ) {
		if ( ! is_admin() ) {
			return;
		}
		$orderby = $query->get( 'orderby');
		if ( 'datetime' == $orderby ) {
			$meta_query = array(
				'relation' => 'OR',
				array(
					'key' => 'kmp-cf-datetime',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => 'kmp-cf-datetime',
				),
			);
			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value_datetime' );
			$query->set( 'meta_type', 'DATETIME' );
		}
	}

	public function kmp_submission_remove_metaboxes( $post ) {
		global $wp_meta_boxes;
		remove_meta_box( 'wpseo_meta', 'kmp-submission', 'normal' );
		remove_meta_box( 'wc-memberships-post-memberships-data', 'kmp-submission', 'normal' );
		remove_meta_box( 'crp_metabox', 'kmp-submission', 'advanced' );
		remove_meta_box( 'customsidebars-mb', 'kmp-submission', 'side' );
		// remove_meta_box( 'submitdiv', 'cpt-slug', 'side' );
		// add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'cpt-slug', 'normal', 'low' );
	}

	public function kmp_submission_metabox_order( $order ) {
		return array(
			'normal' => join( ',', array(
				'kmp_submission_metabox',
			)),
			'side'     => '',
			'advanced' => '',
		);
	}

	private function verify_date($date) {
		return (DateTime::createFromFormat('m-d-Y H:i:s', $date) !== false);
	}
}
