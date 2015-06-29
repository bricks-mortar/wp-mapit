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
		$this->settings_page = new WP_MapIt_Settings();

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_notices', array( $this, 'success_notice' ) );
		add_action( 'admin_notices', array( $this, 'error_notice' ) );
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
		// Parent Menu -> Map Listings
		add_menu_page( __( 'Maps', 'wp-mapit' ), __( 'MapIt', 'wp-mapit' ), 'manage_options', 'mapit-maps', array(
			$this,
			'maps_template'
		), 'dashicons-location-alt', 99 );

		// Map listings
		add_submenu_page( 'mapit-maps', __( 'Maps', 'wp-mapit' ), __( 'Maps', 'wp-mapit' ), 'manage_options', 'mapit-maps', array(
			$this,
			'maps_template'
		) );

		// Single map
		add_submenu_page( null, __( 'Single Map', 'wp-mapit' ), __( 'Single Map', 'wp-mapit' ), 'manage_options', 'mapit-manage-map', array(
			$this,
			'manage_map_template'
		) );

		// Settings
		// add_submenu_page( 'mapit-maps', __( 'WP MapIt Settings', 'wp-mapit' ), __( 'Settings', 'wp-mapit' ), 'manage_options', 'mapit-settings', array( $this->settings_page, 'output' ) );

		// Support
		add_submenu_page( 'mapit-maps', __( 'WP MapIt Support', 'wp-mapit' ), __( 'Support', 'wp-mapit' ), 'manage_options', 'mapit-support', array(
			$this,
			'support_template'
		) );

	}

	public function success_notice( $message ) {
		return '<div class="updated"><p>' . __( $message, 'wp-mapit' ) . '</p></div>';
	}

	public function error_notice( $message ) {
		return '<div class="error"><p>' . __( $message, 'wp-mapit' ) . '</p></div>';
	}


	/**
	 * mapit-maps - Maps listing admin page
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function maps_template() {

		echo '<div class="wrap">' .
		     '<h2>Maps <a href="#" class="add-new-h2">Add New</a></h2>';


		// Create map POST request
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			$map_name = $_POST['name'];
			$new_map  = wpmapit()->maps->create_map( $map_name );

			if ( ! is_wp_error( $new_map ) ) {
				$success_msg = 'Succesfully created new map: <strong>' . $map_name . '</strong>';
				echo $this->success_notice( $success_msg );
			} else {
				$error_msg = 'Failed to create map: <strong>' . $map_name . '</strong>';
				echo $this->error_notice( $error_msg );
			}

		}

		// Map actions
		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {

		}

		// Create map form
		$this->create_map_form_partial();

		// Map listing table
		$all_maps = wpmapit()->maps->get_maps();
		$this->maps_table_partial( $all_maps );


	}


	public function manage_map_template() {
		$map_id = $_GET['map-id'];

		echo '<div class="wrap">';

		if ( ! empty( $map_id ) ) {
			echo '<h2>' . $map_id . '</h2>';
		} else {
			echo '<h2>Theres no map with this id!</h2>';
		}

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

	/**
	 * Outputs a form to create a new map
	 *
	 * @since 0.1.0
	 * @access private
	 * @return void
	 */
	private function create_map_form_partial() {
		echo '<div class="create-container">' .
		     '<form id="create-map" method="post" action="">' .
		     '<input type="text" name="name" id="map-name" placeholder="Map Name">' .
		     '<input type="text" name="desc" id="map-desc" placeholder="Map Description">' .
		     '<button class="add-new-h2" type="submit">' . __( 'Create', 'wp-mapit' ) . '</button>' .
		     '</form>' .
		     '</div>';
	}


	/**
	 * Outputs maps listing table
	 *
	 * @since 0.1.0
	 * @access private
	 *
	 * @param $maps Array of map terms
	 *
	 * @return void
	 */
	private function maps_table_partial( $maps ) {
		if ( empty( $maps ) ) {
			echo '<div id="no-maps" class="info-block">'
			     . '<h3>' . __( 'You havent created any maps yet!', 'wp-mapit' ) . '</h3>'
			     . '</div>';

		}

		// table open
		echo '<div id="maps-table">'
		     . '<form id="maps-filter" method="get">'
		     . '<table class="wp-list-table widefat fixed striped posts">'
		     . '<thead><tr>'
		     . '<td>' . __( 'ID', 'wp-mapit' ) . '</td>'
		     . '<td>' . __( 'Name', 'wp-mapit' ) . '</td>'
		     . '<td>' . __( 'Description', 'wp-mapit' ) . '</td>'
		     . '<td>' . __( 'Shortcode', 'wp-mapit' ) . '</td>'
		     . '<td>' . __( 'Actions', 'wp-mapit' ) . '</td>'
		     . '</tr></thead>';


		// table body
		echo '<tbody id="the-list">';

		if ( ! empty( $maps ) && ! is_wp_error( $maps ) ) {
			foreach ( $maps as $map ) {
				echo '<tr>'
				     . '<td>' . $map->term_id . '</td>'
				     . '<td>' . $map->name . '</td>'
				     . '<td>' . $map->description . '</td>'
				     . '<td> [wpmapit id="' . $map->term_id . '"]</td>'
				     . '<td><button>edit</button><button>x</button></td>'
				     . '</tr>';
			}
		}

		echo '</tbody>';

		// table close
		echo '</table>'
		     . '</form>'
		     . '</div>';
	}
}

new WP_MapIt_Admin();
