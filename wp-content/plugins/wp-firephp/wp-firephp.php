<?php
/**
 * Plugin Name: WP FirePHP
 * Plugin URI:  http://bueltge.de/
 * Description: Work with FirePHP and a sample af values of WordPress
 * Author:      Frank B&uuml;ltge
 * Version:     0.3
 * License:     GPLv3
 * Author URI:  http://bueltge.de/
 */

// avoid direct calls to this file, because now WP core and framework has been used
if ( ! function_exists('add_filter') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}
// error reporting
if ( error_reporting(E_ALL) )
	error_reporting(E_ALL ^ E_NOTICE);

if ( ! class_exists('WPFirePHP') ) {
	
	class WPFirePHP {
	
		function WPFirePHP() {
			
			add_action( 'admin_init', array( $this, 'fb_exit' ) );
			add_action( 'admin_init', array( $this, 'add_scripts' ) );
		}
		
		function add_scripts() {
			
			wp_register_script(
				'jquery-debug',
				plugins_url( '/js/jquery.debug.js', __FILE__ ),
				array( 'jquery' ) ,
				'1.3.1', 
				TRUE
			);
			
			wp_register_script(
				'my-jquery-debug',
				plugins_url( '/js/my.debug.js', __FILE__ ),
				array('jquery-debug'),
				'1.3.1',
				TRUE
			);
			wp_enqueue_script( 'my-jquery-debug' );
		}
		
		function fb_exit() {
			global $wp_version, $wp_roles, $wp_constants,
			       $wp_globals_a_l, $wp_globals_m_r, $wp_globals_s_v,
			       $wp_globals_w, $data, $update_options;
		
			if ( ! class_exists('FB') )
				require_once( 'FirePHPCore/fb.php' );
			
			$firephp = FirePHP::getInstance( TRUE );
			
			$wp_constants = array(
			'ABSPATH'                            => ABSPATH,
			'ADMIN_COOKIE_PATH'                  => ADMIN_COOKIE_PATH,
			'APP_REQUEST'                        => APP_REQUEST,
			'ARRAY_A'                            => ARRAY_A,
			'ARRAY_N'                            => ARRAY_N,
			'ATOM'                               => ATOM,
			'AUTH_COOKIE'                        => AUTH_COOKIE,
			'AUTH_KEY'                           => AUTH_KEY,
			'AUTOSAVE_INTERVAL'                  => AUTOSAVE_INTERVAL,
			'COMMENTS_TEMPLATE'                  => COMMENTS_TEMPLATE,
			'COOKIEHASH'                         => COOKIEHASH,
			'COOKIEPATH'                         => COOKIEPATH,
			'COOKIE_DOMAIN'                      => COOKIE_DOMAIN,
			'CRLF'                               => CRLF,
			'CUSTOM_TAGS'                        => CUSTOM_TAGS,
			'DB_CHARSET'                         => DB_CHARSET,
			'DB_COLLATE'                         => DB_COLLATE,
			'DB_HOST'                            => DB_HOST,
			'DB_NAME'                            => DB_NAME,
			'DB_PASSW'                           => DB_PASSW,
			'DB_PASSWORD'                        => DB_PASSWORD,
			'DB_USER'                            => DB_USER,
			'DOING_AJAX'                         => DOING_AJAX,
			'DOING_AUTOSAVE'                     => DOING_AUTOSAVE,
			'DOING_CRON'                         => DOING_CRON,
			'EP_ALL'                             => EP_ALL,
			'EP_ATTACHMENT'                      => EP_ATTACHMENT,
			'EP_AUTHORS'                         => EP_AUTHORS,
			'EP_CATEGORIES'                      => EP_CATEGORIES,
			'EP_COMMENTS'                        => EP_COMMENTS,
			'EP_DATE'                            => EP_DATE,
			'EP_DAY'                             => EP_DAY,
			'EP_MONTH'                           => EP_MONTH,
			'EP_NONE'                            => EP_NONE,
			'EP_PAGES'                           => EP_PAGES,
			'EP_PERMALINK'                       => EP_PERMALINK,
			'EP_ROOT'                            => EP_ROOT,
			'EP_SEARCH'                          => EP_SEARCH,
			'EP_TAGS'                            => EP_TAGS,
			'EP_YEAR'                            => EP_YEAR,
			'EZSQL_VERSION'                      => EZSQL_VERSION,
			'FORCE_SSL_ADMIN'                    => FORCE_SSL_ADMIN,
			'FORCE_SSL_LOGIN'                    => FORCE_SSL_LOGIN,
			'FTP_ASCII'                          => FTP_ASCII,
			'FTP_AUTOASCII'                      => FTP_AUTOASCII,
			'FTP_BINARY'                         => FTP_BINARY,
			'FTP_FORCE'                          => FTP_FORCE,
			'IS_PROFILE_PAGE'                    => IS_PROFILE_PAGE,
			'JSON_BOOL'                          => JSON_BOOL,
			'JSON_END_ARRAY'                     => JSON_END_ARRAY,
			'JSON_END_OBJ'                       => JSON_END_OBJ,
			'JSON_FLOAT'                         => JSON_FLOAT,
			'JSON_INT'                           => JSON_INT,
			'JSON_IN_ARRAY'                      => JSON_IN_ARRAY,
			'JSON_IN_BETWEEN'                    => JSON_IN_BETWEEN,
			'JSON_IN_OBJECT'                     => JSON_IN_OBJECT,
			'JSON_KEY'                           => JSON_KEY,
			'JSON_NULL'                          => JSON_NULL,
			'JSON_SKIP'                          => JSON_SKIP,
			'JSON_START_ARRAY'                   => JSON_START_ARRAY,
			'JSON_START_OBJ'                     => JSON_START_OBJ,
			'JSON_STR'                           => JSON_STR,
			'LANGDIR'                            => LANGDIR,
			'LOGGED_IN_COOKIE'                   => LOGGED_IN_COOKIE,
			'LOGGED_IN_KEY'                      => LOGGED_IN_KEY,
			'MAGPIE_CACHE_AGE'                   => MAGPIE_CACHE_AGE,
			'MAGPIE_CACHE_DIR'                   => MAGPIE_CACHE_DIR,
			'MAGPIE_CACHE_FRESH_ONLY'            => MAGPIE_CACHE_FRESH_ONLY,
			'MAGPIE_CACHE_ON'                    => MAGPIE_CACHE_ON,
			'MAGPIE_DEBUG'                       => MAGPIE_DEBUG,
			'MAGPIE_FETCH_TIME_OUT'              => MAGPIE_FETCH_TIME_OUT,
			'MAGPIE_INITALIZED'                  => MAGPIE_INITALIZED,
			'MAGPIE_USER_AGENT'                  => MAGPIE_USER_AGENT,
			'MAGPIE_USE_GZIP'                    => MAGPIE_USE_GZIP,
			'MAX_EXECUTION_TIME'                 => MAX_EXECUTION_TIME,
			'MAX_RESULTS'                        => MAX_RESULTS,
			'MC_LOGGER_DEBUG'                    => MC_LOGGER_DEBUG,
			'MC_LOGGER_ERROR'                    => MC_LOGGER_ERROR,
			'MC_LOGGER_FATAL'                    => MC_LOGGER_FATAL,
			'MC_LOGGER_INFO'                     => MC_LOGGER_INFO,
			'MC_LOGGER_WARN'                     => MC_LOGGER_WARN,
			'OBJECT'                             => OBJECT,
			'OBJECT_K'                           => OBJECT_K,
			'PASS_COOKIE'                        => PASS_COOKIE,
			'PCLZIP_ATT_FILE_NAME'               => PCLZIP_ATT_FILE_NAME,
			'PCLZIP_ATT_FILE_NEW_FULL_NAME'      => PCLZIP_ATT_FILE_NEW_FULL_NAME,
			'PCLZIP_ATT_FILE_NEW_SHORT_NAME'     => PCLZIP_ATT_FILE_NEW_SHORT_NAME,
			'PCLZIP_CB_POST_ADD'                 => PCLZIP_CB_POST_ADD,
			'PCLZIP_CB_POST_DELETE'              => PCLZIP_CB_POST_DELETE,
			'PCLZIP_CB_POST_EXTRACT'             => PCLZIP_CB_POST_EXTRACT,
			'PCLZIP_CB_POST_LIST'                => PCLZIP_CB_POST_LIST,
			'PCLZIP_CB_PRE_ADD'                  => PCLZIP_CB_PRE_ADD,
			'PCLZIP_CB_PRE_DELETE'               => PCLZIP_CB_PRE_DELETE,
			'PCLZIP_CB_PRE_EXTRACT'              => PCLZIP_CB_PRE_EXTRACT,
			'PCLZIP_CB_PRE_LIST'                 => PCLZIP_CB_PRE_LIST,
			'PCLZIP_ERROR_EXTERNAL'              => PCLZIP_ERROR_EXTERNAL,
			'PCLZIP_ERR_ALREADY_A_DIRECTORY'     => PCLZIP_ERR_ALREADY_A_DIRECTORY,
			'PCLZIP_ERR_BAD_CHECKSUM'            => PCLZIP_ERR_BAD_CHECKSUM,
			'PCLZIP_ERR_BAD_EXTENSION'           => PCLZIP_ERR_BAD_EXTENSION,
			'PCLZIP_ERR_BAD_EXTRACTED_FILE'      => PCLZIP_ERR_BAD_EXTRACTED_FILE,
			'PCLZIP_ERR_BAD_FORMAT'              => PCLZIP_ERR_BAD_FORMAT,
			'PCLZIP_ERR_DELETE_FILE_FAIL'        => PCLZIP_ERR_DELETE_FILE_FAIL,
			'PCLZIP_ERR_DIRECTORY_RESTRICTION'   => PCLZIP_ERR_DIRECTORY_RESTRICTION,
			'PCLZIP_ERR_DIR_CREATE_FAIL'         => PCLZIP_ERR_DIR_CREATE_FAIL,
			'PCLZIP_ERR_FILENAME_TOO_LONG'       => PCLZIP_ERR_FILENAME_TOO_LONG,
			'PCLZIP_ERR_INVALID_ARCHIVE_ZIP'     => PCLZIP_ERR_INVALID_ARCHIVE_ZIP,
			'PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE' => PCLZIP_ERR_INVALID_ATTRIBUTE_VALUE,
			'PCLZIP_ERR_INVALID_OPTION_VALUE'    => PCLZIP_ERR_INVALID_OPTION_VALUE,
			'PCLZIP_ERR_INVALID_PARAMETER'       => PCLZIP_ERR_INVALID_PARAMETER,
			'PCLZIP_ERR_INVALID_ZIP'             => PCLZIP_ERR_INVALID_ZIP,
			'PCLZIP_ERR_MISSING_FILE'            => PCLZIP_ERR_MISSING_FILE,
			'PCLZIP_ERR_MISSING_OPTION_VALUE'    => PCLZIP_ERR_MISSING_OPTION_VALUE,
			'PCLZIP_ERR_NO_ERROR'                => PCLZIP_ERR_NO_ERROR,
			'PCLZIP_ERR_READ_OPEN_FAIL'          => PCLZIP_ERR_READ_OPEN_FAIL,
			'PCLZIP_ERR_RENAME_FILE_FAIL'        => PCLZIP_ERR_RENAME_FILE_FAIL,
			'PCLZIP_ERR_UNSUPPORTED_COMPRESSION' => PCLZIP_ERR_UNSUPPORTED_COMPRESSION,
			'PCLZIP_ERR_UNSUPPORTED_ENCRYPTION'  => PCLZIP_ERR_UNSUPPORTED_ENCRYPTION,
			'PCLZIP_ERR_USER_ABORTED'            => PCLZIP_ERR_USER_ABORTED,
			'PCLZIP_ERR_WRITE_OPEN_FAIL'         => PCLZIP_ERR_WRITE_OPEN_FAIL,
			'PCLZIP_OPT_ADD_COMMENT'             => PCLZIP_OPT_ADD_COMMENT,
			'PCLZIP_OPT_ADD_PATH'                => PCLZIP_OPT_ADD_PATH,
			'PCLZIP_OPT_BY_EREG'                 => PCLZIP_OPT_BY_EREG,
			'PCLZIP_OPT_BY_INDEX'                => PCLZIP_OPT_BY_INDEX,
			'PCLZIP_OPT_BY_NAME'                 => PCLZIP_OPT_BY_NAME,
			'PCLZIP_OPT_BY_PREG'                 => PCLZIP_OPT_BY_PREG,
			'PCLZIP_OPT_COMMENT'                 => PCLZIP_OPT_COMMENT,
			'PCLZIP_OPT_EXTRACT_AS_STRING'       => PCLZIP_OPT_EXTRACT_AS_STRING,
			'PCLZIP_OPT_EXTRACT_DIR_RESTRICTION' => PCLZIP_OPT_EXTRACT_DIR_RESTRICTION,
			'PCLZIP_OPT_EXTRACT_IN_OUTPUT'       => PCLZIP_OPT_EXTRACT_IN_OUTPUT,
			'PCLZIP_OPT_NO_COMPRESSION'          => PCLZIP_OPT_NO_COMPRESSION,
			'PCLZIP_OPT_PATH'                    => PCLZIP_OPT_PATH,
			'PCLZIP_OPT_PREPEND_COMMENT'         => PCLZIP_OPT_PREPEND_COMMENT,
			'PCLZIP_OPT_REMOVE_ALL_PATH'         => PCLZIP_OPT_REMOVE_ALL_PATH,
			'PCLZIP_OPT_REMOVE_PATH'             => PCLZIP_OPT_REMOVE_PATH,
			'PCLZIP_OPT_REPLACE_NEWER'           => PCLZIP_OPT_REPLACE_NEWER,
			'PCLZIP_OPT_SET_CHMOD'               => PCLZIP_OPT_SET_CHMOD,
			'PCLZIP_OPT_STOP_ON_ERROR'           => PCLZIP_OPT_STOP_ON_ERROR,
			'PCLZIP_READ_BLOCK_SIZE'             => PCLZIP_READ_BLOCK_SIZE,
			'PCLZIP_SEPARATOR'                   => PCLZIP_SEPARATOR,
			'PCLZIP_TEMPORARY_DIR'               => PCLZIP_TEMPORARY_DIR,
			'PLUGINDIR'                          => PLUGINDIR,
			'PLUGINS_COOKIE_PATH'                => PLUGINS_COOKIE_PATH,
			'RSS'                                => RSS,
			'SECURE_AUTH_COOKIE'                 => SECURE_AUTH_COOKIE,
			'SECURE_AUTH_KEY'                    => SECURE_AUTH_KEY,
			'SITECOOKIEPATH'                     => SITECOOKIEPATH,
			'STATUS_INTERVAL'                    => STATUS_INTERVAL,
			'STYLESHEETPATH'                     => STYLESHEETPATH,
			'TEMPLATEPATH'                       => TEMPLATEPATH,
			'TEST_COOKIE'                        => TEST_COOKIE,
			'USER_COOKIE'                        => USER_COOKIE,
			'WPINC'                              => WPINC,
			'WPLANG'                             => WPLANG,
			'WP_ADMIN'                           => WP_ADMIN,
			'WP_CONTENT_DIR'                     => WP_CONTENT_DIR,
			'WP_CONTENT_URL'                     => WP_CONTENT_URL,
			'WP_IMPORTING'                       => WP_IMPORTING,
			'WP_INSTALLING'                      => WP_INSTALLING,
			'WP_LANG_DIR'                        => WP_LANG_DIR,
			'WP_MEMORY_LIMIT'                    => WP_MEMORY_LIMIT,
			'WP_PLUGIN_DIR'                      => WP_PLUGIN_DIR,
			'WP_PLUGIN_URL'                      => WP_PLUGIN_URL,
			'WP_USE_THEMES'                      => WP_USE_THEMES,
			'WXR_VERSION'                        => WXR_VERSION,
			'XMLRPC_REQUEST'                     => XMLRPC_REQUEST
			);
			
			$wp_globals_a_l = array(
			'$admin_page_hooks'                  => $admin_page_hooks,
			'$ajax_results'                      => $ajax_results,
			'$all_links'                         => $all_links,
			'$allowedposttags'                   => $allowedposttags,
			'$allowedtags'                       => $allowedtags,
			'$authordata'                        => $authordata,
			'$bgcolor'                           => $bgcolor,
			'$cache_categories'                  => $cache_categories,
			'$cache_lastcommentmodified'         => $cache_lastcommentmodified,
			'$cache_lastpostdate'                => $cache_lastpostdate,
			'$cache_lastpostmodified'            => $cache_lastpostmodified,
			'$cache_userdata'                    => $cache_userdata,
			'$category_cache'                    => $category_cache,
			'$class'                             => $class,
			'$comment'                           => $comment,
			'$comment_cache'                     => $comment_cache,
			'$comment_count_cache'               => $comment_count_cache,
			'$commentdata'                       => $commentdata,
			'$current_user'                      => $current_user,
			'$day'                               => $day,
			'$debug'                             => $debug,
			'$descriptions'                      => $descriptions,
			'$error'                             => $error,
			'$feeds'                             => $feeds,
			'$id'                                => $id,
			'$is_apache'                         => $is_apache,
			'$is_IIS'                            => $is_IIS,
			'$is_macIE'                          => $is_macIE,
			'$is_winIE'                          => $is_winIE,
			'$l10n'                              => $l10n,
			'$locale'                            => $locale,
			'$link'                              => $link
			);
			$wp_globals_m_r = array(
			'$m'                                 => $m,
			'$map'                               => $map,
			'$max_num_pages'                     => $max_num_pages,
			'$menu'                              => $menu,
			'$mode'                              => $mode,
			'$month'                             => $month,
			'$month_abbrev'                      => $month_abbrev,
			'$monthnum'                          => $monthnum,
			'$more'                              => $more,
			'$multipage'                         => $multipage,
			'$names'                             => $names,
			'$newday'                            => $newday,
			'$numpages'                          => $numpages,
			'$page'                              => $page,
			'$page_cache'                        => $page_cache,
			'$paged'                             => $paged,
			'$pagenow'                           => $pagenow,
			'$pages'                             => $pages,
			'$parent_file'                       => $parent_file,
			'$preview'                           => $preview,
			'$previousday'                       => $previousday,
			'$previousweekday'                   => $previousweekday,
			'$plugin_page'                       => $plugin_page,
			'$post'                              => $post,
			'$post_cache'                        => $post_cache,
			'$post_default_category'             => $post_default_category,
			'$post_default_title'                => $post_default_title,
			'$post_meta_cache'                   => $post_meta_cache,
			'$postc'                             => $postc,
			'$postdata'                          => $postdata,
			'$posts'                             => $posts,
			'$posts_per_page'                    => $posts_per_page,
			'$previousday'                       => $previousday,
			'$request'                           => $request,
			'$result'                            => $result,
			'$richedit'                          => $richedit
			);
			$wp_globals_s_v = array(
			'$single'                            => $single,
			'$submenu'                           => $submenu,
			'$table_prefix'                      => $table_prefix,
			'$targets'                           => $targets,
			'$timedifference'                    => $timedifference,
			'$timestart'                         => $timestart,
			'$timeend'                           => $timeend,
			'$updated_timestamp'                 => $updated_timestamp,
			'$urls'                              => $urls,
			'$user_ID'                           => $user_ID,
			'$user_email'                        => $user_email,
			'$user_identity'                     => $user_identity,
			'$user_level'                        => $user_level,
			'$user_login'                        => $user_login,
			'$user_pass_md5'                     => $user_pass_md5,
			'$user_url'                          => $user_url
			);
			$wp_globals_w = array(
			'$weekday'                           => $weekday,
			'$weekday_abbrev'                    => $weekday_abbrev,
			'$weekday_initial'                   => $weekday_initial,
			'$withcomments'                      => $withcomments,
			'$wp'                                => $wp,
			'$wp_broken_themes'                  => $wp_broken_themes,
			'$wp_db_version'                     => $wp_db_version,
			'$wp_did_header'                     => $wp_did_header,
			'$wp_did_template_redirect'          => $wp_did_template_redirect,
			'$wp_file_description'               => $wp_file_description,
			'$wp_filter'                         => $wp_filter,
			'$wp_importers'                      => $wp_importers,
			'$wp_plugins'                        => $wp_plugins,
			'$wp_themes'                         => $wp_themes,
			'$wp_object_cache'                   => $wp_object_cache,
			'$wp_query'                          => $wp_query,
			'$wp_queries'                        => $wp_queries,
			'$wp_rewrite'                        => $wp_rewrite,
			'$wp_roles'                          => $wp_roles,
			'$wp_similiesreplace'                => $wp_similiesreplace,
			'$wp_smiliessearch'                  => $wp_smiliessearch,
			'$wp_version'                        => $wp_version,
			'$wpcommentspopupfile'               => $wpcommentspopupfile,
			'$wpcommentsjavascript'              => $wpcommentsjavascript
			);
			
			FB::info($wp_version, 'WP Version');
//			FB::info($wp_roles, 'WP Roles');
//			FB::info($wp_constants, 'WP Constant');
//			FB::info($wp_globals_a_l, 'WP Globals A-L');
//			FB::info($wp_globals_m_r, 'WP Globals M-R');
//			FB::info($wp_globals_s_v, 'WP Globals S-V');
//			FB::info($wp_globals_w, 'WP Globals W');
//			FB::info($data, 'WP DATA');
//			FB::info($update_options, 'Update Array');
			
			//PHP Predefined Variables
			FB::info($_COOKIE, '_COOKIE');
			FB::info($_ENV, '_ENV');
			FB::info($_FILES, '_FILES');
			FB::info($_GET, '_GET');
			FB::info($PHP_SELF, '_PHP_SELF');
			FB::info($_POST, '_POST');
			FB::info($_REQUEST, '_REQUEST');
			FB::info($_SERVER, '_SERVER');
			FB::info($_SESSION, '_SESSION');
		}
	
	}
	
	$WPFirePHP = new WPFirePHP();
}