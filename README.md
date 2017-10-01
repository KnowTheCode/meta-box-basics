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

To use this plugin, you will need to do the following tasks:

1. Create a view file for your meta box.  Store that view file in the `src/views`.
2. Create a configuration file for your meta box.
    1. Copy the default configuration file found in `src/metadata/default`.
    2. Store it in `config/name-of-your-meta-box`.
    3. Specific a unique meta box ID in the `'your-meta-box-id'` key.  This key is used as the ID and for storage in the config store.
    4. Add the title.
    5. Add each of the custom fields in the `custom_fields` array.  Follow the instructions.
    6. Add the absolute path your view file into the `'view' => ''` value.
3. Add your meta box configuration file [here in the `bootstrap.php` file](https://github.com/KnowTheCode/meta-box-basics/blob/reusable/bootstrap.php#L79).
4. Repeat steps 1-3 for each meta box.    
