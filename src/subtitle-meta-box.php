<?php
/**
 * Subtitle Meta Box
 *
 * @package     KnowTheCode\MetaBox
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

namespace KnowTheCode\MetaBox;

use WP_Post;

add_action( 'admin_menu', __NAMESPACE__ . '\register_subtitle_meta_box', 0 );
/**
 * Register the meta box.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_subtitle_meta_box() {
	add_meta_box(
		'mbbasics_subtitle',
		__( 'Subtitle', 'mbbasics' ),
		__NAMESPACE__ . '\render_subtitle_meta_box',
		array( 'post' )
	);
}

/**
 * Render the meta box
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Instance of the post for this meta box
 * @param array $meta_box_args Array of meta box arguments
 *
 * @return void
 */
function render_subtitle_meta_box( WP_Post $post, array $meta_box_args ) {
	// Security with a nonce
	wp_nonce_field( 'mbbasics_save', 'mbbasics_nonce' );

	// Get the metadata
	$subtitle      = get_post_meta( $post->ID, 'subtitle', true );
	$show_subtitle = get_post_meta( $post->ID, 'show_subtitle', true );

	// Do any processing that needs to be done

	// Load the view file
	include METABOX_DIR . 'src/views/subtitle.php';
}

add_action( 'save_post', __NAMESPACE__ . '\save_subtitle_meta_box', 10, 2 );
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
function save_subtitle_meta_box( $post_id, $post ) {
	// If this is not the right meta box, then bail out.
	if ( ! array_key_exists( 'mbbasics', $_POST ) ) {
		return;
	}

	// Another conditional where we don't save
	// CRON AJAX....

	// If the nonce doesn't match, return false.
	if ( ! wp_verify_nonce( $_POST['mbbasics_nonce'], 'mbbasics_save' ) ) {
		return false;
	}

	// Merge with defaults.
	$metadata = wp_parse_args(
		$_POST['mbbasics'],
		// defaults
		array(
			'subtitle'      => '',
			'show_subtitle' => 0,
		)
	);

	foreach ( $metadata as $meta_key => $value ) {
		// if no value, delete the post meta record.
		if ( ! $value ) {
			delete_post_meta( $post_id, $meta_key );
			continue;
		}

		// validation and sanitizing
		if ( 'subtitle' === $meta_key ) {
			$value = sanitize_text_field( $value );
		} else {
			$value = 1;
		}

		update_post_meta( $post_id, $meta_key, $value );
	}
}
