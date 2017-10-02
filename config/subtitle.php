<?php
/**
 * Runtime configuration parameters for the subtitle meta box.
 *
 * @package     KnowTheCode\MetaBox
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://KnowTheCode.io
 * @license     GNU-2.0+
 */

return array(
	/************************************************************
	 * Configure a unique ID for your meta box.
	 *
	 * This ID is used when running add_meta_box and for storing
	 * in the Config Store.
	 ***********************************************************/
	'metabox_subtitle' => array(

		/************************************************************
		 * Configuration parameters for adding the meta box.
		 * For more information on each of the parameters, see this
		 * article in Codex:
		 * @link https://developer.wordpress.org/reference/functions/add_meta_box/#parameters
		 ***********************************************************/
		'add_meta_box' => array(
			// Title of the meta box
			'title'         => 'Add a Subtitle',
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

		/************************************************************
		 * Configure each custom field, specifying its meta_key, default
		 * value, delete_state, and sanitizing function.
		 ***********************************************************/
		'custom_fields'     => array(
			'subtitle'      => array(
				'default'      => '',
				'delete_state' => '',
				'sanitize'     => 'sanitize_text_field',
			),
			'show_subtitle' => array(
				'default'      => 0,
				'delete_state' => 0,
				'sanitize'     => 'intval',
			),
		),

		/************************************************************
		 * Configure the absolute path to your meta box's view file.
		 ***********************************************************/
		'view' => METABOX_DIR . 'src/views/subtitle-meta-box.php',
	),
);
