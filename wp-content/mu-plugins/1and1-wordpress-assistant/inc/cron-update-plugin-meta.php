<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class One_And_One_Cron_Update_Plugin_Meta {

	/**
	 * One_And_One_Cron_Update_Plugin_Meta constructor.
	 * (Set up cronjob only in Managed mode)
	 */
	public function __construct() {
		
		if ( oneandone_is_managed() ) {
			add_action( 'login_form', array( $this, 'setup_schedule' ) );
			add_action( 'oneandone_cron_update_meta_cache', array( $this, 'update_meta_cache' ) );
		}
	}

	public function setup_schedule() {
		if ( ! wp_next_scheduled( 'oneandone_cron_update_meta_cache' ) ) {
			wp_schedule_event( time(), 'daily', 'oneandone_cron_update_meta_cache' );
		}
	}

	public function update_meta_cache() {
		include_once 'cache-manager.php';
		include_once 'sitetype-filter.php';

		$cache_manager = new One_And_One_Assistant_Cache_Manager();
		$site_type_filter = new One_And_One_Assistant_Sitetype_Filter();
		
		$cache_manager->fill_cache( $site_type_filter );
	}
}

new One_And_One_Cron_Update_Plugin_Meta();
