<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_MapIt_Admin
 */
class WP_MapIt_Admin {

	public function __construct() {
		include_once( 'class-wp-mapit-settings.php' );
		include_once( 'class-wp-mapit-maps.php' );

		$this->setting_page = new WP_MapIt_Settings();

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}


	/**
	 * admin_enqueue_scripts function.
	 *
	 * @since 0.1.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'wp-mapit-admin-style', plugins_url( 'assets/css/wp-mapit.css', __FILE__ ) );
		wp_enqueue_script( 'wp-mapit-admin-script', plugins_url( 'assets/js/wp-mapit.js', __FILE__ ) );
	}

	/**
	 * admin_menu function.
	 *
	 * @since 0.1.0
	 * @access public
	 * @return void
	 */
	public function admin_menu() {

	}
}

new WP_MapIt_Admin();
