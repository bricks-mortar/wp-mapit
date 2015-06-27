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

		$this->settings_page = new WP_MapIt_Settings();


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
	 * Creates top level menu for plugin
	 *
	 * @since 0.1.0
	 * @access public
	 * @return void
	 */
	public function admin_menu() {
		// Parent Menu -> Map Listings4
		add_menu_page( __( 'Maps', 'wp-mapit' ), __( 'MapIt', 'wp-mapit' ), 'manage_options', 'mapit-maps', array( $this, 'maps_template' ), 'dashicons-location-alt', 99 );

		// Map listings
		add_submenu_page ('mapit-maps', __( 'Maps', 'wp-mapit' ), __( 'Maps', 'wp-mapit' ), 'manage_options', 'mapit-maps', array( $this, 'maps_template' ) );

		// Settings
		add_submenu_page( 'mapit-maps', __( 'WP MapIt Settings', 'wp-mapit' ), __( 'Settings', 'wp-mapit' ), 'manage_options', 'mapit-settings', array( $this->settings_page, 'output' ) );

		// Support
		add_submenu_page( 'mapit-maps', __( 'WP MapIt Support', 'wp-mapit' ), __( 'Support', 'wp-mapit' ), 'manage_options', 'mapit-support', array( $this, 'support_template' ) );

	}

	/**
	 * Creates admin submenus
	 *
	 * @since 0.1.0
	 * @access public
	 * @return void
	 */
	public function admin_submenus() {
//		add_menu_page( __( 'MapIt', 'wp-mapit' ), __( 'MapIt', 'wp-mapit' ), 'manage_options', 'mapit-admin', array( $this, 'output' ), 'dashicons-location-alt', 99 );
	}


	public function maps_template() {
		echo '<div class="wrap">';
		echo '<h2>Maps <a href="http://localhost:8888/testing/wp_blank/wp-admin/post-new.php" class="add-new-h2">Add New</a></h2>';
		echo '<form id="maps-filter" method="get">';
		echo    '<table class="wp-list-table widefat fixed striped posts">';
		echo 	    '<thead><tr>';
		echo        '<td>ID</td><td>Name</td><td>Shortcode</td><td>Actions</td>';
		echo        '</tr></thead>';
		echo        '<tbody id="the-list"><p>You havent created any maps yet!</p></tbody>';
		echo 	    '<tfoot><tr>';
		echo        '</tr></tfoot>';
		echo    '</table>';
		echo '</form>';
		echo '</div>';
	}

	public function support_template() {
		echo '<div class="wrap">';
		echo '<h2>WP MapIt Support</h2>';
		echo '<p>Thanks for download WP MapIt! This plugin is actively developed
				and maintained by <a href="http://bricksandmortarweb.com">Bricks
				& Mortar Creative</a>.';
		echo '<div class="admin-page-content">';
		echo '<br><h3>Follow the link that corresponds to your issue:</h3>';
		echo '<ul>';
		echo '<li><strong>Documentation:</strong> <a href="https://github.com/bricks-mortar/wp-mapit">https://github.com/bricks-mortar/wp-mapit</a></li>';
		echo '<li><strong>Report Issue:</strong> <a href="https://github.com/bricks-mortar/wp-mapit/issues">https://github.com/bricks-mortar/wp-mapit/issues</a></li>';
		echo '</ul><ul>';
		echo '<li><strong>Contribute:</strong> This project is open source on GitHub.
				Please feel free to open any issues or PRs.';
		echo '<li><strong>Custom Development:</strong> <a href="http://bricksandmortarweb.com">Developer Website</a> - <a href="mailto:info@bricksandmortarweb.com">Email Contact</a></li>';
		echo '</ul></div>';
		echo '</div>';
	}
}

new WP_MapIt_Admin();
