<?php
/**
 * Template Name: KMP App Page Template
 */

get_header();

// The Query
$args = array(
	'post_status' 		=> 'publish',
	'post_type' 		=> 'kmp-meal',
	'posts_per_page' 	=> -1,
	/*
	'meta_query'		=> array(
		'relation' => 'AND',
		$mealtype_mq_Arr,
		$diettype_mq_Arr,
		$sensitivity_mq_Arr,
		$complexity_mq_Arr,
	)
	*/
);

$the_query = new WP_Query( $args );

$found = $the_query->found_posts;
// var_dump( $found );

// The Loop
if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		//echo '<div>' . $post->ID . '</div>';
	}
} else {
	// no posts found
}
/* Restore original Post Data */
wp_reset_postdata();
?>

<div>

	<div id="keto-content" <?php post_class(); ?>>
		<?php
		global $post;

		if ( is_single() ):
			?>
			<div class="post-thumbnail-image">
				<?php
				if (function_exists('wc_memberships_is_post_content_restricted') && function_exists('wc_memberships_user_can') && wc_memberships_is_post_content_restricted() &&
					(
						(!is_user_logged_in()) ||
						(is_user_logged_in() && !wc_memberships_user_can(get_current_user_id(), 'view', array($post->post_type => $post->ID)))
					)
				):
					echo get_the_post_thumbnail( $post, 'large' );
				else:
					echo apply_filters( 'efly_post_thumbnail_html', get_the_post_thumbnail( $post, 'large' ), $post->ID, '', '', '' );
				endif;
				?>
			</div>
			<?php
		endif;

		/*if (function_exists('wc_memberships_is_post_content_restricted') && function_exists('wc_memberships_user_can') && wc_memberships_is_post_content_restricted() &&
			(
				(!is_user_logged_in()) ||
				(is_user_logged_in() && !wc_memberships_user_can(get_current_user_id(), 'view', array($post->post_type => $post->ID)))
			)
		):
			include( 'paywall-block.php' );
		else:*/
