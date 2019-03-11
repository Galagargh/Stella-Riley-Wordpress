<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Assistant_Dashboard_Widget {

	/**
	 * @var array
	 */
	public $links = array();

	/**
	 * One_And_One_Assistant_Dashboard_Widget constructor.
	 */
	public function __construct() {

		// Load links from the global configuration
		$this->links = One_And_One_Assistant_Config::section( 'links' );

		// If links are available, build and show the Widget instead of the standard WP Widget
		if ( array_key_exists( 'community_' . One_And_One_Assistant::get_market(), $this->links ) ) {

			// Add styles
			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles_scripts' ) );

			// Add new dashboard widget
			add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );

			// Remove default dashboard widgets
			add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_meta' ) );
		}
	}

	/**
	 * Add CSS and JS for the widget
	 */
	public function add_styles_scripts() {
		wp_register_style( '1and1-assistant-dashboard-widget', One_And_One_Assistant::get_css_url( 'dashboard-widget.css' ), array(), One_And_One_Assistant::VERSION );

		global $pagenow;

		if ( is_admin() && $pagenow == 'index.php' ) {
			wp_enqueue_style( '1and1-assistant-dashboard-widget' );
		}
	}

	/**
	 * Add widget to the WP Dashboard and configure its position
	 */
	public function add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'oneandone_assistant_dashboard_widget',
			__( 'Community News', '1and1-wordpress-wizard' ),
			array( $this, 'dashboard_widget_function' )
		);

		// forcing widget to the top (works only if user never reordered the dashboard widgets)
		global $wp_meta_boxes;

		$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$widget_backup    = array( 'oneandone_assistant_dashboard_widget' => $normal_dashboard['oneandone_assistant_dashboard_widget'] );
		unset( $normal_dashboard['oneandone_assistant_dashboard_widget'] );
		$sorted_dashboard                             = array_merge( $widget_backup, $normal_dashboard );
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}

	/**
	 * Render widget HTML code
	 */
	public function dashboard_widget_function() {

		// Get "more" link
		$moreLinkUrl = '';

		if ( isset( $this->links[ 'community_' . One_And_One_Assistant::get_market() ] ) ) {
			$moreLinkUrl = $this->links[ 'community_' . One_And_One_Assistant::get_market() ];
		}
		
		// Get feed URL
		if ( ! isset( $this->links[ 'community_feed_' . One_And_One_Assistant::get_market() ] ) ) {
			return '';
		}
		$feedUrl = $this->links[ 'community_feed_' . One_And_One_Assistant::get_market() ];
		$feed = fetch_feed( $feedUrl );

		// Get feed items
		$maxitems = 0;

		if ( ! is_wp_error( $feed ) ) {
			$maxitems = $feed->get_item_quantity( 3 );
			// We need to load 4 items because one item could be tagged as a featured item
			$feed_items = $feed->get_items( 0, $maxitems + 1 );
		}

		// Get featured feed URL
		if ( isset( $this->links[ 'community_feed_featured_' . One_And_One_Assistant::get_market() ] ) ) {

			$feed_featured_url = $this->links[ 'community_feed_featured_' . One_And_One_Assistant::get_market() ];
			$feed_featured = fetch_feed( $feed_featured_url );
	
			if ( ! is_wp_error( $feed_featured ) ) {
				$feed_featured_item = $feed_featured->get_items( 0, 1 );
			}
		}

		// Build article list with featured and not featured feed
		if ( ! empty( $feed_featured_item[0] ) ) {
			$feed_featured_item['featured'] = $feed_featured_item[0];
			unset( $feed_featured_item[0] );
			$this->create_feed_array( $feed_items, $feed_featured_item );
		} else {
			array_pop( $feed_items );
		}
		?>

		<ul>
			<?php if ( $maxitems == 0 ) : ?>
				<li><?php _e( 'No items', '1and1-wordpress-wizard' ); ?></li>
			<?php else : ?>
				<?php // Loop through each feed item and display each item as a hyperlink. ?>
				<?php foreach ( $feed_items as $key => $item ) : ?>
					<?php $class = 'one-and-one-feeditem'; ?>
					<?php if ( $key === 'featured' ) : ?>
						<?php $class = 'one-and-one-featureditem'; ?>
					<?php endif; ?>
					<li class="<?php echo $class; ?>">
						<a target="_blank" class="news-title" href="<?php echo esc_url( $item->get_permalink() ); ?>" title="<?php echo esc_html( $item->get_title() ); ?>">
							<?php echo esc_html( $item->get_title() ); ?>
						</a>
						<p>
							<a target="_blank" href="<?php echo esc_url( $item->get_permalink() ); ?>" title="<?php echo esc_html( $item->get_title() ); ?>">
								<?php echo wp_trim_words( $item->get_description(), 25, null ); ?>
							</a>
						</p>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ( $moreLinkUrl ): ?>
				<li>
					<a target="_blank" class="button button-primary" href="<?php echo $moreLinkUrl; ?>" title=""><?php _e( 'community_widget_link_label', '1and1-wordpress-wizard' ); ?></a>
				</li>
			<?php endif; ?>
		</ul>

		<?php
	}

	/**
	 * Remove standard WordPress Blog widget
	 * (our own Community widget is replacing it)
	 */
	public function remove_dashboard_meta() {
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	}

	/**
	 * Build array of feed article data
	 * 
	 * @param array $feed_items
	 * @param array $feed_featured_item
	 */
	private function create_feed_array( &$feed_items, $feed_featured_item ) {
		$feed_featured_title = $feed_featured_item['featured']->get_title();

		foreach ( $feed_items as $key => $feed_item ) {
			$feed_title = $feed_item->get_title();

			if ( $feed_title == $feed_featured_title ) {
				unset( $feed_items[$key] );
				break;
			}
		}

		if ( count( $feed_items ) > 3 ) {
			$feed_items = array_slice( $feed_items, 0, 3 );
		}

		$feed_items = array_merge( $feed_featured_item, $feed_items );
	}

}

new One_And_One_Assistant_Dashboard_Widget();
