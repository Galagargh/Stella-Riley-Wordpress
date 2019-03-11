<?php
$sitetype_transient = get_transient( 'oneandone_assistant_process_sitetype_user_' . get_current_user_id() );

if ( $sitetype_transient ) {
	$site_type = $sitetype_transient;
} else {
	$site_type = get_option( 'oneandone_assistant_sitetype', '' );
}

$popup_index = 0;

$themes = One_And_One_Assistant_Sitetype_Filter::get_filtered_themes(
	wp_prepare_themes_for_js(),
	$site_type
);
?>

<form action="" method="post" id="oneandone-install-form-<?php echo $site_type; ?>">

	<?php wp_nonce_field( 'activate' ) ?>

	<div>
		<?php foreach ( $themes as $theme ): ?>
			<?php
			if ( ! $theme['id'] ) {
				continue;
			}
			$popup_index ++;

			// Only first theme can be active set all other to false if cached version is used
			if ( array_key_exists( 'active', $theme ) && $theme['active'] == true && $popup_index == 1 ) {
				$theme_id = '';
				$theme_submit_label = esc_attr__( 'Keep Current Theme', '1and1-wordpress-wizard' );
			} else {
				$theme['active'] = false;
				$theme_id = $theme[ 'id' ];
				$theme_submit_label = esc_attr__( 'Select', '1and1-wordpress-wizard' );
			}

			?>

			<div class="oneandone-selectable-item <?php echo $theme['active'] == true ? 'active' : '' ?> ">

				<div class="oneandone-theme-screenshot"
				     onclick="showBox( '<?php echo $site_type . $popup_index; ?>', false, 900, 500 )">

					<img src="<?php echo $theme['screenshot'][0]; ?>" alt="">
				</div>

				<span class="oneandone-theme-more-details"
				      onclick="showBox( '<?php echo $site_type . $popup_index; ?>', false, 900, 500 )">

		                  <?php _e( 'More information', '1and1-wordpress-wizard' ) ?>
		            </span>

				<h3 class="oneandone-theme-name">
					<?php echo $theme['name']; ?>
				</h3>

				<div class="oneandone-theme-actions" style="-ms-filter:'alpha(Opacity=1)';opacity:1">
					<a href="#" class="button button-primary" onclick="startInstall( '<?php echo $site_type; ?>', '<?php echo $theme_id; ?>' )">
						<?php echo $theme_submit_label ?>
					</a>
				</div>

				<div id="<?php echo $site_type . $popup_index; ?>" style="display:none">
					<div class="oneandone-theme-info-box">

						<div class="oneandone-info-box-theme-screenshot">
							<?php if ( isset( $theme['screenshot'][0] ) && $theme['screenshot'][0] ) { ?>
								<div class="screenshot">
									<img src="<?php echo esc_url( $theme['screenshot'][0] ); ?>" alt="Screenshot"/></div>
							<?php } else { ?>
								<div class="screenshot blank"></div>
							<?php } ?>
						</div>

						<div class="oneandone-theme-info">

							<h3 class="oneandone-theme-name"><?php echo esc_html( $theme['name'] ); ?>
								<span class="oneandone-theme-version"><?php esc_html_e( 'Version: %s', '1and1-wordpress-wizard' ); ?></span>
							</h3>

							<h4 class="oneandone-theme-author"><?php printf( esc_html__( 'By %s', '1and1-wordpress-wizard' ), esc_html( $theme['author'] ) ); ?></h4>

							<p class="oneandone-theme-description">
								<?php
								if ( $theme['description'] ) {
									echo $theme['description'];
								}
								?>
							</p>

						</div>
					</div>

					<a href="#" style="position: absolute; bottom: 15px; right: 17px;" class="button button-primary" onclick="startInstall( '<?php echo $site_type; ?>', '<?php echo $theme_id; ?>' )">
						<?php echo $theme_submit_label ?>
					</a>
				</div>

			</div>
		<?php endforeach; ?>
	</div>

</form>