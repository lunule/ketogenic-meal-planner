<?php

/**
 * The public Ajax request class
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/public
 */
class Kmp_Public_Ajax {

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

	public function ajax_cb_get_meals() {

		$nonce = $_POST['ajaxNonce'];
		$values_Arr = $_POST['valuesObj'];
		$number_of_cards = ( true == USER_IS_PLATINUM_MEMBER ) ? 7 : 2;

		if ( ! wp_verify_nonce( $nonce, 'kmp_dbquery' ) ) {
			die();
		}

		/*
		if (
				get_option( 'kmp_field_notification_email' ) &&
				( '' !== get_option( 'kmp_field_notification_email' ) ) &&
				filter_var( get_option( 'kmp_field_notification_email' ), FILTER_VALIDATE_EMAIL )
			) :

			$to = get_option( 'kmp_field_notification_email' );
			$subject = 'Apple Computer';
			$message = 'Steve, I think this computer thing might really take off.';

			wp_mail( $to, $subject, $message );

		endif;
		*/

		/**
		 * 1. Save the submission
		 * ========================================================================================
		 */

		$sq_args_Arr = array(
			'posts_per_page' 	=> -1,
			'post_type' 		=> 'kmp-submission',
			'meta_query' 		=> array(
				array(
					'key' 		=> 'kmp-cf-email',
					'value' 	=> strtolower( $values_Arr['email'] ),
				),
			),
		);
		$sq_posts_Arr = get_posts( $sq_args_Arr );

		// If the query doesn't return a result where the kmp-cf-email custom field
		// value matches the submitted email, AND the email specified is valid,
		// save the submission
		if (
			( count( $sq_posts_Arr ) === 0 ) &&
			( filter_var( strtolower( $values_Arr['email'] ), FILTER_VALIDATE_EMAIL ) )
		) {

			// insert the post and set the category
			$post_id = wp_insert_post(array (
				'post_type' => 'kmp-submission',
				'post_title' => $values_Arr['firstName'] . ' ' . $values_Arr['lastName'],
				'post_content' => $values_Arr['yourGoals'],
				'post_status' => 'publish',
				'comment_status' => 'closed',   // if you prefer
				'ping_status' => 'closed',      // if you prefer
			));

			$now 		= new DateTime('now', new DateTimeZone('America/New_York') );
			$datetime 	= $now->format('m-d-Y H:i:s');

			if ($post_id) {
				update_post_meta( $post_id, 'kmp-cf-datetime', $datetime );
				update_post_meta( $post_id, 'kmp-cf-caltarget', $values_Arr['calTarget'] );
				update_post_meta( $post_id, 'kmp-cf-mealsnumber', $values_Arr['mealsNumber'] );
				update_post_meta( $post_id, 'kmp-cf-email', strtolower( $values_Arr['email'] ) );
				update_post_meta( $post_id, 'kmp-cf-phone', $values_Arr['phone'] );
				update_post_meta( $post_id, 'kmp-cf-fasting', $values_Arr['fasting'] );
				update_post_meta( $post_id, 'kmp-cf-adddessert', $values_Arr['addDessert'] );
				update_post_meta( $post_id, 'kmp-cf-mealcomplexity', $values_Arr['mealComplexity'] );
				update_post_meta( $post_id, 'kmp-cf-diettype1', $values_Arr['dietType1'] );
				update_post_meta( $post_id, 'kmp-cf-diettype2', $values_Arr['dietType2'] );
				update_post_meta( $post_id, 'kmp-cf-sensitivities', $values_Arr['sensitivities'] );
				update_post_meta( $post_id, 'kmp-cf-sensitivitiesother', $values_Arr['sensitivitiesOther'] );
			}
		}

		/**
		 * 2. Set up the AJAX reply
		 * ========================================================================================
		 */

		$diettype_mq_Arr    = [];
		$sensitivity_mq_Arr = [];
		$complexity_mq_Arr  = [];

		$mealtype_mq_Arr = array(
			'relation' => 'OR',
			array(
				'key'     => 'kmp-cf-mealtype',
				'value'   => 'breakfast',
				'compare' => 'LIKE',
			),
			array(
				'key'     => 'kmp-cf-mealtype',
				'value'   => 'lunch',
				'compare' => 'LIKE',
			),
			array(
				'key'     => 'kmp-cf-mealtype',
				'value'   => 'dinner',
				'compare' => 'LIKE',
			),
		);

		if ( 'snack' == $values_Arr['addDessert'] ) {
			$snack_Arr = array(
				'key'     => 'kmp-cf-mealtype',
				'value'   => 'snack',
				'compare' => 'LIKE',
			);
			array_push( $mealtype_mq_Arr, $snack_Arr );
		}

		if ( 'dessert' == $values_Arr['addDessert'] ) {
			$snack_Arr = array(
				'key'     => 'kmp-cf-mealtype',
				'value'   => 'dessert',
				'compare' => 'LIKE',
			);
			array_push( $mealtype_mq_Arr, $snack_Arr );
		}

		/* ------------------------------------------------------------------------------------- */

		// If the user selects 'vegetarian', the meals can be VEGAN OR VEGETARIAN, BUT
		// NOT CARNIVORE.
		if ( 'vegetarian' == $values_Arr['dietType1'] ) {
			$diettype_mq_Arr = array(
				'relation' => 'AND',
				array(
					'relation' => 'OR',
					array(
						'key'     => 'kmp-cf-vegetarian',
						'value'   => '1',
						'compare' => 'LIKE',
					),
					array(
						'key'     => 'kmp-cf-vegan',
						'value'   => '1',
						'compare' => 'LIKE',
					),
				),
				array(
					'key'     => 'kmp-cf-carnivore',
					'value'   => '0',
					'compare' => 'LIKE',
				),
			);
		}

		// If the user selects 'vegan', the meals can be VEGAN ONLY.
		if (
			( 'vegan' == $values_Arr['dietType1'] ) ||
			(
				( 'no' == $values_Arr['dietType1'] ) &&
				( 'vegan' == $values_Arr['dietType2'] )
			)
		) {
			$diettype_mq_Arr = array(
				'relation' => 'AND',
				array(
					'key'     => 'kmp-cf-vegan',
					'value'   => '1',
					'compare' => 'LIKE',
				),
				array(
					'key'     => 'kmp-cf-carnivore',
					'value'   => '0',
					'compare' => 'LIKE',
				),
			);
		}

		/* ------------------------------------------------------------------------------------- */

		// If the user selects 'carnivore', the meals can be anything, so we don't need to add to the sql here in that case.
		if ( 'dairy-free' == $values_Arr['sensitivities'] ) {
			$sensitivity_mq_Arr = array(
				'key'     => 'kmp-cf-dairyfree',
				'value'   => '1',
				'compare' => 'LIKE',
			);
		}
		if ( 'egg-free' == $values_Arr['sensitivities'] ) {
			$sensitivity_mq_Arr = array(
				'key'     => 'kmp-cf-eggfree',
				'value'   => '1',
				'compare' => 'LIKE',
			);
		}

		/* ------------------------------------------------------------------------------------- */

		if ( 'beginner' == $values_Arr['mealComplexity'] ) {
			$complexity_mq_Arr = array(
				'relation' => 'OR',
				array(
					'relation' => 'OR',
					array(
						'key'     => 'kmp-cf-beginner',
						'value'   => '0',
						'compare' => 'LIKE',
					),
					array(
						'key'     => 'kmp-cf-advanced',
						'value'   => '0',
						'compare' => 'LIKE',
					),
				),
				array(
					'key'     => 'kmp-cf-beginner',
					'value'   => '1',
					'compare' => 'LIKE',
				),
			);
		}

		/* ------------------------------------------------------------------------------------- */

		$query_args_Arr = array(
			'post_status' 		=> 'publish',
			'post_type' 		=> 'kmp-meal',
			'posts_per_page' 	=> -1,
			'meta_query'		=> array(
				'relation' => 'AND',
				$mealtype_mq_Arr,
				$diettype_mq_Arr,
				$sensitivity_mq_Arr,
				$complexity_mq_Arr,
			)
		);

		$posts_Arr = get_posts( $query_args_Arr );
		$results = [];
		$i = 0;

		/**
		 * The above query returns "default" WP post objects - but this is not what we need.
		 *
		 * Let's build our own array of custom-built post data objects - these objects
		 * will contain only the post data we really need in the AJAX callback
		 */
		foreach ( $posts_Arr as $post ) {

			$pid = $post->ID;

			$results[$i] = new stdClass();

			$results[$i]->id                = strval($pid);
			$results[$i]->calorierange      = get_post_meta( $pid, 'kmp-cf-calorierange', true );
			$results[$i]->kmpid             = get_post_meta( $pid, 'kmp-cf-kmpid', true );
			$results[$i]->meal              = get_post_meta( $pid, 'kmp-cf-mealtype', true );
			$results[$i]->name              = $post->post_title;
			$results[$i]->ingredients       = $post->post_content;
			$results[$i]->calories          = get_post_meta( $pid, 'kmp-cf-calories', true );
			$results[$i]->fat               = get_post_meta( $pid, 'kmp-cf-fat', true );
			$results[$i]->carbs             = get_post_meta( $pid, 'kmp-cf-carbs', true );
			$results[$i]->fiber             = get_post_meta( $pid, 'kmp-cf-fiber', true );
			$results[$i]->netcarbs          = get_post_meta( $pid, 'kmp-cf-netcarbs', true );
			$results[$i]->protein           = get_post_meta( $pid, 'kmp-cf-protein', true );
			$results[$i]->vegetarian        = get_post_meta( $pid, 'kmp-cf-vegetarian', true );
			$results[$i]->vegan             = get_post_meta( $pid, 'kmp-cf-vegan', true );
			$results[$i]->carnivore         = get_post_meta( $pid, 'kmp-cf-carnivore', true );
			$results[$i]->dairyfree         = get_post_meta( $pid, 'kmp-cf-dairyfree', true );
			$results[$i]->eggfree           = get_post_meta( $pid, 'kmp-cf-eggfree', true );
			$results[$i]->beginnerfriendly  = get_post_meta( $pid, 'kmp-cf-beginner', true );
			$results[$i]->advanced          = get_post_meta( $pid, 'kmp-cf-advanced', true );
			$results[$i]->url               = get_post_meta( $pid, 'kmp-cf-url', true );

			// Special case - we use the same url value for the posturl property as well.
			//
			// If this url value is an empty string, both the url and the posturl properties
			// will be the same: empty strings.
			//
			// If however the url value is not an empty string, this value gets updated below,
			// and both the url and the posturl properties get their new, correct values:
			// url = post thumbnail url | posturl = post url (the permalink, actually).
			$results[$i]->posturl           = get_post_meta( $pid, 'kmp-cf-url', true );

			// the $result object should be cloned, and the preg_match_all applied on the clone
			// to keep the original $result object unaffected.
			$resultTemp = $results[$i];
			$urlstring 	= $resultTemp->url;

			if ( '' !== $results[$i]->url ) {

				preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $urlstring , $url_matches);

				// The project spreadsheet's url column might include multiple urls - we
				// only need one, so let's get the first one and use it to get the related
				// recipe/post's id
				$fixed_permalink 	= get_site_url() . preg_replace('#^.+://[^/]+#', '', $url_matches[0])[0];
				$meal_post_id 		= url_to_postid( $fixed_permalink );

				// Now we can use the post id to get the post's featured image/post thumbnail
				$image_attributes 	= wp_get_attachment_image_src( get_post_thumbnail_id( $meal_post_id ), 'thumbnail' );

				// If there's no URL result, the meal result's new url value should be
				// an empty string
				if ( false == $image_attributes ) {
					$results[$i]->url = '';
				// ... otherwise it shoul be the featured image/post thumbnail url
				} else {
					$results[$i]->url = $image_attributes[0];
				}

				// Let's update the posturl property value as well - it's here that it gets the
				// post permalink url as final/new/correct value.
				$results[$i]->posturl = $fixed_permalink;
			}
			$i++;
		}

		// Now that we have the basic results array based on the boolean values
		// specified by the user, let's invoke the class handling the daily meals
		// card setup based on the calorie possibilities specified in the project
		// spreadsheet

		// First - set up the class and method names based on dynamic values
		$cpclassname 	= 'Cp_' . $values_Arr['calTarget'];
		$cpmethodname	= 'return_meals_' . $values_Arr['mealsNumber'] . '_' . $values_Arr['addDessert'];

		// Second - invoke the appropriate class' appropriate method.
		// The returned array includes a 'meals', an 'errors', and a 'merge'
		// sub-array
		$cpclass 		= new $cpclassname($results, $values_Arr, $number_of_cards);
		$meals_Arr 		= $cpclass->$cpmethodname();

		$json_reply = json_encode($meals_Arr);

		//echo json_encode( $query_args_Arr );
		echo $json_reply;
		wp_die();
	}
}
