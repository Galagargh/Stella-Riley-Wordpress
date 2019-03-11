<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

/**
 * Class One_And_One_Assistant_View
 *
 * Provides a small WordPress-like view handler for our templates,
 * not perfect but still better than row PHP includes
 */
class One_And_One_Assistant_View {

	const ASSISTANT_VIEW_DIR = 'views';

	/**
	 * Return the path of a template given the template name (file name without extension)
	 *
	 * @param  string $template_name
	 * @return string
	 */
	public static function locate_template( $template_name ) {

		if ( ! $template_name ) {
			return '';
		}
		return self::get_default_views_path() . $template_name . '.php';
	}

	/**
	 * Include a located template and render attached variables available for use
	 *
	 * @param string $template_name
	 * @param array  $args
	 */
	public static function load_template( $template_name, $args = array() ) {

		if ( isset( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		require( self::locate_template( $template_name ) );
	}

	/**
	 * Default view path:
	 * /<html_dir>/wp-content/mu-plugins/1and1-wordpress-assistant/inc/views/
	 *
	 * @return string
	 */
	public static function get_default_views_path() {
		return One_And_One_Assistant::get_inc_dir_path() . self::ASSISTANT_VIEW_DIR . '/';
	}
}