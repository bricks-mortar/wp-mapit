<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_MapIt_Geocode {

	public function __construct() {
		add_action( 'mapit_location_updated', array( $this, 'update_location_data', 20, 2 ) );
	}


	public function update_location_data( $location, $post_id = false ) {
		$post_id = apply_filters( 'wpmapit/get_post_id', $post_id );


	}


}