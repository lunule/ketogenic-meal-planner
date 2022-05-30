<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Ketogenic Meal Planner
 * Plugin URI:        https://ketogenic.com/meal-planner/
 * Description:       Ketogenic.com Custom Meal Planner App
 * Version:           2.0.2
 * Author:            Codeable
 * Author URI:        https://codeable.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kmp
 * Domain Path:       /languages
 */

defined( 'WPINC' ) || die;

// Current plugin version. - https://semver.org
define( 'KMP_VERSION', '2.0.2' );

// Define if in dev mode.
define( 'KMP_DEV_MODE', false );

// Define RESTRICTION WITH COOKIE feature status.
// If the cookie-based temporary disabling of meal generations should be active
define( 'KMP_WITH_COOKIES', true );

// The number of meal plan generations a non-member user can do before disabling
// the regenerate feature
define( 'COOKIE_RESTRICTION_NUMBER', 2 );

// The cookie expiration time in minutes - after this period, meal generation
// gets reactivated for non-member users as well
define( 'COOKIE_EXPIRATION_MINS', 120 );

// Define meal app page id.
$app_page_id = get_option( 'kmp_field_application_page' )
				? intval( get_option( 'kmp_field_application_page' ) )
				: 218248;
define( 'MEAL_APP_PAGE_ID', $app_page_id );

/**
 * Set up global $is_app_page - its value will be updated by the update_is_app_page()
 * function ( class-kmp-public.php ) hooked into template_redirect.
 *
 * The reason why we're doing this is that when this main plugin file gets loaded,
 * template_redirect is still waiting to happen, so it's impossible to find out the id
 * of the page we're on.
 */
$is_app_page = false;

// Set up global to check login status.
$user_is_logged_in = false;

// Define user's full app access.
$has_full_app_access 	= false;

// Check if user is logged in and has one of the required membership plans.
add_action('init', function() {
	$req_mem_plan_ids_Arr 	= explode(',', get_option( 'kmp_field_required_membership' ));
	$user_is_platinum_member = false;
	if (class_exists( 'woocommerce' ) && is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' )) {
		$user_memberships_Arr = wc_memberships_get_user_active_memberships( get_current_user_id() );
		$plan_ids_Arr = [];
		foreach ( $user_memberships_Arr as $m ) {
			$plan_ids_Arr[] = $m->plan_id;
		}
		$intersection = array_intersect($req_mem_plan_ids_Arr, $plan_ids_Arr);
		if ( count($intersection) >= 1 ) {
			$user_is_platinum_member = 1;
		}
	}
	define( 'USER_IS_PLATINUM_MEMBER', $user_is_platinum_member );
});

// Define meal app build dir url.
define( 'MEAL_APP_BUILD_DIR_URL', plugin_dir_url( __FILE__ ) . 'public/app' );
define( 'MEAL_APP_BUILD_DIR_PATH', plugin_dir_path( __FILE__ ) . 'public/app' );
define( 'MEAL_APP_ASSET_MANIFEST', MEAL_APP_BUILD_DIR_PATH . '/asset-manifest.json' );

// Define plugin paths
define( 'KMP_ADMIN_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) . 'admin' ) );
define( 'KMP_PUBLIC_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) . 'public' ) );

// Activate Plguin
function activate_kmp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kmp-activator.php';
	Kmp_Activator::activate();
	kmp_create_db_on_plugin_activation();
}

// Deactivate Plugin
function deactivate_kmp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kmp-deactivator.php';
	Kmp_Deactivator::deactivate();
	if ( true == KMP_DEV_MODE ) {
		kmp_delete_db_on_plugin_deactivation();
	}
}

register_activation_hook( __FILE__, 'activate_kmp' );
register_deactivation_hook( __FILE__, 'deactivate_kmp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kmp.php';

/**
 * Begin execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kmp() {
	$plugin = new Kmp();
	$plugin->run();
}
run_kmp();

/**
 * EOF plugin init
 * ------------------------------------------------------------------------------------------------
 *
 * Custom plugin init helpers
 */

// Create database table on plugin activation
function kmp_create_db_on_plugin_activation() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'kmp_meal_data';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		calorierange smallint NOT NULL,
		kmpid varchar(50) NOT NULL,
		meal varchar(50) NOT NULL,
		name varchar(1024) NOT NULL,
		ingredients varchar(2048) NOT NULL,
		calories smallint NOT NULL,
		fat smallint NOT NULL,
		carbs smallint NOT NULL,
		fiber smallint NOT NULL,
		netcarbs smallint NOT NULL,
		protein smallint NOT NULL,
		vegetarian tinyint(1) NOT NULL,
		vegan tinyint(1) NOT NULL,
		carnivore tinyint(1) NOT NULL,
		dairyfree tinyint(1) NOT NULL,
		eggfree tinyint(1) NOT NULL,
		beginnerfriendly tinyint(1) NOT NULL,
		advanced tinyint(1) NOT NULL,
		url varchar(2048)
	) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

// Delete database table on plugin deactivation in dev mode
function kmp_delete_db_on_plugin_deactivation() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'kmp_meal_data';
	$sql = "DROP TABLE IF EXISTS $table_name";
	$wpdb->query($sql);
	//delete_option("my_plugin_db_version");
}
