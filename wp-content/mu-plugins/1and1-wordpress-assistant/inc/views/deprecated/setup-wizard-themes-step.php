<?php
add_thickbox();

$site_types = One_And_One_Assistant_Sitetype_Filter::get_sitetypes();
?>

<div class="wrap">
	<h1><?php esc_html_e( 'setup_assistant_header', '1and1-wordpress-wizard' ) ?></h1>

	<h2 class="clear"><?php esc_html_e( 'setup_assistant_design_title', '1and1-wordpress-wizard' ); ?></h2>
	<p><?php _e( 'setup_assistant_design_description', '1and1-wordpress-wizard' ); ?></p>

	<div class="oneandone-themes-browser">
		<ul class="site-type-selector">

			<?php if ( $site_types ): ?>
				<?php $site_type_count = 0; ?>

				<?php foreach ( $site_types as $site_type_id => $site_type ): ?>
					<li class="site-type">
						<a id="oneandone-site-type-<?php echo $site_type_id ?>" href="#">
							<?php _ex( $site_type[ 'headline' ], 'website-types', '1and1-wordpress-wizard' ); ?>
						</a>
					</li>

					<?php $site_type_count ++; ?>
				<?php endforeach; ?>

			<?php else: ?>

				<strong style="font-size:14px;">
					<?php esc_html_e( "The website types couldn't get retrieved. Please refresh the page.", '1and1-wordpress-wizard' ); ?>
				</strong>

			<? endif; ?>
		</ul>

		<div class="site-type-themes">
			<?php if ( $site_types ): ?>
				<?php $site_type_count = 0; ?>

				<?php foreach ( $site_types as $site_type_id => $site_type ): ?>
					<div id="oneandone-themes-<?php echo $site_type_id ?>" class="site-type-themes-box">
						<h3><?php _ex( $site_type[ 'headline' ], 'website-types', '1and1-wordpress-wizard' ); ?></h3>
						<p><?php echo esc_attr_x( $site_type[ 'description' ], 'website-types', '1and1-wordpress-wizard' ); ?></p>

						<div class="site-type-themes-list">
							<span class="spinner is-active"></span>
						</div>
					</div>

					<?php $site_type_count ++; ?>
				<?php endforeach; ?>

			<?php endif; ?>
		</div>
	</div>

	<div id="oneandone-setup-install" style="display:none;">
		<div class="oneandone-theme-info-box oneandone-loading">
			<h2>
				<span><?php esc_html_e( 'setup_assistant_progress_title', '1and1-wordpress-wizard' ); ?></span>
				<span class="spinner is-active"></span>
			</h2>
			<p><?php _e( 'setup_assistant_progress_desc', '1and1-wordpress-wizard' ); ?></p>
		</div>
	</div>
</div>