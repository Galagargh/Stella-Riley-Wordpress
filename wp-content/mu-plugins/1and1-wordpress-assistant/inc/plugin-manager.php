<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Assistant_Plugin_Manager {

	protected $site_type = '';

	protected $plugin_meta = array();

	/**
	 * One_And_One_Assistant_Plugin_Manager constructor.
	 *
	 * @param string $site_type
	 */
	public function __construct( $site_type ) {
		$this->site_type = $site_type;
	}

	/**
	 * Install and activate recommended plugins for the current site type
	 *
	 * @param array $plugin_slugs
	 */
	public function setup_plugins( $plugin_slugs ) {
		include_once( One_And_One_Assistant::get_inc_dir_path().'installer.php' );
		include_once( One_And_One_Assistant::get_inc_dir_path().'plugin-adapter.php' );

		new One_And_One_Assistant_Plugin_Adapter();

		// If not cached plugins available, then update cache file
		$plugin_meta_filename = One_And_One_Assistant::get_plugin_dir_path() . 'cache/plugin-'. $this->site_type . '-meta.txt';

		// Create cache file if not created yet
		if ( ! file_exists( $plugin_meta_filename ) ) {
			$this->update_plugin_meta_cache( $plugin_slugs, array( $this->site_type ) );
		}

		// Get plugin infos
		$this->plugin_meta = $this->get_plugin_meta();

		// Update already installed plugins
		$this->update_outdated_plugins( $plugin_slugs );

		// Download and install missing plugins
		$this->install_missing_plugins( $plugin_slugs );

		// Activate the previously installed plugins
		foreach ( One_And_One_Assistant_Installer::get_plugin_installation_paths( $plugin_slugs ) as $plugin_path => $plugin_slug ) {

            try {
                $is_active_plugin = is_plugin_active( $plugin_slug );

                if ( ! $is_active_plugin ) {

                    // Fix for plugin stuff after installation (ex. in WooCommerce)
	                do_action( 'oneandone_post_install_' . $plugin_slug, $plugin_slugs );

                    $result = activate_plugin( plugin_basename( $plugin_path ) );

                    if ( is_wp_error( $result ) ) {
                        if ( ! empty( $result->errors['plugin_not_found'][0] ) ) {
                            error_log( $result->errors['plugin_not_found'][0] );
                        }
                    }
                    $is_active_plugin = true;
                }

                if ( $is_active_plugin ) {

                    // Fix for plugin stuff after activation (ex. in WooCommerce)
	                do_action( 'oneandone_post_activate_' . $plugin_slug, $plugin_slugs );
                }
            }
            catch ( Exception $e ) {
                error_log( $e->getMessage() );
            }
		}
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
			$themes_meta = $this->get_theme_meta();

			if ( ! array_key_exists( $theme_slug, $installed_themes ) ) {

				include_once( One_And_One_Assistant::get_inc_dir_path() . 'installer.php' );
				One_And_One_Assistant_Installer::install_theme( $themes_meta[ $theme_slug ] );
			}

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
	 */
	public function install_missing_plugins( $plugin_slugs ) {

		$plugins = get_plugins();
		$plugins_installed = array();

		foreach ( $plugins as $plugin_path => $plugin ) {
			$parts = explode( '/', $plugin_path );
			$plugins_installed[] = $parts[ 0 ];
		}

		foreach ( $plugin_slugs as $plugin_slug ) {

			if ( ! in_array( $plugin_slug, $plugins_installed ) ) {
				One_And_One_Assistant_Installer::install_plugin( $this->plugin_meta[ $plugin_slug ] );
			}
		}
	}

	/**
	 * @param array $plugins_all
	 * @param array $sitetype
	 */
	public function update_plugin_meta_cache( $plugins_all, $sitetype = array() ) {
		include_once One_And_One_Assistant::get_plugin_dir_path().'inc/cron-update-plugin-meta.php';

		$cron_class = new One_And_One_Cron_Update_Plugin_Meta();
		$cron_class->update_plugin_meta( $plugins_all, $sitetype );
	}

	/**
	 * @return array
	 */
	public function get_plugin_meta() {
		$plugins              = array();
		$plugin_meta_filename = One_And_One_Assistant::get_plugin_dir_path().'cache/plugin-' . $this->site_type . '-meta.txt';

		if ( file_exists( $plugin_meta_filename ) ) {
			$plugins = unserialize( file_get_contents( $plugin_meta_filename ) );
		}

		return $plugins;
	}

	/**
	 * @return array
	 */
	public function get_theme_meta() {
		$themes              = array();
		$theme_meta_filename = One_And_One_Assistant::get_plugin_dir_path() . 'cache/theme-'. $this->site_type . '-meta.txt';

		if ( file_exists( $theme_meta_filename ) ) {
			$themes = unserialize( file_get_contents( $theme_meta_filename ) );
		}

		return $themes;
	}
}
