<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class One_And_One_Cron_Update_Deactivated_Plugins {

	public function __construct() {
		// Setup cronjob but only in Managed mode
		if ( oneandone_is_managed() ) {
			add_action( 'wp', array( $this, 'setup_schedule' ) );
			add_action( 'oneandone_cron_update_deactivated_plugins', array( $this, 'update_deactivated_plugins' ) );
		}
	}

	public function setup_schedule() {
		$recurrence = 'daily';

		if ( ! wp_next_scheduled( 'oneandone_cron_update_deactivated_plugins' ) ) {
			wp_schedule_event( time(), $recurrence, 'oneandone_cron_update_deactivated_plugins' );
		}
	}

	public function update_deactivated_plugins() {
		include_once ABSPATH.'wp-admin/includes/plugin.php';
		include_once ABSPATH.'wp-admin/includes/theme.php';
		include_once ABSPATH.'wp-includes/pluggable.php';
		include_once ABSPATH.'wp-admin/includes/file.php';
		include_once ABSPATH.'wp-admin/includes/misc.php';
		include_once ABSPATH.'wp-admin/includes/class-wp-upgrader.php';
		
		// Update deactivated themes
		register_theme_directory( WP_CONTENT_DIR.'/themes' );
		$upgrader = new Theme_Upgrader();
		$themes = wp_prepare_themes_for_js();
		$active_theme = wp_get_theme();

		wp_update_themes();

		foreach ( $themes as $theme ) {
			if ( $theme['id'] == $active_theme->get_template() ) {
				continue;
			}
			$upgrader->upgrade( $theme['id'], array( 'clear_update_cache' => false ) );
		}

		wp_update_themes();

		// Update deactivated plugins
		$upgrader = new Plugin_Upgrader();
		$plugins = get_plugins();

		wp_update_plugins();

		foreach ( $plugins as $key => $plugin ) {
			if ( is_plugin_active( $key ) ) {
				continue;
			}
			$upgrader->upgrade( $key, array( 'clear_update_cache' => false ) );
		}
		wp_update_plugins();
	}
}

new One_And_One_Cron_Update_Deactivated_Plugins();
