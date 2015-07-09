<?php
/**
 * Meta data for locations
 *
 * @since 1.0.0
 *
 * @package WordPress\WP_MapIt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WP_MapIt_Location_Meta
 *
 * @class WP_MapIt_Location_Meta
 * @version 1.0.0
 *
 * @todo: Allow creation multiple custom meta boxes
 */
class WP_MapIt_Location_Meta {


	/**
	 * Prefix for field keys
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var string
	 */
	private $prefix = 'mapit_';

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->location_fields = $this->meta_fields();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}


	/**
	 * Returns meta field prefix
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_prefix() {
		return $this->prefix;
	}


	/**
	 * Array of meta box fields
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return array $fields
	 */
	private function meta_fields() {

		$fields = array(
			array(
				'label' => 'Address',
				'id'    => $this->prefix . 'address',
				'type'  => 'text'
			),
			array(
				'label' => 'City',
				'id'    => $this->prefix . 'city',
				'type'  => 'text'
			),
			array(
				'label' => 'State',
				'id'    => $this->prefix . 'state',
				'type'  => 'text'
			),
			array(
				'label' => 'Zip code',
				'id'    => $this->prefix . 'zip',
				'type'  => 'text'
			),
			array(
				'label' => 'Country',
				'id'    => $this->prefix . 'country',
				'type'  => 'text'
			),
		);

		return $fields;
	}


	/**
	 * Add the meta box container
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type
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
	 * @since 1.0.0
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


		$field_changed = false;

		// Save all fields
		foreach ( $this->location_fields as $field ) {

			$old = get_post_meta( $post_id, $field['id'], true );
			$new = $_POST[ $field['id'] ];

			if ( $new && $new != $old ) {
				// Save field value if changed
				$field_changed = true;
				update_post_meta( $post_id, $field['id'], $new );
			} elseif ( '' == $new && $old ) {
				// Delete field if empty
				delete_post_meta( $post_id, $field['id'], $old );
			}
		}

		if ( $field_changed ) {
			do_action( 'mapit_update_coordinates', $post_id );
		}

		return $field_changed;
	}


	/**
	 * Display the meta box on post edit page
	 *
	 * @since 1.0.0
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


	/**
	 * Return an array of all location meta fields
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $post_id optional
	 *
	 * @return array
	 */
	public static function get_location_fields( $post_id = false ) {
		$post_id     = apply_filters( 'wpmapit/get_post_id', $post_id );
		$meta_fields = get_post_meta( $post_id );


		$location_fields = array_map(
			function ( $val ) {
				return $val[0];
			}, array_intersect_key(
				$meta_fields,
				array_flip(
					array_filter(
						array_keys( $meta_fields ),
						function ( $v ) {
							return strpos( $v, 'mapit_' ) !== false;
						}
					)
				)
			)
		);

		return $location_fields;
	}


	/**
	 * Returns a single location field
	 *
	 * @since 1.0.0
	 *
	 * @param $field_name
	 * @param bool $post_id
	 *
	 * @return string
	 */
	public static function get_location_field( $field_name, $post_id = false ) {
		$field_name = $this->prefix . $field_name;
		$post_id    = apply_filters( 'wpmapit/get_post_id', $post_id );

		$meta_field = get_post_meta( $post_id, $field_name, true );

		return $meta_field;
	}


	public function set_location_field( $field_name, $field_value, $post_id = false ) {
		$prefix = $this->get_prefix();

		update_post_meta( $post_id, $prefix . $field_name, $field_value );
	}

}