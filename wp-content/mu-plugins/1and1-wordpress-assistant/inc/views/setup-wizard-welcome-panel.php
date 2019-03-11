<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Assistant_Welcome_Panel {

	/**
	 * Render the Welcome Panel HTML
	 *
	 * @param string $context
	 */
	public static function render( $context ) {

		/**
		 * The Dashboard Panel is shown on the WP Dashboard, when the user already went through the Assistant
		 * Follow-up step are then shown to the user like start blogging, manage widgets / menus, change theme / plugins
		 */
		if ( $context == 'dashboard-panel-second-run' ) {
			self::render_dashboard_panel( $context );

		/**
		 * The Simple Panel is shown on the WP Dashboard and on the WP Plugins page
		 * It shows a welcome message and invite the user to start the Assistant
		 */
		} else {
			self::render_simple_panel( $context );
		}
	}

	/**
	 * Render the Welcome Panel Dashboard:
	 * - Blogging links
	 * - Widgets / Menus management links
	 * - Theme / Plugins management links
	 *
	 * @param string $context
	 */
	public static function render_dashboard_panel( $context ) {
		
		$communityUrl = One_And_One_Assistant_Config::get( 'community_managed_' . One_And_One_Assistant::get_market(), 'links' );
		$cpApplicationsUrl = One_And_One_Assistant_Config::get( 'control_panel_applications_' . One_And_One_Assistant::get_market(), 'links' );
		?>

		<div id="oneandone-welcome-panel" class="updated welcome-panel oneandone-setup-panel oneandone-setup-panel_second">
			<div class="welcome-panel-content">
				<?php self::render_header_image(); ?>
				<a title="<?php esc_html_e( 'title_link_visual', '1and1-wordpress-wizard' ); ?>" href="<?php echo esc_url( admin_url( 'tools.php?page=1and1-wordpress-wizard' ) ); ?>">
					<div class="oneandone-setup-image"></div>
				</a>
				<div class="oneandone-setup-message">
					<h3><?php _ex( 'Managed WordPress', $context, '1and1-wordpress-wizard' ) ?></h3>
					<h2><?php _ex( 'Customize and upgrade your website now.', $context, '1and1-wordpress-wizard' ) ?></h2>
					<div class="panel-float-box first">
						<h4><?php _e( 'Next Steps', '1and1-wordpress-wizard' ); ?></h4>
						<ul>
							<?php if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_for_posts' ) ) : ?>
								<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">'.__( 'Edit your front page', '1and1-wordpress-wizard' ).'</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
								<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">'.__( 'Add additional pages' ).'</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
							<?php elseif ( 'page' == get_option( 'show_on_front' ) ) : ?>
								<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">'.__( 'Edit your front page' ).'</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
								<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">'.__( 'Add additional pages' ).'</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
								<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">'.__( 'Add a blog post' ).'</a>', admin_url( 'post-new.php' ) ); ?></li>
							<?php else : ?>
								<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">'.__( 'Write your first blog post', '1and1-wordpress-wizard' ).'</a>', admin_url( 'post-new.php' ) ); ?></li>
								<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">'.__( 'Add an About page', '1and1-wordpress-wizard' ).'</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
							<?php endif; ?>
							<li><?php printf( '<a href="%s" class="welcome-icon welcome-view-site">'.__( 'View your site' ).'</a>', home_url( '/' ) ); ?></li>
							<?php if ( self::is_product_domain() ) : ?>
								<li><?php printf( '<a href="%s" target="_blank" class="welcome-icon dashicons-admin-links">'.__( 'dashboard_change_domain', '1and1-wordpress-wizard' ).'</a>', $cpApplicationsUrl ); ?></li>
							<?php elseif ( ! is_ssl() ) : ?>
								<li><?php printf( '<a href="%s" target="_blank" class="welcome-icon dashicons-lock">'.__( 'dashboard_activate_ssl', '1and1-wordpress-wizard' ).'</a>', $cpApplicationsUrl ); ?></li>
							<?php endif; ?>
						</ul>
					</div>
					<div class="panel-float-box">
						<h4><?php _e( 'More Actions' ); ?></h4>
						<ul>
							<?php if ( current_theme_supports( 'widgets' ) || current_theme_supports( 'menus' ) ) : ?>
								<li>
									<div class="welcome-icon welcome-widgets-menus">
										<?php
										if ( current_theme_supports( 'widgets' ) && current_theme_supports( 'menus' ) ) {
											printf(
												__( 'Manage <a href="%1$s">widgets</a> or <a href="%2$s">menus</a>' ),
												admin_url( 'widgets.php' ), admin_url( 'nav-menus.php' )
											);
										} elseif ( current_theme_supports( 'widgets' ) ) {
											echo '<a href="'.admin_url( 'widgets.php' ).'">'.__( 'Manage widgets' ).'</a>';
										} else {
											echo '<a href="'.admin_url( 'nav-menus.php' ).'">'.__( 'Manage menus' ).'</a>';
										}
										?>
									</div>
								</li>
							<?php endif; ?>
							<?php if ( current_user_can( 'manage_options' ) ) : ?>
								<li><?php printf( '<a href="%s" class="welcome-icon welcome-comments">'.__( 'Turn comments on or off' ).'</a>', admin_url( 'options-discussion.php' ) ); ?></li>
							<?php endif; ?>
							<?php if ( $communityUrl ): ?>
								<li>
									<a href="<?php echo $communityUrl; ?>" target="_blank" class="welcome-icon welcome-learn-more"><?php esc_html_e( 'first_steps_community_link', '1and1-wordpress-wizard' ); ?></a>
								</li>
							<?php endif; ?>
							<li>
								<a href="<?php echo wp_customize_url(); ?>" class="welcome-icon dashicons-admin-appearance"><?php _e( 'customize_theme_in_widget', '1and1-wordpress-wizard' ); ?></a>
							</li>
						</ul>
					</div>
					<div class="panel-float-box last">
						<div class="oneandone-setup-links">
							<a title="<?php _ex( 'Bullet Point 1', $context, '1and1-wordpress-wizard' ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=1and1-wordpress-wizard' ) ); ?>" class="button button-hero oneandone-welcome-panel-get-started">
								<?php esc_html_e( 'dashboard_widget_website-type', '1and1-wordpress-wizard' ); ?>
							</a>
							<a title="<?php _ex( 'Bullet Point 2', $context, '1and1-wordpress-wizard' ); ?>" href="<?php echo esc_url( admin_url( 'themes.php' ) ); ?>" class="button button-hero oneandone-welcome-panel-get-started">
								<?php esc_html_e( 'dashboard_widget_design', '1and1-wordpress-wizard' ); ?>
							</a>
							<a title="<?php _ex( 'Bullet Point 3', $context, '1and1-wordpress-wizard' ); ?>" href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button button-hero oneandone-welcome-panel-get-started">
								<?php esc_html_e( 'dashboard_widget_plugins', '1and1-wordpress-wizard' ); ?>
							</a>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the simple Welcome Panel
	 * with welcome message & link to the Assistant
	 *
	 * @param string $context
	 */
	public static function render_simple_panel( $context ) {
		?>

		<div id="oneandone-welcome-panel" class="updated welcome-panel oneandone-setup-panel">
			<div class="welcome-panel-content">
				<?php self::render_header_image(); ?>
				<a title="<?php esc_html_e( 'title_link_visual', '1and1-wordpress-wizard' ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=1and1-wordpress-wizard' ) ); ?>">
					<div class="oneandone-setup-image"></div>
				</a>
				<div class="oneandone-setup-message">
					<h3><?php _ex( 'Managed WordPress', $context, '1and1-wordpress-wizard' ) ?></h3>
					<h2><?php _ex( 'Configure your WordPress in just a few steps.', $context, '1and1-wordpress-wizard' ) ?></h2>
					<p><?php _ex( 'Get started and optimize your website with selected themes and plugins.', $context, '1and1-wordpress-wizard' ) ?></p>
					<?php if ( $context != 'plugins-panel-second-run' ): ?>
						<div class="panel-float-box first">
							<div class="oneandone-setup-links">
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=1and1-wordpress-wizard' ) ); ?>" class="button button-primary button-hero oneandone-welcome-panel-get-started">
									<?php esc_html_e( 'dashboard_widget_start', '1and1-wordpress-wizard' ); ?>
								</a>
							</div>
						</div>
						<div class="panel-float-box">
							<div class="oneandone-setup-links">
								<form action="<?php echo esc_url( admin_url( 'admin.php?page=1and1-wordpress-wizard&setup_action=choose_appearance' ) ); ?>" method="post">
									<input type="submit" name="sitetype[eshop]"
									       value="<?php esc_html_e( 'dashboard_widget_shop_start', '1and1-wordpress-wizard' ); ?>"
									       class="button button-primary button-hero oneandone-welcome-panel-get-started" />
								</form>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Check if the current WP Domain is a product domain
	 * (If yes a link will be shown to redirect the user to the Control Panel, where a new domain can be assigned)
	 *
	 * @return boolean
	 */
	public static function is_product_domain() {

		$product_domains = array(
			'apps-1and1.net',
			'apps-1and1.com',
			'online.de',
			'live-website.com'
		);
		$domain = get_site_url();

		foreach ( $product_domains as $product_domain ) {
			if ( stripos( $domain, $product_domain ) !== false ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Render logo image tag
	 */
	public static function render_header_image() {
		$html = '';

		$header_image_src = One_And_One_Assistant_Config::get( 'logo_default', 'branding' );
		$header_image_alt = One_And_One_Assistant_Config::get( 'name', 'branding' );
		$header_image_link = One_And_One_Assistant_Config::get( 'homepage_' . One_And_One_Assistant::get_market(), 'links' );;

		if ( $header_image_src ) {
			
			$html = sprintf(
				'<img class="oneandone-setup-image-logo" src="%s" alt="%s">',
				$header_image_src,
				$header_image_alt
			);
		
			if ( $header_image_link ) {
			
				$html = sprintf(
					'<a title="%s" href="%s" target="_blank">%s</a>',
					$header_image_alt,
					$header_image_link,
					$html
				);
			}
		}
		
		echo $html;
	}
}
