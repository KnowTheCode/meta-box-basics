<?php
/**
 * Meta Box Basics WordPress Plugin
 *
 * @package     MetaBoxBasics
 * @author      hellofromTonya
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Meta Box Basics WordPress Plugin
 * Plugin URI:  https://github.com/KnowTheCode/meta-box-basics
 * Description: A boilerplate for creating custom meta boxes from scratch.
 * Version:     1.0.0
 * Author:      hellofromTonya
 * Author URI:  https://KnowTheCode.io
 * Text Domain: mbbasics
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace KnowTheCode\MetaBoxBasics;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheatin&#8217; uh?' );
}

/**
 * Setup the plugin's constants.
 *
 * @since 1.0.3
 *
 * @return void
 */
function init_constants() {
	$plugin_url = plugin_dir_url( __FILE__ );
	if ( is_ssl() ) {
		$plugin_url = str_replace( 'http://', 'https://', $plugin_url );
	}

	define( 'METABOXBASICS_URL', $plugin_url );
	define( 'METABOXBASICS_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Launch the plugin
 *
 * @since 1.0.0
 *
 * @return void
 */
function launch() {
	init_constants();

	require __DIR__ . '/src/meta-box.php';
}

launch();
