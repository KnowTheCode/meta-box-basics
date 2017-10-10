<?php
/**
 * Configuration Repository (store)
 *
 * @package     KnowTheCode\ConfigStore
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

namespace KnowTheCode\ConfigStore;

use Exception;

/**
 * The Configuration Store.
 *
 * It does 2 tasks:
 *
 *      1. If a $config_to_store is provided, it will store it into the container.
 *      2. Else, it gets the parameter(s) stored in the container via the $store_key.
 *
 * @since 1.0.0
 *
 * @param string $store_key The unique storage key
 * @param array|mixed $config_to_store Runtime parameter(s) to store
 *
 * @return string|array Returns the store key if none was passed in;
 *                      else returns the array of configuration parameters.
 * @throws Exception
 */
function _the_store( $store_key = '', $config_to_store = array() ) {
	static $configStore = array();

	// Get All

	if ( ! $store_key && ! $config_to_store ) {
		return $configStore;
	}

	// Store
	if ( $config_to_store ) {
		$configStore[ $store_key ] = $config_to_store;

		return true;
	}

	// Get

	// Key does not exist in the store.
	if ( ! array_key_exists( $store_key, $configStore ) ) {
		throw new Exception( sprintf(
			__( 'Configuration for [%s] does not exist in the ConfigStore.', 'config-store' ),
			esc_html( $store_key )
		) );
	}

	return $configStore[ $store_key ];
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
 * @throws Exception
 */
function _load_config_from_filesystem( $path_to_file ) {

	if ( ! is_readable( $path_to_file ) ) {
		$error_message = sprintf(
			__( 'The configuration file [%s] could not be found or is not readable.', 'config-store' ),
			$path_to_file
		);

		throw new Exception( $error_message );
	}

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
 * @param array $config
 * @param array $defaults
 *
 * @return array
 */
function _merge_with_defaults( array $config, array $defaults ) {
	return array_replace_recursive( $defaults, $config );
}