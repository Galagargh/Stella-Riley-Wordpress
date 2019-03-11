<?php
/*
Plugin Name: Annual Archive
Text Domain: anual-archive
Plugin URI: https://plugins.twinpictures.de/plugins/annual-archive/
Description: Display daily, weekly, monthly, yearly, decade, postbypost and alpha archives with a sidebar widget or shortcode.
Version: 1.5.2
Author: Twinpictures
Author URI: https://www.twinpictures.de/
License: GPL2
*/

/**
 * Class WP_Plugin_Annual_Archive
 * @package WP_plugin
 * @category WordPress Plugins
 */

class WP_Plugin_Annual_Archive {

	/**
	 * Plugin vars
	 * @var string
	 */
	var $plugin_name = 'Annual Archive';
	var $version = '1.5.2';
	var $domain = 'anarch';

	/**
	 * Options page
	 * @var string
	 */
	var $plugin_options_page_title = 'Annual Archive Options';
	var $plugin_options_menue_title = 'Annual Archive';
	var $plugin_options_slug = 'annual-archive-optons';

	/**
	 * Name of the options
	 * @var string
	 */
	var $options_name = 'WP_AnnualArchive_options';

	/**
	 * @var array
	 */
	var $options = array(
		'custom_css' => '',
	);

	/**
	 * PHP5 constructor
	 */
	function __construct() {
		// set option values
		$this->_set_options();

		// load text domain for translations
		load_plugin_textdomain('anual-archive');

		// add actions
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_actions' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'archive_admin_js_scripts' ) );
		add_action( 'wp_head', array( $this, 'plugin_head_inject' ) );

		// add shortcode
		add_shortcode('archives', array($this, 'shortcode'));
		add_shortcode('arcpromat', array($this, 'shortcode'));

		// add filters
		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'query_vars', array($this, 'annual_archive_query_vars') );
		add_filter( 'pre_get_posts', array($this, 'annual_archive_decade_filter') );
		add_filter( 'get_the_archive_title', array($this, 'annual_archive_decade_title') );
	}

	// Add decade to the query args
	function annual_archive_query_vars($vars) {
	  $vars[] = 'decade';
	  return $vars;
	}

	//check for decade query var
	function annual_archive_decade_filter( $query ) {
		if ( $query->is_main_query() && $query->is_archive() ){
			$decade = get_query_var( 'decade' );
			if( !empty($decade) ){
				$start = $decade.'-01-01';
				$end = ($decade+9).'-12-31';
				$query->set('year', '');
				$query->set( 'date_query',
				    array (
						'after'     => $start,
						'before'	=> $end,
						'inclusive' => true,
				    )
				);
			}
		}
	}

	//modify the title
	function annual_archive_decade_title($title) {
		$decade = get_query_var( 'decade' );
		if(!empty($decade)){
	        $title = __('The').' '.$decade.'\'s';
	    }
	    return $title;
	}

	//plugin header inject
	function plugin_head_inject(){
		// custom css
		if( !empty( $this->options['custom_css'] ) ){
			echo "\n<style>\n";
			echo $this->options['custom_css'];
			echo "\n</style>\n";
		}
	}

	/**
	 * Callback admin_menu
	 */
	function admin_menu() {
		if ( function_exists( 'add_options_page' ) AND current_user_can( 'manage_options' ) ) {
			// add options page
			$options_page = add_options_page($this->plugin_options_page_title, $this->plugin_options_menue_title, 'manage_options', $this->plugin_options_slug, array( $this, 'options_page' ));
		}
	}

	/**
	 * Callback admin_init
	 */
	function admin_init() {
		// register settings
		register_setting( $this->domain, $this->options_name );
	}

	//load scripts on the widget admin page
	function archive_admin_js_scripts($hook){
		global $pagenow;
		if( $hook == 'widgets.php' && $pagenow != 'customize.php'){
			$plugin_url = plugins_url() .'/'. dirname( plugin_basename(__FILE__) );
			wp_register_script('archive-admin-script', $plugin_url.'/js/widget_form.js', array ('jquery'), '0.2', true);
			wp_enqueue_script('archive-admin-script');
		}
	}

	/**
	* Advaned wp_get_archives
	* adds the ability to order alpha and postbypost Archives
	* adds archive by decade
	**/
	static function wp_get_archives_advanced( $args = '' ) {
		global $wpdb, $wp_locale;

		$defaults = array(
			'type'            => 'monthly',
			'limit'           => '',
			'format'          => 'html',
			'before'          => '',
			'after'           => '',
			'show_post_count' => false,
			'echo'            => 1,
			'order'           => 'DESC',
	        'alpha_order'      => 'ASC',
	        'post_order'       => 'DESC',
			'post_type'       => 'post',
		);

		$r = wp_parse_args( $args, $defaults );

		$post_type_object = get_post_type_object( $r['post_type'] );
		if ( ! is_post_type_viewable( $post_type_object ) ) {
			return;
		}
		$r['post_type'] = $post_type_object->name;

		if ( '' == $r['type'] ) {
			$r['type'] = 'monthly';
		}

		if ( ! empty( $r['limit'] ) ) {
			$r['limit'] = absint( $r['limit'] );
			$r['limit'] = ' LIMIT ' . $r['limit'];
		}

		$order = strtoupper( $r['order'] );
		if ( $order !== 'ASC' ) {
			$order = 'DESC';
		}

		// this is what will separate dates on weekly archive links
		$archive_week_separator = '&#8211;';

		$sql_where = $wpdb->prepare( "WHERE post_type = %s AND post_status = 'publish'", $r['post_type'] );

		/**
		 * Filters the SQL WHERE clause for retrieving archives.
		 *
		 * @since 2.2.0
		 *
		 * @param string $sql_where Portion of SQL query containing the WHERE clause.
		 * @param array  $r         An array of default arguments.
		 */
		$where = apply_filters( 'getarchives_where', $sql_where, $r );

		/**
		 * Filters the SQL JOIN clause for retrieving archives.
		 *
		 * @since 2.2.0
		 *
		 * @param string $sql_join Portion of SQL query containing JOIN clause.
		 * @param array  $r        An array of default arguments.
		 */
		$join = apply_filters( 'getarchives_join', '', $r );

		$output = '';

		$last_changed = wp_cache_get_last_changed( 'posts' );

		$limit = $r['limit'];

		if ( 'monthly' == $r['type'] ) {
			$query = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date $order $limit";
			$key   = md5( $query );
			$key   = "wp_get_archives_advanced:$key:$last_changed";
			if ( ! $results = wp_cache_get( $key, 'posts' ) ) {
				$results = $wpdb->get_results( $query );
				wp_cache_set( $key, $results, 'posts' );
			}
			if ( $results ) {
				$after = $r['after'];
				foreach ( (array) $results as $result ) {
					$url = get_month_link( $result->year, $result->month );
					if ( 'post' !== $r['post_type'] ) {
						$url = add_query_arg( 'post_type', $r['post_type'], $url );
					}
					/* translators: 1: month name, 2: 4-digit year */
					$text = sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $result->month ), $result->year );
					if ( $r['show_post_count'] ) {
						$r['after'] = '&nbsp;(' . $result->posts . ')' . $after;
					}
					$output .= get_archives_link( $url, $text, $r['format'], $r['before'], $r['after'] );
				}
			}
		} elseif ( 'yearly' == $r['type'] ) {
			$query = "SELECT YEAR(post_date) AS `year`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date) ORDER BY post_date $order $limit";
			$key   = md5( $query );
			$key   = "wp_get_archives_advanced:$key:$last_changed";

			if ( ! $results = wp_cache_get( $key, 'posts' ) ) {
				$results = $wpdb->get_results( $query );
				wp_cache_set( $key, $results, 'posts' );
			}
			if ( $results ) {
				$after = $r['after'];
				foreach ( (array) $results as $result ) {
					$url = get_year_link( $result->year );
					if ( 'post' !== $r['post_type'] ) {
						$url = add_query_arg( 'post_type', $r['post_type'], $url );
					}
					$text = sprintf( '%d', $result->year );
					if ( $r['show_post_count'] ) {
						$r['after'] = '&nbsp;(' . $result->posts . ')' . $after;
					}
					$output .= get_archives_link( $url, $text, $r['format'], $r['before'], $r['after'] );
				}
			}
		} elseif ( 'daily' == $r['type'] ) {
			$query = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, DAYOFMONTH(post_date) AS `dayofmonth`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date), MONTH(post_date), DAYOFMONTH(post_date) ORDER BY post_date $order $limit";
			$key   = md5( $query );
			$key   = "wp_get_archives_advanced:$key:$last_changed";
			if ( ! $results = wp_cache_get( $key, 'posts' ) ) {
				$results = $wpdb->get_results( $query );
				wp_cache_set( $key, $results, 'posts' );
			}
			if ( $results ) {
				$after = $r['after'];
				foreach ( (array) $results as $result ) {
					$url = get_day_link( $result->year, $result->month, $result->dayofmonth );
					if ( 'post' !== $r['post_type'] ) {
						$url = add_query_arg( 'post_type', $r['post_type'], $url );
					}
					$date = sprintf( '%1$d-%2$02d-%3$02d 00:00:00', $result->year, $result->month, $result->dayofmonth );
					$text = mysql2date( get_option( 'date_format' ), $date );
					if ( $r['show_post_count'] ) {
						$r['after'] = '&nbsp;(' . $result->posts . ')' . $after;
					}
					$output .= get_archives_link( $url, $text, $r['format'], $r['before'], $r['after'] );
				}
			}
		} elseif ( 'weekly' == $r['type'] ) {
			$week  = _wp_mysql_week( '`post_date`' );
			$query = "SELECT DISTINCT $week AS `week`, YEAR( `post_date` ) AS `yr`, DATE_FORMAT( `post_date`, '%Y-%m-%d' ) AS `yyyymmdd`, count( `ID` ) AS `posts` FROM `$wpdb->posts` $join $where GROUP BY $week, YEAR( `post_date` ) ORDER BY `post_date` $order $limit";
			$key   = md5( $query );
			$key   = "wp_get_archives_advanced:$key:$last_changed";
			if ( ! $results = wp_cache_get( $key, 'posts' ) ) {
				$results = $wpdb->get_results( $query );
				wp_cache_set( $key, $results, 'posts' );
			}
			$arc_w_last = '';
			if ( $results ) {
				$after = $r['after'];
				foreach ( (array) $results as $result ) {
					if ( $result->week != $arc_w_last ) {
						$arc_year       = $result->yr;
						$arc_w_last     = $result->week;
						$arc_week       = get_weekstartend( $result->yyyymmdd, get_option( 'start_of_week' ) );
						$arc_week_start = date_i18n( get_option( 'date_format' ), $arc_week['start'] );
						$arc_week_end   = date_i18n( get_option( 'date_format' ), $arc_week['end'] );
						$url            = add_query_arg(
							array(
								'm' => $arc_year,
								'w' => $result->week,
							), home_url( '/' )
						);
						if ( 'post' !== $r['post_type'] ) {
							$url = add_query_arg( 'post_type', $r['post_type'], $url );
						}
						$text = $arc_week_start . $archive_week_separator . $arc_week_end;
						if ( $r['show_post_count'] ) {
							$r['after'] = '&nbsp;(' . $result->posts . ')' . $after;
						}
						$output .= get_archives_link( $url, $text, $r['format'], $r['before'], $r['after'] );
					}
				}
			}
		} elseif ( 'decade' == $r['type'] ) {
			$query = "SELECT count(*), decade, decade + 9 FROM (SELECT FLOOR(YEAR(post_date) / 10) * 10 AS decade FROM $wpdb->posts $join $where) t GROUP BY decade $order $limit";
	        $key   = md5( $query );
			$key   = "wp_get_archives_advanced:$key:$last_changed";
			if ( ! $results = wp_cache_get( $key, 'posts' ) ) {
				$results = $wpdb->get_results( $query );
				wp_cache_set( $key, $results, 'posts' );
			}
			if ( $results ) {
				$after = $r['after'];
				foreach ( (array) $results as $result ) {
					$url = get_year_link($result->decade).'?decade='.$result->decade;
					if ( 'post' !== $r['post_type'] ) {
						$url = add_query_arg( 'post_type', $r['post_type'], $url );
					}
					$text = sprintf( '%d\'s', $result->decade );
					if ( $r['show_post_count'] ) {
						$r['after'] = '&nbsp;(' . $result->posts . ')' . $after;
					}
					$output .= get_archives_link( $url, $text, $r['format'], $r['before'], $r['after'] );
				}
			}
		} elseif ( ( 'postbypost' == $r['type'] ) || ( 'alpha' == $r['type'] ) ) {
	        $alpha_order = strtoupper( $r['alpha_order'] );
	        if ( $alpha_order !== 'ASC' ) {
	    		$alpha_order = 'DESC';
	    	}
	        $post_order = strtoupper( $r['post_order'] );
	        if ( $post_order !== 'DESC' ) {
	    		$post_order = 'ASC';
	    	}
	        $orderby = ( 'alpha' == $r['type'] ) ? 'post_title '.$alpha_order. ' ' : 'post_date '.$post_order.', ID DESC ';
			$query   = "SELECT * FROM $wpdb->posts $join $where ORDER BY $orderby $limit";
			$key     = md5( $query );
			$key     = "wp_get_archives_advanced:$key:$last_changed";
			if ( ! $results = wp_cache_get( $key, 'posts' ) ) {
				$results = $wpdb->get_results( $query );
				wp_cache_set( $key, $results, 'posts' );
			}
			if ( $results ) {
				foreach ( (array) $results as $result ) {
					if ( $result->post_date != '0000-00-00 00:00:00' ) {
						$url = get_permalink( $result );
						if ( $result->post_title ) {
							/** This filter is documented in wp-includes/post-template.php */
							$text = strip_tags( apply_filters( 'the_title', $result->post_title, $result->ID ) );
						} else {
							$text = $result->ID;
						}
						$output .= get_archives_link( $url, $text, $r['format'], $r['before'], $r['after'] );
					}
				}
			}
		}
		if ( $r['echo'] ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Callback shortcode
	 */
	function shortcode($atts, $content = null){
		extract(shortcode_atts(array(
			'type' => 'yearly',
			'limit' => '',
			'format' => 'html', //html, option, link
			'before' => '',
			'after' => '',
			'showcount' => '0',
			'tag' => 'ul',
			'order' => 'DESC',
			'alpha_order' => 'ASC',
			'post_order' => 'DESC',
			'select_text' => '',
			'post_type' => 'post',
		), $atts));

		if ($format == 'option') {
			if( !empty($select_text) ){
				$dtitle = $select_text;
			}
			else{
				$dtitle = __('Select Year', 'anual-archive');
				if ($type == 'monthly'){
					$dtitle = __('Select Month', 'anual-archive');
				}
				else if($type == 'weekly'){
					$dtitle = __('Select Week', 'anual-archive');
				}
				else if($type == 'daily'){
					$dtitle = __('Select Day', 'anual-archive');
				}
				else if($type == 'postbypost' || $type == 'alpha'){
					$dtitle = __('Select Post', 'anual-archive');
				}
			}
			$arc = '<select name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;"> <option value="">'.esc_attr($dtitle).'</option>';
			$arc .= WP_Plugin_Annual_Archive::wp_get_archives_advanced(array('type' => $type, 'limit' => $limit, 'format' => 'option', 'show_post_count' => $showcount, 'post_type' => $post_type, 'order' => $order, 'alpha_order' => $alpha_order, 'post_order' => $post_order, 'echo' => 0)).'</select>';
		} else {
			$arc = '<'.$tag.'>';
			$arch_arr = array(
				'type' => $type,
				'limit' => $limit,
				'format' => $format,
				'before' => $before,
				'after' => $after,
				'show_post_count' => $showcount,
				'post_type' => $post_type,
				'order' => $order,
				'alpha_order' => $alpha_order,
				'post_order' => $post_order,
				'echo' => 0
			);

			$arc .= WP_Plugin_Annual_Archive::wp_get_archives_advanced($arch_arr);
			$arc .= '</'.$tag.'>';
		}
		return $arc;
	}

	// Add link to options page from plugin list
	function plugin_actions($links) {
		$new_links = array();
		$new_links[] = '<a href="options-general.php?page='.$this->plugin_options_slug.'">' . __('Settings', 'anual-archive') . '</a>';
		return array_merge($new_links, $links);
	}

	/**
	 * Admin options page
	 */
	function options_page() {
		$like_it_arr = array(
						__('really tied the room together', 'anual-archive'),
						__('made you feel all warm and fuzzy on the inside', 'anual-archive'),
						__('restored your faith in humanity... even if only for a fleeting second', 'anual-archive'),
						__('rocked your world', 'provided a positive vision of future living', 'anual-archive'),
						__('inspired you to commit a random act of kindness', 'anual-archive'),
						__('encouraged more regular flossing of the teeth', 'anual-archive'),
						__('helped organize your life in the small ways that matter', 'anual-archive'),
						__('saved your minutes--if not tens of minutes--writing your own solution', 'anual-archive'),
						__('brightened your day... or darkened if if you are trying to sleep in', 'anual-archive'),
						__('caused you to dance a little jig of joy and joyousness', 'anual-archive'),
						__('inspired you to tweet a little @twinpictues social love', 'anual-archive'),
						__('tasted great, while also being less filling', 'anual-archive'),
						__('caused you to shout: "everybody spread love, give me some mo!"', 'anual-archive'),
						__('helped you keep the funk alive', 'anual-archive'),
						__('<a href="https://www.youtube.com/watch?v=dvQ28F5fOdU" target="_blank">soften hands while you do dishes</a>', 'anual-archive'),
						__('helped that little old lady <a href="https://www.youtube.com/watch?v=Ug75diEyiA0" target="_blank">find the beef</a>', 'anual-archive')
					);
	$rand_key = array_rand($like_it_arr);
	$like_it = $like_it_arr[$rand_key];
	?>
		<div class="wrap">
			<h2><?php echo $this->plugin_name; ?></h2>
		</div>

		<div class="postbox-container metabox-holder meta-box-sortables" style="width: 69%">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'anual-archive' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'Annual Archive Settings', 'anual-archive' ) ?></h3>
					<div class="inside">
						<form method="post" action="options.php">
							<?php
								settings_fields( $this->domain );
								$this->_set_options();
								$options = $this->options;
							?>

							<fieldset class="options">
								<table class="form-table">
									<tr>
										<th><?php _e( 'Custom Style', 'anual-archive' ) ?>:</th>
										<td><label><textarea id="<?php echo $this->options_name ?>[custom_css]" name="<?php echo $this->options_name ?>[custom_css]" style="width: 100%; height: 150px;"><?php echo $options['custom_css']; ?></textarea>
											<br /><span class="description"><?php _e( 'Custom CSS style for <em>ultimate flexibility</em>', 'anual-archive' ) ?></span></label>
										</td>
									</tr>

									<tr>
										<th><?php _e( 'Go Pro', 'anual-archive' ) ?>:</th>
										<td>
											<p><?php printf(__( '%sArchive-Pro-Matic%s adds the ability to display archives by <strong>post type</strong>, <strong>custom post type</strong> and <strong>category</strong>.  In addition it comes with %svery high level of personal support%s&mdash;that alone is well worth the price of admission.', 'anual-archive' ), '<a href="https://plugins.twinpictures.de/premium-plugins/archive-pro-matic/?utm_source=annual-archive&utm_medium=plugin-settings-page&utm_content=archive-pro-matic&utm_campaign=archive-pro-level-up">', '</a>', '<a href="https://plugins.twinpictures.de/testimonial/archive-pro-matic-testimonias&utm_medium=plugin-settings-page&utm_content=archive-pro-matic&utm_campaign=archive-pro-support">', '</a>'); ?></p>
										</td>
									</tr>

									<tr>
										<th><?php _e( 'Free Advice', 'anual-archive' ) ?></th>
										<td>
											<p><?php _e( '<p>Congratulations! You have reach the very bottom of your Dashboard. This is probably least visited corner of your site, and yet here you are, reading this, hoping to be enlightened or rewarded in some way.</p><p>Well, we hate to leave you hanging, all disappointed, so here is a little tip or two for you.</p><p>Get back to work!</p><p>The sooner you finish that task, check that box of your list, wrap your day up, the sooner you can get on with the more important things in life that matter.</p> <p>Things like: <ul><li>playing with your dog</li><li>calling your mother</li><li>changing the sheets on the bed</li><li>flossing your teeth</li><li>simply sipping a cool beverage in a hammock</li></ul><p>Now get on with it, there is a whole world out there to see!</p>', 'anual-archive' ); ?></p>
										</td>
									</tr>

								</table>
							</fieldset>

							<p class="submit" style="margin-bottom: 20px;">
								<input class="button-primary" type="submit" value="<?php _e( 'Save Changes', 'anual-archive' ) ?>" style="float: right;" />
							</p>
					</div>
				</div>
			</div>
		</div>

		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'anual-archive' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'About', 'anual-archive' ) ?></h3>
					<div class="inside">
						<h4><?php echo $this->plugin_name; ?> <?php _e('Version', 'anual-archive'); ?> <?php echo $this->version; ?></h4>
						<p><?php printf( __('Annual Archive widget extends the default WordPress Archive widget to allow daily, weekly, monthly, yearly, decade, postbypost and alpha archives to be displayed.  Archives can be displayed in the sidebar using a widget&mdash;and even placed in a post or page by using a shortcode. A %scomplete listing of shortcode options and attribute demos%s are available, as well as %sfree, open-source community support%s. The Annual Archive widget&mdash;A better archive widget. Oh, one more thing: The plugin can be translated into any language using the WordPress %scommunity translation tool%s.', 'anual-archive') ,'<a href="https://translate.wordpress.org/projects/wp-plugins/anual-archive">','</a>', '<a href="https://wordpress.org/support/plugin/anual-archive">', '</a>', '<a href="https://translate.twinpictures.de/projects/anual-archive">', '</a>') ?></p>
						<ul>
							<li>
								<?php printf( __( '%sDetailed documentation%s, complete with working demonstrations of all shortcode attributes, is available for your instructional enjoyment.', 'anual-archive'), '<a href="https://plugins.twinpictures.de/plugins/annual-archive/documentation/" target="_blank">', '</a>'); ?>
							</li>
							<li><?php printf( __('If this plugin %s, please consider %ssharing your story%s with others.', 'anual-archive'), $like_it, '<a href="https://www.facebook.com/twinpictures" target="_blank">', '</a>' ) ?></li>
							<li><?php printf( __('Your %sreviews%s, %sbug-reports, feedback%s and %scocktail recipes%s are always welcomed.', 'anual-archive'), '<a href="https://wordpress.org/support/view/plugin-reviews/anual-archive">', '</a>', '<a href="https://wordpress.org/support/plugin/anual-archive">', '</a>', '<a href="https://www.facebook.com/twinpictures">', '</a>'); ?></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'Level Up!' ) ?></h3>
					<div class="inside">
						<p><?php printf(__( '%sArchive-Pro-Matic%s is our premium plugin that adds the ability to display archives by <strong>post type</strong> or <strong>category</strong>', 'anual-archive' ), '<a href="https://plugins.twinpictures.de/premium-plugins/archive-pro-matic/?utm_source=annual-archive&utm_medium=plugin-settings-page&utm_content=archive-pro-matic&utm_campaign=archive-pro-level-up">', '</a>'); ?></p>
						<?php /*<p style="padding: 5px; border: 1px dashed #cccc66; background: #EEE;"><strong>Star Wars Day Discount:</strong> <a href="https://plugins.twinpictures.de/premium-plugins/archive-pro-matic/?utm_source=annual-archive&utm_medium=plugin-settings-page&utm_content=archive-pro-matic&utm_campaign=archive-pro-year-end">Update to Archive-Pro-Matic</a> before May 4th, 2016 using discount code MAYTHE4TH and receive 10% off.</p> */ ?>
						<h4><?php _e('Reasons To Go Pro', 'anual-archive'); ?></h4>
						<ol>
							<li><?php _e("You are an advanced user and want/need advanced features", "anual-archive"); ?></li>
							<li><?php _e("Annual Archive was just what you needed and you'd like to put a bit of bread in our jar", "anual-archive"); ?></li>
							<?php /*<li><?php _e("Because MAYTHE4TH is strong with this one", "anual-archive"); ?></li>*/ ?>
						</ol>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	<?php
	}

	/**
	 * Set options from save values or defaults
	 */
	function _set_options() {
		// set options
		$saved_options = get_option( $this->options_name );

		// backwards compatible (old values)
		if ( empty( $saved_options ) ) {
			$saved_options = get_option( $this->domain . 'options' );
		}

		// set all options
		if ( ! empty( $saved_options ) ) {
			foreach ( $this->options AS $key => $option ) {
				$this->options[ $key ] = ( empty( $saved_options[ $key ] ) ) ? '' : $saved_options[ $key ];
			}
		}
	}

} // end class WP_Plugin_Template

