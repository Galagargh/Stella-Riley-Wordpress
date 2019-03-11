<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Assistant_Modify_Settings_Page {

	public function __construct() {

		// hide home description
		if ( oneandone_is_managed() ) {
			add_action( 'admin_head', array( $this, 'change_home_description' ) );
		}
	}

	public function change_home_description() {
		global $pagenow;

		$cp_application_link = One_And_One_Assistant_Config::get(
			'control_panel_applications_' . One_And_One_Assistant::get_market(),
			'links'
		);
		
		if ( is_admin() && $pagenow == 'options-general.php' && $cp_application_link ) {
			
			$websiteUrlDescription = sprintf(
				__( 'Website-Url-Description', '1and1-wordpress-wizard' ),
				$cp_application_link,
				One_And_One_Assistant_Config::get( 'name', 'branding' )
			);

			?>
			<style type="text/css">
				#home-description {
					display: none;
				}
			</style>
			<script type="text/javascript">
				(function ($) {
					$(document).ready(function () {
						$('#siteurl').parent().append('<p class="description"><?php echo addslashes( $websiteUrlDescription ); ?></p>');
						$('#home').parent().append('<p class="description"><?php echo addslashes( $websiteUrlDescription ); ?></p>');
						$('.update-nag').hide();
					});
				})(jQuery);
			</script>
			<?php
		}
	}
}

new One_And_One_Assistant_Modify_Settings_Page();
