<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

/**
 * Class One_And_One_Assistant_Handler_Login
 *
 * Configure the alternative login page
 */
class One_And_One_Assistant_Handler_Login {

	/**
	 * Initialize the redirections/hooks in the front-end
	 * N.B. new design & behaviour is an extra feature that needs to be activated
	 *
	 * @param boolean $design_enabled
	 */
	public static function init( $design_enabled = true ) {

		// Redirect to assistant after first login
		add_filter( 'login_redirect', array( 'One_And_One_Assistant_Handler_Login', 'automatic_redirect_after_login' ), 90, 3 );

		// Add the cookie script to control feature switches through JS
		add_action( 'login_enqueue_scripts', array( 'One_And_One_Assistant_Handler_Login', 'enqueue_cookie_script' ) );

		if ( $design_enabled ) {

			// Add the tweaks to adjust visual details
			add_filter( 'login_body_class', array( 'One_And_One_Assistant_Handler_Login', 'add_body_class' ) );
			add_filter( 'login_link_separator', array( 'One_And_One_Assistant_Handler_Login', 'remove_login_link_separator' ) );

			// Add the alternative login scripts
			add_action( 'login_enqueue_scripts', array( 'One_And_One_Assistant_Handler_Login', 'enqueue_scripts' ) );

			// Modify / Add some HTML components for the styles and animations to work
			add_action( 'login_header', function() { self::load_template_part( 'header' ); } );
			add_action( 'login_footer', function() { self::load_template_part( 'footer' ); } );
		}
	}

	/**
	 * Handle redirection to the Assistant after first login
	 * (Administrators only: other roles don't have the permissions to use the Assistant)
	 *
	 * @param  WP_User $logged_user
	 * @param  string  $redirect_to
	 * @return string
	 */
	public static function redirect_after_login( $logged_user, $redirect_to ) {

		$current_user_authorized = ( $logged_user instanceof WP_User && $logged_user->has_cap( 'manage_options' ) );

		if ( $current_user_authorized && get_option( 'oneandone_assistant_completed' ) == false ) {
			return admin_url( 'admin.php?page=1and1-wordpress-wizard&setup_action=greeting' );
		} else {
			return $redirect_to;
		}
	}

	/**
	 * Handle redirection to the Assistant after first login,
	 * in the WP Standard login (when the "redesign" feature is not activated)
	 *
	 * @param  string $redirect_to
	 * @return string
	 */
	public static function automatic_redirect_after_login( $redirect_to ) {
		global $user;
		return self::redirect_after_login( $user, $redirect_to );
	}

	/**
	 * Add the cookie script to control feature switches through JS
	 */
	public static function enqueue_cookie_script() {
		wp_enqueue_script( '1and1-wp-cookies', One_And_One_Assistant::get_js_url( 'cookies.js' ) );
	}

	/**
	 * Add the alternative login scripts
	 */
	public static function enqueue_scripts() {
		if ( ! self::is_interim_login_screen() ) {

			// Remove WP standard CSS
			wp_deregister_style( 'login' );

			// Queue WordPress CSS for dashicons
			wp_enqueue_style( 'dashicons' );
			
			// Add Assistant CSS and fonts
			One_And_One_Assistant_Handler_Dispatch::enqueue_assistant_styles( true );
			
			// Add Login specific CSS styles
			wp_enqueue_style( '1and1-wp-login', One_And_One_Assistant::get_css_url( 'login.css' ) );

			// Add JS for extra styling & queue WordPress JS for login forms
			wp_enqueue_script( '1and1-wp-login', One_And_One_Assistant::get_js_url( 'login.js' ), array( 'user-profile', 'jquery' ), null, true );
		}
	}

	/**
	 * Add the special CSS classes to the login page
	 *
	 * @param  array $classes
	 * @return array
	 */
	public static function add_body_class( $classes ) {
		$classes[] = 'assistant-page';
		$classes[] = 'branding';
		return $classes;
	}

	/**
	 * Remove the " | " login link separator
	 * (not needed in our design and breaking it anyway)
	 * 
	 * @param  string $separator
	 * @return string
	 */
	public static function remove_login_link_separator( $separator ) {
		return '';
	}

	/**
	 * Extends the login form HTML code with template parts
	 *
	 * @param string $template
	 */
	public static function load_template_part( $template ) {
		if ( ! self::is_interim_login_screen() ) {
			One_And_One_Assistant_View::load_template( 'parts/login-' . $template );
		}
	}

	/**
	 * Check if we aren't in the lightbox-login (interim login)
	 * that appears when you're in the back-end and your session times out
	 *
	 * @return boolean
	 */
	public static function is_interim_login_screen() {
		global $interim_login;
		return $interim_login;
	}
}
