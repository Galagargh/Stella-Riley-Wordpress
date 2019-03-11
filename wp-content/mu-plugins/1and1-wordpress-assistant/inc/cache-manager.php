<?php

/**
 * Class One_And_One_Assistant_Cache_Manager
 * Handles the creation and reading of the Assistant cache
 */
class One_And_One_Assistant_Cache_Manager {

	/**
	 * @var string
	 */
	private $cache_path = '';

	/**
	 * One_And_One_Assistant_Cache_Manager constructor.
	 *
	 * @param string $cache_path
	 */
	function __construct( $cache_path = null ) {

		if ( $cache_path ) {
			$this->cache_path = $cache_path;
		} else {
			$this->cache_path = One_And_One_Assistant::get_plugin_dir_path() . '/cache/';
		}
	}

	/**
	 * Loads the cache for all themes and plugins
	 * (or the themes and plugins of a given site type)
	 * 
	 * @param One_And_One_Assistant_Sitetype_Filter $site_type_filter
	 */
	public function fill_cache( $site_type_filter ) {
		$site_types = $site_type_filter->get_sitetypes( false );

		foreach ( $site_types as $name ) {
			$this->fill_theme_cache( $name, $site_type_filter->get_theme_slugs( $name ) );
			$this->fill_plugin_cache( $name, $site_type_filter->get_plugins( $name ) );
		}
	}

	/**
	 * Loads the plugins/themes cache for a given site type
	 * 
	 * @param  string $type
	 * @param  string $site_type
	 * @return string
	 */
	public function load_cache( $type, $site_type ) {
		return $this->load_cache_file( $type, $site_type );
	}

	/**
	 * Checks if plugins/themes cache exists for a given site type
	 * 
	 * @param string $type
	 * @param string $site_type
	 *
	 * @return boolean
	 */
	public function has_cache( $type, $site_type ) {
		return ( is_file ( $this->get_cache_file_path( $type, $site_type ) ) );
	}

	/**
	 * Generates a serialized cache file for themes
	 *
	 * @param string $site_type
	 * @param array  $theme_slugs
	 */
	public function fill_theme_cache( $site_type, $theme_slugs ) {
		$content = array();

		foreach ( $theme_slugs as $theme_slug ) {
			$theme_data = $this->get_data_from_api( 'theme', $theme_slug );
			$theme_data[ 'id' ] = $theme_slug;
			$theme_data[ 'description' ] = $theme_data[ 'sections' ][ 'description' ];
			$content[ $theme_slug ] = $theme_data;
		}

		$this->save_cache_file( 'theme', $site_type, $content );
	}
	
	/**
	 * Generates a serialized cache file for plugins
	 *
	 * @param string $site_type
	 * @param array  $plugins
	 */
	public function fill_plugin_cache( $site_type, $plugins ) {
		$content = array();

		foreach ( $plugins as $plugin_slug => $plugin_config ) {
			$plugin_data = $this->get_data_from_api( 'plugin', $plugin_slug );
			$content[ $plugin_slug ] = array_merge( $plugin_data, $plugin_config );
		}

		$this->save_cache_file( 'plugin', $site_type, $content );
	}

	/**
	 * Loads plugin or theme data to be cached, using the WordPress API
	 * 
	 * @param String $type  Possible types: theme|plugin
	 * @param String $slug  The slug from theme or plugin that should be load
	 *
	 * @return array        Data from api to the given slug
	 * @throws Exception    When no valid type is given
	 */
	public function get_data_from_api( $type, $slug ) {
		$url = $this->get_api_url( $type, $slug );

		$net_client = curl_init( $url );
		// body is returned as value instead of error code
		curl_setopt( $net_client, CURLOPT_RETURNTRANSFER, true );
		$data = curl_exec( $net_client );
		curl_close( $net_client );

		// Data can be empty if the plugin isn't available on WP.org, for example German Market
		if ( empty( $data ) || ( $data === 'null' ) || ( $data === 'false' ) ) {
			return array();
		} else {
			return json_decode( $data, true );
		}
	}

	/**
	 * Builds and returns WordPress API URL for the needed plugin or theme
	 * 
	 * @param string $type
	 * @param string $slug
	 *
	 * @return string
	 * @throws Exception  When no valid type is given
	 */
	public function get_api_url( $type, $slug ) {

		switch ( $type ) {
			case 'theme':
				$url = 'https://api.wordpress.org/themes/info/1.1/?action=theme_information&request[slug]=' . $slug;
				break;
			case 'plugin':
				$url = 'https://api.wordpress.org/plugins/info/1.1/?' .
				       http_build_query( array(
					       'action'  => 'plugin_information',
					       'request' => array(
						       'slug'   => $slug,
						       'fields' => array(
							       'short_description' => true,
							       'icons'             => true
						       )
					       )
				       ) );
				break;
			default:
				throw new Exception( 'No valid type was given. Type given was: ' . $type );
				break;
		}

		return $url;
	}

	/**
	 * Generates the cache file
	 * 
	 * @param string $type
	 * @param string $site_type
	 * @param array $content
	 *
	 * @return bool|int
	 */
	public function save_cache_file( $type, $site_type, $content ) {
		return file_put_contents(
			$this->get_cache_file_path( $type, $site_type ),
			serialize( $content )
		);
	}

	/**
	 * Opens the cache file
	 * 
	 * @param string $type
	 * @param string $site_type
	 *
	 * @return bool|string
	 */
	public function load_cache_file( $type, $site_type ) {
		return unserialize(
			file_get_contents(
				$this->get_cache_file_path( $type, $site_type )
			)
		);
	}

	/**
	 * Builds the plugins or themes cache filename for a given site type
	 * 
	 * @param string $type
	 * @param string $site_type
	 * 
	 * @return string
	 */
	public function get_cache_file_path( $type, $site_type ) {
		return $this->cache_path . $type . '-' . $site_type . '-meta.txt';
	}
}
