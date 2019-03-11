<?php
/** Do not allow direct access! */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

/**
 * Class One_And_One_Setup_Wizard_Dispatcher
 * Computes and shows to the corresponding view of the Assistant in the WP Admin
 *
 * @deprecated use new One_And_One_Assistant_Handler_Dispatch instead
 */
class One_And_One_Setup_Wizard_Dispatcher {

	/**
	 * Start and configure the Wizard
	 */
	public static function admin_init() {

		// Configure AJAX hook for the themes loading
		add_action( 'wp_ajax_ajaxload', array( 'One_And_One_Setup_Wizard_Dispatcher', 'load_recommended_themes' ) );

		// Configure AJAX hook for the plugins & themes installation
		add_action( 'wp_ajax_ajaxinstall', array( 'One_And_One_Setup_Wizard_Dispatcher', 'install_plugins_and_themes' ) );

		// Create and configure the wizard page in the admin area
		add_action( 'admin_menu', array( 'One_And_One_Setup_Wizard_Dispatcher', 'add_admin_menu_wizard_page' ), 5 );
		add_action( 'admin_bar_menu', array ( 'One_And_One_Setup_Wizard_Dispatcher', 'add_admin_top_bar_wizard_menu' ), 70 );

		// Configure wizard-related actions in the admin
		add_action( 'admin_init', array( 'One_And_One_Setup_Wizard_Dispatcher', 'handle_wizard_params' ) );
	}

	/**
	 * Create and configure the wizard page in the admin area
	 * WP Hook https://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
	 */
	public static function add_admin_menu_wizard_page() {
		global $menu;

		$pos   = 50;
		$posp1 = $pos + 1;

		while ( isset( $menu[ $pos ] ) || isset( $menu[ $posp1 ] ) ) {
			$pos ++;
			$posp1 = $pos + 1;

			/** check that there is no menu at our level neither at ourlevel+1 because that will make us disappear in some case */
			if ( ! isset( $menu[ $pos ] ) && isset( $menu[ $posp1 ] ) ) {
				$pos = $pos + 2;
			}
		}

		add_menu_page(
			__( '1&1 WP Assistant', '1and1-wordpress-wizard' ),
			__( '1&1 WP Assistant', '1and1-wordpress-wizard' ),
			'manage_options',
			'1and1-wordpress-wizard',
			array( 'One_And_One_Setup_Wizard_Dispatcher', 'dispatch_wizard_actions' ),
			'none',
			$pos
		);

	}

	/**
	 * Add an extra menu item in the top admin bar
	 * https://codex.wordpress.org/Class_Reference/WP_Admin_Bar/add_menu
	 */
	public static function add_admin_top_bar_wizard_menu() {
		global $wp_admin_bar;

		if ( get_current_screen()->id == get_plugin_page_hookname( '1and1-wordpress-wizard', '' ) ) {
			$class = 'current';
		} else {
			$class = '';
		}

		$title_element = sprintf(
			"<span class='ab-icon'></span>" .
			"<span class='ab-label'>%s</span>",
			__( '1&1 WP Assistant', '1and1-wordpress-wizard' )
		);

		$wp_admin_bar->add_menu(
			array(
				'parent' => null,
				'id'     => '1and1-wordpress-wizard',
				'title'  => $title_element,
				'href'   => admin_url(
					add_query_arg( 'page', '1and1-wordpress-wizard', 'admin.php' )
				),
				'meta'   => array(
					'class' => $class
				)
			)
		);
	}

	/**
	 * Handle status change of the wizard anywhere in the admin area (via GET parameters)
	 * WP Hook https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
	 */
	public static function handle_wizard_params() {

		/** reset the wizard (restart from the beginning) */
		if ( isset( $_GET['1and1-wordpress-wizard-reset'] ) ) {
			delete_option( 'oneandone_assistant_completed' );
			delete_option( 'oneandone_assistant_sitetype' );
		}

		/** skip the wizard completely (the user won't be bother by it anymore) */
		if ( isset( $_GET['1and1-wordpress-wizard-cancel'] ) ) {
			update_option( 'oneandone_assistant_completed', true );
		}
	}

	/**
	 * Load the themes list for a given site type (AJAX)
	 */
	public static function load_recommended_themes() {

		if ( isset( $_POST[ 'site_type' ] ) ) {

			$site_type = sanitize_text_field( $_POST['site_type'] );
			$sitetype_transient = 'oneandone_assistant_process_sitetype_user_' . get_current_user_id();

			set_transient( $sitetype_transient, $site_type, 1200 );

			load_template( One_And_One_Assistant::get_views_dir_path() . 'deprecated/site-type-theme-list.php' );
		}
		die;
	}