?>
			<section class="app-background">

				<div class="app-background-overlay" style="background-image: url(<?php echo get_site_url(); ?>/wp-content/uploads/2021/07/BG-HomePage-High.png);"></div>

				<div data-negative="false" class="app-background-shape app-background-shape-bottom">

					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">

						<path d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
						c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
						c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z" class="app-background-shape-fill"></path>

					</svg>

				</div>

			</section>

			<div id="kmpAllRoot">

				<div class="kmp-all-wrap">

					<?php
					if ( true === KMP_DEV_MODE ) : ?>

						<div class="kmp-sql-testing">

							<?php
							// Direct query testing with the 2500_(...)_4_snack function content -
							// WORKS ONLY WITH CALTARGET = 2500, MEALS NUMBER = 4 AND ADD
							// DESSERT = SNACK VALUES.

							//include_once KMP_PUBLIC_DIR_PATH . 'testing/query-testing.php';
							?>

							<h4>Query args</h4>

							<?php
							/*
							$values_Arr = array(
								'addDessert'         => 'snack',
								'calTarget'          => '2500',
								'dietType1'          => 'vegetarian',
								'dietType2'          => 'no',
								'email'              => 'email@example.com',
								'fasting'            => 'yes',
								'mealComplexity'     => 'beginner',
								'mealsNumber'        => '4',
								'firstName'          => 'George',
								'lastName'           => 'Washington',
								'sensitivities'      => 'egg-free',
								'sensitivitiesOther' => 'Other sensitivity',
								'yoarGoals'          => 'My Goals'
							);
							*/
							?>
							<table class="kmp-testing-table">
								<thead>
									<tr>
										<th>Add Dessert</th>
										<th>Calorie Target</th>
										<th>Diet Type 1</th>
										<th>Diet Type 2</th>
										<th>Email</th>
										<th>Fasting</th>
										<th>Meal Complexity</th>
										<th>Number of Meals</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Phone</th>
										<th>Sensitivities</th>
										<th>Sensitivities - Other</th>
										<th>Your Goals</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>snack</td>
										<td>2500</td>
										<td>vegetarian</td>
										<td>no</td>
										<td>email@example.com</td>
										<td>no</td>
										<td>beginner</td>
										<td>4</td>
										<td>George</td>
										<td>Washington</td>
										<td>+1 503-736-0119</td>
										<td>no</td>
										<td>Some other food sensitivity...</td>
										<td>Some goal...</td>
									</tr>
								</tbody>
							</table>

							<div class="kmp-testing-button-container">

								<button class="kmp-testing-button">Generate</button>
								<div class="kmp-testing-button--pseudo"></div>

							</div>

							<h4>Cards</h4>

							<div class="kmp-results-wrap testing">

								<div class="kmp-results">

									<div class="kmp-results__loader-wrap">

										<div class="kmp-results__loader">
											<div class="square" style="background: #2FAFBE;"></div>
											<div class="square" style="background: #2FAFBE;"></div>
											<div class="square last" style="background: #e6b081;"></div>
											<div class="square clear" style="background: #2FAFBE;"></div>
											<div class="square" style="background: #2FAFBE;"></div>
											<div class="square last" style="background: #2FAFBE;"></div>
											<div class="square clear" style="background: #2FAFBE;"></div>
											<div class="square" style="background: #2FAFBE;"></div>
											<div class="square last" style="background: #2FAFBE;"></div>
										</div>

									</div>

									<div class="kmp-results__list">
										<ul class="cards-list kmp-masonry">
											<li class="kmp-masonry__grid-sizer"></li>
										</ul>

										<?php
										if ( false === USER_IS_PLATINUM_MEMBER ) : ?>

											<div class="kmp-results-restricted-wrap kmp-gauges">

												<div class="kmp-results-restricted gauge-block showing overall">

													<div class="gauge-title">

														<div class="mo-message">

															<div><i class="fas fa-lock" aria-hidden="true"></i></div>

															<div>Members Only</div>

															<div><a href="<?php echo get_site_url(); ?>/join/">Join Today!</a></div>

														</div>

													</div>

												</div>

											</div>

										<?php
										endif; ?>

									</div>

								</div>

							</div>

							<h4>Results</h4>

							<div class="kmp-sql-testing__results">

								<table class="kstr-table kmp-testing-table">
									<thead>
										<tr>
											<th>Shown As</th>
											<th>ID</th>
											<th>Calorie Range</th>
											<th>Meal</th>
											<th>Name</th>
											<th>Cals</th>
											<th>Vegetarian</th>
											<th>Vegan</th>
											<th>Carnivore</th>
											<th>Dairy-Free</th>
											<th>Egg-Free</th>
											<th>Beginner-Friendly</th>
											<th>Advanced</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>

							</div>

						</div>

					<?php
					endif; ?>

					<div class="kmp-outer-wrap">

						<div class="kmp-backstep-wrap">

							<div class="kmp-backstep">

								<p><?php _e( 'Want to try a different meal plan?', 'kmp' ) ?></p>
								<p><a class="kmp-link--backstep" href="#"><?php _e( 'Change your options', 'kmp' ) ?></a></p>

							</div>

						</div>

						<div class="kmp-results-header-wrap">

							<div class="kmp-results-header">

								<div>

									<h2>This Week's Meal Plan</h2>

									<div class="kmp-button--regenerate">
										<span></span>
									</div>

								</div>

								<div class="kmp-choices-summary">
								</div>

							</div>

						</div>

						<div class="kmp-app-wrap">

							<div id="ketogenic-meal-planner"></div>

						</div>

						<?php
						if ( false == KMP_DEV_MODE ) : ?>

							<div class="kmp-results-wrap">

								<div class="kmp-results">

									<div class="kmp-results__loader-wrap">

										<div class="kmp-results__loader">
											<div class="square" style="background: #2FAFBE;"></div>
											<div class="square" style="background: #2FAFBE;"></div>
											<div class="square last" style="background: #e6b081;"></div>
											<div class="square clear" style="background: #2FAFBE;"></div>
											<div class="square" style="background: #2FAFBE;"></div>
											<div class="square last" style="background: #2FAFBE;"></div>
											<div class="square clear" style="background: #2FAFBE;"></div>
											<div class="square" style="background: #2FAFBE;"></div>
											<div class="square last" style="background: #2FAFBE;"></div>
										</div>

									</div>

									<div class="kmp-results__list">
										<ul class="cards-list kmp-masonry">
											<li class="kmp-masonry__grid-sizer"></li>
										</ul>

										<?php
										if ( false === USER_IS_PLATINUM_MEMBER ) : ?>

											<div class="kmp-results-restricted-wrap kmp-gauges">

												<div class="kmp-results-restricted gauge-block showing overall">

													<div class="gauge-title">

														<div class="mo-message">

															<div><i class="fas fa-lock" aria-hidden="true"></i></div>

															<div>Members Only</div>

															<div><a href="<?php echo get_site_url(); ?>/join/">Join Today!</a></div>

														</div>

													</div>

												</div>

											</div>

										<?php
										endif; ?>

									</div>

								</div>

							</div>

						<?php
						endif; ?>

					</div>

				</div>

			</div>

			<?php
			the_content();

			if ( !is_front_page() ):
				if ( is_single() && comments_open() && !post_password_required() ):
					// Comments
					comments_template('/comments.php');
				endif;
				if ( is_single() && !post_password_required() ):
					// Related Posts
					if ( function_exists( 'echo_crp' ) ) { echo_crp(); }
				endif;
			endif;

		/*endif;*/
		?>
	</div>

</div>

<?php
get_footer();
