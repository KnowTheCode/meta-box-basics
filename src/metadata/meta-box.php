<?php
/**
 * Meta Box Boilerplate
 *
 * @package     KnowTheCode\Metadata
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

namespace KnowTheCode\Metadata;

use WP_Post;
use KnowTheCode\ConfigStore as configStore;

add_action( 'admin_menu', __NAMESPACE__ . '\register_meta_boxes' );
/**
 * Register the meta box.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_meta_boxes() {
	$keys = get_meta_box_keys();

	foreach ( $keys as $meta_box_key ) {
		$config = configStore\getConfigParameter( $meta_box_key, 'add_meta_box' );

		add_meta_box(
			$meta_box_key,
			$config['title'],
			__NAMESPACE__ . '\render_meta_box',
			$config['screen'],
			$config['context'],
			$config['priority'],
			$config['callback_args']
		);
	}
}

/**
 * Render the meta box
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Instance of the post for this meta box
 * @param array $meta_box_args Array of meta box arguments
 *
 * @return void
 */
function render_meta_box( WP_Post $post, array $meta_box_args ) {
	$meta_box_key = $meta_box_args['id'];
	$config       = configStore\getConfig( $meta_box_key );
	if ( ! $config ) {
		return;
	}

	// Security with a nonce
	wp_nonce_field( $meta_box_key . '_nonce_action', $meta_box_key . '_nonce_name' );

	$custom_fields = get_custom_fields_values( $post->ID, $meta_box_key, $config['custom_fields'] );

	// Load the view file
	include $config['view'];
}

/**
 * Get the values for each custom field.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post's ID
 * @param string $meta_box_key Meta box's key (ID) - used to identify this meta box
 * @param array $config Custom field's configuration parameters
 *
 * @return array
 */
function get_custom_fields_values( $post_id, $meta_box_key, array $config ) {
	$custom_fields = array();

	foreach ( $config as $meta_key => $meta_config ) {
		$custom_fields[ $meta_key ] = get_post_meta( $post_id, $meta_key, $meta_config['is_single'] );

		if ( ! $custom_fields[ $meta_key ] ) {
			$custom_fields[ $meta_key ] = $meta_config['default'];
		}
	}

	/**
	 * Filter the custom fields values before rendering to the meta box.
	 *
	 * Allows for processing and filtering work before the meta box is sent out to the browser.
	 *
	 * @since 1.0.0
	 *
	 * @param array $custom_fields Array of custom fields values
	 * @param string $meta_box_key Meta box's key (ID) - used to identify this meta box
	 * @param int $post_id Post's ID
	 */
	return (array) apply_filters( 'filter_meta_box_custom_fields',
		$custom_fields,
		$meta_box_key,
		$post_id
	);
}

add_action( 'save_post', __NAMESPACE__ . '\save_meta_boxes' );
/**
 * Description.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post's ID
 *
 * @return void
 */
function save_meta_boxes( $post_id ) {
	$keys = get_meta_box_keys();

	foreach ( $keys as $meta_box_key ) {
		if ( is_okay_to_save_meta_box( $meta_box_key ) ) {
			save_custom_fields( $meta_box_key, $post_id );
		}
	}
}

/**
 * Checks if it's okay to save the custom field(s).
 *
 * @since 1.0.0
 *
 * @param string $meta_box_key Meta box's key (ID) - used to identify this meta box
 *
 * @return bool
 */
function is_okay_to_save_meta_box( $meta_box_key ) {
	// If this is not the right meta box, then bail out.
	if ( ! array_key_exists( $meta_box_key, $_POST ) ) {
		return false;
	}

	if ( ! wp_verify_nonce(
		$_POST[ $meta_box_key . '_nonce_name' ],
		$meta_box_key . '_nonce_action'
	) ) {
		return false;
	}


	// When doing an Ajax or future post, don't save the custom fields.
	return ! (
		( defined( 'DOING_AJAX' ) && DOING_AJAX ) ||
		( defined( 'DOING_CRON' ) && DOING_CRON )
	);
}

/**
 * Save the custom fields for this meta box.
 *
 * @since 1.0.0
 *
 * @param string $meta_box_key Meta box's key (ID) - used to identify this meta box
 * @param int $post_id Post's ID
 *
 * @return void
 */
function save_custom_fields( $meta_box_key, $post_id ) {
	// Get and initialize the custom fields configuration.
	$config = init_custom_fields_config(
		configStore\getConfigParameter( $meta_box_key, 'custom_fields' )
	);

	// Merge with defaults.
	$metadata = wp_parse_args(
		$_POST[ $meta_box_key ],
		$config['default']
	);

	foreach ( $metadata as $meta_key => $value ) {
		// if no value, delete the post meta record.
		if ( $config['delete_state'][$meta_key] === $value ) {
			delete_post_meta( $post_id, $meta_key );
			continue;
		}

		// Invoke the sanitize function as configured to
		// sanitize the value before storing.
		$sanitize_func = $config['sanitize'][ $meta_key ];
		$value = $sanitize_func( $value );

		update_post_meta( $post_id, $meta_key, $value );
	}
}

/**
 * Initialize the custom fields configuration parameters, rearrange them
 * into the array structure needed for the saving process.
 *
 * Why not just put it in that order in the configuration file?
 * Redundancy - The config file then would repeat the meta keys over and over again.
 *
 * @since 1.0.0
 *
 * @param array $config Custom fields configuration parameters.
 *
 * @return array
 */
function init_custom_fields_config( array $config ) {
	$custom_fields_config = array(
		'default'     => array(),
		'delete_state' => array(),
		'sanitize'     => array(),
	);

	foreach ( $config as $meta_key => $meta_config ) {
		foreach ( $meta_config as $group => $value ) {
			$custom_fields_config[ $group ][ $meta_key ] = $value;
		}
	}

	return $custom_fields_config;
}
