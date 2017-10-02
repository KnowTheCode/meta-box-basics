# Reusable Meta Box

This repository is a reusable WordPress meta box plugin.  It contains a configuration store and meta box module.

To build this plugin and learn how it works, see the [Reusable Meta Box Lab](https://knowthecode.io/labs/reusable-meta-box-module) on [Know the Code](https://knowthecode.io).

## Installation

1. In terminal, navigate to `{path to your sandbox project}/wp-content/plugins`.
2. Then type in terminal: `git clone https://github.com/KnowTheCode/meta-box-basics.git reusable-meta-box -b reusable`.
3. Then type in terminal: `cd reusable-meta-box`
4. Log into your WordPress website.
5. Go to Plugins and activate the Reusable Meta Box plugin.

## Your Meta Boxes

Each meta box requires:

1. A view file
2. A configuration file

The view file is the custom fields' HTML, which renders inside of the meta box.

The configuration file holds the runtime configuration parameters that defines your meta box and its custom fields.

To use this plugin, you will need to do the following tasks:

1. Create a view file for your meta box.  Store that view file in the `src/views`.
2. Create a configuration file for your meta box.
3. Add your meta box configuration file to the list of files to [load in the `bootstrap.php` file](https://github.com/KnowTheCode/meta-box-basics/blob/reusable/bootstrap.php#L79).
4. Repeat steps 1-3 for each meta box.

### The View File

Each meta box requires a view file.  The view file is the custom fields' HTML, which renders inside of the meta box.

#### Grouping Custom Fields

All of the custom fields in a meta box are grouped together using a single meta box key.  That key groups all of the custom fields into one array when the post/page/custom post type is published or updated. 

The generator exposes a variable for you to use in your view file: `$meta_box_key`. Use it in the form field's name like this:

```
name="<?php echo $meta_box_key; ?>[your-meta-key]" 
```

where `your-meta-key` is the meta key to be stored in the database for this custom field.  For example, a text input field for a subtitle custom field would be:

```
<input class="large-text" type="text" name="<?php echo $meta_box_key; ?>[subtitle]" value="<?php esc_attr_e( $custom_fields['subtitle'] ); ?>">
```

#### Custom Field Value

The meta box generator will fetch the custom fields out of the database and then store them into `$custom_fields` and each is keyed by the meta key for that custom field.  Use that array to populate the values or text within each custom field in your view file.

For example, let's say you have a custom field named `subtitle`.  To fetch the value for it, you would do `$custom_fields['subtitle']`.  Don't forget to escape the values in your view file. 

#### Practical Example

Here is a completed example of a `subtitle` custom field:

```
<p>
	<label for="subtitle"><?php _e( 'Subtitle', 'your-text-domain' ); ?></label>
	<input class="large-text" type="text" name="<?php echo $meta_box_key; ?>[subtitle]" value="<?php esc_attr_e( $custom_fields['subtitle'] ); ?>">
	<span class="description"><?php _e( 'Enter the subtitle for this piece of content.', 'your-text-domain' ); ?></span>
</p>
```

### The Configuration File

Each meta box requires a configuration file.  This file holds the runtime configuration parameters that define the meta box and its custom fields.  It includes

- a unique ID for your meta box, which is used for both the ID when running `add_menu_box` and for storing in the Config Store.
- `add_meta_box` configuration for the title, screens, context, priority, and callback args.  See Codex for more information on each of [these parameters](https://developer.wordpress.org/reference/functions/add_meta_box/#parameters).
    - The callback is required, as it's handled by the meta box generator.
- custom fields configuration - each custom field requires:
    - a unique `meta_key`, which is used in the database to find this custom field.
    - a default value
    - a delete state, which the generator uses to know when to delete the custom field or save it into the database.  For example an empty string for a text input field would be deleted out of the database.
    - sanitize function, which is used to validate and sanitize the value before saving it into the database.
- the absolute path to the meta box's view file        

To create a configuration file for your meta box, follow these instructions:

1. Copy the default configuration file found in `src/metadata/default`.
2. Store it in `config/name-of-your-meta-box`.
3. Specific the unique meta box ID in the [`''unique-meta-box-id'` key](https://github.com/KnowTheCode/meta-box-basics/blob/reusable/src/metadata/default/meta-box-config.php#L19).  This key is used as the ID and for storage in the config store.
4. In the [`add_meta_box` section](https://github.com/KnowTheCode/meta-box-basics/blob/reusable/src/metadata/default/meta-box-config.php#L28), fill in the title.  Then customize any of the other parameters to your specific needs.
5. In the [custom fields section](https://github.com/KnowTheCode/meta-box-basics/blob/reusable/src/metadata/default/meta-box-config.php#L50), for each custom field, use the template provided and then:
    - Specify its meta key
    - Specify its default value
    - Specific its delete state
    - Specify its sanitizing function 
6. Add the absolute path your view file in the [view section](https://github.com/KnowTheCode/meta-box-basics/blob/reusable/src/metadata/default/meta-box-config.php#L67).
