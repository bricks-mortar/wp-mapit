<?php
/*
Plugin Name: WP MapIt
Plugin URI: https://github.com/bricks-mortar/wp-mapit
Description: A plugin for plotting markers to Google Maps
Version: 1.0.0
Author: Dane Grant <dane@bricksandmortarweb.com>
Author URI: http://www.bricksandmortarweb.com
License: GPL2
*/

include( 'wp-mapit-functions.php' );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_MapIt' ) ):

	/**
	 * Class WP_MapIt
	 *
	 * @class WP_MapIt
	 * @version 1.0.0
	 */
	class WP_MapIt {

		/**
		 * Instance of WP_MapIt
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object $instance
		 */
		private static $instance;


		/**
		 * Plugin file path
		 *
		 * @since 1.0.0
		 * @var string $file
		 */
		public $file = __FILE__;


		/**
		 * Cache of location data
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $locations;


		/**
		 * Initialize the class
		 *
		 * @since 1.0.0
		 */
		public function __construct() {


			// Includes
			include( 'includes/class-wp-mapit-locations.php' );
			include( 'includes/class-wp-mapit-maps.php' );
			include( 'includes/class-wp-mapit-location-meta.php' );

			if ( is_admin() ) {
				include( 'includes/admin/class-wp-mapit-admin.php' );
			}

			// Init classes
			$this->locations_post_type = new WP_MapIt_Locations();
			$this->maps                = new WP_MapIt_Maps();
			$this->location_meta       = new WP_MapIt_Location_Meta();

			// Activation
			register_activation_hook( __FILE__, array( $this, 'activate' ) );

			// Actions
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

			// Filters
			add_filter( 'mapit/get_post_id', 'get_post_id', 1, 1 );

		}


		/**
		 * Singleton
		 *
		 * An global instance of the class. Used to retrieve the instance
		 * to use on other files/plugins/themes.
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		/**
		 * Activates the plugin
		 *
		 * @since 1.0.0
		 */
		public function activate() {
			// register post types & taxonomies
			// flush_rewrite_rules()
		}


		/**
		 * Register and enqueue scripts and css for the frontend
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function frontend_scripts() {
			wp_enqueue_style( 'wp-mapit-style', plugins_url( 'assets/css/wp-mapit.css', __FILE__ ) );
			wp_enqueue_script( 'wp-mapit-script', plugins_url( 'assets/js/wp-mapit.js', __FILE__ ) );
		}

	}


	/**
	 * Expose the plugin globally
	 *
	 * @since 1.0.0
	 * @return object
	 */
	function wpmapit() {
		return WP_MapIt::instance();
	}

	add_action( 'plugins_loaded', 'wpmapit' );

endif;
