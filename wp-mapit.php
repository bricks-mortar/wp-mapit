<?php
/*
Plugin Name: WP MapIt
Plugin URI: https://github.com/bricks-mortar/wp-mapit
Description: A plugin for plotting markers to Google Maps
Version: 0.1.0
Author: Dane Grant <dane@bricksandmortarweb.com>
Author URI: http://www.bricksandmortarweb.com
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_MapIt
 */
class WP_MapIt {

	public function __construct() {


		// Includes
		include( 'includes/class-wp-mapit-post-types.php' );

		if ( is_admin() ) {
			include( 'includes/admin/class-wp-mapit-admin.php' );
		}

		// Init classes
		$this->post_types = new WP_MapIt_Post_Types();

		// Activation
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( $this, 'activate' ) );

	}

	/**
	 * Called on plugin activation
	 */
	public function activate() {

	}

	/**
	 * Load functions
	 */
	public function include_template_functions() {
		include( 'wp-mapit-functions.php' );
		include( 'wp-mapit-template.php' );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function frontend_scripts() {
//		$ajax_url         = WP_Job_Manager_Ajax::get_endpoint();
//		$ajax_filter_deps = array( 'jquery', 'jquery-deserialize' );
//
//		if ( apply_filters( 'job_manager_chosen_enabled', true ) ) {
//			wp_register_script( 'chosen', JOB_MANAGER_PLUGIN_URL . '/assets/js/jquery-chosen/chosen.jquery.min.js', array( 'jquery' ), '1.1.0', true );
//			wp_register_script( 'wp-job-manager-term-multiselect', JOB_MANAGER_PLUGIN_URL . '/assets/js/term-multiselect.min.js', array(
//				'jquery',
//				'chosen'
//			), JOB_MANAGER_VERSION, true );
//			wp_register_script( 'wp-job-manager-multiselect', JOB_MANAGER_PLUGIN_URL . '/assets/js/multiselect.min.js', array(
//				'jquery',
//				'chosen'
//			), JOB_MANAGER_VERSION, true );
//			wp_enqueue_style( 'chosen', JOB_MANAGER_PLUGIN_URL . '/assets/css/chosen.css' );
//			$ajax_filter_deps[] = 'chosen';
//		}
//
//		if ( apply_filters( 'job_manager_ajax_file_upload_enabled', true ) ) {
//			wp_register_script( 'jquery-iframe-transport', JOB_MANAGER_PLUGIN_URL . '/assets/js/jquery-fileupload/jquery.iframe-transport.js', array( 'jquery' ), '1.8.3', true );
//			wp_register_script( 'jquery-fileupload', JOB_MANAGER_PLUGIN_URL . '/assets/js/jquery-fileupload/jquery.fileupload.js', array(
//				'jquery',
//				'jquery-iframe-transport',
//				'jquery-ui-widget'
//			), '5.42.3', true );
//			wp_register_script( 'wp-job-manager-ajax-file-upload', JOB_MANAGER_PLUGIN_URL . '/assets/js/ajax-file-upload.min.js', array(
//				'jquery',
//				'jquery-fileupload'
//			), JOB_MANAGER_VERSION, true );
//
//			ob_start();
//			get_job_manager_template( 'form-fields/uploaded-file-html.php', array(
//				'name'      => '',
//				'value'     => '',
//				'extension' => 'jpg'
//			) );
//			$js_field_html_img = ob_get_clean();
//
//			ob_start();
//			get_job_manager_template( 'form-fields/uploaded-file-html.php', array(
//				'name'      => '',
//				'value'     => '',
//				'extension' => 'zip'
//			) );
//			$js_field_html = ob_get_clean();
//
//			wp_localize_script( 'wp-job-manager-ajax-file-upload', 'job_manager_ajax_file_upload', array(
//				'ajax_url'               => $ajax_url,
//				'js_field_html_img'      => esc_js( str_replace( "\n", "", $js_field_html_img ) ),
//				'js_field_html'          => esc_js( str_replace( "\n", "", $js_field_html ) ),
//				'i18n_invalid_file_type' => __( 'Invalid file type. Accepted types:', 'wp-job-manager' )
//			) );
//		}
//
//		wp_register_script( 'jquery-deserialize', JOB_MANAGER_PLUGIN_URL . '/assets/js/jquery-deserialize/jquery.deserialize.js', array( 'jquery' ), '1.2.1', true );
//		wp_register_script( 'wp-job-manager-ajax-filters', JOB_MANAGER_PLUGIN_URL . '/assets/js/ajax-filters.min.js', $ajax_filter_deps, JOB_MANAGER_VERSION, true );
//		wp_register_script( 'wp-job-manager-job-dashboard', JOB_MANAGER_PLUGIN_URL . '/assets/js/job-dashboard.min.js', array( 'jquery' ), JOB_MANAGER_VERSION, true );
//		wp_register_script( 'wp-job-manager-job-application', JOB_MANAGER_PLUGIN_URL . '/assets/js/job-application.min.js', array( 'jquery' ), JOB_MANAGER_VERSION, true );
//		wp_register_script( 'wp-job-manager-job-submission', JOB_MANAGER_PLUGIN_URL . '/assets/js/job-submission.min.js', array( 'jquery' ), JOB_MANAGER_VERSION, true );
//		wp_localize_script( 'wp-job-manager-ajax-filters', 'job_manager_ajax_filters', array(
//			'ajax_url'                => $ajax_url,
//			'is_rtl'                  => is_rtl() ? 1 : 0,
//			'lang'                    => defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : '',
//			// WPML workaround until this is standardized
//			'i18n_load_prev_listings' => __( 'Load previous listings', 'wp-job-manager' )
//		) );
//		wp_localize_script( 'wp-job-manager-job-dashboard', 'job_manager_job_dashboard', array(
//			'i18n_confirm_delete' => __( 'Are you sure you want to delete this listing?', 'wp-job-manager' )
//		) );
//
//		wp_enqueue_style( 'wp-job-manager-frontend', JOB_MANAGER_PLUGIN_URL . '/assets/css/frontend.css' );
	}

}

$mapit = new WP_MapIt();
