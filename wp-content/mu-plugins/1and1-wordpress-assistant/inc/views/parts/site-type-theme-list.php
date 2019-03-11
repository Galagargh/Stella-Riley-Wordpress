<?php if ( ! empty( $themes ) && is_array( $themes ) ): ?>
	
	<form action="" method="post" id="oneandone-install-form-<?php echo $site_type; ?>">
		<?php wp_nonce_field( 'activate' ) ?>
	
	    <?php foreach ( $themes as $theme ): ?>
	        <?php
	            if ( empty( $theme['id'] ) ) {
	                continue;
	            }
	        ?>
	
	        <a class="theme" href="#" data-site-type="<?php echo $site_type; ?>" data-theme="<?php echo $theme[ 'id' ]; ?>">
	
	            <span class="theme-thumbnail">
	                <?php if ( $theme['active'] ): ?>
	                    <span class="theme-actived"><?php echo __( 'Active theme' ); ?></span>
	                <?php endif; ?>
	
	                <img src="<?php echo esc_url( $theme['screenshot_url'] ); ?>" alt="<?php esc_html_e( $theme['name'] ); ?>">
	                <span class="theme-name"><?php esc_html_e( $theme['name'] ); ?></span>
	
		            <?php if ( $theme['active'] ): ?>
			            <span class="theme-btn"><?php esc_html_e( 'Keep', '1and1-wordpress-wizard' ) ?></span>
	                <?php else: ?>
			            <span class="theme-btn"><?php esc_html_e( 'Install', '1and1-wordpress-wizard' ) ?></span>
	                <?php endif; ?>
	            </span>
	        </a>
	    <?php endforeach; ?>
	
	</form>
	
<?php endif; ?>