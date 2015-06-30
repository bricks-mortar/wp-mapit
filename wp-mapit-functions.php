<?php

if ( ! function_exists( 'get_post_id' ) ) :
	/**
	 * Helper function to get a posts ID
	 *
	 * @param $post_id
	 *
	 * @return int|string
	 */
	function get_post_id( $post_id ) {

		if ( ! $post_id ) {
			global $post;

			if ( $post ) {
				$post_id = intval( $post->ID );
			}
		}

		if ( $post_id == "option" ) {
			$post_id = "options";
		}

		if ( is_object( $post_id ) ) {
			if ( isset( $post_id->roles, $post_id->ID ) ) {
				$post_id = 'user_' . $post_id->ID;
			} elseif ( isset( $post_id->taxonomy, $post_id->term_id ) ) {
				$post_id = $post_id->taxonomy . '_' . $post_id->term_id;
			} elseif ( isset( $post_id->ID ) ) {
				$post_id = $post_id->ID;
			}
		}

		if ( isset( $_GET['preview_id'] ) ) {
			$autosave = wp_get_post_autosave( $_GET['preview_id'] );
			if ( $autosave->post_parent == $post_id ) {
				$post_id = intval( $autosave->ID );
			}
		}

		return $post_id;
	}
endif; // end get_post_id


