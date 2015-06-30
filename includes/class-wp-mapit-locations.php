<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_MapIt_Locations
 *
 * @class WP_MapIt_Locations
 * @version 0.1.0
 * @author Dane Grant
 *
 */
class WP_MapIt_Locations {


	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'locations_post_type' ) );
	}


	/**
	 * Register locations post type
	 *
	 * @since 0.1.0
	 * @access public
	 */
	function locations_post_type() {

		if ( post_type_exists( 'mapit_locations' ) ) {
			return;
		}

		$labels = array(
			'name'               => _x( 'Locations', 'Post Type General Name', 'wp_mapit' ),
			'singular_name'      => _x( 'Location', 'Post Type Singular Name', 'wp_mapit' ),
			'menu_name'          => __( 'Locations', 'wp_mapit' ),
			'name_admin_bar'     => __( 'Locations', 'wp_mapit' ),
			'parent_item_colon'  => __( 'Parent Item:', 'wp_mapit' ),
			'all_items'          => __( 'All Locations', 'wp_mapit' ),
			'add_new_item'       => __( 'Add Location', 'wp_mapit' ),
			'add_new'            => __( 'Add New', 'wp_mapit' ),
			'new_item'           => __( 'New Location', 'wp_mapit' ),
			'edit_item'          => __( 'Edit Location', 'wp_mapit' ),
			'update_item'        => __( 'Update Location', 'wp_mapit' ),
			'view_item'          => __( 'View Location', 'wp_mapit' ),
			'search_items'       => __( 'Search Locations', 'wp_mapit' ),
			'not_found'          => __( 'Not found', 'wp_mapit' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'wp_mapit' ),
		);

		$args   = array(
			'label'               => __( 'mapit_locations', 'wp_mapit' ),
			'description'         => __( 'Default post type to map locations to maps', 'wp_mapit' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', ),
			'taxonomies'          => array( 'mapit_maps' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-site',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);

		register_post_type( 'mapit_locations', $args );
	}

}
