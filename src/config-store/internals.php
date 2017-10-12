<?php
/**
 * ConfigStore's Internal Functionality (Private)
 *
 * @package     KnowTheCode\ConfigStore
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

namespace KnowTheCode\ConfigStore;

/**
 * Description.
 *
 * @since 1.0.0
 *
 * @param string $store_key
 * @param array $config_to_store
 *
 * @return void
 * @throws \Exception
 */
function _the_store( $store_key = '', $config_to_store = array() ) {
	static $config_store = array();

	// Get the store.
	if ( ! $store_key ) {
		return $config_store;
	}

	// Store
	if ( $config_to_store ) {
		$config_store[ $store_key ] = $config_to_store;

		return true;
	}

	// Get
	if ( ! array_key_exists( $store_key, $config_store ) ) {
		throw new \Exception(
			sprintf(
				__('Configuration for [%s] does not exist in the ConfigStore', 'config-store'),
				esc_html( $store_key )
			)
		);
	}

	return $config_store[ $store_key ];
}

/**
 * Load a configuration from the filesystem, returning its
 * storage key and configuration parameters.
 *
 * @since 1.0.0
 *
 * @param string $path_to_file Absolute path to the config file.
 *
 * @return array
 */
function _load_config_from_filesystem( $path_to_file ) {
	$config = (array) require $path_to_file;

	return array(
		key( $config ),
		current( $config ),
	);
}

/**
 * Merge the configuration with defaults.
 *
 * @since 1.0.0
 *
 * @param array $config Array of configuration parameters
 * @param array $defaults Array of default parameters
 *
 * @return array
 */
function _merge_with_defaults( array $config, array $defaults ) {
	return array_replace_recursive( $defaults, $config );
}


/**
 * Checks if a string starts with a character or substring.
 *
 * @since 1.0.0
 *
 * @param string $haystack  The string to be searched
 * @param string $needle    The character or substring to
 *                          find at the start of the $haystack
 * @param string $encoding  Default is UTF-8
 *
 * @return bool
 */
function str_starts_with( $haystack, $needle, $encoding = 'UTF-8' ) {
	$needle_length = mb_strlen( $needle, $encoding );

	return ( mb_substr( $haystack, 0, $needle_length, $encoding ) === $needle );
}