/**
 * Create instance
 */
$WP_Plugin_Annual_Archive = new WP_Plugin_Annual_Archive;


//Widget
class Annual_Archive_Widget extends WP_Widget {
    /** constructor */
	function __construct() {

		$widget_ops = array(
			'classname'		=> 'Annual_Archive_Widget',
			'description'	=> __( 'Display daily, weekly, monthly or annual archives with a sidebar widget or shortcode', 'anual-archive' )
		);

		parent::__construct( 'Annual_Archive_Widget', __( 'Annual Archive', 'anual-archive' ), $widget_ops );

	}

    /** Widget */
    function widget($args, $instance) {
	extract( $args );

	$format = empty($instance['format']) ? 'html' : apply_filters('widget_format', $instance['format']);
	$type = empty($instance['type']) ? 'yearly' : apply_filters('widget_type', $instance['type']);
	$before = empty($instance['before']) ? '' : apply_filters('widget_before', $instance['before']);
	$after = empty($instance['after']) ? '' : apply_filters('widget_after', $instance['after']);
	$limit = apply_filters('widget_limit', $instance['limit']);
	$title = apply_filters('widget_title', empty($instance['title']) ? __('Annual Archive', 'anual-archive') : $instance['title'], $instance, $this->id_base);
	$count = empty($instance['count']) ? 0 : $instance['count'];
	$order = empty($instance['order']) ? 'DESC' : apply_filters('widget_order', $instance['order']);
	$alpha_order = empty($instance['alpha_order']) ? 'ASC' : apply_filters('widget_alpha_order', $instance['alpha_order']);
	$post_order = empty($instance['post_order']) ? 'DESC' : apply_filters('widget_post_order', $instance['post_order']);
	$select_text = empty($instance['select_text']) ? '' : apply_filters('widget_slelect_text', $instance['select_text']);
	$post_type = empty($instance['post_type']) ? 'post' : apply_filters('widget_post_type', $instance['post_type']);
	echo $before_widget;
	if ( $title )
		echo $before_title . $title . $after_title;

	if ($format == 'option') {
		if($select_text){
			$dtitle = $select_text;
		}
		else{
			$dtitle = __('Select Year', 'anual-archive');
			if ($type == 'monthly'){
				$dtitle = __('Select Month', 'anual-archive');
			}
			else if($type == 'weekly'){
				$dtitle = __('Select Week', 'anual-archive');
			}
			else if($type == 'daily'){
				$dtitle = __('Select Day', 'anual-archive');
			}
			else if($type == 'postbypost' || $type == 'alpha'){
				$dtitle = __('Select Post', 'anual-archive');
			}
		}
	?>
	<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> <option value=""><?php echo esc_attr(__($dtitle, 'anual-archive')); ?></option> <?php WP_Plugin_Annual_Archive::wp_get_archives_advanced(apply_filters('widget_archive_dropdown_args', array('type' => $type, 'format' => 'option', 'show_post_count' => $count, 'limit' => $limit, 'order' => $order, 'alpha_order' => $alpha_order, 'post_order' => $post_order))); ?> </select>
	<?php
	} else {
	?>
	<ul>
	<?php WP_Plugin_Annual_Archive::wp_get_archives_advanced(apply_filters('widget_archive_args', array('type' => $type, 'limit' => $limit, 'format' => $format, 'before' => $before, 'after' => $after, 'show_post_count' => $count, 'post_type' => $post_type, 'order' => $order, 'alpha_order' => $alpha_order, 'post_order' => $post_order))); ?>
	</ul>
	<?php
	}

	echo $after_widget;
    }

