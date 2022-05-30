<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Kmp
 * @subpackage Kmp/includes
 * @author     Codeable <info@codeable.io>
 */
class Kmp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Kmp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'KMP_VERSION' ) ) {
			$this->version = KMP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'kmp';

		$this->settings_array = array(
			'application-page',
			'required-membership',

			// Re-enable only if the client needs notification email functionality, in which case we'll need the MailGun API key
			//'notification-email',
		);

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_admin_post_types();
		$this->define_admin_metaboxes();
		$this->define_admin_settings();

		// Not used
		// $this->define_admin_rest_api();

		$this->define_admin_ajax();

		$this->define_public_hooks();
		$this->define_public_ajax();
		$this->define_public_pagetemplate();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Kmp_Loader. Orchestrates the hooks of the plugin.
	 * - Kmp_i18n. Defines internationalization functionality.
	 * - Kmp_Admin. Defines all hooks for the admin area.
	 * - Kmp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Plugin interfaces.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'interfaces/interface-kmp-setting.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kmp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kmp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kmp-admin.php';

		/**
		 * Custom post types class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kmp-admin-post-types.php';

		/**
		 * Metabox class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kmp-admin-metaboxes.php';

		/**
		 * Admin settings class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kmp-admin-settings.php';

		/**
		 * Class handling the custom Rest API endpoint.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kmp-admin-rest-api.php';

		/**
		 * Settings API - Setting classes.
		 */
		foreach ( $this->settings_array as $setting ) :
			require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/setting-classes/class-kmp-setting-{$setting}.php";
		endforeach;

