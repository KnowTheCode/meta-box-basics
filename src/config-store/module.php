<?php
/**
 * ConfigStore Module - bootstrap file.
 *
 * @package     KnowTheCode\ConfigStore
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */
namespace KnowTheCode\ConfigStore;

/**
 * Autoload the module's files.
 *
 * @since 1.0.0
 */
function autoload() {
	require __DIR__ . '/api.php';
	require __DIR__ . '/internals.php';
}

autoload();
