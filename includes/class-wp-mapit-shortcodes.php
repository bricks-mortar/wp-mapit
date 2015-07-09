<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_MapIt_Shortcodes {

	public function __construct() {
		add_shortcode( 'wpmapit', array( $this, 'display_map' ) );
	}

	// pass in a map id, output data, display on map, win
	public function display_map( $atts ) {

		if ( ! is_array( $atts ) || ! array_key_exists( 'map', $atts ) ) {
			$error = 'Please include a map attribute containing a map ID in your shortcode. '
			         . '<em>ie: [wpmapit map="1"]</em>';
			$this->display_error( $error );
		}

		$map = WP_MapIt_Maps::get_map( $atts['map'] );
		if ( ! is_object( $map ) || empty( $map ) ) {
			$error = 'No map found with ID: ' . $atts['map'] . ' .';

			return $this->display_error( $error );
		}

		// todo: might want to change this to WP_Query
		$map_locations = WP_MapIt_Maps::get_map_locations( $map->term_id );
		if ( empty( $map_locations ) ) {
			return $this->display_error( 'This map is empty. Add some locations to your map!' );
		}


		// Markup
		$map_container_el = '<div class="wp-mapit" id="wp-mapit-' . $map->term_id . '">';

		$map_el = '<div class="wp-mapit-map"></div>';

		$map_data_el = '<ul class="wp-mapit-data" style="display: none;" '
		               . 'data-mapid="' . $map->term_id . '" '
		               . 'data-locationcount="' . sizeof( $map_locations ) . '">';

		foreach ( $map_locations as $location ) {
			$location_data = WP_MapIt_Location_Meta::get_location_fields( $location->ID );

			$map_data_el .= '<li class="wp-mapit-location" style="display: none;" '
			                . 'data-locTitle="' . $location->post_title . '" '
			                . 'data-locContent="' . $location->post_content . '" '
			                . 'data-locId="' . $location->ID . '" '
			                . 'data-locImage="' . wp_get_attachment_url( get_post_thumbnail_id( $location->ID ) ) . '" ';

			foreach ( $location_data as $key => $value ) {
				$map_data_el .= 'data-' . $key . '="' . $value . '" ';
			}

			$map_data_el .= '></li>';
		}

		$map_data_el .= '</ul>';

		$map_container_el .= $map_el . $map_data_el . '</div>';

		return $map_container_el;
	}

	public function map_template( ) {

	}

	private function display_error( $error ) {
		return '<div>'
		       . '<strong>WP MapIt Error:</strong> '
		       . $error
		       . '</div>';
	}

}

new WP_MapIt_Shortcodes();
