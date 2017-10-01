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
 * Autoload module files.
 *
 * @since 1.0.0
 *
 * @return void
 */
function autoload() {
	$files = array(
		'meta-box.php',
	);

	foreach ( $files as $file ) {
		require __DIR__ . '/' . $file;
	}
}

/**
 * Description.
 *
 * @since 1.0.0
 *
 * @param array $config_files
 *
 * @return void
 */
function autoload_configurations( array $config_files ) {
	$meta_box_keys = configStore\loadConfigs( $config_files );

	meta_box_keys( $meta_box_keys );
}

function meta_box_keys( $keys_to_store = false ) {
	static $keys = array();

	if ( is_array( $keys_to_store ) ) {
		$keys = $keys_to_store;
	}

	return $keys;

}

autoload();