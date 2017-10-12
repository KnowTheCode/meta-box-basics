<?php
/**
 * Metadata Helpers for the metadata module.
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
