<?php
/**
 * Geocode location data
 *
 * @since 1.0.0yeah
 *
 * @package WordPress\WP_MapIt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WP_MapIt_Geocode
 *
 * @class WP_MapIt_Geocode
 * @version 1.0.0
 */
class WP_MapIt_Geocode {


	public function __construct() {
		add_action( 'mapit_update_coordinates', array( $this, 'update_location_coordinates' ) );
	}


	/**
	 * Tries to get updated location coordinates and adds them to location meta
	 *
	 * @since 1.0.0
	 *
	 * @param bool $post_id
	 *
	 * @return bool
	 */
	public function update_location_coordinates( $post_id = false ) {
		$post_id         = apply_filters( 'wpmapit/get_post_id', $post_id );
		$location_fields = wpmapit()->location_meta->get_location_fields( $post_id );
		$address         = $this->format_address( $location_fields );
		$coordinates     = $this->get_coordinates( $address );

		if ( ! empty( $coordinates ) ) {
			foreach ( $coordinates as $key => $value ) {
				wpmapit()->location_meta->set_location_field( $key, $value, $post_id );
			}

			return true;
		}

		return false;
	}


	/**
	 * Converts location fields into an address string
	 *
	 * @since 1.0.0
	 *
	 * @param $fields
	 *
	 * @return string
	 */
	public function format_address( $fields ) {
		$full_address = $fields['mapit_address'] . ' ' .
		                $fields['mapit_city'] . ', ' .
		                $fields['mapit_state'] . ' ' .
		                $fields['mapit_zip'] . ' ' .
		                $fields['mapit_country'];

		return $this->normalize_address( $full_address );
	}


	/**
	 * Returns a normalized address string
	 *
	 * @param $address
	 *
	 * @return string
	 */
	public function normalize_address( $address ) {
		$invalid_chars = array( " " => "+", "," => "", "?" => "", "&" => "", "=" => "", "#" => "" );

		return trim(
			strtolower(
				str_replace(
					array_keys( $invalid_chars ),
					array_values( $invalid_chars ),
					$address
				)
			)
		);
	}


	/**
	 * Try to return coordinates from sanitized address
	 *
	 * @since 1.0.0
	 *
	 * @param $address
	 *
	 * @return array|bool|WP_Error
	 */
	public function get_coordinates( $address ) {
		if ( empty ( $address ) ) {
			return false;
		}

		try {
			$response = wp_remote_get(
				"http://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&sensor=false",
				array(
					'timeout'     => 5,
					'redirection' => 1,
					'httpversion' => '1.1',
					'user-agent'  => 'WordPress/WP_MapIt',
					'sslverify'   => false
				)
			);

			$result           = wp_remote_retrieve_body( $response );
			$geocoded_address = json_decode( $result );

			if ( $geocoded_address->status ) {
				switch ( $geocoded_address->status ) {
					case 'ZERO_RESULTS' :
						throw new Exception( __( "No results found", 'wp-mapit' ) );
						break;
					case 'OVER_QUERY_LIMIT' :
						throw new Exception( __( "Query limit reached", 'wp-mapit' ) );
						break;
					case 'OK' :
						if ( empty( $geocoded_address->results[0] ) ) {
							throw new Exception( __( "Geocoding error", 'wp-mapit' ) );
						}
						break;
					default :
						throw new Exception( __( "Geocoding error", 'wp-mapit' ) );
						break;
				}
			} else {
				throw new Exception( __( "Geocoding error", 'wp-mapit' ) );
			}
		} catch ( Exception $e ) {

			return new WP_Error( 'error', $e->getMessage() );
		}

		$coordinates         = array();
		$coordinates['lat']  = sanitize_text_field( $geocoded_address->results[0]->geometry->location->lat );
		$coordinates['long'] = sanitize_text_field( $geocoded_address->results[0]->geometry->location->lng );

		return $coordinates;
	}

}

new WP_MapIt_Geocode();

