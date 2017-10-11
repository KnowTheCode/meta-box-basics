<?php
/**
 * Description
 *
 * @package     ${NAMESPACE}
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

namespace KnowTheCode\Metadata;

use KnowTheCode\ConfigStore as configStore;

define( 'METADATA_DIR', __DIR__ );

/**
 * Autoload the Configurations.
 *
 * @since 1.0.0
 *
 * @param array $config_files Array of configuration files to load.
 *
 * @return void
 */
function autoload_configurations( array $config_files ) {
	// Load the defaults, as we'll merge them upon loading into the store.
	$defaults = (array) require __DIR__ . '/default/meta-box-config.php';
	$defaults = current( $defaults );

	// Loop through the config files and load them into the store.
	// Store the returned key into our array so that we can store them separately.
	foreach ( $config_files as $path_to_file ) {
		configStore\loadConfigFromFilesystem( $path_to_file, $defaults );
	}
}

/**
 * Autoload the module's files.
 *
 * @since 1.0.0
 */
function autoload() {
	$files = array(
		'helpers.php',
		'meta-box.php',
	);

	foreach ( $files as $filename ) {
		require __DIR__ . '/' . $filename;
	}
}

autoload();
