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
 * Get the runtime configuration parameters.
 *
 * @since 1.0.0
 *
 * @param string $store_key Key to fetch the configuration out of the store.
 * @param string $key_to_get (Optional) Key to fetch out of the specific configuration.
 *
 * @return mixed
 */
function getConfig( $store_key, $key_to_get = '' ) {
	$config = the_store( $store_key );

	if ( ! $key_to_get || ! array_key_exists( $key_to_get, $config ) ) {
		return $config;
	}

	return $config[ $key_to_get ];
}

/**
 * Load the specified configuration files into the Config Store
 * for reuse in this plugin.
 *
 * @since 1.0.0
 *
 * @param array $configs Array of configurations to load into the store.
 *              structure is: array(
 *                  'store_key' => 'path/to/the/configuration-file.php'
 *              );
 *
 * @return array Returns an array of store keys
 */
function loadConfigs( array $configs ) {
	$store_keys = array();

	foreach( $configs as $path_to_config_file ) {
		$store_keys[] = the_store( '', $path_to_config_file );
	}

	return $store_keys;
}

/**
 * Store the runtime configuration parameters via the 'store_key'.
 *
 * 1. If the configuration does not already exist in the store, then load it using `require`.
 * 2. Returns the array of configuration parameters.
 *
 * @since 1.0.0
 *
 * @param string $store_key Key to fetch the configuration out of the store.
 * @param string $path_to_config_file Absolute path to the configuration file.
 *
 * @return string|array Returns the store key if none was passed in;
 *                      else returns the array of configuration parameters.
 */
function the_store( $store_key = '', $path_to_config_file = '' ) {
	// Hmm, hello, you have to pass me at least one of these.
	if ( ! $store_key && ! $path_to_config_file ) {
		return;
	}

	static $configStore = array();

	// Config exists in the store, just return.
	if ( $store_key && array_key_exists( $store_key, $configStore ) ) {
		return $configStore[ $store_key ];
	}

	// If no store key was passed in, then return the generated store key.
	$return_store_key = ! $store_key;

	if ( $path_to_config_file ) {
		list( $store_key, $config ) = load_the_config_file( $path_to_config_file );
		$configStore[ $store_key ] = $config;
	}

	return $return_store_key
		? $store_key
		: $configStore[ $store_key ];
}

/**
 * Load the configuration file.
 *
 * @since 1.0.0
 *
 * @param string $path_to_config_file Absolute path to the configuration file to be loaded.
 *
 * @return array
 * @throws \Exception If the file does not exist or is not readable, an error is thrown.
 */
function load_the_config_file($path_to_config_file) {
	if ( ! is_readable( $path_to_config_file ) ) {
		$error_message = sprintf(
			__('The configuration file [%s] could not be found or is not readable.', 'mbboilerplate' ),
			$path_to_config_file
		);

		throw new Exception($error_message);
	}

	$full_config = (array) require $path_to_config_file;

	foreach( $full_config as $store_key => $config ) {
		return array( $store_key, $config );
	}
}