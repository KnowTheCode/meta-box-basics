<p>
	<label for="subtitle"><?php _e( 'Subtitle', 'mbbasics' ); ?></label>
	<input class="large-text" type="text" name="mbbasics[subtitle]" value="<?php esc_attr_e( $subtitle ); ?>">
	<span class="description"><?php _e( 'Enter the subtitle for this piece of content.', 'mbbasics' ); ?></span>
</p>

<p>
    <input type="checkbox" value="1" name="mbbasics[show_subtitle]" <?php checked( $show_subtitle, 1 ); ?>
    <label for="subtitle"><?php _e( 'Show Subtitle?', 'mbbasics' ); ?></label>
    <div><span class="description"><?php _e( 'Check if you want to show the subtitle for this article.', 'mbbasics' ); ?></span></div>
</p>