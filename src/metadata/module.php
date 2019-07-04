<?php
/**
 * Metadata Module - bootstrap file.
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
 * Autoload the module's configuration models.
 *
 * @since 1.0.0
 *
 * @param array $config_files Array of configuration models files.
 *
 * @return void
 */
function autoload_configurations( array $config_files ) {
	$defaults = (array) require __DIR__ . '/default/meta-box-config.php';
	$defaults = current( $defaults );

	foreach ( $config_files as $config_file ) {
		$store_key = configStore\loadConfigFromFilesystem( $config_file, $defaults );

		init_custom_fields_configuration( $store_key );
	}
}

/**
 * Initialize the custom fields configurations.
 *
 * @since 1.0.0
 *
 * @param string $store_key Configuration's store key.
 *
 * @return void
 */
function init_custom_fields_configuration( $store_key ) {
	$config = configStore\getConfig( $store_key );

	$default_config = array_shift( $config['custom_fields'] );

	foreach ( $config['custom_fields'] as $meta_key => $custom_field_config ) {
		$config['custom_fields'][ $meta_key ] = array_merge( $default_config, $custom_field_config );
	}

	configStore\loadConfig( $store_key, $config );
}

/**
 * Autoload the module's files.
 *
 * @since 1.0.0
 *
 * @return void
 */
function autoload() {
	include __DIR__ . '/helpers.php';
	include __DIR__ . '/meta-box.php';
}

autoload();
