<?php
/**
 * Default runtime configuration parameters for a meta box.
 *
 * @package     KnowTheCode\Metadata
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

return array(
	// store key => config for this meta box.
	'your-meta-box-id' => array(

		'add_meta_box' => array(
			// Title of the meta box
			'title'         => '',
			// The screen or screens on which to show the box
			// such as a post type, link, comment, etc.
			'screens'       => array( 'post' ),
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

		// Custom fields (metadata)
		'custom_fields'     => array(
			'meta_key'      => array(
				// Specify the custom field's default value.
				'default'      => '',
				// What is the state that signals to delete this meta key
				// from the database.
				'delete_state' => '',
				// callable sanitizer function such as
				// sanitize_text_field, sanitize_email, strip_tags, intval, etc.
				'sanitize'     => 'sanitize_text_field',
			),
		),

		// Absolute path to your meta box's view file.
		'view' => '',
	),
);
