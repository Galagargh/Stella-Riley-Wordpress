<?php
/**
 * Plugin Name: Remove "Comments are closed"
 * Plugin URI: http://soderlind.no/archives/2012/01/11/wordpress-plugin-remove-comments-are-closed/
 * Description: On posts where comments are closed, the plugin will remove the text 'Comments are closed.' The plugin supports any languages/text domains, and will remove the text from themes and plugins.
 * Author: Per Soderlind
 * Version: 1.3.2
 * Author URI: http://soderlind.no/
 */

add_filter( 'gettext', 'ps_remove_comments_are_closed', 20, 3 );

/**
 * [ps_remove_comments_are_closed description]
 *
 * @author soderlind
 * @version 1.0
 * @param   string    $translated_text   The translated text.
 * @param   string    $untranslated_text The untranslated text.
 * @param   string    $domain            Text domain.
 * @return  string                       The translated text.
 */
function ps_remove_comments_are_closed( $translated_text, $untranslated_text, $domain ) {
	if ( 'Comments are closed.' == $untranslated_text ) {
		return '';
	}
	return $translated_text;
}
