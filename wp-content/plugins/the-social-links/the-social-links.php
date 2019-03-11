<?php
/**
Plugin Name: The Social Links
Plugin URI: https://leapsandbounds.io/the-social-links/
Description: The Social Links plugin adds a widget and shortcode to your WordPress website allowing you to display icons linking to your social profiles.
Version: 1.3.1
Author: Leaps+Bounds
Author URI: https://leapsandbounds.io/
License: GPL2
Text Domain: the-social-links

Copyright 2016 Leaps+Bounds (email : plugins@leapsandbounds.io)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package   TheSocialLinks
 * @category  Class
 * @author    Leaps+Bounds
 */

/**
 * The Social Links Main Class
 *
 * @version   1.3.1
 * @package   TheSocialLinks
 */
class TheSocialLinks {

	/**
	 * Will hold available social networks
	 *
	 * @var array
	 */
	public $social_networks;

	/**
	 * Current version of The Social Links
	 *
	 * @var string Current version number
	 * @since 1.0
	 */
	protected $the_social_links_version = '1.3.1';

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @var TheSocialLinks The single instance of the class
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return  TheSocialLinksFrontend A single instance of this class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * The construct of TheSocialLinksFrontend
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'admin_init', array( $this, 'register_settings' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 2 );

		add_action( 'plugins_loaded', array( $this, 'update_db_check' ) );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		$this->includes();

		do_action( 'tsl_loaded' );

		$this->social_networks = apply_filters(
			'add_tsl_social_networks', array(
				'facebook'     => 'Facebook',
				'google-plus'  => 'Google+',
				'instagram'    => 'Instagram',
				'linkedin'     => 'LinkedIn',
				'pinterest'    => 'Pinterest',
				'rss'          => 'RSS Feed',
				'twitter'      => 'Twitter',
				'vimeo-square' => 'Vimeo',
				'youtube'      => 'YouTube',
				'behance'      => 'Behance',
				'bitcoin'      => 'Bitcoin',
				'delicious'    => 'Delicious',
				'deviantart'   => 'DeviantArt',
				'digg'         => 'Digg',
				'dribbble'     => 'Dribbble',
				'flickr'       => 'Flickr',
				'foursquare'   => 'Foursquare',
				'github'       => 'GitHub',
				'lastfm'       => 'LastFM',
				'medium'       => 'Medium',
				'skype'        => 'Skype',
				'soundcloud'   => 'Soundcloud',
				'spotify'      => 'Spotify',
				'tumblr'       => 'Tumblr',
				'vine'         => 'Vine',
				'wordpress'    => 'WordPress',
			)
		);

