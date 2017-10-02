<p>
	<label for="client_name"><?php _e( 'Client', 'mbbasics' ); ?></label>
	<input class="large-text" type="text" name="portfolio[client_name]" value="<?php esc_attr_e( $client_name ); ?>">
	<span class="description"><?php _e( 'Enter the client\'s name', 'mbbasics' ); ?></span>
</p>

<p>
	<label for="client_url"><?php _e( 'URL', 'mbbasics' ); ?></label>
	<input class="large-text" type="url" name="portfolio[client_url]" value="<?php echo esc_url( $client_url ); ?>">
	<span class="description"><?php _e( 'Enter the client\'s URL', 'mbbasics' ); ?></span>
</p>