<?php One_And_One_Assistant_View::load_template( 'card/header-default' ); ?>

<div class="card-content">
	<div class="card-content-inner">
		<h2><?php esc_html_e( 'setup_assistant_design_title', '1and1-wordpress-wizard' ); ?></h2>
		<p><?php _e( 'setup_assistant_design_description', '1and1-wordpress-wizard' ); ?></p>
	</div>

	<div class="diys-toolbar">
		<div class="diys-active-category">
			<a href="#" class="diys-sidebar-menu-btn open">
				<?php esc_html_e( 'setup_assistant_design_menu_open', '1and1-wordpress-wizard' ) ?>
			</a>
			<span class="diys-sidebar-label">
				<?php esc_html_e( 'Website Type', '1and1-wordpress-wizard' ) ?>:
				<strong class="current-site-type"></strong>
			</span>
		</div>
	</div>

	<div class="themes-browser">
		<div class="diys-sidebar-wrapper">
			<div class="diys-sidebar">
				<div class="diys-sidebar-background">
					<div class="diys-sidebar-background-disc"></div>
				</div>
				<div class="diys-sidebar-tabs">
					<ul>
						<?php if ( ! empty( $site_types ) ): ?>

							<?php foreach ( $site_types as $site_type_id => $site_type ): ?>
								<li class="site-type">
									<a id="site-type-<?php echo $site_type_id ?>" href="#">
										<?php _ex( $site_type[ 'headline' ], 'website-types', '1and1-wordpress-wizard' ); ?>
									</a>
								</li>
							<?php endforeach; ?>

						<? endif; ?>
					</ul>
				</div>
			</div>
			<a href="#" class="diys-sidebar-menu-btn close">
				<?php esc_html_e( 'setup_assistant_design_menu_close', '1and1-wordpress-wizard' ) ?>
			</a>
		</div>

		<div class="theme-selector">
			<?php if ( ! empty( $site_types ) ): ?>

				<?php foreach ( $site_types as $site_type_id => $site_type ): ?>
					<div id="themes-<?php echo $site_type_id ?>" class="theme-list">
						<div class="theme-list-inner">
							<div class="progress"><?php _e( 'Loading&#8230;' ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>

			<?php endif; ?>
		</div>
	</div>
</div>

<?php
	One_And_One_Assistant_View::load_template( 'card/footer', array(
		'card_actions' => array(
			'left'  => array(),
			'right' => array(
				'skip-design' => array(
					'label' => esc_html__( 'Close', '1and1-wordpress-wizard' ),
					'class' => 'btn btn-link btn-secondary',
					'href'  => esc_url( wp_get_referer() ?: admin_url() )
				)
			)
		)
	) );
?>