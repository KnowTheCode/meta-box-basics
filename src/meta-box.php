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

	foreach( (array) $config['screen'] as $screen ) {
		add_meta_box(
			$config['id'],
			$config['title'],
			__NAMESPACE__ . '\render_meta_box',
			$screen,
			$config['context'],
			$config['priority'],
			$config['callback_args']
		);
	}
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

	if ( ! is_ok_to_save_custom_field(
		$config['nonce']['nonce_name'],
		$config['nonce']['nonce_action'],
		$config['custom_field_key'],
		$post->post_type )
	) {
		return;
	}

	// Merge the metadata.
	$data = wp_parse_args(
		$_POST[ $config['custom_field_key'] ],
		$config['metadata']
	);

	// Loop through the custom fields and update the `wp_postmeta` database.
	foreach ( $data as $meta_key => $value ) {
		if ( $value ) {
			update_post_meta( $post_id, $meta_key, strip_tags( $value ) );
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}
}

/**
 * Checks if it's okay to save custom field(s).
 *
 * @since 1.0.0
 *
 * @param string $nonce_name Nounce name
 * @param string $nonce_action Nonce action
 * @param string $custom_field_key Custom field key $_POST[ $custom_field_key ]
 * @param string $post_type Post type for this post
 *
 * @return bool
 */
function is_ok_to_save_custom_field( $nonce_name, $nonce_action, $custom_field_key, $post_type ) {
	// If the nonce doesn't match, return false.
	if ( ! wp_verify_nonce( $_POST[ $nonce_name ], $nonce_action ) ) {
		return false;
	}

	// If there's no data, return false.
	if ( ! isset( $_POST[ $custom_field_key ] ) ) {
		return;
	}

	// When doing an autosave, Ajax, future post, or revision,
	// don't save the custom fields.
	return ! (
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		( defined( 'DOING_AJAX' ) && DOING_AJAX ) ||
		( defined( 'DOING_CRON' ) && DOING_CRON ) ||
		( 'revision' === $post_type )
	);
}

/**
 * Get the runtime configuration parameters.
 *
 * @since 1.0.0
 *
 * @param string $key_to_get (Optional) Key to get from the configuration.
 *
 * @return mixed
 */
function getConfig( $key_to_get = '' ) {
	static $config;

	if ( ! $config ) {
		$config = require METABOXBASICS_DIR . 'config/meta-box.php';
	}

	if ( ! $key_to_get || ! array_key_exists( $key_to_get, $config ) ) {
		return $config;
	}

	return $config[ $key_to_get ];
}