		asort( $this->social_networks );

	}

	/**
	 * Include class files for the plugin
	 *
	 * @return void
	 */
	public function includes() {

		include_once 'includes/class-frontend.php';
		include_once 'includes/class-widget.php';
	}

	/**
	 * Checks to see if the plugin needs to run updates.
	 *
	 * @todo set up updates if needed.
	 * @return void
	 */
	public function update_db_check() {

		$the_social_links_version = $this->the_social_links_version;

		$installed_version = get_site_option( 'the_social_links_version' );
		if ( ! $installed_version ) :
			$this->legacy_update();
		endif;

	}

	/**
	 * Runs when the plugin is activated and sets defaults.
	 *
	 * @return void
	 */
	public function activate() {

		$the_social_links_version = $this->the_social_links_version;

		if ( ! get_option( 'the_social_links_settings' ) ) :
			update_option(
				'the_social_links_settings', array(
					'style'    => 'default',
					'style'    => 'square',
					'size'     => 32,
					'target'   => '_blank',
					'networks' => array(),
					'links'    => array(),
				)
			);
		endif;

		update_option( 'the_social_links_version', $the_social_links_version );

	}

	/**
	 * Legacy update of The Social Links from version 0.4.
	 *
	 * @return void
	 */
	public function legacy_update() {

		$the_social_links_version = $this->the_social_links_version;

		$settings = get_option( 'the_social_links_settings' );

		if ( ! $settings ) :
			$settings = array(
				'style'    => 'rounded',
				'size'     => 32,
				'target'   => '_blank',
				'networks' => array(),
				'links'    => array(),
			);
		endif;

		foreach ( $this->social_networks as $social_network => $network_name ) :

			$old_network = get_option( 'tsl_' . $social_network );

			if ( $old_network && ! empty( $old_network ) ) :

				$settings['networks'][] = $social_network;
				$settings['links'][]    = array( $social_network => $old_network );

			endif;

		endforeach;

		$size = get_option( 'tsl_icon_size' );

		if ( '16x16' == $size || '24x24' == $size ) :
			$settings['size'] = '24';
		elseif ( '32x32' == $size ) :
			$settings['size'] = '32';
		elseif ( '48x48' == $size || '64x64' == $size ) :
			$settings['size'] = '48';
		elseif ( '48x48' == $size || '60x60' == $size ) :
			$settings['size'] = '60';
		endif;

		$target = get_option( 'tsl_link_target' );

		if ( '_parent' == $target ) :
			$settings['target'] = '_top';
		else :
			$settings['target'] = '_blank';
		endif;

		update_option( 'the_social_links_settings', $settings );
		update_option( 'the_social_links_version', $the_social_links_version );

	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'jquery-ui-sortable', null, array( 'jquery' ) );

		wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'assets/css/font-awesome.min.css' );
		wp_enqueue_style( 'the-social-links-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );

	}

	/**
	 * Add The Social Links to the WordPress Dashboard menu.
	 *
	 * @return void
	 */
	public function admin_menu() {

		add_menu_page( 'The Social Links', 'The Social Links', 'administrator', 'the-social-links', array( $this, 'settings_page' ), 'dashicons-share' );

	}

	/**
	 * Output of the admin settings page.
	 *
	 * @return void
	 */
	public function settings_page() {

		$settings = get_option( 'the_social_links_settings' );

?>

		<div class="wrap admin">

			<h2><?php esc_html_e( 'The Social Links', 'the-social-links' ); ?></h2>

			<h3><?php esc_html_e( 'Social Networks and Options', 'the-social-links' ); ?></h3>

			<form method="post" action="options.php">

			<?php settings_fields( 'the_social_links_settings' ); ?>
			<?php do_settings_sections( 'the_social_links_settings' ); ?>

			<table class="form-table">
				<tr valign="top">
					<td scope="row" style="width:270px;"><strong><?php esc_html_e( 'Networks', 'the-social-links' ); ?></strong><br /><?php esc_html_e( 'Select the social networks that you would like to display', 'the-social-links' ); ?></td>
					<td class="social-networks">
						<?php
						$networks = $settings['networks'];
						if ( ! $networks ) :
							$networks = array();
						endif;
						?>
						<?php foreach ( $this->social_networks as $key => $social_network ) : ?>
							<label><input type="checkbox" name="the_social_links_settings[networks][]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $networks ), true ); ?> /> <?php echo esc_html( $social_network ); ?></label>
						<?php endforeach; ?>
					</td>
				</tr>
			</table>

			<?php
			$styles = apply_filters(
				'add_tsl_styles', array(
					'square'  => __( 'Square', 'the-social-links' ),
					'rounded' => __( 'Rounded', 'the-social-links' ),
					'circle'  => __( 'Circle', 'the-social-links' ),
				)
			);
?>

			<table class="form-table">
				<tr valign="top">
					<td scope="row" style="width:270px;"><strong><?php _e( 'Style', 'the-social-links' ); ?></strong><br /><?php _e( 'Select the style of the icons.', 'the-social-links' ); ?></td>
					<td>
						<select name="the_social_links_settings[style]">
							<?php foreach ( $styles as $key => $style ) : ?>
							<option value="<?php echo $key; ?>" <?php selected( $key, $settings['style'] ); ?>><?php echo $style; ?></option>
							<?php
							endforeach;
		?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row"><strong><?php _e( 'Size', 'the-social-links' ); ?></strong><br /><?php _e( 'Select the size of the icons', 'the-social-links' ); ?></td>
					<td>
						<select name="the_social_links_settings[size]">
							<option value="24" <?php selected( '24', $settings['size'] ); ?>>24px x 24px</option>
							<option value="32" <?php selected( '32', $settings['size'] ); ?>>32px x 32px</option>
							<option value="48" <?php selected( '48', $settings['size'] ); ?>>48px x 48px</option>
							<option value="60" <?php selected( '48', $settings['size'] ); ?>>60px x 60px</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row"><strong><?php _e( 'Link Target', 'the-social-links' ); ?></strong><br /><?php _e( 'Open links in a new window or the current window. A new window is recommended.', 'the-social-links' ); ?></td>
					<td>
						<select name="the_social_links_settings[target]">
							<option value="_blank" <?php selected( '_blank', $settings['target'] ); ?>><?php _e( 'New Window', 'the-social-links' ); ?></option>
							<option value="_top" <?php selected( '_top', $settings['target'] ); ?>><?php _e( 'Current Window', 'the-social-links' ); ?></option>
						</select>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>

			<h3><?php _e( 'Order and Links', 'the-social-links' ); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<td scope="row" style="width:270px;"><strong><?php _e( 'Links and Order', 'the-social-links' ); ?></strong><br /><?php _e( 'Enter your network (including http:// or https://) and drag the networks into the order you would like.', 'the-social-links' ); ?></td>
					<td>
						<?php if ( $networks && ! empty( $networks ) ) : ?>
							<?php
							$current_links = $settings['links'];
							if ( ! $current_links ) :
								$current_links = array();
							endif;

							$links = array();

							if ( ! empty( $current_links ) ) :

								foreach ( $current_links as $current_link ) :

									foreach ( $networks as $key => $network ) :

										if ( isset( $current_link[ $network ] ) ) :
											$links[] = $current_link;
											unset( $networks[ $key ] );
										endif;

									endforeach;

								endforeach;

							endif;

							foreach ( $networks as $network ) :

								$links[] = array( $network => '' );

							endforeach;

							?>

							<ul class="sortable tsl-links">

								<?php foreach ( $links as $link ) : ?>

									<?php
									foreach ( $link as $network => $value ) :
										$network = $network;
										$value   = $value;
									endforeach;
									?>

									<li class="tsl-item">
										<i class="fa fa-arrows-v"></i>&nbsp;
										<a class="the-social-links tsl-<?php echo $settings['style']; ?> tsl-<?php echo $settings['size']; ?> tsl-default tsl-<?php echo $network; ?>" target="<?php echo $settings['target']; ?>" alt="<?php echo $this->social_networks[ $network ]; ?>" title="<?php echo $this->social_networks[ $network ]; ?>"><i class="fa fa-<?php echo $network; ?>"></i></a>
										<input placeholder="<?php echo $this->social_networks[ $network ]; ?> <?php _e( 'URL', 'the-social-links' ); ?>" type="text" name="the_social_links_settings[links][][<?php echo $network; ?>]" value="<?php echo $value; ?>" />
									</li>

								<?php endforeach; ?>

							</ul>

						<?php else : ?>
							<?php _e( 'Please select social networks before adding links and sorting them.', 'the-social-links' ); ?>
						<?php endif; ?>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>

			</form>

			<div>

				<p>
					<?php printf( __( 'If you like <strong>The Social Links</strong> please leave us a %1$s&#9733;&#9733;&#9733;&#9733;&#9733;%2$s rating. A huge thank you from Leaps+Bounds in advance!', 'the-social-links' ), '<a href="https://wordpress.org/support/view/plugin-reviews/the-social-links?filter=5#postform" target="_blank" class="tsl-rating-link" data-rated="' . __( 'Thanks a lot! :D', 'the-social-links' ) . '" target="_blank">', '</a>' ); ?><br />
					<a href="https://leapsandbounds.io/the-social-links/"><?php printf( __( 'Visit %1$s page on the %2$s website', 'the-social-links' ), 'The Social Links', 'Leaps+Bounds' ); ?><br /></a>
				</p>
				<p><a href="https://leapsandbounds.io/" target="_blank"><img src="https://leapsandbounds.io/wp-content/uploads/2017/04/Leaps-and-bounds-logo-flickerleap-2.png" width="250" alt="Leaps+Bounds" title="Leaps+Bounds" /></p>

			</div>

		</div>

		<script>
		jQuery(document).ready(function($){
			$('.sortable').sortable();
		});
		</script>

		<?php

	}

	/**
	 * Register dashboard settings for the settings page.
	 *
	 * @return void
	 */
	public function register_settings() {

		register_setting( 'the_social_links_settings', 'the_social_links_settings', array( $this, 'sanitize' ) );

	}

	/**
	 * Sanitize the input from the user.
	 *
	 * @param string $input String inputted by the user.
	 * @return string Returns a string that has been sanitized.
	 */
	public function sanitize( $input ) {

		// Say our second option must be safe text with no HTML tags!
		if ( ! empty( $input['links'] ) ) :
			foreach ( $input['links'] as $key => $link ) :

				foreach ( $link as $network => $value ) :
					$network = $network;
					$value   = $value;
				endforeach;

				$input['links'][ $key ] = array( $network => esc_url_raw( $value, array( 'http', 'https' ) ) );

			endforeach;
		endif;

		return $input;
	}

	/**
	 * Add settings and website links to The Social Links on the WordPress plugin page.
	 *
	 * @param array  $links An array of current links.
	 * @param string $file  The filename and path of the plugin to apply action links to.
	 * @return array Returns an array of links to desiplay.
	 */
	public function action_links( array $links, $file ) {
		if ( plugin_basename( dirname( __FILE__ ) . '/the-social-links.php' ) == $file ) {
			$links[] = '<a href="' . admin_url( 'admin.php?page=the-social-links' ) . '">' . __( 'Settings', 'the-social-links' ) . '</a>';
			$links[] = '<a href="https://leapsandbounds.io/the-social-links/">' . __( 'Plugin Website', 'the-social-links' ) . '</a>';
		}

		return $links;
	}
}

/** Initiates an instance of TheSocialLinks. */
TheSocialLinks::instance();
