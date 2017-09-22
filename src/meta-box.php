<?php
/**
 * Meta Box Boilerplate
 *
 * @package     KnowTheCode\MetaBoxBasics
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

namespace KnowTheCode\MetaBoxBasics;

use WP_Post;

add_action( 'admin_menu', __NAMESPACE__ . '\register_meta_box' );
/**
 * Register the meta box.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_meta_box() {
	$config = getConfig( 'add_meta_box' );

	add_meta_box(
		$config['id'],
		$config['title'],
		$config['callback'],
		$config['screen'],
		$config['context'],
		$config['priority'],
		$config['callback_args']
	);
}

/**
 * Render the meta box
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Instance of the post for this meta box
 * @param array $meta_box Array of meta box arguments
 *
 * @return void
 */
function render_meta_box( WP_Post $post, array $meta_box ) {
	$config = getConfig();

	// Security with a nonce
	wp_nonce_field( $config['nonce']['nonce_action'], $config['nonce']['nonce_name'] );

	// Get the metadata

	// Do any processing that needs to be done

	// Load the view file
	include $config['view'];
}

add_action( 'save_post', __NAMESPACE__ . '\save_meta_box', 10, 2 );
/**
 * Description.
 *
 * @since 1.0.0
 *
 * @param integer $post_id Post ID.
 * @param stdClass $post Post object.
 *
 * @return void
 */
function save_meta_box( $post_id, $post ) {
	$config = getConfig();

	// If the nonce doesn't match, bail out.
	if ( ! wp_verify_nonce(
		$_POST[ $config['nonce']['nonce_name'] ],
		$config['nonce']['nonce_action'] )
	) {
		return;
	}

	// prepare the data

	// If there's data to save/update, do it here.
	// update_post_meta($post_id, $meta_key, $data);

	// else, delete it.
	// delete_post_meta($post_id, $meta_key);

}

function getConfig( $key_to_get = '' ) {
	$config = array(
		'add_meta_box' => array(
			// Meta box ID (used in the 'id' attribute for the meta box)
			'id'            => '',
			// Title of the meta box
			'title'         => '',
			// callback
			'callback'      => '\\KnowTheCode\MetaBoxBasics\render_meta_box',
			// The screen or screens on which to show the box
			// such as a post type, link, comment, etc.
			'screen'        => null,
			// (Optional) The context within the screen where this will display.
			// Choices: normal, side, or advanced (default).
			'context'       => 'advanced',
			// (Optional) Sets the priority of when the meta box will render.
			// Choices: high, low, or default (which is the default).
			'priority'      => 'default',
			// (Optional) You can send arguments to your render callback.
			// Send as an array of arguments.
			'callback_args' => array(),
		),
		'nonce'        => array(
			'nonce_action' => '',
			'nonce_name'   => '',
		),
		'metadata'     => array(
			// fill in the metadata here.
			// 'meta_key' => 'default value',
		),
		'view'         => '',
	);

	if ( ! $key_to_get || ! array_key_exists( $key_to_get, $config ) ) {
		return $config;
	}

	return $config[ $key_to_get ];
}