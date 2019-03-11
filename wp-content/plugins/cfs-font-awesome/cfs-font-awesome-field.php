<?php
function cfsfa_assets() {
wp_enqueue_style('cfsfa_font_awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
wp_enqueue_style('cfsfa_fa_iconpicker', plugins_url('assets/css/fontawesome-iconpicker.min.css',__FILE__ ));
wp_enqueue_script( 'cfsfa_fa_iconpicker', plugins_url('assets/js/fontawesome-iconpicker.min.js',__FILE__ ));
}
add_action( 'admin_init','cfsfa_assets');

class cfs_font_awesome_field extends cfs_field {
	function __construct() {
		$this->name  = 'font_awesome_field';
		$this->label = __( 'Font Awesome', 'cfsfa' );
	}
	function html( $field ) {  $icon = $field->value; if($icon == '') { $icon = "fa-anchor"; } ?>
		<div class="cfsfa_wrap"><span id="fa_icon" class="input-group-addon"></span><input class="icp icp-auto" name="<?php esc_attr_e( $field->input_name ); ?>" value="<?php echo $icon; ?>" type="text" /></div>
	    <script type="text/javascript">
            jQuery(function($) {
                $('.icp-auto').iconpicker();
                
           		$('.iconpicker-item').on('click', function(e) {
           			$(".popover").hide();
           		});
           		
           		$('.icp').on('click', function(e) {
           			$(this).next().show();
           		});
            });
        </script>
		
		<style type="text/css">
			.cfsfa_wrap{
				position: relative;
				padding-left: 50px;
				min-height: 50px;
			}
			
			.cfsfa_wrap .icp{
				margin-top: 15px;
			}
			
			span#fa_icon{
				background-color: #363B3F; 
				color: #fff;
				font-size: 24px;
				line-height: 40px;
				width: 40px;
				height: 40px;
				display: inline-block;
				text-align: center;
				border-radius: 3px;
				margin: 7px 0;
				position: absolute;
				left: 0;
			}
		</style>
		
		<?php
	}
}