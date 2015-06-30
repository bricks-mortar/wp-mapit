<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_MapIt_Location_Meta
 *
 * @class WP_MapIt_Location_Meta
 * @version 0.1.0
 * @author Dane Grant
 *
 * @todo: Allow creation multiple custom meta boxes
 */
class WP_MapIt_Location_Meta {


	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		$this->location_fields = $this->meta_fields();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}


	/**
	 * Array of meta box fields
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @return array
	 */
	public function meta_fields() {
		$prefix                = 'mapit_';
		$fields = array(
			array(
				'label' => 'Address',
				'id'    => $prefix . 'address',
				'type'  => 'text'
			),
			array(
				'label' => 'City',
				'id'    => $prefix . 'city',
				'type'  => 'text'
			),
			array(
				'label' => 'State',
				'id'    => $prefix . 'state',
				'type'  => 'text'
			),
			array(
				'label' => 'Zip code',
				'id'    => $prefix . 'zip',
				'type'  => 'text'
			),
		);

		return $fields;
	}


	/**
	 * Add the meta box container
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function add_meta_box( $post_type ) {
		add_meta_box(
			'mapit_location_meta',
			__( 'WP MapIt Location', 'wp-mapit' ),
			array( $this, 'render_meta_box_content' ),
			'mapit_locations',
			'advanced',
			'high'
		);
	}


	/**
	 * Save location meta fields
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function save( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['mapit_inner_custom_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['mapit_inner_custom_box_nonce'];

		// Verify that the nonce is valid
		if ( ! wp_verify_nonce( $nonce, 'mapit_inner_custom_box' ) ) {
			return $post_id;
		}

		// Ignore autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}


		// Save all fields
		foreach ( $this->location_fields as $field ) {

			$old = get_post_meta( $post_id, $field['id'], true );
			$new = $_POST[ $field['id'] ];

			if ( $new && $new != $old ) {
				// Save field value if changed
				update_post_meta( $post_id, $field['id'], $new );
			} elseif ( '' == $new && $old ) {
				// Delete field if empty
				delete_post_meta( $post_id, $field['id'], $old );
			}
		}
	}


	/**
	 * Display the meta box on post edit page
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param $post
	 */
	public function render_meta_box_content( $post ) {

		wp_nonce_field( 'mapit_inner_custom_box', 'mapit_inner_custom_box_nonce' );

		// Render fields
		echo '<table class="form-table">';
		foreach ( $this->location_fields as $field ) {
			$meta_val = get_post_meta( $post->ID, $field['id'], true );

			echo '<tr>'
			     . '<th>'
			     . '<label for="' . $field['id'] . '">' . $field['label'] . '</label>'
			     . '</th>'
			     . '<td>'
			     . '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta_val . '" size="30" />';

			if ( ! empty( $field['desc'] ) ) {
				echo '<br /><span class="description">' . $field['desc'] . '</span>';
			}

			echo '</td>'
			     . '</tr>';

		}
		echo '</table>';
	}

}