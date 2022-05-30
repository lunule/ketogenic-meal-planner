<?php
/**
 * The custom post type query with the meta query buildig
 * ========================================================================
 */

// TESTING THE FUNCTIONS USED IN THE AJAX FILE
//
echo "<h4>Testing</h4>";

$values_Arr  	= array(
	'addDessert'         => 'snack',
	'calTarget'          => '2500',
	'dietType1'          => 'vegetarian',
	'dietType2'          => 'no',
	'email'              => 'email@example.com',
	'phone'              => '1233-5667',
	'fasting'            => 'yes',
	'mealComplexity'     => 'beginner',
	'mealsNumber'        => '4',
	'firstName'          => 'George',
	'lastName'           => 'Washington',
	'sensitivities'      => 'egg-free',
	'sensitivitiesOther' => 'Other sensitivity',
	'yoarGoals'          => 'Some value',
);

$calTarget 			= $values_Arr['calTarget'];
$mealsNumber 		= $values_Arr['mealsNumber'];
$adddessert 		= $values_Arr['adddessert'];
$number_of_cards 	= 2;

$diettype_mq_Arr 		= [];
$sensitivity_mq_Arr 	= [];
$complexity_mq_Arr 		= [];

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

/* --------------------------------------------------------------------- */

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
	( 'vegan' == $values_Arr['dietType2'] )
) {
	$diettype_mq_Arr = array(
		'relation' => 'AND',
		array(
			'key'     => 'kmp-cf-vegetarian',
			'value'   => '0',
			'compare' => 'LIKE',
		),
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

/* --------------------------------------------------------------------- */

// If the user selects 'carnivore', the meals can be ANYTHING SO WE DON'T NEED TO
// ADD ANYTHING TO THE SQL HERE
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
/* --------------------------------------------------------------------- */

if ( 'beginner' == $values_Arr['mealComplexity'] ) {
	$complexity_mq_Arr = array(
		'key'     => 'kmp-cf-advanced',
		'value'   => '1',
		'compare' => 'NOT LIKE',
	);
}
// if 'advanced', we don't need anything extra. It just means that both 'beginner'
// and 'advanced' can have any value.

/* --------------------------------------------------------------------- */

$query_args_Arr = array(
	'post_status'       => 'publish',
	'post_type'         => 'kmp-meal',
	'posts_per_page'    => -1,
	'meta_query'        => array(
		'relation' => 'AND',
		$mealtype_mq_Arr,
		$diettype_mq_Arr,
		$sensitivity_mq_Arr,
		$complexity_mq_Arr,
	)
);

$posts_Arr 	= get_posts( $query_args_Arr );
$results 	= [];
$i 			= 0;

/**
 * The above query returns "default" WP post objects - but this is not what we need.
 *
 * Let's build our own array of custom-built post data objects - these objects
 * will contain only the post data we really need in the AJAX callback
 */
foreach ( $posts_Arr as $post ) {

	$pid = $post->ID;

	$results[$i]->id                = $pid;
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

	// the $result object should be cloned, and the preg_match_all applied on the clone
	// to keep the original $result object unaffected.
	$resultTemp = $results[$i];
	$urlstring 	= $resultTemp->url;

	if ( '' !== $results[$i]->url ) {

		preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $urlstring , $resultTemp);

		// The project spreadsheet's url column might include multiple urls - we
		// only need one, so let's get the first one and use it to get the related
		// recipe/post's id
		$fixed_permalink = get_site_url() . preg_replace('#^.+://[^/]+#', '', $resultTemp[0])[0];
		$meal_post_id = url_to_postid( $fixed_permalink );

		// Now we can use the post id to get the post's featured image/post thumbnail
		$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $meal_post_id ), 'thumbnail' );

		// If there's no URL result, the meal result's new url value should be
		// an empty string
		if ( false == $image_attributes ) {

			$results[$i]->url = '';

		// ... otherwise it shoul be the featured image/post thumbnail url
		} else {
			$results[$i]->url = $image_attributes[0];
		}
	}
	$i++;
}

/**
 * Test a cpclass function
 * ========================================================================
 */

//var_dump($results[15]);

// Set up variables
$breakfasts_Arr = [];
$lunches_Arr = [];
$dinners_Arr = [];
$desserts_Arr = [];
$snacks_Arr = [];
$breakfast = [];
$lunch = [];
$dinner = [];
$dessert = [];
$snack = [];
$sub_results_Arr = [];
$errors_Arr = array(
	'breakfast_error' => '',
	'lunch_error' => '',
	'dinner_error' => '',
	'snack_error' => '',
	'dessert_error' => '',
);
$merge_Arr = array(
	'breakfast_merge' => false,
	'lunch_merge' => false,
	'dinner_merge' => false,
	'dessert_merge' => false,
	'snack_merge' => false,
);

/**
 * Get the subResults array based on the spreadsheet's Meal
 * Possibilities sheet:
 */
$sub_results_Arr = array_filter($results, function($value, $key) {
	return (
		( 200 == $value->calorierange ) ||
		( 500 == $value->calorierange ) ||
		( 600 == $value->calorierange )
	);
}, ARRAY_FILTER_USE_BOTH);

$mealcards_Arr = [];

