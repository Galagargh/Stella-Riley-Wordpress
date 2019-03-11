<?php
/*
Plugin Name:  WP Assistant
Plugin URI:   http://www.1and1.com
Description:  WordPress Setup Assistant
Version:      5.2.0
License:      GPLv2 or later
Author:       1&1
Author URI:   http://www.1and1.com
Text Domain:  1and1-wordpress-wizard
Domain Path:  /languages
*/

/*
Copyright 2016 1&1
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Online: http://www.gnu.org/licenses/gpl.txt
*/

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Assistant {
	const VERSION = '5.0.0';

	public function __construct() {
		$this->load_global_files();

		One_And_One_Assistant_Handler_Login::init(
			One_And_One_Assistant_Config::feature( 'login_redesign' )
		);

		/** admin actions */
		if ( is_admin() ) {
			$this->load_admin_files();

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles_scripts' ) );

			// shows the "get started" message as a seperate panel above the welcome panel
			add_action( 'admin_notices', array( $this, 'show_welcome_panel' ) );

			// Start and configure the Wizard in the admin area
			One_And_One_Assistant_Handler_Dispatch::admin_init();

			// add checks on plugin activation
			register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );

			// show info on update page
			add_action( 'admin_notices', array( $this, 'show_update_info' ) );

			// hide welcome panel
			add_action( 'load-index.php', array( $this, 'hide_welcome_screen' ) );

			// hide welcome panel option
			add_action( 'admin_head', array( $this, 'hide_welcome_screen_option' ) );

			// register deactivation hook
			register_deactivation_hook( __FILE__, array( $this, 'deactivation_hook' ) );

		/** front-end actions */
		} else {
			$this->load_frontend_files();

			add_action( 'wp_enqueue_scripts', array( $this, 'add_styles_scripts' ) );

			// add some Assistant elements on the front-end
			One_And_One_Assistant_Handler_Dispatch::wp_init();
		}
	}

	public function load_global_files() {
		include_once 'inc/config.php';
		include_once 'inc/view.php';
		include_once 'inc/functions.php';
		include_once 'inc/handlers/login.php';
		include_once 'inc/handlers/dispatch.php';
	}

	public function load_admin_files() {
		include_once 'inc/modify-settings-page.php';
		include_once 'inc/modify-plugins-page.php';
		include_once 'inc/sitetype-filter.php';
		include_once 'inc/cache-manager.php';
		include_once 'inc/dashboard-widget.php';
	}

	public function load_frontend_files() {
		include_once 'inc/cron-update-deactivated-plugins.php';
		include_once 'inc/cron-update-plugin-meta.php';
	}

	public function deactivation_hook() {
		wp_clear_scheduled_hook( 'oneandone_cron_update_deactivated_plugins' );
		wp_clear_scheduled_hook( 'oneandone_cron_update_plugin_meta' );
		delete_option( 'oneandone_assistant_completed' );
		delete_option( 'oneandone_assistant_sitetype' );
	}

	public function hide_welcome_screen_option() {
		echo '<style>[for="wp_welcome_panel-hide"] {display: none !important;}</style>';
	}

	public function hide_welcome_screen() {
		$user_id = get_current_user_id();

		if ( 1 == get_user_meta( $user_id, 'show_welcome_panel', true ) ) {
			update_user_meta( $user_id, 'show_welcome_panel', 0 );
		}
	}

	public function load_textdomain() {
		if ( oneandone_is_must_use_plugin_folder() ) {
			$language_loaded = load_muplugin_textdomain( '1and1-wordpress-wizard', basename( dirname( __FILE__ ) ).'/languages' );
		} else {
			$language_loaded = load_plugin_textdomain( '1and1-wordpress-wizard', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		// Check whether language could be loaded properly. If not, use en_US as a fallback.
		if ( ! empty( $language_loaded ) || false === $language_loaded ) {
			if ( oneandone_is_must_use_plugin_folder() ) {
				$plugin_dir = WPMU_PLUGIN_DIR;
			} else {
				$plugin_dir = WP_PLUGIN_DIR;
			}

			$domain = '1and1-wordpress-wizard';
			$path   = trailingslashit( $plugin_dir.'/'.ltrim( dirname( plugin_basename( __FILE__ ) ).'/languages/', '/' ) );
			$mofile = $domain.'-en_US.mo';

			load_textdomain( $domain, $path . $mofile );
		}
	}

	public function add_styles_scripts() {

		// Welcome Panel CSS
		wp_enqueue_style( '1and1-wizard-welcome', self::get_css_url( 'welcome-panel.css' ), array(), self::VERSION );

		// Add the cookie script to control feature switches through JS
		wp_enqueue_script( '1and1-wp-cookies', One_And_One_Assistant::get_js_url( 'cookies.js' ) );
	}

	public function show_update_info() {
		// Check user permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! oneandone_is_managed() ) {
			return;
		}

		// Check if we are in the dashboard
		$current_screen = get_current_screen()->id;

		if ( in_array( $current_screen, array( 'update-core' ) ) ) {
			include( One_And_One_Assistant::get_views_dir_path().'setup-wizard-welcome-panel.php' );
			$communityUrl = One_And_One_Assistant_Config::get( 'community_managed_' . One_And_One_Assistant::get_market(), 'links' );

			if ( $communityUrl ) {
				?>
				<div id="oneandone-welcome-panel" class="updated welcome-panel oneandone-update-panel">
					<div class="update-panel-content" style="margin-left: 0;">
						<div class="update-panel-first">
							<?php
								echo sprintf(
									_x( 'Your WordPress installation ...', 'update-core-info', '1and1-wordpress-wizard' ),
									$communityUrl,
									One_And_One_Assistant_Config::get( 'name', 'branding' )
								);
							?>
						</div>
						<div class="update-panel-logo">
							<?php One_And_One_Assistant_Welcome_Panel::render_header_image(); ?>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}

	/**
	 * Render the Welcome Panel HTML in:
	 * - Dashboard
	 * - Plugins page
	 */
	public function show_welcome_panel() {
		/** Check user permissions */
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		/** Check the current WP admin page */
		$wp_current_screen = get_current_screen()->id;

		/** Check the current Assistant status */
		$is_assistant_completed = ( get_option( 'oneandone_assistant_completed' ) == true );

		/** Set up the Welcome Panel context (= which version to show) */
		switch ( $wp_current_screen ) {
			case 'dashboard':
				if ( $is_assistant_completed ) {
					$context = 'dashboard-panel-second-run';
				} else {
					$context = 'dashboard-panel-first-run';
				}
				break;

			case 'plugins':
				if ( $is_assistant_completed ) {
					$context = 'plugins-panel-second-run';
				} else {
					$context = 'plugins-panel-first-run';
				}
				break;

			default:
				$context = false;
				break;
		}

		/** Render the Welcome Panel */
		if ( $context ) {
			include( One_And_One_Assistant::get_views_dir_path().'setup-wizard-welcome-panel.php' );
			One_And_One_Assistant_Welcome_Panel::render( $context );
		}
	}

	public static function get_site_type_label( $site_type ) {
		switch ( $site_type ) {
			case 'gallery':
				$site_type = _x( 'Gallery', 'website-types', '1and1-wordpress-wizard' );
				break;
			case 'blog':
				$site_type = _x( 'Blog', 'website-types', '1and1-wordpress-wizard' );
				break;
			case 'personal':
				$site_type = _x( 'Personal Website', 'website-types', '1and1-wordpress-wizard' );
				break;
			case 'business':
				$site_type = _x( 'Business Website', 'website-types', '1and1-wordpress-wizard' );
				break;
		}

		return $site_type;
	}

	public function activate_plugin() {
		// Check WordPress version
		if ( version_compare( get_bloginfo( 'version' ), '3.5', '<' ) ) {
			die( __( 'The Assistant could not be activated. To activate the plugin, you need WordPress 3.5 or higher.', '1and1-wordpress-wizard' ) );
		}
	}

	/**
	 * Return the contract's market value provided by the installation
	 *
	 * @return string
	 */
	public static function get_market() {

		$default_market = 'US';
		$supported_markets = array( 'DE', 'CA', 'GB', 'US', 'ES', 'MX', 'FR', 'IT' );
		
		$market = ( string ) strtoupper( get_option( 'assistant_market', $default_market ) );

		// fix wrong market code for GB
		if ( ! $market || ! in_array( $market, $supported_markets ) ) {
			$market = $default_market;
		}

		return $market;
	}

	public static function get_css_url( $file = '' ) {
		return plugins_url( 'css/'.$file, __FILE__ );
	}

	public static function get_js_url( $file = '' ) {
		return plugins_url( 'js/'.$file, __FILE__ );
	}

	public static function get_images_url( $image = '' ) {
		return plugins_url( 'images/'.$image, __FILE__ );
	}


	public static function get_plugin_file_path() {
		return __FILE__;
	}

	public static function get_plugin_dir_path() {
		return apply_filters( '1and1-plugin-dir-path', plugin_dir_path( __FILE__ ) );
	}

	public static function get_inc_dir_path() {
		return plugin_dir_path( __FILE__ ).'inc/';
	}

	public static function get_views_dir_path() {
		return One_And_One_Assistant_View::get_default_views_path();
	}

	public static function get_abspath() {
		return apply_filters( '1and1-abspath', ABSPATH );
	}
}

$one_and_one_wizard = new One_And_One_Assistant();
