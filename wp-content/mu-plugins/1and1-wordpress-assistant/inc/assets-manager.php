<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Assistant_Assets_Manager {

	/**
	 * @var string
	 */
	protected $site_type = '';

	/**
	 * @var One_And_One_Assistant_Cache_Manager
	 */
	protected $cache_manager;

	/**
	 * One_And_One_Assistant_Assets_Manager constructor.
	 *
	 * @param string $site_type
	 */
	public function __construct( $site_type ) {
		include_once( One_And_One_Assistant::get_inc_dir_path().'installer.php' );
		include_once( One_And_One_Assistant::get_inc_dir_path().'plugin-adapter.php' );

		$this->site_type = $site_type;
		$this->cache_manager = new One_And_One_Assistant_Cache_Manager();
	}

	/**
	 * Install and activate recommended plugins for the current site type
	 *
	 * @param array $selected_plugin_slugs
	 */
	public function setup_plugins( $selected_plugin_slugs ) {
		new One_And_One_Assistant_Plugin_Adapter();

		// Get plugins from the cache
		$plugins = $this->cache_manager->load_cache( 'plugin', $this->site_type );

		// Update already installed plugins
		$this->update_outdated_plugins( $selected_plugin_slugs );

		// Download and install missing plugins
		$this->install_missing_plugins( $selected_plugin_slugs, $plugins );

		// Activate the previously installed/updated plugins
		$this->activate_plugins( $selected_plugin_slugs );
	}

	/**
	 * Install and activate a recommended theme for the current site type,
	 * chosen by the user
	 *
	 * @param string $theme_slug
	 */
	public function setup_theme( $theme_slug ) {

		if ( ! empty( $theme_slug ) ) {
			$installed_themes = wp_get_themes();

			// Get theme download info and install it
			if ( ! array_key_exists( $theme_slug, $installed_themes ) ) {
				$themes_meta = $this->cache_manager->load_cache( 'theme', $this->site_type );
				One_And_One_Assistant_Installer::install_theme( $themes_meta[ $theme_slug ] );
			}
			
			// Activate theme
			switch_theme( $theme_slug );
		}
	}

	/**
	 * Update given set of plugins to the last version
	 *
	 * @param array $plugin_slugs
	 */
	public function update_outdated_plugins( $plugin_slugs ) {
		$plugin_info = get_site_transient( 'update_plugins' );

		if ( isset( $plugin_info->response ) ) {
			foreach ( $plugin_info->response as $plugin_path => $plugin ) {

				if ( in_array( $plugin->slug, $plugin_slugs ) ) {
					One_And_One_Assistant_Installer::update_plugin( $plugin_path );
				}
			}
		}
	}

	/**
	 * Install given set of plugins
	 *
	 * @param array $plugin_slugs
	 * @param array $plugin_data
	 */
	public function install_missing_plugins( $plugin_slugs, $plugin_data ) {
		
		$installed_plugins = get_plugins();
		$installed_plugin_slugs = array();

		foreach ( $installed_plugins as $plugin_path => $wp_plugin_data ) {
			$parts = explode( '/', $plugin_path );
			$installed_plugin_slugs[] = $parts[ 0 ];
		}

		foreach ( $plugin_slugs as $plugin_slug ) {

			if ( ! in_array( $plugin_slug, $installed_plugin_slugs ) ) {
				One_And_One_Assistant_Installer::install_plugin( $plugin_data[ $plugin_slug ] );
			}
		}
	}

	/**
	 * Activate a given set of plugins
	 * 
	 * @param array $plugin_slugs
	 */
	public function activate_plugins( $plugin_slugs ) {
		
		// Get plugins installation paths
		$plugin_paths = One_And_One_Assistant_Installer::get_plugin_installation_paths( $plugin_slugs );

		// Activate the previously installed plugins
		foreach ( $plugin_paths as $plugin_slug => $plugin_path ) {
			$plugin_base_path = plugin_basename( $plugin_path );
			
			try {

				// Fix for plugin stuff after installation (ex. in WooCommerce)
				do_action( 'oneandone_post_install_' . $plugin_slug, $plugin_slugs );

				// Plugin activation
				activate_plugin( $plugin_base_path );

				// Plugins state update
				$recent = ( array ) get_option( 'recently_activated' );
				unset( $recent[ $plugin_base_path ] );
				update_option( 'recently_activated', $recent );

				// Fix for plugin stuff after activation (ex. in WooCommerce)
				do_action( 'oneandone_post_activate_' . $plugin_slug, $plugin_slugs );
			}
			
			catch ( Exception $e ) {
				error_log( $e->getMessage() );
			}
		}
	}
}
