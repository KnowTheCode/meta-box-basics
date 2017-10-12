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

function autoload() {
	include __DIR__ . '/api.php';
	include __DIR__ . '/internals.php';
}

autoload();
