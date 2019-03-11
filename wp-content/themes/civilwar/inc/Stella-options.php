<?php /*
// create custom plugin settings menu
add_action('admin_menu', 'stella_options_menu');

function stella_options_menu() {

	//create new top-level menu
	add_menu_page('Stella Site Settings', 'Site Settings', 'administrator', __FILE__, 'stella_settings_page' , plugins_url('/images/icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'my-cool-plugin-settings-group', 'header_settings' );
	register_setting( 'my-cool-plugin-settings-group', 'header_reviews' );
	register_setting( 'my-cool-plugin-settings-group', 'library_page' );
}

function stella_settings_page() {
?>
<div class="wrap">
<h1>Stella Riley Theme Site Options</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Header Settings</th>
        <td><input type="text" name="header_settings" value="<?php echo esc_attr( get_option('header_settings') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Header Reviews</th>
        <td><input type="text" name="header_reviews" value="<?php echo esc_attr( get_option('header_reviews') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Set Library Page</th>
        <td><input type="text" name="library_page" value="<?php echo esc_attr( get_option('library_page') ); ?>" /></td>
        </tr>
    </table>
    
    	
	<?php $library_page = get_option("civilwar_library_page"); ?>
    <?php $pages = get_pages(); ?>
     
	<li class="front-page-element" id="front-page-element-placeholder">
	    <label for="element-page-id">Featured post:</label>
	    <select name="element-page-id">
	    
	        <?php foreach ($pages as $page) : ?>
	            <option value="<?php echo $page->ID; ?>">
	                <?php echo $page->post_title; ?>
	            </option>
	        <?php endforeach; ?>
	        
	    </select>
	    <a href="#">Remove</a>
	</li>
    
    <?php submit_button(); ?>
    
    <?php
    if (isset($_POST["update_settings"])) {
    // Do the saving
    	$library_page = esc_attr($_POST["num_elements"]);
    	update_option("civilwar_library_page", $library_page);
    	echo '<div id="message" class="updated">Settings Saved!</div>';
	}
	?>
	
	<input type="hidden" name="update_settings" value="Y" />

</form>
</div>
<?php }*/ ?>