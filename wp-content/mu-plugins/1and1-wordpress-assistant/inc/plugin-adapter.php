<?php
/** Do not allow direct access! */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

/**
 * Class One_And_One_Assistant_Plugin_Adapter
 * Enhances the Assistant Interface according to which plugins have been installed
 */
class One_And_One_Assistant_Plugin_Adapter {

	/**
	 * One_And_One_Assistant_Plugin_Adapter constructor.
	 */
	public function __construct() {

		// Post-install action hooks
		add_action( 'oneandone_post_install_woocommerce', array( $this, 'install_woocommerce' ) );
		add_action( 'oneandone_post_install_woocommerce-germanized', array( $this, 'install_woocommerce_germanized' ) );

		// Post-activation action hooks
		add_action( 'oneandone_post_activate_woocommerce', array( $this, 'setup_woocommerce' ) );
		add_action( 'oneandone_post_activate_a3-lazy-load', array( $this, 'setup_a3_lazy_load' ) );
		add_action( 'oneandone_post_activate_nextgen-gallery', array( $this, 'setup_nextgen_gallery' ) );
		add_action( 'oneandone_post_activate_the-events-calendar', array( $this, 'setup_the_events_calendar' ) );
	}

	/**
	 * WooCommerce Plugin Installation
	 */
	public function install_woocommerce() {

		if ( ! function_exists( 'wc_get_screen_ids' ) ) {
			function wc_get_screen_ids() {
				return array();
			}
		}
	}

	/**
	 * WooCommerce Germanized Plugin Installation
	 * - fix WooCommerce Plugin dependency
	 */
	public function install_woocommerce_germanized( $plugins_to_activate ) {

		if ( in_array( 'woocommerce', $plugins_to_activate ) && function_exists( 'WC' ) ) {
			WC()->init();
		}
	}

	/**
	 * WooCommerce Plugin Setup
	 * - adds post-setup buttons for WooCommerce in the Assistant
	 */
	public function setup_woocommerce() {

        add_action( 'oneandone_post_setup_custom', function() {

	        printf(
		        '<a href="%1$s" class="button button-primary" title="%2$s" target="_parent">%2$s</a><p>%3$s</p>',
		        admin_url( 'index.php?page=wc-setup' ),
	            __( 'setup_assistant_woocommerce_configuration', '1and1-wordpress-wizard' ),
	            __( 'setup_assistant_woocommcerce_installation_ready', '1and1-wordpress-wizard' )
			);
		} );
	}

	/**
	 * a3 Lazy Load Plugin Setup
	 * - removes the automatic redirection
	 */
	public function setup_a3_lazy_load() {
		delete_option( 'a3_lazy_load_just_installed' );
	}

	/**
	 * NextGEN Gallery Plugin Setup
	 * - removes the automatic redirection
	 */
	public function setup_nextgen_gallery() {
		delete_option( 'fs_nextgen-gallery_activated' );
	}

	/**
	 * The Events Calendar Plugin Setup
	 * - removes the automatic redirection
	 */
	public function setup_the_events_calendar() {
		delete_transient( '_tribe_events_activation_redirect' );
	}
}