for ($i = 0; $i < $number_of_cards; $i++ ) {

	/**
	 * Set up the breakfast/lunch/dinner/dessert/snack arrays - these
	 * will be used to get a random value later
	 */
	$breakfasts_Arr = array_filter($sub_results_Arr, function($value, $key) {
		return (
			( 'breakfast' == $value->meal ) &&
			( 600 == $value->calorierange )
		);
	}, ARRAY_FILTER_USE_BOTH);

	//var_dump( $breakfasts_Arr );

	/**
	 *  BEGIN Special case with merge.
	 *  ===================================================================
	 */

	$lunches_pt1_Arr = array_filter($sub_results_Arr, function($value, $key) {
		return (
			( 'lunch' == $value->meal ) &&
			( 500 == $value->calorierange )
		);
	}, ARRAY_FILTER_USE_BOTH);

	$lunches_pt2_Arr = array_filter($sub_results_Arr, function($value, $key) {
		return (
			( 'lunch' == $value->meal ) &&
			( 600 == $value->calorierange )
		);
	}, ARRAY_FILTER_USE_BOTH);

	/**
	 *  ===================================================================
	 *  END Special case with merge.
	 */

	$dinners_Arr = array_filter($sub_results_Arr, function($value, $key) {
		return (
			( 'dinner' == $value->meal ) &&
			( 600 == $value->calorierange )
		);
	}, ARRAY_FILTER_USE_BOTH);

	$snacks_Arr = array_filter($sub_results_Arr, function($value, $key) {
		return ( 'snack' == $value->meal );
	}, ARRAY_FILTER_USE_BOTH);

	/**
	 * Get the card's breakfast/lunch/dinner/dessert/snack values
	 */

	if ( count($breakfasts_Arr) > 0 ) {
		$bre_randkeys_Arr = array_rand( $breakfasts_Arr, 1 );
		$breakfast        = $breakfasts_Arr[$bre_randkeys_Arr];
	} else {
		$errors_Arr['breakfast_error'] = 'We couldn\'t find a breakfast with the selected criteria';
	}

	/**
	 *  BEGIN Special case with merge.
	 *  ===================================================================
	 */

	if (
		( count($lunches_pt1_Arr) > 0 ) ||
		( count($lunches_pt2_Arr) > 0 )
	) {

		if (
			( count($lunches_pt1_Arr) > 0 ) &&
			( count($lunches_pt2_Arr) > 0 )
		) {
			// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
			$lun_pt1_randkeys_Arr   = array_rand( $lunches_pt1_Arr );
			$lun_pt2_randkeys_Arr   = array_rand( $lunches_pt2_Arr );

			$lunch_pt1              = $lunches_pt1_Arr[$lun_pt1_randkeys_Arr];
			$lunch_pt2              = $lunches_pt2_Arr[$lun_pt2_randkeys_Arr];
		}
		elseif (
			( count($lunches_pt1_Arr) > 0 ) &&
			( count($lunches_pt2_Arr) == 0 )
		) {
			// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
			$lun_randkeys_Arr   = array_rand( $lunches_pt1_Arr, 2 );
			$lunch_pt1          = $lunches_pt1_Arr[$lun_randkeys_Arr[0]];
			$lunch_pt2          = $lunches_pt1_Arr[$lun_randkeys_Arr[1]];
		}
		elseif (
			( count($lunches_pt1_Arr) == 0 ) &&
			( count($lunches_pt2_Arr) > 0 )
		) {
			// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
			$lun_randkeys_Arr   = array_rand( $lunches_pt2_Arr, 2 );
			$lunch_pt1          = $lunches_pt2_Arr[$lun_randkeys_Arr[0]];
			$lunch_pt2          = $lunches_pt2_Arr[$lun_randkeys_Arr[1]];
		}

		// GET THE TWO RANDOM ELEMENTS THAT WILL NEED TO BE MERGED
		$lun_pt1_randkeys_Arr   = array_rand( $lunches_pt1_Arr );
		$lun_pt2_randkeys_Arr   = array_rand( $lunches_pt2_Arr );

		$lunch_pt1          = $lunches_pt1_Arr[$lun_pt1_randkeys_Arr];
		$lunch_pt2          = $lunches_pt2_Arr[$lun_pt2_randkeys_Arr];

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

	} else {
		$errors_Arr['lunch_error'] = 'We couldn\'t find a lunch with the selected criteria';
	}

	/**
	 *  ===================================================================
	 *  END Special case with merge.
	 */

	if ( count($dinners_Arr) > 0 ) {
		$din_randkeys_Arr  = array_rand( $dinners_Arr, 1 );
		$dinner            = $dinners_Arr[$din_randkeys_Arr];
	} else {
		$errors_Arr['dinner_error'] = 'We couldn\'t find a dinner with the selected criteria';
	}

	if ( count($snacks_Arr) > 0 ) {

		$sna_randkeys_Arr  = array_rand( $snacks_Arr, 1 );
		$snack             = $snacks_Arr[$sna_randkeys_Arr];
	} else {
		$errors_Arr['snack_error'] = 'We couldn\'t find a snack with the selected criteria';
	}

	$mealcards_Arr['day' . ($i + 1)] = array(
		'meals' 	=> array(
			'breakfast' => array(
				'meal'      => $breakfast,
				'error'     => $errors_Arr['breakfast_error'],
				'merge'     => $merge_Arr['breakfast_merge'],
			),
			'lunch' => array(
				'meal'      => $lunch,
				'error'     => $errors_Arr['lunch_error'],
				'merge'     => $merge_Arr['lunch_merge'],
			),
			'dinner' => array(
				'meal'      => $dinner,
				'error'     => $errors_Arr['dinner_error'],
				'merge'     => $merge_Arr['dinner_merge'],
			),
			'snack' => array(
				'meal'      => $snack,
				'error'     => $errors_Arr['snack_error'],
				'merge'     => $merge_Arr['snack_merge'],
			),
			'dessert' => array(
				'meal'      => $dessert,
				'error'     => $errors_Arr['dessert_error'],
				'merge'     => $merge_Arr['dessert_merge'],
			),
		),
	);

}

/**
 * And test the result
 * ========================================================================
 */

echo str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' . var_export($mealcards_Arr, true) . ' ?>', true ) );
