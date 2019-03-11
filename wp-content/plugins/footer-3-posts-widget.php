<?php
/*
 * Plugin Name: Footer 3 Posts Widget
 * Plugin URI: http://mgdesign.co.uk
 * Version: 1.0
 * Description: Displays top 3 latest posts in footer
 * Author: Matthew Gallagher
 * Author URI: http://mgdesign.co.uk
 */

// Creating the widget
class Three_Posts_Widget extends WP_Widget {
	function __construct() {
		parent::__construct ( 
				// Base ID of your widget
				'Three_Posts_Widget', 
				
				// Widget name will appear in UI
				__ ( '3 Posts Widget', 'Three_Posts_Widget_domain' ), 
				
				// Widget description
				array (
						'description' => __ ( 'Displays three posts in footer', 'Three_Posts_Widget_domain' ) 
				) );
	}
	
	// Creating widget front-end
	// This is where the action happens
	
	public function widget($args, $instance) {
		
		$title = apply_filters ( 'widget_title', $instance ['title'] );
		
		// before and after widget arguments are defined by themes
		echo $args ['before_widget'];
		if (! empty ( $title ))
			echo $args ['before_title'] . $title . $args ['after_title'];
			
		// This is where you run the code and display the output
		
		// echo __ ( 'Hello, World!', 'Three_Posts_Widget_domain' );
		


		echo '<ul>';
		
		//Query 3 recent published post in descending order
		$args = array( 'numberposts' => '3', 'order' => 'DESC','post_status' => 'publish' );
		$recent_posts = wp_get_recent_posts( $args );
		//attributes as array to add correct class to thumbnail
		$post_thumbnail = array('class' => "media-object circle");
		

		//loop for each array item (post) in $args
		foreach( $recent_posts as $recent )
		{
			
			echo '<li class="post-link">';
			
			//code for individual post thumbnails
			//echo '<a class="pull-left three-posts-fix circle-bg"'.'href='.get_permalink($recent["ID"]).'>';
			
			if ( has_post_thumbnail( $recent["ID"] ) ) {
				echo '<a class="pull-left three-posts-fix circle-bg"'.'href='.get_permalink($recent["ID"]).'>';
				echo get_the_post_thumbnail( $recent["ID"], 'post-footer-thumbnail', $post_thumbnail);
			} else {
				echo '<a class="pull-left three-posts-fix circle-outline"'.'href='.get_permalink($recent["ID"]).'>';
				echo '<span class="fa fa-book no-pic" aria-hidden="true"></span>';
			}
			
			echo '</a>';
			
			//code for post Titles, Dates
			echo '<div class="media-body">';
			echo '<a href='.get_permalink($recent["ID"]).'>';
			echo '<h4 class="media-heading">'.$recent["post_title"].'</h4>';
			echo '<p>Published '.get_the_date('jS F, Y', $recent["ID"]).'</p>';
			echo '</a>';
			echo '</div>';
			
			echo '</li>';
			//Do whatever else you please with this WordPress post
		}
		
		echo '</ul>';
		
		//echo $args ['after_widget'];
		
	}
	
	// Widget Backend
	public function form($instance) {
		if (isset ( $instance ['title'] )) {
			$title = $instance ['title'];
		} else {
			$title = __ ( 'New title', 'Three_Posts_Widget_domain' );
		}
		// Widget admin form
		?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'title' ); ?>"
		name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
		value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
	}
	
	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance) {
		$instance = array ();
		$instance ['title'] = (! empty ( $new_instance ['title'] )) ? strip_tags ( $new_instance ['title'] ) : '';
		return $instance;
	}
} // Class Three_Posts_Widget ends here
  
// Register and load the widget
function wpb_load_posts_widget() {
	register_widget ( 'Three_Posts_Widget' );
}
add_action ( 'widgets_init', 'wpb_load_posts_widget' );
