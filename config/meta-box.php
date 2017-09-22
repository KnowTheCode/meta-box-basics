<?php
/**
 * Runtime configuration parameters.
 *
 * @package     KnowTheCode\MetaBoxBasics
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

namespace KnowTheCode\MetaBoxBasics;

return array(
	'add_meta_box' => array(
		// Meta box ID (used in the 'id' attribute for the meta box)
		'id'            => '',
		// Title of the meta box
		'title'         => '',
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
	// The key that holds all of the custom fields
	// in the $_POST[ $custom_field_key ]
	'custom_field_key'  => '',
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