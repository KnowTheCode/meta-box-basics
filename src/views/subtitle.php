<p>
	<label for="subtitle"><?php _e( 'Subtitle', 'mbreusable' ); ?></label>
	<input class="large-text" type="text" name="<?php echo $meta_box_id; ?>[subtitle]" value="<?php esc_attr_e( $custom_fields['subtitle'] ); ?>">
	<span class="description"><?php _e( 'Enter the subtitle for this piece of content.', 'mbreusable' ); ?></span>
</p>

<p>
    <input type="checkbox" value="1" name="<?php echo $meta_box_id; ?>[show_subtitle]" <?php checked( $custom_fields['show_subtitle'], 1 ); ?>
    <label for="subtitle"><?php _e( 'Show Subtitle?', 'mbreusable' ); ?></label>
    <div><span class="description"><?php _e( 'Check if you want to show the subtitle for this article.', 'mbreusable' ); ?></span></div>
</p>
