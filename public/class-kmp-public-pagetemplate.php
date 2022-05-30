<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codeable.io
 * @since      1.0.0
 *
 * @package    Kmp
 * @subpackage Kmp/public
 * @author     Codeable <info@codeable.io>
 */

class Kmp_Public_Pagetemplate {

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

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name 	= $plugin_name;
		$this->version 		= $version;
	}

	/**
	 * Add page templates.
	 *
	 * @param  array  $templates  The list of page templates
	 *
	 * @return array  $templates  The modified list of page templates
	 */
	public function add_page_template_to_dropdown( $templates ) {
		$templates[KMP_PUBLIC_DIR_PATH . 'page-templates/page-kmp-app.php'] = __( 'KMP App Page Template', 'plugin-slug' );
		return $templates;
	}

	/**
	 * Change the page template to the selected template on the dropdown
	 * Change the single template to the fixed template in the plugin
	 *
	 * @param $template
	 *
	 * @return mixed
	 */
	public function change_page_template( $template ) {
		global $post, $is_app_page;
		if ( $is_app_page ) {
			$fileTemplate = KMP_PUBLIC_DIR_PATH . 'page-templates/page-kmp-app.php';
			if ( file_exists($fileTemplate) ) {
				$template = $fileTemplate;
			}
		}
		return $template;
	}
}
