<?php
/**
 * Meta Box Generator.
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
 * Register the meta boxes.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_meta_boxes() {
	foreach ( get_meta_box_keys() as $store_key ) {

		$config = configStore\getConfigParameter(
			$store_key,
			'add_meta_box'
		);

		add_meta_box(
			get_meta_box_id( $store_key ),
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
	$meta_box_id = $meta_box_args['id'];
	$config       = configStore\getConfig( 'meta_box.' . $meta_box_id );

	// Security with a nonce
	wp_nonce_field( $meta_box_id . '_nonce_action', $meta_box_id . '_nonce_name' );

	// Get the metadata
	$custom_fields = get_custom_fields_values( $post->ID, $meta_box_id, $config );

	// Load the view file
	include $config['view'];
}

add_action( 'save_post', __NAMESPACE__ . '\save_meta_boxes' );
/**
 * Save the Meta Boxes.
 *
 * @since 1.0.0
 *
 * @param integer $post_id Post ID.
 *
 * @return void
 */
function save_meta_boxes( $post_id ) {
	foreach ( get_meta_box_keys() as $store_key ) {

		$meta_box_key = get_meta_box_id( $store_key );

		if ( ! is_okay_to_save_meta_box( $meta_box_key ) ) {
			continue;
		}

		$config = configStore\getConfigParameter(
			$store_key,
			'custom_fields'
		);

		save_custom_fields( $config, $meta_box_key, $post_id );
	}
}

/****************************************************
 * Supporting Functions for the Meta Box Generator
 ***************************************************/

/**
 * Check if it's okay to save this meta box and its
 * custom fields.
 *
 * @since 1.0.0
 *
 * @param string $meta_box_key Meta Box ID
 *
 * @return bool
 */
function is_okay_to_save_meta_box( $meta_box_key ) {
	// If this is not the right meta box, then bail out.
	if ( ! array_key_exists( $meta_box_key, $_POST ) ) {
		return false;
	}

	// If doing autosave, ajax, or future posting, bail out.
	if (
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		( defined( 'DOING_AJAX' ) && DOING_AJAX ) ||
		( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
		return false;
	}

	// Security check.
	return wp_verify_nonce(
		$_POST[ $meta_box_key . '_nonce_name' ],
		$meta_box_key . '_nonce_action'
	);
}

/**
 * Remap the custom fields configuration parameters, rearrange them
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
function remap_custom_fields_config( array $config ) {
	$remapped_config = array(
		'is_single'    => array(),
		'default'      => array(),
		'delete_state' => array(),
		'sanitize'     => array(),
	);

	foreach ( $config as $meta_key => $custom_field_config ) {
		foreach ( $custom_field_config as $parameter => $value ) {
			$remapped_config[ $parameter ][ $meta_key ] = $value;
		}
	}


	return $remapped_config;
}

/**
 * Save the custom fields for this meta box.
 *
 * @since 1.0.0
 *
 * @param array $config Custom Fields configuration parameters.
 * @param string $meta_box_key Meta box's key (ID) - used to identify this meta box
 * @param int $post_id Post's ID
 *
 * @return void
 */
function save_custom_fields( $config, $meta_box_key, $post_id ) {
	$config = remap_custom_fields_config( $config );

	// Merge with defaults.
	$metadata = wp_parse_args(
		$_POST[ $meta_box_key ],
		$config['default']
	);

	foreach ( $metadata as $meta_key => $value ) {
		// if in delete state, delete the custom field.
		if ( $config['delete_state'][ $meta_key ] === $value ) {
			delete_post_meta( $post_id, $meta_key );
			continue;
		}

		// validation and sanitizing
		$sanitizing_func = $config['sanitize'][ $meta_key ];
		$value           = $sanitizing_func( $value );

		update_post_meta( $post_id, $meta_key, $value );
	}
}
