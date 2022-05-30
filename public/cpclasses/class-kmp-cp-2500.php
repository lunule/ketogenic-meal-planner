<?php

/**
 * The calorie possibilities class if the calorie target specified by the user is 2500.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/public
 */

class Cp_2500 {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($results, $app_vals_Arr, $number_of_cards) {

		$this->results 			= $results;
		$this->app_vals_Arr 	= $app_vals_Arr;
		$this->number_of_cards 	= $number_of_cards;

	}

	public function return_meals_1_no() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 700 == $value->calorierange ) ||
					( 900 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 *  BEGIN Special case with merge.
				 *  ===============================================================================
				 */

				$dinners_pt1_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 900 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$dinners_pt2_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 700 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 *  ===============================================================================
				 *  END Special case with merge.
				 */

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if (
						( count($dinners_pt1_Arr) > 0 ) &&
						( count($dinners_pt2_Arr) > 0 )
					) :

					/**
					 * SPECIAL CASE - THE SUM SHOULD ADD UP EXACTLY TO 2500. THIS MEANS WE ONLY CHECK THE CASE WHERE BOTH THE 900 AND THE 700 CALS ARRAYS HAVE AT LEAST ONE ITEM.
					 */

					// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
					$din_pt1_randkeys_Arr 	= array_rand( $dinners_pt1_Arr, 2 );
					$din_pt2_randkeys_Arr 	= array_rand( $dinners_pt2_Arr );

					$dinner_pt1 			= $dinners_pt1_Arr[$din_pt1_randkeys_Arr[0]];
					$dinner_pt2 			= $dinners_pt1_Arr[$din_pt1_randkeys_Arr[1]];
					$dinner_pt3 			= $dinners_pt2_Arr[$din_pt2_randkeys_Arr];

					// UPDATE MERGE VALUE
					$merge_Arr['dinner_merge'] = true;

					// CREATE NEW OBJECT
					$dinner = new stdClass();

					// SET UP OBJECT VALUES
					$dinner->id = $dinner_pt1->id . '|' . $dinner_pt2->id . '|' . $dinner_pt3->id;
					$dinner->calorierange = $dinner_pt1->calorierange . '|' . $dinner_pt2->calorierange . '|' . $dinner_pt3->calorierange;
					$dinner->kmpid = $dinner_pt1->kmpid . '|' . $dinner_pt2->kmpid . '|' . $dinner_pt3->kmpid;
					$dinner->meal = $dinner_pt1->meal . '|' . $dinner_pt2->meal . '|' . $dinner_pt3->meal;
					$dinner->name = $dinner_pt1->name . '|' . $dinner_pt2->name . '|' . $dinner_pt3->name;
					$dinner->ingredients = $dinner_pt1->ingredients . '|' . $dinner_pt2->ingredients . '|' . $dinner_pt3->ingredients;
					$dinner->calories = $dinner_pt1->calories  . '|' .  $dinner_pt2->calories  . '|' .  $dinner_pt3->calories;
					$dinner->fat = $dinner_pt1->fat  . '|' .  $dinner_pt2->fat  . '|' .  $dinner_pt3->fat;
					$dinner->carbs = $dinner_pt1->carbs  . '|' .  $dinner_pt2->carbs  . '|' .  $dinner_pt3->carbs;
					$dinner->fiber = $dinner_pt1->fiber  . '|' .  $dinner_pt2->fiber  . '|' .  $dinner_pt3->fiber;
					$dinner->netcarbs = $dinner_pt1->netcarbs  . '|' .  $dinner_pt2->netcarbs  . '|' .  $dinner_pt3->netcarbs;
					$dinner->protein = $dinner_pt1->protein  . '|' .  $dinner_pt2->protein  . '|' .  $dinner_pt3->protein;
					$dinner->vegetarian = $dinner_pt1->vegetarian  . '|' .  $dinner_pt2->vegetarian  . '|' .  $dinner_pt3->vegetarian;
					$dinner->vegan = $dinner_pt1->vegan  . '|' .  $dinner_pt2->vegan  . '|' .  $dinner_pt3->vegan;
					$dinner->carnivore = $dinner_pt1->carnivore  . '|' .  $dinner_pt2->carnivore  . '|' .  $dinner_pt3->carnivore;
					$dinner->dairyfree = $dinner_pt1->dairyfree  . '|' .  $dinner_pt2->dairyfree  . '|' .  $dinner_pt3->dairyfree;
					$dinner->eggfree = $dinner_pt1->eggfree  . '|' .  $dinner_pt2->eggfree  . '|' .  $dinner_pt3->eggfree;
					$dinner->beginnerfriendly = $dinner_pt1->beginnerfriendly  . '|' .  $dinner_pt2->beginnerfriendly  . '|' .  $dinner_pt3->beginnerfriendly;
					$dinner->advanced = $dinner_pt1->advanced  . '|' .  $dinner_pt2->advanced  . '|' .  $dinner_pt3->advanced;
					$dinner->url = $dinner_pt1->url  . '|' .  $dinner_pt2->url  . '|' .  $dinner_pt3->url;
					$dinner->url = $dinner_pt1->posturl  . '|' .  $dinner_pt2->posturl  . '|' .  $dinner_pt3->posturl;

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				/**
				 *  ===============================================================================
				 *  END Special case with merge.
				 */

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
							'merge'		=> $merge_Arr['breakfast_merge'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
							'merge'		=> $merge_Arr['lunch_merge'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
							'merge'		=> $merge_Arr['dinner_merge'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
							'merge'		=> $merge_Arr['snack_merge'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
							'merge'		=> $merge_Arr['dessert_merge'],
						),
					),
				);

			endfor;

		ob_get_clean();

		return $mealcards_Arr;

	}

	public function return_meals_2_no() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 1200 == $value->calorierange ) ||
					( 1200 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
				 * will be used to get a random value later
				 */
				$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'breakfast' == strtolower( $value->meal ) ) &&
						( 1200 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 1200 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if ( count($breakfasts_Arr) > 0 ) :

					$bre_randkeys_Arr 	= array_rand( $breakfasts_Arr, 1 );
					$breakfast 			= $breakfasts_Arr[$bre_randkeys_Arr];

				else :

					$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';

				endif;

				if ( count($dinners_Arr) > 0 ) :

					$din_randkeys_Arr 	= array_rand( $dinners_Arr, 1 );
					$dinner 			= $dinners_Arr[$din_randkeys_Arr];

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
						),
					),
				);

			endfor;

			$mealcards_Arr['merge'] = false;

		ob_get_clean();

		return $mealcards_Arr;

	}

	public function return_meals_2_dessert() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 200 == $value->calorierange ) ||
					( 1200 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
				 * will be used to get a random value later
				 */
				$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'breakfast' == strtolower( $value->meal ) ) &&
						( 1200 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 1200 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$desserts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return ( 'dessert' == strtolower( $value->meal ) );
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if ( count($breakfasts_Arr) > 0 ) :

					$bre_randkeys_Arr 	= array_rand( $breakfasts_Arr, 1 );
					$breakfast 			= $breakfasts_Arr[$bre_randkeys_Arr];

				else :

					$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';

				endif;

				if ( count($dinners_Arr) > 0 ) :

					$din_randkeys_Arr 	= array_rand( $dinners_Arr, 1 );
					$dinner 			= $dinners_Arr[$din_randkeys_Arr];

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				if ( count($desserts_Arr) > 0 ) :

					$des_randkeys_Arr 	= array_rand( $desserts_Arr, 1 );
					$dessert 			= $desserts_Arr[$des_randkeys_Arr];

				else :

					$errors_Arr['dessert_error'] = 'We couldn\'t find a dessert with the selected criteria';

				endif;

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
						),
					),
				);

			endfor;

			$mealcards_Arr['merge'] = false;

		ob_get_clean();

		return $mealcards_Arr;

	}

	public function return_meals_2_snack() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 200 == $value->calorierange ) ||
					( 1200 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
				 * will be used to get a random value later
				 */
				$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'breakfast' == strtolower( $value->meal ) ) &&
						( 1200 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 1200 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$snacks_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return ( 'snack' == strtolower( $value->meal ) );
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if ( count($breakfasts_Arr) > 0 ) :

					$bre_randkeys_Arr 	= array_rand( $breakfasts_Arr, 1 );
					$breakfast 			= $breakfasts_Arr[$bre_randkeys_Arr];

				else :

					$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';

				endif;

				if ( count($dinners_Arr) > 0 ) :

					$din_randkeys_Arr 	= array_rand( $dinners_Arr, 1 );
					$dinner 			= $dinners_Arr[$din_randkeys_Arr];

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				if ( count($snacks_Arr) > 0 ) :

					$sna_randkeys_Arr 	= array_rand( $snacks_Arr, 1 );
					$snack 			= $snacks_Arr[$sna_randkeys_Arr];

				else :

					$errors_Arr['snack_error'] = 'We couldn\'t find a snack with the selected criteria';

				endif;

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
						),
					),
				);

			endfor;

			$mealcards_Arr['merge'] = false;

		ob_get_clean();

		return $mealcards_Arr;

	}

	public function return_meals_3_dessert() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 200 == $value->calorierange ) ||
					( 700 == $value->calorierange ) ||
					( 800 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
				 * will be used to get a random value later
				 */
				$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'breakfast' == strtolower( $value->meal ) ) &&
						( 700 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$lunches_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'lunch' == strtolower( $value->meal ) ) &&
						( 800 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 800 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$desserts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return ( 'dessert' == strtolower( $value->meal ) );
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if ( count($breakfasts_Arr) > 0 ) :

					$bre_randkeys_Arr 	= array_rand( $breakfasts_Arr, 1 );
					$breakfast 			= $breakfasts_Arr[$bre_randkeys_Arr];

				else :

					$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';

				endif;

				if ( count($lunches_Arr) > 0 ) :

					$lun_randkeys_Arr 	= array_rand( $lunches_Arr, 1 );
					$lunch 			= $lunches_Arr[$lun_randkeys_Arr];

				else :

					$errors_Arr['lunch_error'] = 'We couldn\'t find a lunch with the selected criteria';

				endif;

				if ( count($dinners_Arr) > 0 ) :

					$din_randkeys_Arr 	= array_rand( $dinners_Arr, 1 );
					$dinner 			= $dinners_Arr[$din_randkeys_Arr];

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				if ( count($desserts_Arr) > 0 ) :

					$des_randkeys_Arr 	= array_rand( $desserts_Arr, 1 );
					$dessert 			= $desserts_Arr[$des_randkeys_Arr];

				else :

					$errors_Arr['dessert_error'] = 'We couldn\'t find a dessert with the selected criteria';

				endif;

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
						),
					),
				);

			endfor;

			$mealcards_Arr['merge'] = false;

		ob_get_clean();

		return $mealcards_Arr;

	}

	public function return_meals_3_no() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 800 == $value->calorierange ) ||
					( 900 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
				 * will be used to get a random value later
				 */
				$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'breakfast' == strtolower( $value->meal ) ) &&
						( 800 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$lunches_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'lunch' == strtolower( $value->meal ) ) &&
						( 800 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 900 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if ( count($breakfasts_Arr) > 0 ) :

					$bre_randkeys_Arr 	= array_rand( $breakfasts_Arr, 1 );
					$breakfast 			= $breakfasts_Arr[$bre_randkeys_Arr];

				else :

					$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';

				endif;

				if ( count($lunches_Arr) > 0 ) :

					$lun_randkeys_Arr 	= array_rand( $lunches_Arr, 1 );
					$lunch 			= $lunches_Arr[$lun_randkeys_Arr];

				else :

					$errors_Arr['lunch_error'] = 'We couldn\'t find a lunch with the selected criteria';

				endif;

				if ( count($dinners_Arr) > 0 ) :

					$din_randkeys_Arr 	= array_rand( $dinners_Arr, 1 );
					$dinner 			= $dinners_Arr[$din_randkeys_Arr];

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
						),
					),
				);

			endfor;

			$mealcards_Arr['merge'] = false;

		ob_get_clean();

		return $mealcards_Arr;

	}

	public function return_meals_3_snack() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 200 == $value->calorierange ) ||
					( 700 == $value->calorierange ) ||
					( 800 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
				 * will be used to get a random value later
				 */
				$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'breakfast' == strtolower( $value->meal ) ) &&
						( 700 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$lunches_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'lunch' == strtolower( $value->meal ) ) &&
						( 800 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 800 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$snacks_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return ( 'snack' == strtolower( $value->meal ) );
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if ( count($breakfasts_Arr) > 0 ) :

					$bre_randkeys_Arr 	= array_rand( $breakfasts_Arr, 1 );
					$breakfast 			= $breakfasts_Arr[$bre_randkeys_Arr];

				else :

					$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';

				endif;

				if ( count($lunches_Arr) > 0 ) :

					$lun_randkeys_Arr 	= array_rand( $lunches_Arr, 1 );
					$lunch 			= $lunches_Arr[$lun_randkeys_Arr];

				else :

					$errors_Arr['lunch_error'] = 'We couldn\'t find a lunch with the selected criteria';

				endif;

				if ( count($dinners_Arr) > 0 ) :

					$din_randkeys_Arr 	= array_rand( $dinners_Arr, 1 );
					$dinner 			= $dinners_Arr[$din_randkeys_Arr];

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				if ( count($snacks_Arr) > 0 ) :

					$sna_randkeys_Arr 	= array_rand( $snacks_Arr, 1 );
					$snack 			= $snacks_Arr[$sna_randkeys_Arr];

				else :

					$errors_Arr['snack_error'] = 'We couldn\'t find a snack with the selected criteria';

				endif;

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
						),
					),
				);

			endfor;

			$mealcards_Arr['merge'] = false;

		ob_get_clean();

		return $mealcards_Arr;

	}

	public function return_meals_4_dessert() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 200 == $value->calorierange ) ||
					( 500 == $value->calorierange ) ||
					( 600 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
				 * will be used to get a random value later
				 */
				$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'breakfast' == strtolower( $value->meal ) ) &&
						( 600 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 *  BEGIN Special case with merge.
				 *  ===============================================================================
				 */

				$lunches_pt1_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'lunch' == strtolower( $value->meal ) ) &&
						( 500 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$lunches_pt2_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'lunch' == strtolower( $value->meal ) ) &&
						( 600 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 *  ===============================================================================
				 *  END Special case with merge.
				 */

				$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 600 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$desserts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return ( 'dessert' == strtolower( $value->meal ) );
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if ( count($breakfasts_Arr) > 0 ) :

					$bre_randkeys_Arr 	= array_rand( $breakfasts_Arr, 1 );
					$breakfast 			= $breakfasts_Arr[$bre_randkeys_Arr];

				else :

					$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';

				endif;

				/**
				 *  BEGIN Special case with merge.
				 *  ===============================================================================
				 */

				if (
						( count($lunches_pt1_Arr) > 0 ) ||
						( count($lunches_pt2_Arr) > 0 )
					) :

					if (
							( count($lunches_pt1_Arr) > 0 ) &&
							( count($lunches_pt2_Arr) > 0 )
						) :

						// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
						$lun_pt1_randkeys_Arr 	= array_rand( $lunches_pt1_Arr );
						$lun_pt2_randkeys_Arr 	= array_rand( $lunches_pt2_Arr );

						$lunch_pt1 				= $lunches_pt1_Arr[$lun_pt1_randkeys_Arr];
						$lunch_pt2 				= $lunches_pt2_Arr[$lun_pt2_randkeys_Arr];

					elseif (
							( count($lunches_pt1_Arr) > 0 ) &&
							( count($lunches_pt2_Arr) == 0 )
						) :

						// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
						$lun_randkeys_Arr 	= array_rand( $lunches_pt1_Arr, 2 );
						$lunch_pt1 			= $lunches_pt1_Arr[$lun_randkeys_Arr[0]];
						$lunch_pt2 			= $lunches_pt1_Arr[$lun_randkeys_Arr[1]];

					elseif (
							( count($lunches_pt1_Arr) == 0 ) &&
							( count($lunches_pt2_Arr) > 0 )
						) :

						// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
						$lun_randkeys_Arr 	= array_rand( $lunches_pt2_Arr, 2 );
						$lunch_pt1 			= $lunches_pt2_Arr[$lun_randkeys_Arr[0]];
						$lunch_pt2 			= $lunches_pt2_Arr[$lun_randkeys_Arr[1]];

					endif;

					// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
					$lun_pt1_randkeys_Arr 	= array_rand( $lunches_pt1_Arr );
					$lun_pt2_randkeys_Arr 	= array_rand( $lunches_pt2_Arr );

					$lunch_pt1 			= $lunches_pt1_Arr[$lun_pt1_randkeys_Arr];
					$lunch_pt2 			= $lunches_pt2_Arr[$lun_pt2_randkeys_Arr];

					// UPDATE MERGE VALUE
					$merge_Arr['lunch_merge'] = true;

					// CREATE NEW OBJECT
					$lunch = new stdClass();

					// SET UP OBJECT VALUES
					$lunch->id = $lunch_pt1->id . '|' . $lunch_pt2->id;
					$lunch->calorierange = $lunch_pt1->calorierange . '|' . $lunch_pt2->calorierange;
					$lunch->kmpid = $lunch_pt1->kmpid . '|' . $lunch_pt2->kmpid;
					$lunch->meal = $lunch_pt1->meal . '|' . $lunch_pt2->meal;
					$lunch->name = $lunch_pt1->name . '|' . $lunch_pt2->name;
					$lunch->ingredients = $lunch_pt1->ingredients . '|' . $lunch_pt2->ingredients;
					$lunch->calories = $lunch_pt1->calories  . '|' .  $lunch_pt2->calories;
					$lunch->fat = $lunch_pt1->fat  . '|' .  $lunch_pt2->fat;
					$lunch->carbs = $lunch_pt1->carbs  . '|' .  $lunch_pt2->carbs;
					$lunch->fiber = $lunch_pt1->fiber  . '|' .  $lunch_pt2->fiber;
					$lunch->netcarbs = $lunch_pt1->netcarbs  . '|' .  $lunch_pt2->netcarbs;
					$lunch->protein = $lunch_pt1->protein  . '|' .  $lunch_pt2->protein;
					$lunch->vegetarian = $lunch_pt1->vegetarian  . '|' .  $lunch_pt2->vegetarian;
					$lunch->vegan = $lunch_pt1->vegan  . '|' .  $lunch_pt2->vegan;
					$lunch->carnivore = $lunch_pt1->carnivore  . '|' .  $lunch_pt2->carnivore;
					$lunch->dairyfree = $lunch_pt1->dairyfree  . '|' .  $lunch_pt2->dairyfree;
					$lunch->eggfree = $lunch_pt1->eggfree  . '|' .  $lunch_pt2->eggfree;
					$lunch->beginnerfriendly = $lunch_pt1->beginnerfriendly  . '|' .  $lunch_pt2->beginnerfriendly;
					$lunch->advanced = $lunch_pt1->advanced  . '|' .  $lunch_pt2->advanced;
					$lunch->url = $lunch_pt1->url  . '|' .  $lunch_pt2->url;
					$lunch->posturl = $lunch_pt1->posturl  . '|' .  $lunch_pt2->posturl;

				else :

					$errors_Arr['lunch_error'] = 'We couldn\'t find a lunch with the selected criteria';

				endif;

				/**
				 *  ===============================================================================
				 *  END Special case with merge.
				 */

				if ( count($dinners_Arr) > 0 ) :

					$din_randkeys_Arr 	= array_rand( $dinners_Arr, 1 );
					$dinner 			= $dinners_Arr[$din_randkeys_Arr];

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				if ( count($desserts_Arr) > 0 ) :

					$des_randkeys_Arr 	= array_rand( $desserts_Arr, 1 );
					$dessert 			= $desserts_Arr[$des_randkeys_Arr];

				else :

					$errors_Arr['dessert_error'] = 'We couldn\'t find a dessert with the selected criteria';

				endif;

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
							'merge'		=> $merge_Arr['breakfast_merge'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
							'merge'		=> $merge_Arr['lunch_merge'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
							'merge'		=> $merge_Arr['dinner_merge'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
							'merge'		=> $merge_Arr['snack_merge'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
							'merge'		=> $merge_Arr['dessert_merge'],
						),
					),
				);

			endfor;

		ob_get_clean();

		return $mealcards_Arr;

	}

	public function return_meals_4_no() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 600 == $value->calorierange ) ||
					( 700 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
				 * will be used to get a random value later
				 */
				$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'breakfast' == strtolower( $value->meal ) ) &&
						( 600 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$lunches_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'lunch' == strtolower( $value->meal ) ) &&
						( 600 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);


				$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 700 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if ( count($breakfasts_Arr) > 0 ) :

					$bre_randkeys_Arr 	= array_rand( $breakfasts_Arr, 1 );
					$breakfast 			= $breakfasts_Arr[$bre_randkeys_Arr];

				else :

					$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';

				endif;

				/**
				 *  BEGIN Special case with merge.
				 *  ===============================================================================
				 */
				if ( count($lunches_Arr) > 0 ) :

					// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
					$lun_randkeys_Arr 	= array_rand( $lunches_Arr, 2 );
					$lunch_pt1 			= $lunches_Arr[$lun_randkeys_Arr[0]];
					$lunch_pt2 			= $lunches_Arr[$lun_randkeys_Arr[1]];

					// UPDATE MERGE VALUE
					$merge_Arr['lunch_merge'] = true;

					// CREATE NEW OBJECT
					$lunch = new stdClass();

					// SET UP OBJECT VALUES
					$lunch->id = $lunch_pt1->id . '|' . $lunch_pt2->id;
					$lunch->calorierange = $lunch_pt1->calorierange . '|' . $lunch_pt2->calorierange;
					$lunch->kmpid = $lunch_pt1->kmpid . '|' . $lunch_pt2->kmpid;
					$lunch->meal = $lunch_pt1->meal . '|' . $lunch_pt2->meal;
					$lunch->name = $lunch_pt1->name . '|' . $lunch_pt2->name;
					$lunch->ingredients = $lunch_pt1->ingredients . '|' . $lunch_pt2->ingredients;
					$lunch->calories = $lunch_pt1->calories  . '|' .  $lunch_pt2->calories;
					$lunch->fat = $lunch_pt1->fat  . '|' .  $lunch_pt2->fat;
					$lunch->carbs = $lunch_pt1->carbs  . '|' .  $lunch_pt2->carbs;
					$lunch->fiber = $lunch_pt1->fiber  . '|' .  $lunch_pt2->fiber;
					$lunch->netcarbs = $lunch_pt1->netcarbs  . '|' .  $lunch_pt2->netcarbs;
					$lunch->protein = $lunch_pt1->protein  . '|' .  $lunch_pt2->protein;
					$lunch->vegetarian = $lunch_pt1->vegetarian  . '|' .  $lunch_pt2->vegetarian;
					$lunch->vegan = $lunch_pt1->vegan  . '|' .  $lunch_pt2->vegan;
					$lunch->carnivore = $lunch_pt1->carnivore  . '|' .  $lunch_pt2->carnivore;
					$lunch->dairyfree = $lunch_pt1->dairyfree  . '|' .  $lunch_pt2->dairyfree;
					$lunch->eggfree = $lunch_pt1->eggfree  . '|' .  $lunch_pt2->eggfree;
					$lunch->beginnerfriendly = $lunch_pt1->beginnerfriendly  . '|' .  $lunch_pt2->beginnerfriendly;
					$lunch->advanced = $lunch_pt1->advanced  . '|' .  $lunch_pt2->advanced;
					$lunch->url = $lunch_pt1->url  . '|' .  $lunch_pt2->url;
					$lunch->posturl = $lunch_pt1->posturl  . '|' .  $lunch_pt2->posturl;

				else :

					$errors_Arr['lunch_error'] = 'We couldn\'t find a lunch with the selected criteria';

				endif;

				/**
				 *  ===============================================================================
				 *  END Special case with merge.
				 */

				if ( count($dinners_Arr) > 0 ) :

					$din_randkeys_Arr 	= array_rand( $dinners_Arr, 1 );
					$dinner 			= $dinners_Arr[$din_randkeys_Arr];

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
							'merge'		=> $merge_Arr['breakfast_merge'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
							'merge'		=> $merge_Arr['lunch_merge'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
							'merge'		=> $merge_Arr['dinner_merge'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
							'merge'		=> $merge_Arr['snack_merge'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
							'merge'		=> $merge_Arr['dessert_merge'],
						),
					),
				);

			endfor;

		ob_get_clean();

		return $mealcards_Arr;

	}

	public function return_meals_4_snack() {

		ob_start();

			// Set up variables
			$breakfasts_Arr 	= [];
			$lunches_Arr 		= [];
			$dinners_Arr		= [];
			$desserts_Arr 		= [];
			$snacks_Arr 		= [];
			$breakfast 			= [];
			$lunch 				= [];
			$dinner 			= [];
			$dessert 			= [];
			$snack 				= [];
			$sub_results_Arr 	= [];
			$errors_Arr 		= array(
				'breakfast_error' => '',
				'lunch_error' => '',
				'dinner_error' => '',
				'snack_error' => '',
				'dessert_error' => '',
			);
			$merge_Arr 			= array(
				'breakfast_merge' 	=> false,
				'lunch_merge' 		=> false,
				'dinner_merge' 		=> false,
				'dessert_merge' 	=> false,
				'snack_merge' 		=> false,
			);

			/**
			 * Get the subResults array based on the spreadsheet's Meal
			 * Possibilities sheet:
			 */
			$sub_results_Arr= array_filter($this->results, function($value, $key) {
				return (
					( 200 == $value->calorierange ) ||
					( 500 == $value->calorierange ) ||
					( 600 == $value->calorierange )
				);
			}, ARRAY_FILTER_USE_BOTH);

			$mealcards_Arr = [];

			for ($i = 0; $i < $this->number_of_cards; $i++ ) :

				/**
				 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
				 * will be used to get a random value later
				 */
				$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'breakfast' == strtolower( $value->meal ) ) &&
						( 600 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 *  BEGIN Special case with merge.
				 *  ===============================================================================
				 */

				$lunches_pt1_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'lunch' == strtolower( $value->meal ) ) &&
						( 500 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$lunches_pt2_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'lunch' == strtolower( $value->meal ) ) &&
						( 600 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 *  ===============================================================================
				 *  END Special case with merge.
				 */

				$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return (
						( 'dinner' == strtolower( $value->meal ) ) &&
						( 600 == $value->calorierange )
					);
				}, ARRAY_FILTER_USE_BOTH);

				$snacks_Arr = array_filter($sub_results_Arr, function($value, $key) {
					return ( 'snack' == strtolower( $value->meal ) );
				}, ARRAY_FILTER_USE_BOTH);

				/**
				 * Get the card's breakfast/lunch/dinner/dessert/snack values
				 */

				if ( count($breakfasts_Arr) > 0 ) :

					$bre_randkeys_Arr 	= array_rand( $breakfasts_Arr, 1 );
					$breakfast 			= $breakfasts_Arr[$bre_randkeys_Arr];

				else :

					$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';

				endif;

				/**
				 *  BEGIN Special case with merge.
				 *  ===============================================================================
				 */

				if (
						( count($lunches_pt1_Arr) > 0 ) ||
						( count($lunches_pt2_Arr) > 0 )
					) :

					if (
							( count($lunches_pt1_Arr) > 0 ) &&
							( count($lunches_pt2_Arr) > 0 )
						) :

						// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
						$lun_pt1_randkeys_Arr 	= array_rand( $lunches_pt1_Arr );
						$lun_pt2_randkeys_Arr 	= array_rand( $lunches_pt2_Arr );

						$lunch_pt1 				= $lunches_pt1_Arr[$lun_pt1_randkeys_Arr];
						$lunch_pt2 				= $lunches_pt2_Arr[$lun_pt2_randkeys_Arr];

					elseif (
							( count($lunches_pt1_Arr) > 0 ) &&
							( count($lunches_pt2_Arr) == 0 )
						) :

						// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
						$lun_randkeys_Arr 	= array_rand( $lunches_pt1_Arr, 2 );
						$lunch_pt1 			= $lunches_pt1_Arr[$lun_randkeys_Arr[0]];
						$lunch_pt2 			= $lunches_pt1_Arr[$lun_randkeys_Arr[1]];

					elseif (
							( count($lunches_pt1_Arr) == 0 ) &&
							( count($lunches_pt2_Arr) > 0 )
						) :

						// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
						$lun_randkeys_Arr 	= array_rand( $lunches_pt2_Arr, 2 );
						$lunch_pt1 			= $lunches_pt2_Arr[$lun_randkeys_Arr[0]];
						$lunch_pt2 			= $lunches_pt2_Arr[$lun_randkeys_Arr[1]];

					endif;

					// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
					$lun_pt1_randkeys_Arr 	= array_rand( $lunches_pt1_Arr );
					$lun_pt2_randkeys_Arr 	= array_rand( $lunches_pt2_Arr );

					$lunch_pt1 			= $lunches_pt1_Arr[$lun_pt1_randkeys_Arr];
					$lunch_pt2 			= $lunches_pt2_Arr[$lun_pt2_randkeys_Arr];

					// UPDATE MERGE VALUE
					$merge_Arr['lunch_merge'] = true;

					// CREATE NEW OBJECT
					$lunch = new stdClass();

					// SET UP OBJECT VALUES
					$lunch->id = $lunch_pt1->id . '|' . $lunch_pt2->id;
					$lunch->calorierange = $lunch_pt1->calorierange . '|' . $lunch_pt2->calorierange;
					$lunch->kmpid = $lunch_pt1->kmpid . '|' . $lunch_pt2->kmpid;
					$lunch->meal = $lunch_pt1->meal . '|' . $lunch_pt2->meal;
					$lunch->name = $lunch_pt1->name . '|' . $lunch_pt2->name;
					$lunch->ingredients = $lunch_pt1->ingredients . '|' . $lunch_pt2->ingredients;
					$lunch->calories = $lunch_pt1->calories  . '|' .  $lunch_pt2->calories;
					$lunch->fat = $lunch_pt1->fat  . '|' .  $lunch_pt2->fat;
					$lunch->carbs = $lunch_pt1->carbs  . '|' .  $lunch_pt2->carbs;
					$lunch->fiber = $lunch_pt1->fiber  . '|' .  $lunch_pt2->fiber;
					$lunch->netcarbs = $lunch_pt1->netcarbs  . '|' .  $lunch_pt2->netcarbs;
					$lunch->protein = $lunch_pt1->protein  . '|' .  $lunch_pt2->protein;
					$lunch->vegetarian = $lunch_pt1->vegetarian  . '|' .  $lunch_pt2->vegetarian;
					$lunch->vegan = $lunch_pt1->vegan  . '|' .  $lunch_pt2->vegan;
					$lunch->carnivore = $lunch_pt1->carnivore  . '|' .  $lunch_pt2->carnivore;
					$lunch->dairyfree = $lunch_pt1->dairyfree  . '|' .  $lunch_pt2->dairyfree;
					$lunch->eggfree = $lunch_pt1->eggfree  . '|' .  $lunch_pt2->eggfree;
					$lunch->beginnerfriendly = $lunch_pt1->beginnerfriendly  . '|' .  $lunch_pt2->beginnerfriendly;
					$lunch->advanced = $lunch_pt1->advanced  . '|' .  $lunch_pt2->advanced;
					$lunch->url = $lunch_pt1->url  . '|' .  $lunch_pt2->url;
					$lunch->posturl = $lunch_pt1->posturl  . '|' .  $lunch_pt2->posturl;

				else :

					$errors_Arr['lunch_error'] = 'We couldn\'t find a lunch with the selected criteria';

				endif;

				/**
				 *  ===============================================================================
				 *  END Special case with merge.
				 */

				if ( count($dinners_Arr) > 0 ) :

					$din_randkeys_Arr 	= array_rand( $dinners_Arr, 1 );
					$dinner 			= $dinners_Arr[$din_randkeys_Arr];

				else :

					$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';

				endif;

				if ( count($snacks_Arr) > 0 ) :

					$sna_randkeys_Arr 	= array_rand( $snacks_Arr, 1 );
					$snack 			= $snacks_Arr[$sna_randkeys_Arr];

				else :

					$errors_Arr['snack_error'] = 'We couldn\'t find a snack with the selected criteria';

				endif;

				$mealcards_Arr['day' . ($i + 1)] = array(
					'meals' 	=> array(
						'breakfast' => array(
							'meal' 		=> $breakfast,
							'error' 	=> $errors_Arr['breakfast_error'],
							'merge'		=> $merge_Arr['breakfast_merge'],
						),
						'lunch' => array(
							'meal' 		=> $lunch,
							'error' 	=> $errors_Arr['lunch_error'],
							'merge'		=> $merge_Arr['lunch_merge'],
						),
						'dinner' => array(
							'meal' 		=> $dinner,
							'error' 	=> $errors_Arr['dinner_error'],
							'merge'		=> $merge_Arr['dinner_merge'],
						),
						'snack' => array(
							'meal' 		=> $snack,
							'error' 	=> $errors_Arr['snack_error'],
							'merge'		=> $merge_Arr['snack_merge'],
						),
						'dessert' => array(
							'meal' 		=> $dessert,
							'error' 	=> $errors_Arr['dessert_error'],
							'merge'		=> $merge_Arr['dessert_merge'],
						),
					),
				);

			endfor;

		ob_get_clean();

		return $mealcards_Arr;

	}

}
