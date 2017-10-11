<?php
/**
 * Module Helpers
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
 * Get all of the meta box keys.
 *
 * @since 1.0.0
 *
 * @return array|bool
 */
function get_meta_box_keys() {
	return configStore\getAllKeysStartingWith( 'meta_box.' );
}

/**
 * Get the meta box key.
 * Strips off the component prefix of 'meta_box.' returning on the
 * meta box's key.
 *
 * @since 1.0.0
 *
 * @param string $store_key Full meta box store key.
 *
 * @return string
 */
function get_meta_box_key( $store_key ) {
	return str_replace( 'meta_box.', '', $store_key );
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
function save_custom_fields( array $config, $meta_box_key, $post_id ) {
	// Get and initialize the custom fields configuration.
	$config = init_custom_fields_config( $config );

	// Merge with defaults.
	$metadata = wp_parse_args(
		$_POST[ $meta_box_key ],
		$config['default']
	);

	foreach ( $metadata as $meta_key => $value ) {
		// if no value, delete the post meta record.
		if ( $config['delete_state'][ $meta_key ] === $value ) {
			delete_post_meta( $post_id, $meta_key );
			continue;
		}

		// Invoke the sanitize function as configured to
		// sanitize the value before storing.
		$sanitize_func = $config['sanitize'][ $meta_key ];
		$value         = $sanitize_func( $value );

		update_post_meta( $post_id, $meta_key, $value );
	}
}