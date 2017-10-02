<?php
/**
 * Portfolio Meta Box
 *
 * @package     KnowTheCode\MetaBox
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

namespace KnowTheCode\MetaBox;

use WP_Post;

add_action( 'admin_menu', __NAMESPACE__ . '\register_portfolio_meta_box', 0 );
/**
 * Register the meta box.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_portfolio_meta_box() {
	add_meta_box(
		'portfolio',
		__( 'Portfolio Details', 'portfolio' ),
		__NAMESPACE__ . '\render_portfolio_meta_box',
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
function render_portfolio_meta_box( WP_Post $post, array $meta_box_args ) {
	// Security with a nonce
	wp_nonce_field( 'portfolio_save', 'portfolio_nonce' );

	// Get the metadata
	$client_name = get_post_meta( $post->ID, 'client_name', true );
	$client_url  = get_post_meta( $post->ID, 'client_url', true );

	// Do any processing that needs to be done

	// Load the view file
	include METABOX_DIR . 'src/views/portfolio.php';
}

add_action( 'save_post', __NAMESPACE__ . '\save_portfolio_meta_box', 10, 2 );
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
function save_portfolio_meta_box( $post_id, $post ) {
	// If this is not the right meta box, then bail out.
	if ( ! array_key_exists( 'portfolio', $_POST ) ) {
		return;
	}

	// Another conditional where we don't save
	// CRON AJAX....

	// If the nonce doesn't match, return false.
	if ( ! wp_verify_nonce( $_POST['portfolio_nonce'], 'portfolio_save' ) ) {
		return false;
	}

	// Merge with defaults.
	$metadata = wp_parse_args(
		$_POST['portfolio'],
		// defaults
		array(
			'client_name' => '',
			'client_url'  => '',
		)
	);

	foreach ( $metadata as $meta_key => $value ) {
		// if no value, delete the post meta record.
		if ( ! $value ) {
			delete_post_meta( $post_id, $meta_key );
			continue;
		}

		$value = sanitize_text_field( $value );

		update_post_meta( $post_id, $meta_key, $value );
	}
}
