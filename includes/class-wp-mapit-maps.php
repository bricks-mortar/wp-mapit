<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_MapIt_Maps
 *
 * @class WP_MapIt_Maps
 * @version 0.1.0
 * @author Dane Grant
 *
 */
class WP_MapIt_Maps {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'maps_taxonomy' ), 0 );
	}


	/**
	 * Register map taxonomy
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function maps_taxonomy() {

		if ( taxonomy_exists( 'mapit-maps' ) ) {
			return;
		}

		$labels = array(
			'name'                       => _x( 'Maps', 'Taxonomy General Name', 'wp-mapit' ),
			'singular_name'              => _x( 'Map', 'Taxonomy Singular Name', 'wp-mapit' ),
			'menu_name'                  => __( 'Maps', 'wp-mapit' ),
			'all_items'                  => __( 'All Items', 'wp-mapit' ),
			'parent_item'                => __( 'Parent Item', 'wp-mapit' ),
			'parent_item_colon'          => __( 'Parent Item:', 'wp-mapit' ),
			'new_item_name'              => __( 'New Item Name', 'wp-mapit' ),
			'add_new_item'               => __( 'Add New Item', 'wp-mapit' ),
			'edit_item'                  => __( 'Edit Item', 'wp-mapit' ),
			'update_item'                => __( 'Update Item', 'wp-mapit' ),
			'view_item'                  => __( 'View Item', 'wp-mapit' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'wp-mapit' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'wp-mapit' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'wp-mapit' ),
			'popular_items'              => __( 'Popular Items', 'wp-mapit' ),
			'search_items'               => __( 'Search Items', 'wp-mapit' ),
			'not_found'                  => __( 'Not Found', 'wp-mapit' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);

		register_taxonomy( 'mapit_maps', array( 'mapit_locations' ), $args );
	}


	/**
	 * Create a new map
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param $name
	 * @param string $desc
	 */
	public function create_map( $name, $desc = '' ) {

		if ( empty( $name ) ) {
			echo 'Please name your map!';

			return;
		}

		$slug = sanitize_title( $name );

		$args = array(
			'slug'        => $slug,
			'description' => $desc
		);

		return wp_insert_term( $name, 'mapit_maps', $args );
	}


	/**
	 * Returns all maps
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @return mixed
	 */
	public static function get_maps() {

		$args = array(
			'hide_empty' => 0
		);

		return get_terms( 'mapit_maps', $args );
	}


	/**
	 * Returns a single map
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public static function get_map( $id ) {

		return get_term_by( 'id', $id, 'mapit_maps' );
	}


	/**
	 * Returns all location posts assigned to a map
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public static function get_map_locations( $id ) {

		$args = array(
			'post_type' => 'mapit_locations',
			'tax_query' => array(
				array(
					'taxonomy' => 'mapit_maps',
					'field'    => 'id',
					'terms'    => array( $id ),
				),
			),
		);

		return get_posts( $args );
	}


	/**
	 * Deletes a map
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function delete_map( $id ) {

		return wp_delete_term( $id, 'mapit_maps' );
	}

}