    /** Update **/
    function update($new_instance, $old_instance) {
		$instance = array_merge($old_instance, $new_instance);
		$instance['count'] = $new_instance['count'];
		return $instance;
    }

    /** Form **/
    function form($instance) {
		$title = empty($instance['title']) ? '' : stripslashes($instance['title']);
		$count = empty($instance['count']) ? 0 : $instance['count'];
		$format = empty($instance['format']) ? '' : stripslashes($instance['format']);
		$before = empty($instance['before']) ? '' : stripslashes($instance['before']);
		$after = empty($instance['after']) ? '' : stripslashes($instance['after']);
		$type = empty($instance['type']) ? '' : strip_tags($instance['type']);
		$limit = empty($instance['limit']) ? '' : stripslashes($instance['limit']);
		$post_type = empty($instance['post_type']) ? 'post' : stripslashes($instance['post_type']);
		$order = empty($instance['order']) ? 'DESC' : stripslashes($instance['order']);
		$alpha_order = empty($instance['alpha_order']) ? 'ASC' : stripslashes($instance['alpha_order']);
		$post_order = empty($instance['post_order']) ? 'DESC' : stripslashes($instance['post_order']);
		$select_text = empty($instance['select_text']) ? '' : stripslashes($instance['select_text']);
        ?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','anual-archive'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><input type="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" value="1" <?php checked( $count, 1 ); ?>/>&nbsp;&nbsp;<?php _e('Show post counts', 'anual-archive'); ?></label></p>
		<p><label><?php _e('Archive type:', 'anual-archive'); ?> <select name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>" class="annual_archive_type_select">
		<?php
		$types_arr = array(
			'daily' => __('Daily', 'anual-archive'),
			'weekly' => __('Weekly', 'anual-archive'),
			'monthly' => __('Monthly', 'anual-archive'),
			'yearly' => __('Yearly', 'anual-archive'),
			'decade' => __('Decade', 'anual-archive'),
			'alpha' => __('Alpha', 'anual-archive'),
			'postbypost' => __('Post By Post', 'anual-archive')
		);

		$order_style = '';
		$alpha_style = 'style="display: none;"';
		$post_style = 'style="display: none;"';

		foreach($types_arr as $key => $value){
			$selected = '';
			if($key == $type || (!$type && $key == 'yearly')){
				$selected = 'SELECTED';
			}
			//order switcher
			$data_att = 'data-orderfield="wrap_'.$this->get_field_id('order').'"';
			if($key == 'alpha'){
				$data_att = 'data-orderfield="wrap_'.$this->get_field_id('alpha_order').'"';
				if($key == $type){
					$order_style = 'style="display: none;"';
					$alpha_style = '';
				}
			}
			if($key == 'postbypost'){
				$data_att = 'data-orderfield="wrap_'.$this->get_field_id('post_order').'"';
				if($key == $type){
					$order_style = 'style="display: none;"';
					$post_style = '';
				}
			}
			echo '<option value="'.$key.'" '.$data_att.' '.$selected.'>'.$value.'</option>';
		}
		?>
		</select></lable>
	</p>

	<p><label><?php _e('Format:', 'anual-archive'); ?> <select name="<?php echo $this->get_field_name('format'); ?>" id="<?php echo $this->get_field_id('format'); ?>">
		<?php
		$format_arr = array(
			'html' => __('HTML', 'anual-archive'),
			'option' => __('Option', 'anual-archive'),
			'link' => __('Link', 'anual-archive'),
			'custom' => __('Custom', 'anual-archive')
		);
		foreach($format_arr as $key => $value){
			$selected = '';
			if($key == $format || (!$format && $key == 'html')){
				$selected = 'SELECTED';
			}
			echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		?>
		</select></lable><br/>
		<span class="description"><a href="https://codex.wordpress.org/Function_Reference/wp_get_archives#Parameters" target="_blank"><?php _e('Format details', 'anual-archive'); ?></a></span>
	</p>


	<p><label><?php _e('Post Type:', 'anual-archive'); ?> <select name="<?php echo $this->get_field_name('post_type'); ?>" id="<?php echo $this->get_field_id('post_type'); ?>">
		<?php
		$pt_args = array(
			'public' => true
		);
		$post_types = get_post_types($pt_args);
		foreach($post_types as $type){
			$selected = '';
			if($type == $post_type ){
				$selected = 'SELECTED';
			}
			echo '<option value="'.$type.'" '.$selected.'>'.$type.'</option>';
		}
		?>
		</select></lable><br/>
		<span class="description"><a href="https://codex.wordpress.org/Function_Reference/wp_get_archives#Parameters" target="_blank"><?php _e('Post Type', 'anual-archive'); ?></a></span>
	</p>


	<p><label for="<?php echo $this->get_field_id('before'); ?>"><?php _e('Text Before Link:', 'anual-archive'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('before'); ?>" name="<?php echo $this->get_field_name('before'); ?>" type="text" value="<?php echo $before; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('after'); ?>"><?php _e('Text After Link:', 'anual-archive'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('after'); ?>" name="<?php echo $this->get_field_name('after'); ?>" type="text" value="<?php echo $after; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('select_text'); ?>"><?php _e('Select Text:', 'anual-archive'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('select_text'); ?>" name="<?php echo $this->get_field_name('select_text'); ?>" type="text" value="<?php echo $select_text; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of archives to display:', 'anual-archive'); ?></label> <input class="widefat" style="width: 50px;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" /></p>
	<p id="wrap_<?php echo $this->get_field_id('order'); ?>" class="order_<?php echo $this->get_field_id('type'); ?>" <?php echo $order_style; ?>><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Sort order:', 'anual-archive'); ?></label> <input id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="DESC" <?php checked( $order, 'DESC' ); ?> /> DESC <input name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="ASC" <?php checked( $order, 'ASC' ); ?>  />  ASC</p>
	<p id="wrap_<?php echo $this->get_field_id('alpha_order'); ?>" class="order_<?php echo $this->get_field_id('type'); ?>" <?php echo $alpha_style; ?>><label for="<?php echo $this->get_field_id('alpha_order'); ?>"><?php _e('Alpha order:', 'anual-archive'); ?></label> <input id="<?php echo $this->get_field_id('alpha_order'); ?>" name="<?php echo $this->get_field_name('alpha_order'); ?>" type="radio" value="DESC" <?php checked( $alpha_order, 'DESC' ); ?> /> DESC <input name="<?php echo $this->get_field_name('alpha_order'); ?>" type="radio" value="ASC" <?php checked( $alpha_order, 'ASC' ); ?>  />  ASC</p>
	<p id="wrap_<?php echo $this->get_field_id('post_order'); ?>" class="order_<?php echo $this->get_field_id('type'); ?>" <?php echo $post_style; ?>><label for="<?php echo $this->get_field_id('post_order'); ?>"><?php _e('Post order:', 'anual-archive'); ?></label> <input id="<?php echo $this->get_field_id('post_order'); ?>" name="<?php echo $this->get_field_name('post_order'); ?>" type="radio" value="DESC" <?php checked( $post_order, 'DESC' ); ?> /> DESC <input name="<?php echo $this->get_field_name('post_order'); ?>" type="radio" value="ASC" <?php checked( $post_order, 'ASC' ); ?>  />  ASC</p>
	<?php
    }
} // class Annual_Archive_Widget

// register Annual_Archive_Widget
function anarch_register_widget() {
	register_widget( 'Annual_Archive_Widget' );
}
add_action( 'widgets_init', 'anarch_register_widget' );
?>