	/**
	 * Install selected plugins and themes (AJAX)
	 */
	public static function install_plugins_and_themes() {

		$sitetype_transient = 'oneandone_assistant_process_sitetype_user_' . get_current_user_id();
		$theme_transient = 'oneandone_assistant_process_theme_user_' . get_current_user_id();

		if ( isset( $_POST['site_type'] ) && isset( $_POST['theme'] ) ) {

			/** Increase PHP limits to avoid timeouts and memory limits */
			@ini_set( 'error_reporting', 0 );
			@ini_set( 'memory_limit', '256M' );
			@set_time_limit( 300 );

			include_once( One_And_One_Assistant::get_inc_dir_path().'plugin-manager.php' );
			include_once( One_And_One_Assistant::get_inc_dir_path().'plugin-adapter.php' );

			$site_type = sanitize_text_field( $_POST['site_type'] );
			$theme  = sanitize_text_field( $_POST['theme'] );

			$plugin_manager = new One_And_One_Assistant_Plugin_Manager( $site_type );

			/** Check nonce */
			check_admin_referer( 'activate' );

			// Activate / install chosen theme
			$plugin_manager->setup_theme( $theme );

			// Activate / install recommended plugins with the chosen site type
			$plugin_slugs = One_And_One_Assistant_Sitetype_Filter::get_plugins_slugs( $site_type, 'recommended' );
			$plugin_manager->setup_plugins( $plugin_slugs );

			/** store assistant is completed */
			update_option( 'oneandone_assistant_completed', true );

			/** store website type in db */
			update_option( 'oneandone_assistant_sitetype', $site_type );

			// Store plugins and theme
			$plugins_imploded = implode( ',', $plugin_slugs );
			update_option( 'oneandone_assistant_plugins', $plugins_imploded );
			update_option( 'oneandone_assistant_theme', $theme );

			delete_transient( $sitetype_transient );
			delete_transient( $theme_transient );

			wp_send_json_success(
				array(
					'referer'  => 'admin.php?page=1and1-wordpress-wizard&setup_action=activate&site_type=' . $site_type
				)
			);
		}
	}

	/**
	 * Get current action and load corresponding view
	 * If something is missing show the start of the wizard
	 */
	public static function dispatch_wizard_actions() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Sorry, you do not have permission to access the 1&1 WP Assistant.', '1and1-wordpress-wizard' ) );
		}

		/** Add Wizard CSS stylesheet */
		wp_enqueue_style( '1and1-wp-wizard', One_And_One_Assistant::get_css_url( 'deprecated/wizard.css' ) );

		/** Load parameter for the current requested action */
		$action = isset( $_GET['setup_action'] ) ? $_GET['setup_action'] : 'choose_appearance';

		/** Dispatch action */
		switch ( $action ) {

			/** First step after login */
			default:
			case 'greeting':
				self::_greeting();
				break;

			/** Choose and install a theme */
			case 'choose_appearance':
				self::_choose_appearance();
				break;

			/** Installation and activation processes */
			case 'activate':
				self::_activate();
				break;
		}
	}

	/**
	 * First step after login
	 * (otherwise the Assistant opens directly the "Choose type" page)
	 */
	private static function _greeting() {

		include( One_And_One_Assistant::get_views_dir_path() . 'deprecated/setup-wizard-greeting-step.php' );
	}

	/**
	 * Choose use case
	 */
	private static function _choose_appearance() {

		// Add the assistant JS scripts for use case filter + installation
		wp_enqueue_script( '1and1-wp-assistant', One_And_One_Assistant::get_js_url( 'deprecated/wizard.js' ), array( 'jquery' ), false, true );

		// Configure the AJAX object for the assistant scripts
		wp_localize_script( '1and1-wp-assistant', 'ajax_assistant_object', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		) );

		load_template( One_And_One_Assistant::get_views_dir_path() . 'deprecated/setup-wizard-themes-step.php' );
	}

	/**
	 * Installation and activation processes
	 */
	private static function _activate() {

		// Render the final step of the wizard
		load_template( One_And_One_Assistant::get_views_dir_path() . 'deprecated/setup-wizard-final-step.php' );
	}
}