<?php
/*
 * Plugin Name: Footer Contact Widget
 * Plugin URI: http://mgdesign.co.uk
 * Version: 1.0
 * Description: Displays email contact
 * Author: Matthew Gallagher
 * Author URI: http://mgdesign.co.uk
 */

// Creating the widget
class foot_contact extends WP_Widget {
	function __construct() {
		parent::__construct ( 
				// Base ID of your widget
				'foot_contact', 
				
				// Widget name will appear in UI
				__ ( 'Footer Contact', 'foot_contact_domain' ), 
				
				// Widget description
				array (
						'description' => __ ( 'Display Contact information in footer', 'foot_contact_domain' ) 
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
			echo '<li class="social-link">';
			echo "<a class=" . ' " ' . "media-left" . ' " ' . "href=" . ' " ' .site_url() . '/feed/" ' . ">";
			echo '<div class="circle-outline">';
			echo '<div class="media-object circle" alt="RSS">';
			echo '<span class="fa fa-rss"></span>';
			echo '</div>';
			echo '</div>';
			echo '</a>';
			echo '<div class="media-body contact-fix">';
			echo '<p class="social-name">RSS</p>';
			echo '</div>';
			echo '</li>';
		
			/*
			echo '<li class="social-link">';
            echo '<a class="media-left" href="#">';
            echo '<div class="circle-outline">';
            echo '<div class="media-object circle" alt="Generic placeholder image">';
            echo '<span class="fa fa-envelope-o"></span>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
            echo '<div class="media-body contact-fix">';
            echo '<p class="social-name">StellaRiley@Gmail.com</p>';
            echo '</div>';
            echo '</li>';
            */
		echo '</ul>';
		
		//echo $args ['after_widget'];
		
	}
	
	// Widget Backend
	public function form($instance) {
		if (isset ( $instance ['title'] )) {
			$title = $instance ['title'];
		} else {
			$title = __ ( 'New title', 'foot_contact_domain' );
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
} // Class foot_contact ends here
  
// Register and load the widget
function wpb_load_foot_widget() {
	register_widget ( 'foot_contact' );
}
add_action ( 'widgets_init', 'wpb_load_foot_widget' );
