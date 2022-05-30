<?php

/**
 * The post types class.
 *
 * @link       	https://codeable.io
 * @since      	1.0.0
 *
 * @package    	Kmp
 * @subpackage 	Kmp/admin
 * @author 		Codeable <info@codeabble.io>
 */

class Kmp_Admin_Post_Types {

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

	public function cpt_kmp_meal_register() {
		$labels = array(
			'name'                  => _x( 'Meals', 'Post Type General Name', 'kmp' ),
			'singular_name'         => _x( 'Meal', 'Post Type Singular Name', 'kmp' ),
			'menu_name'             => __( 'Meals', 'kmp' ),
			'name_admin_bar'        => __( 'Meals', 'kmp' ),
			'archives'              => __( 'Meals Archives', 'kmp' ),
			'attributes'            => __( 'Meals Attributes', 'kmp' ),
			'parent_item_colon'     => __( 'Parent Meal:', 'kmp' ),
			'all_items'             => __( 'All Meals', 'kmp' ),
			'add_new_item'          => __( 'Add New Meal', 'kmp' ),
			'add_new'               => __( 'Add New', 'kmp' ),
			'new_item'              => __( 'New Meal', 'kmp' ),
			'edit_item'             => __( 'Edit Meal', 'kmp' ),
			'update_item'           => __( 'Update Meal', 'kmp' ),
			'view_item'             => __( 'View Meal', 'kmp' ),
			'view_items'            => __( 'View Meals', 'kmp' ),
			'search_items'          => __( 'Search Meal', 'kmp' ),
			'not_found'             => __( 'Not found', 'kmp' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'kmp' ),
			'featured_image'        => __( 'Featured Image', 'kmp' ),
			'set_featured_image'    => __( 'Set featured image', 'kmp' ),
			'remove_featured_image' => __( 'Remove featured image', 'kmp' ),
			'use_featured_image'    => __( 'Use as featured image', 'kmp' ),
			'insert_into_item'      => __( 'Insert into CSV feed', 'kmp' ),
			'uploaded_to_this_item' => __( 'Uploaded to this CSV feed', 'kmp' ),
			'items_list'            => __( 'Meals list', 'kmp' ),
			'items_list_navigation' => __( 'Meals list navigation', 'kmp' ),
			'filter_items_list'     => __( 'Filter CSV feeds list', 'kmp' ),
			'name_admin_bar'        => _x( 'Meal', 'add new on admin bar' ),
		);
		$args = array(
			'label'                 => __( 'Meal', 'kmp' ),
			'description'           => __( 'Meals', 'kmp' ),
			'labels'                => $labels,
			// 'taxonomies'            => array('category', 'post_tag'),
			'hierarchical'          => true,
			'public'                => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => false,
			'show_in_menu'          => 'edit.php?post_type=kmp-meal',
			'show_in_admin_bar'     => false,
			'has_archive'           => false,
			'supports'              => array(
				'title',
				// 'thumbnail',
				'editor',
				'revisions',
				'custom_fields'
			),
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-carrot',
			'can_export'            => true,
			'capability_type'       => 'post',
			'capabilities' => array(
				// Adds support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
				'create_posts' => true,
			),
			// Set to false if users are not allowed to edit/delete existing posts
			'map_meta_cap'          => true,
			'show_in_rest'          => true,
			/*
			'rewrite' 				=> array(
				'slug' => __('kmp-meal', 'kmp'),
			),
			*/
			'yarpp_support'          => false,
		);
		register_post_type( 'kmp-meal', $args );
	}

