<div class="wrap">
	<h1><?php esc_html_e( 'setup_assistant_header', '1and1-wordpress-wizard' ) ?></h1>

	<h2><?php esc_html_e( 'setup_assistant_completed', '1and1-wordpress-wizard' ); ?></h2>
	<p><?php _e( 'setup_assistant_installation_ready', '1and1-wordpress-wizard' ) ?></p>

	<?php do_action( 'oneandone_post_setup_custom' ); ?>

	<p>
		<a href="<?php echo admin_url( 'customize.php?return=' . urlencode( home_url() ) ); ?>" class="button button-primary" title="<?php esc_html_e( 'setup_assistant_customizer_link', '1and1-wordpress-wizard' ); ?>">
			<?php esc_html_e( 'setup_assistant_customizer_link', '1and1-wordpress-wizard' ); ?>
		</a>
		&nbsp; &nbsp;
		<a href="<?php echo admin_url( 'post-new.php' ); ?>" class="button" target="_parent"><?php esc_html_e( 'Write a post', '1and1-wordpress-wizard' ); ?></a>
		&nbsp; &nbsp;
		<a href="<?php echo admin_url( 'index.php' ); ?>" class="button" title="<?php _e( 'Go to Dashboard' ); ?>" target="_parent"><?php _e( 'Go to Dashboard' ); ?></a>
	</p>

	<script type="text/javascript">
		/* <![CDATA[ */
		jQuery(function ($) {
			$('.update-nag').hide();
			$('.is-dismissible').hide();
		});
		/* ]]> */
	</script>
</div>