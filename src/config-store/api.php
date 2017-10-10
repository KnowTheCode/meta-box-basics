<?php
/**
 * Public API to interact with the ConfigStore.
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
 * Get a specific configuration from the store.
 *
 * @since 1.0.0
 *
 * @param string $store_key
 *
 * @return mixed
 */
function getConfig( $store_key ) {
	return _the_store( $store_key );
}

/**
 * Get a specific configuration parameter from the store.
 *
 * @since 1.0.0
 *
 * @param string $store_key
 * @param string $parameter_key
 *
 * @return mixed
 * @throws Exception
 */
function getConfigParameter( $store_key, $parameter_key ) {
	$config = (array) getConfig( $store_key );

	if ( ! array_key_exists( $parameter_key, $config ) ) {
		throw new Exception( sprintf(
			__( 'Configuration for [%s] does not exist in the ConfigStore.', 'config-store' ),
			$store_key
		) );
	}

	return $config[ $parameter_key ];
}

/**
 * Load the configuration into the store from the
 * absolute path to the configuration file.
 *
 * @since 1.0.0
 *
 * @param string $path_to_file Absolute path to the config file.
 * @param array $defaults (Optional) Defaults to merge the parameters with before storing.
 *
 * @return string Returns the store key.
 */
function loadConfigFromFilesystem( $path_to_file, array $defaults = array() ) {
	list( $store_key, $config ) = _load_config_from_filesystem( $path_to_file );

	if ( $defaults ) {
		$config = _merge_with_defaults( $config, $defaults );
	}

	_the_store( $store_key, $config );

	return $store_key;
}

/**
 * Load the configuration into the store.
 *
 * @since 1.0.0
 *
 * @param string $store_key Unique storage key
 * @param mixed $config Runtime configuration parameter(s)
 */
function loadConfig( $store_key, $config ) {
	_the_store( $store_key, $config );
}

/**
 * Gets the stores keys.
 *
 * @since 1.0.0
 *
 * @return array|bool
 */
function getKeys() {
	return _the_key_store();
}