		/**
		 * The admin Ajax request class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kmp-admin-ajax.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-kmp-public.php';

		/**
		 * The calorie possibilities classes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-1200.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-1300.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-1400.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-1500.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-1600.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-1700.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-1800.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-1900.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-2000.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-2100.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-2200.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-2300.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-2400.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/cpclasses/class-kmp-cp-2500.php';

		/**
		 * The public Ajax request class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-kmp-public-ajax.php';

		/**
		 * The app page template class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-kmp-public-pagetemplate.php';

		$this->loader = new Kmp_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Kmp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Kmp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Kmp_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the custom post types added
	 * by the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_post_types() {

		$cpt_class = new Kmp_Admin_Post_Types( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $cpt_class, 'cpt_kmp_meal_register', 0 );
		$this->loader->add_filter( 'post_updated_messages', $cpt_class, 'cpt_kmp_meal_update_messages' );

		$this->loader->add_action( 'init', $cpt_class, 'cpt_kmp_submission_register', 0 );
		$this->loader->add_filter( 'post_updated_messages', $cpt_class, 'cpt_kmp_submission_update_messages' );

		$this->loader->add_action( 'template_redirect', $cpt_class, 'cpt_redirect_singulars');

	}

	/**
	 * Metaboxes class
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_metaboxes() {

		$mb_class = new Kmp_Admin_Metaboxes( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'add_meta_boxes_kmp-meal', $mb_class, 'add_metaboxes' );
		$this->loader->add_action( 'add_meta_boxes_kmp-meal', $mb_class, 'kmp_meal_remove_metaboxes' );

		$this->loader->add_action( 'add_meta_boxes_kmp-submission', $mb_class, 'add_metaboxes' );
		$this->loader->add_action( 'add_meta_boxes_kmp-submission', $mb_class, 'kmp_submission_remove_metaboxes' );

		$this->loader->add_action( 'save_post', $mb_class, 'save_kmp_meal_metabox' );
		$this->loader->add_action( 'save_post', $mb_class, 'save_kmp_submission_metabox' );

		//$this->loader->add_action( 'get_user_option_meta-box-order_kmp-meal', $mb_class, 'kmp_meal_metabox_order' );
		//$this->loader->add_action( 'get_user_option_meta-box-order_kmp-submission', $mb_class, 'kmp_submission_metabox_order' );//

		$this->loader->add_filter( 'manage_edit-kmp-meal_columns', $mb_class, 'kmp_meal_remove_postlist_columns_seo', 10, 1 );
		$this->loader->add_filter( 'cs_support_posttype', $mb_class, 'kmp_meal_remove_postlist_columns_cs', 20, 2 );
		$this->loader->add_filter( 'manage_kmp-meal_posts_columns', $mb_class, 'kmp_meal_add_postlist_columns' );
		$this->loader->add_filter( 'manage_kmp-meal_posts_custom_column', $mb_class, 'kmp_meal_set_new_postlist_columns', 10, 2 );
		$this->loader->add_filter( 'manage_edit-kmp-meal_sortable_columns', $mb_class, 'kmp_meal_make_new_postlist_columns_sortable' );
		$this->loader->add_action( 'pre_get_posts', $mb_class, 'kmp_meal_new_postlist_columns_custom_ordering' );

		$this->loader->add_filter( 'manage_edit-kmp-submission_columns', $mb_class, 'kmp_submission_remove_postlist_columns_seo', 10, 1 );
		$this->loader->add_filter( 'cs_support_posttype', $mb_class, 'kmp_submission_remove_postlist_columns_cs', 20, 2 );
		$this->loader->add_filter( 'manage_kmp-submission_posts_columns', $mb_class, 'kmp_submission_add_postlist_columns' );
		$this->loader->add_filter( 'manage_kmp-submission_posts_custom_column', $mb_class, 'kmp_submission_set_new_postlist_columns', 10, 2 );
		$this->loader->add_filter( 'manage_edit-kmp-submission_sortable_columns', $mb_class, 'kmp_submission_make_new_postlist_columns_sortable' );
		$this->loader->add_action( 'pre_get_posts', $mb_class, 'kmp_submission_new_postlist_columns_custom_ordering' );

	}

	/**
	 * Admin settings class
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_settings() {

		$as_class = new Kmp_Admin_Settings( $this->get_plugin_name(), $this->get_version() );

		// Menu hooks
		$this->loader->add_action( 'admin_menu', $as_class, 'add_menu_items' );

		// Section hooks
		$this->loader->add_action( 'admin_init', $as_class, 'add_settings_sections' );

		// Settings
		foreach ( $this->settings_array as $setting ) {

			// Create class name based on the array member, by replacing its hyphen with underscore, and by capitalizing it
			$setting_to_class 	= ucfirst( str_replace( '-', '_', $setting ) );
			$classname 			= 'Kmp_Setting_' . $setting_to_class;

			// Instantiate an object of the class
			$setting_class 		= new $classname( $this->get_plugin_name(), $this->get_version() );

			// Hook the object's register method
			// note: Use $setting_class, not $as_class from the top of this function
			$this->loader->add_action( 'admin_init', $setting_class, 'register' );
		}
	}

	/**
	 * Class handling the custom Rest API endpoint.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_rest_api() {
		$rest_class = new Kmp_Admin_Rest_Api( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'rest_api_init', $rest_class, 'register_custom_rest_routes' );
	}

	/**
	 * The public Ajax request class
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_ajax() {

		$ajax_admin = new Kmp_Admin_Ajax( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_ajax_kmpGetPages', $ajax_admin, 'ajax_cb_get_pages' );
		$this->loader->add_action( 'wp_ajax_nopriv_kmpGetPages', $ajax_admin, 'ajax_cb_get_pages' );

		$this->loader->add_action( 'wp_ajax_kmpGetWcMemberships', $ajax_admin, 'ajax_cb_get_wc_memberships' );
		$this->loader->add_action( 'wp_ajax_nopriv_kmpGetWcMemberships', $ajax_admin, 'ajax_cb_get_wc_memberships' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Kmp_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_print_scripts', $plugin_public, 'dequeue_scripts', 100 );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 2 );

		// App functions
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_manifest_json' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_app_scripts', 1 );
		$this->loader->add_filter( 'script_loader_tag', $plugin_public, 'app_script_loader_tag', 10, 2 );

		// Body classes
		$this->loader->add_filter( 'body_class', $plugin_public, 'add_body_classes' );

		// Other
		$this->loader->add_filter( 'init', $plugin_public, 'check_login_status', 10, 2);
		$this->loader->add_action( 'template_redirect', $plugin_public, 'update_is_app_page' );
	}

	/**
	 * The public Ajax request class
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_ajax() {

		//$sc_class = new Kmp_Shortcode( $this->get_plugin_name(), $this->get_version() );
		$ajax_public = new Kmp_Public_Ajax( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_ajax_kmpGetMeals', $ajax_public, 'ajax_cb_get_meals' );
		$this->loader->add_action( 'wp_ajax_nopriv_kmpGetMeals', $ajax_public, 'ajax_cb_get_meals' );
	}

	/**
	 * The page template class
	 */
	private function define_public_pagetemplate() {

		$pt_class = new Kmp_Public_Pagetemplate( $this->get_plugin_name(), $this->get_version() );

		// We don't need the template to be added to the Page Attributes > Template
		// dropdown, so let's just deactivate the following add_filter.

		//$this->loader->add_filter( 'theme_page_templates', $pt_class, 'add_page_template_to_dropdown' );
		$this->loader->add_filter( 'template_include', $pt_class, 'change_page_template', 99 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Kmp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