	public function cpt_kmp_meal_update_messages( $messages ) {
		$post             = get_post();
		$post_type        = 'kmp-meal';
		$post_type_object = get_post_type_object( $post_type );

		$messages['kmp-meal'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Meal updated.', 'kmp' ),
			2  => __( 'Custom field updated.', 'kmp' ),
			3  => __( 'Custom field deleted.', 'kmp' ),
			4  => __( 'Meal updated.', 'kmp' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Meal restored to revision from %s', 'kmp' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Meal published.', 'kmp' ),
			7  => __( 'Meal saved.', 'kmp' ),
			8  => __( 'Meal submitted.', 'kmp' ),
			9  => sprintf(
				__( 'Meal scheduled for: <strong>%1$s</strong>.', 'kmp' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i', 'kmp' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Meal draft updated.', 'kmp' )
		);

		if ( $post_type_object->publicly_queryable && 'kmp-meal' === $post_type ) {
			$permalink = get_permalink( $post->ID );

			$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View CSV feed', 'kmp' ) );
			$messages[ $post_type ][1] .= $view_link;
			$messages[ $post_type ][6] .= $view_link;
			$messages[ $post_type ][9] .= $view_link;

			$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
			$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview CSV feed', 'kmp' ) );
			$messages[ $post_type ][8]  .= $preview_link;
			$messages[ $post_type ][10] .= $preview_link;
		}
		return $messages;
	}

	public function cpt_kmp_submission_register() {
		$labels = array(
			'name'                  => _x( 'Submissions', 'Post Type General Name', 'kmp' ),
			'singular_name'         => _x( 'Submission', 'Post Type Singular Name', 'kmp' ),
			'menu_name'             => __( 'Submissions', 'kmp' ),
			'name_admin_bar'        => __( 'Submissions', 'kmp' ),
			'archives'              => __( 'Submissions Archives', 'kmp' ),
			'attributes'            => __( 'Submissions Attributes', 'kmp' ),
			'parent_item_colon'     => __( 'Parent Submission:', 'kmp' ),
			'all_items'             => __( 'All Submissions', 'kmp' ),
			'add_new_item'          => __( 'Add New Submission', 'kmp' ),
			'add_new'               => __( 'Add New', 'kmp' ),
			'new_item'              => __( 'New Submission', 'kmp' ),
			'edit_item'             => __( 'Edit Submission', 'kmp' ),
			'update_item'           => __( 'Update Submission', 'kmp' ),
			'view_item'             => __( 'View Submission', 'kmp' ),
			'view_items'            => __( 'View Submissions', 'kmp' ),
			'search_items'          => __( 'Search Submission', 'kmp' ),
			'not_found'             => __( 'Not found', 'kmp' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'kmp' ),
			'featured_image'        => __( 'Featured Image', 'kmp' ),
			'set_featured_image'    => __( 'Set featured image', 'kmp' ),
			'remove_featured_image' => __( 'Remove featured image', 'kmp' ),
			'use_featured_image'    => __( 'Use as featured image', 'kmp' ),
			'insert_into_item'      => __( 'Insert into CSV feed', 'kmp' ),
			'uploaded_to_this_item' => __( 'Uploaded to this CSV feed', 'kmp' ),
			'items_list'            => __( 'Submissions list', 'kmp' ),
			'items_list_navigation' => __( 'Submissions list navigation', 'kmp' ),
			'filter_items_list'     => __( 'Filter CSV feeds list', 'kmp' ),
			'name_admin_bar'        => _x( 'Submission', 'add new on admin bar' ),
		);
		$args = array(
			'label'                 => __( 'Submission', 'kmp' ),
			'description'           => __( 'Submissions', 'kmp' ),
			'labels'                => $labels,
			//'taxonomies'            => array('category', 'post_tag'),
			'hierarchical'          => true,
			'public'                => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => false,
			'show_in_menu'          => 'edit.php?post_type=kmp-submission',
			'show_in_admin_bar'     => false,
			'has_archive'           => false,
			'supports'              => array(
				'title',
				//'thumbnail',
				'editor',
				'revisions',
				'custom_fields'
			),
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-carrot',
			'can_export'            => true,
			'capability_type'       => 'post',
			'capabilities'          => array(
				// Adds support for the "Add New" function (use 'do_not_allow' instead of false for multisite set ups)
				'create_posts' => true,
			),
			// Set to false if users are not allowed to edit/delete existing posts
			'map_meta_cap'          => true,
			'show_in_rest'          => true,
			/*
			'rewrite' => array(
				'slug' => __('kmp-submission', 'kmp'),
			),
			*/
			'yarpp_support'         => false,
		);
		register_post_type( 'kmp-submission', $args );
	}

	public function cpt_kmp_submission_update_messages( $messages ) {
		$post             = get_post();
		$post_type        = 'kmp-submission';
		$post_type_object = get_post_type_object( $post_type );

		$messages['kmp-submission'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Submission updated.', 'kmp' ),
			2  => __( 'Custom field updated.', 'kmp' ),
			3  => __( 'Custom field deleted.', 'kmp' ),
			4  => __( 'Submission updated.', 'kmp' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Submission restored to revision from %s', 'kmp' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Submission published.', 'kmp' ),
			7  => __( 'Submission saved.', 'kmp' ),
			8  => __( 'Submission submitted.', 'kmp' ),
			9  => sprintf(
				__( 'Submission scheduled for: <strong>%1$s</strong>.', 'kmp' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i', 'kmp' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Submission draft updated.', 'kmp' )
		);

		if ( $post_type_object->publicly_queryable && 'kmp-submission' === $post_type ) {
			$permalink = get_permalink( $post->ID );

			$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View CSV feed', 'kmp' ) );
			$messages[ $post_type ][1] .= $view_link;
			$messages[ $post_type ][6] .= $view_link;
			$messages[ $post_type ][9] .= $view_link;

			$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
			$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview CSV feed', 'kmp' ) );
			$messages[ $post_type ][8]  .= $preview_link;
			$messages[ $post_type ][10] .= $preview_link;
		}
		return $messages;
	}

	// Set custom post type singulars to send users to 404 - unfortunately,
	// there's no register_post_type() parameter that would do both things:
	// - make the singular unavailable on the front-end, BUT
	// - let the cpt being used in front-end queries.
	//
	// note: no, the publically_queriable parameter doesn't help either -
	//    setting this param to false disables all front-end cpt query
	//    functionalities as well.
	public function cpt_redirect_singulars() {
		if (is_singular(array('kmp-submission', 'kmp-meal'))) {
			global $wp_query;
			$wp_query->posts = [];
			$wp_query->post = null;
			$wp_query->set_404();
			status_header(404);
			nocache_headers();
		}
	}
}
