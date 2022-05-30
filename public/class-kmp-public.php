<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Kmp
 * @subpackage Kmp/public
 * @author     Codeable <info@codeable.io>
 */
class Kmp_Public {

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

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kmp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kmp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $post, $is_app_page;

		if ( true === $is_app_page ) {

			wp_enqueue_style(
				$this->plugin_name . '-tippy-theme--light',
				'https://unpkg.com/tippy.js@6/themes/light.css',
				array(),
				$this->version,
				'all'
			);

			wp_enqueue_style(
				$this->plugin_name . '-chartist',
				plugin_dir_url( __FILE__ ) . 'css/chartist.min.css',
				array(),
				$this->version,
				'all'
			);

			wp_enqueue_style(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'css/kmp-public.css',
				array(
					$this->plugin_name . '-tippy-theme--light',
					$this->plugin_name . '-chartist',
				),
				$this->version,
				'all'
			);
		}
	}

	public function dequeue_scripts() {
		global $post, $is_app_page;
		if ( true === $is_app_page ) {
			// wp_dequeue_script( 'keto-scripts' );
			// wp_dequeue_script( 'keto-custom-scripts' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $post, $is_app_page;

		if ( true === $is_app_page ) {

			wp_enqueue_script(
				$this->plugin_name . '-masonry',
				plugin_dir_url( __FILE__ ) . 'js/masonry.pkgd.min.js',
				array(
					'jquery',
				),
				false,
				false
			);

			wp_enqueue_script(
				$this->plugin_name . '-popper-core',
				'https://unpkg.com/@popperjs/core@2',
				array(
					'jquery',
				),
				false,
				false
			);

			wp_enqueue_script(
				$this->plugin_name . '-tippy',
				'https://unpkg.com/tippy.js@6',
				array(
					'jquery',
					$this->plugin_name . '-popper-core',
				),
				false,
				false
			);

			wp_enqueue_script(
				$this->plugin_name . '-chartist',
				plugin_dir_url( __FILE__ ) . 'js/chartist.min.js',
				array(
					'jquery',
				),
				false,
				false
			);

			wp_enqueue_script(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'js/kmp-public.js',
				array(
					'jquery',
					$this->plugin_name . '-masonry',
					$this->plugin_name . '-tippy',
					$this->plugin_name . '-chartist',
				),
				false,
				false
			);

			$first_name = '';
			$last_name = '';
			$email = '';

			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				$first_name = $current_user->user_firstname;
				$last_name 	= $current_user->user_lastname;
				$email 		= $current_user->user_email;
			}

			//var_dump( $first_name );
			//var_dump( $last_name );
			//var_dump( $email );

			wp_localize_script(
				$this->plugin_name,
				'kmp',
				array(
					// The nonce value used to test the Rest API call MUST BE 'wp_rest'.
					'restNonce'     => wp_create_nonce('wp_rest'),
					'ajaxNonce'     => wp_create_nonce('kmp_dbquery'),
					'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
					'siteUrl'       => get_site_url(),
					'pluginUrl'     => plugin_dir_url( dirname(__FILE__) ),
					'firstName'     => $first_name,
					'lastName'      => $last_name,
					'email'         => $email,
					'isLoggedIn'    => is_user_logged_in(),
					'restrictNum'   => COOKIE_RESTRICTION_NUMBER,
					'ckExpMins'     => COOKIE_EXPIRATION_MINS,
				)
			);
		}
	}

	public function enqueue_app_scripts() {

		global $post, $is_app_page;

		if ( true === $is_app_page ) {

			$asset_manifest = json_decode( file_get_contents( MEAL_APP_ASSET_MANIFEST ), true )['files'];

			if ( isset( $asset_manifest[ 'main.css' ] ) ) {
				wp_enqueue_style( 'kmp-app', get_site_url() . $asset_manifest[ 'main.css' ] );
			}

			wp_enqueue_script( 'kmp-app-runtime', get_site_url() . $asset_manifest[ 'runtime-main.js' ], array(), null, false );

			wp_enqueue_script( 'kmp-app-main', get_site_url() . $asset_manifest[ 'main.js' ], array('kmp-app-runtime'), null, false );

			if ( true === KMP_WITH_COOKIES ) {
				wp_localize_script(
					'kmp-app-main',
					'kmp',
					array(
						'restrictNum' 	=> COOKIE_RESTRICTION_NUMBER,
						'ckExpMins' 	=> COOKIE_EXPIRATION_MINS,
					)
				);
			}

			//wp_enqueue_script( 'kmp-app-main', plugin_dir_url( __FILE__ ) . 'app/static/js/bundle.js', array(), null, false );

			foreach ( $asset_manifest as $key => $value ) {

				if ( preg_match( '@static/js/(.*)\.chunk\.js@', $key, $matches ) ) {

					if ( $matches && is_array( $matches ) && count( $matches ) === 2 ) {

						$name = "kmp-app-" . preg_replace( '/[^A-Za-z0-9_]/', '-', $matches[1] );
						wp_enqueue_script( $name, get_site_url() . $value, array( 'kmp-app-main' ), null, false );

						if ( true === KMP_WITH_COOKIES ) {
							wp_localize_script(
								$name,
								'kmp',
								array(
									'restrictNum' 	=> COOKIE_RESTRICTION_NUMBER,
									'ckExpMins' 	=> COOKIE_EXPIRATION_MINS,
								)
							);
						}
					}
				}

				/**
				 * note: The regex should end with a forward slash excluding e.g. .css.map files,
				 * and with a delimiter (@, in this case).
				 */
				if ( preg_match( '@static/css/(.*)\.chunk\.css/@', $key, $matches ) ) {
					if ( $matches && is_array( $matches ) && count( $matches ) == 2 ) {
						$name = "kmp-app-" . preg_replace( '/[^A-Za-z0-9_]/', '-', $matches[1] );
						wp_enqueue_style( $name, get_site_url() . $value, array( 'kmp-app' ), null );
					}
				}
			}
		}
	}

	public function app_script_loader_tag($tag, $handle) {
		global $post, $is_app_page;
		if (
			( !preg_match( '/^kmp-app-/', $handle ) ) ||
			( false === $is_app_page )
		) {
			return $tag;
		}
		return str_replace( ' src', ' defer src', $tag );
	}

	public function add_manifest_json() {
		global $post, $is_app_page;
		if ( true === $is_app_page ) {
			echo '<link rel="manifest" href="' . MEAL_APP_BUILD_DIR_URL . '/manifest.json"/>';
		}
	}

	public function check_login_status() {
		global $user_is_logged_in;
		if ( intVal( get_current_user_id() ) > 0 ) {
			$user_is_logged_in = true;
		}
	}

	public function add_body_classes($classes) {

		global $post, $is_app_page, $user_is_logged_in;

		if ( true === $is_app_page ) {

			$classes[] = 'kmp-app-page';

			if ( true == KMP_DEV_MODE ) {
				$classes[] = 'devMode';
			}
			if ( true == KMP_WITH_COOKIES ) {
				$classes[] = 'withCookies';
			}
			if ( true == USER_IS_PLATINUM_MEMBER ) {
				$classes[] = 'isPlatinumMember';
			}
			if ( false == USER_IS_PLATINUM_MEMBER ) {
				$classes[] = 'notPlatinumMember';
			}
			if ( true === $user_is_logged_in ) {
				$classes[] = 'isLoggedIn';
			}
			if ( false === $user_is_logged_in ) {
				$classes[] = 'notLoggedIn';
			}
		}
		return $classes;
	}

	public function update_is_app_page() {
		global $post, $is_app_page;
		if (
			is_page() &&
			( MEAL_APP_PAGE_ID == get_the_ID() )
		) {
			$is_app_page = true;
		}
	}
}
