<?php
/*
 * Plugin Name: Footer Social Links Widget
 * Plugin URI: http://mgdesign.co.uk
 * Version: 1.0
 * Description: Displays top 3 latest posts in footer
 * Author: Matthew Gallagher
 * Author URI: http://mgdesign.co.uk
 */

// Creating the widget
class Social_Links_Widget extends WP_Widget {
	function __construct() {
		parent::__construct ( 
				// Base ID of your widget
				'Social_Links_Widget', 
				
				// Widget name will appear in UI
				__ ( 'Social Links Widget', 'Social_Links_Widget_domain' ), 
				
				// Widget description
				array (
						'description' => __ ( 'Displays social links in footer', 'Social_Links_Widget_domain' ) 
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
	

		echo '<ul>';
			
		//
		// KEEP THIS	This code can be reused for carousel
		//
		//echo '<img class="media-object circle" '.'src="'.wp_get_attachment_url( get_post_thumbnail_id($recent["ID"]) ).'">';
		//echo '<img class="media-object circle" '.'src="'.the_post_thumbnail( 'post-footer-thumbnail' ).'">';
		//
		
		//Query 3 recent published post in descending order
		$args = array( 'numberposts' => '3', 'order' => 'DESC','post_status' => 'publish' );
		$recent_posts = wp_get_recent_posts( $args );
		//attributes as array to add correct class to thumbnail
		$post_thumbnail = array('class' => "media-object circle");
		

		//loop for each array item (post) in $args
		foreach( $recent_posts as $recent )
		{
			
			echo '<li class="social-link">';
			
			//code for individual post thumbnails
			echo '<a class="pull-left"'.'href='.get_permalink($recent["ID"]).'>';
			echo get_the_post_thumbnail( $recent["ID"], 'post-footer-thumbnail', $post_thumbnail);
			echo '</a>';
			
			//code for post Titles, Dates
			echo '<div class="media-body">';
			echo '<a href='.get_permalink($recent["ID"]).'>';
			echo '<h4 class="media-heading">'.$recent["post_title"].'</h4>';
			echo '<p>Published '.get_the_date('jS F, Y', $recent["ID"].'</p>');
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
			$title = __ ( 'New title', 'Social_Links_Widget_domain' );
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
} // Class Social_Links_Widget ends here
  
// Register and load the widget
function wpb_load_widget() {
	register_widget ( 'Social_Links_Widget' );
}
add_action ( 'widgets_init', 'wpb_load_widget' );
