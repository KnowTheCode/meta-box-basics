<?php
/**
 * Metadata Helpers.
 *
 * @package     KnowTheCode\Metadata
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */
namespace KnowTheCode\Metadata;

use KnowTheCode\ConfigStore as configStore;

/**
 * Get all of the meta box keys from the ConfigStore.
 *
 * @since 1.0.0
 *
 * @return array
 */
function get_meta_box_keys() {
	return (array) configStore\getAllKeysStartingWith( 'meta_box.' );
}

/**
 * Gets the meta box unique ID from the store key.
 *
 * @since 1.0.0
 *
 * @param string $store_key ConfigStore key
 *
 * @return string
 */
function get_meta_box_id( $store_key ) {
	return str_replace( 'meta_box.', '', $store_key );
}

/**
 * Get the values for each custom field.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post's ID
 * @param string $meta_box_id Meta box's key (ID) - used to identify this meta box
 * @param array $config Custom field's configuration parameters
 *
 * @return array
 */
function get_custom_fields_values( $post_id, $meta_box_id, array $config ) {
	$custom_fields = array();

	foreach ( $config['custom_fields'] as $meta_key => $custom_field_config ) {
		$custom_fields[ $meta_key ] = get_post_meta($post_id, $meta_key, $custom_field_config['is_single'] );

		if ( ! $custom_fields[ $meta_key ] ) {
			$custom_fields[ $meta_key ] = $custom_field_config['default'];
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
	 * @param string $meta_box_id Meta box's key (ID) - used to identify this meta box
	 * @param int $post_id Post's ID
	 */
	return (array) apply_filters( 'filter_meta_box_custom_fields',
		$custom_fields,
		$meta_box_id,
		$post_id
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
