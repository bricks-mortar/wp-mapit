<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_MapIt_Maps {

	public function __construct() {
		add_action( 'init', array( $this, 'maps_taxonomy' ), 0 );
	}

	public function maps_taxonomy() {
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
		$args   = array(
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

	public function get_maps() {
		$args = array(
			'hide_empty' => 0
		);

		return get_terms( 'mapit_maps', $args );
	}

	public function get_map( $id ) {

		return get_term_by( 'id', $id, 'mapit_maps' );
	}

	public function get_map_locations( $id ) {
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

	public function delete_map( $id ) {
		return wp_delete_term( $id, 'mapit_maps' );
	}

}
