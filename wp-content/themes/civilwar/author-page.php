<?php
/*
 * 		Template Name: Author
*/
?>

<?php 
/*
 * 		This code makes use of the advanced custom fields plugin API
 * 		https://www.advancedcustomfields.com/resources/code-examples/
 */

/* hack because that's what i am */

if(isset($_SERVER['HTTP_USER_AGENT'])){
    $agent = $_SERVER['HTTP_USER_AGENT'];
}

?>

<?php get_header( '3-columns' ); ?>

<?php $count = 1; ?>

<script type="text/javascript">
navbarScroll();
</script>

<Section class="bio-section">
	<div class="container">
 	   <div class="row">
       	<div id="sidebar-wrapper" class="col-xs-12 col-lg-4 col-lg-push-8 sidebar-toggle">
			<ul class="sidebar-nav text-xs-center text-lg-left">
			<?php 
			while( $count <= 5) { 
			$field_name = "section_$count";
			$label = get_field_object( $field_name ); 
			?>
			<li>
           		<a class="sidebar-link italic brown"><?php echo $label["label"]; ?></a>
           	</li>
           	<?php $count++; 
			} ?>
			</ul>
			</div>

<?php $count = 1; ?>

<?php while( $count <= 5) { ?>

	<?php
	
	$field_name = "section_$count";
	$field = get_field_object( $field_name );
	
	if ($count == 1){
		$first = "col-lg-pull-4";
	} else {
		$first = "";
	}
	
	if ($count % 2 == 0){
		$push = "col-lg-push-6";
		$pull = "col-lg-pull-6";
	} else {
		$push = "";
		$pull = "";
	}
	
	
	if ( $field ) {

		echo '<article class="bio-article col-lg-8 ' . $first . '">';
			echo '<div id="article' . $count . '" class="bio-text col-xs-12 col-lg-6 ' . $push . '">';
				echo '<h2 class="text-uppercase text-xs-center brown">'. $field["label"] . '</h2>';
				
				echo '<div class="bio-img hidden-lg-up">';
				echo '<img class="center-block" src="' .get_field('section_'.$count.'_img'). '" alt="">';	
				echo '</div>';
		
				echo '<p class="text-justify">'. get_field( "section_$count" ) .'</p>';
			echo '</div>';
			
		echo '<div class="bio-img col-xs-12 col-lg-6 ' .$pull. ' hidden-md-down">';
			echo '<img class="center-block" src="' .get_field('section_'.$count.'_img'). '" alt="">';
		echo '</div>';
		
		echo '</article>';
	}
	
	$count++; ?>
	
	

<?php } ?>

		</div><!-- Row -->
	</div><!-- Container -->
</Section><!-- Bio Section -->

</div><!-- Site Content -->

<?php

/*
 * 		HACK FOR STELLA PIC
 */

/*	 check browser  */
if(strlen(strstr($agent,"Firefox")) > 0 ){      
    $browser = 'firefox';
}

if(strlen(strstr($agent,"Chrome")) > 0 ){      
    $browser = 'chrome';
}

if(strlen(strstr($agent,"MSIE")) > 0 ){
	$browser = 'ie';
}

if(strlen(strstr($agent,"Opera")) > 0 ){
	$browser = 'opera';
}

if(strlen(strstr($agent,"Safari")) > 0 ){
	$browser = 'safari';
}
?>

<?php if($browser=='firefox'){ ?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.author-picture').addClass('author-picture-firefox');
	});
	</script>
<?php } ?>

<?php if( $browser=='chrome' ){ ?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.author-picture').addClass('author-picture-chrome');
	});
	</script>
<?php } ?>

<?php if( $browser=='opera' ){ ?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.author-picture').addClass('author-picture-firefox');
	});
	</script>
<?php } ?>

<?php if( $browser=='safari' ){ ?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.author-picture').addClass('author-picture-chrome');
	});
	</script>
<?php } ?>

<?php if( $browser=='ie' ){ ?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.author-picture').addClass('author-picture-firefox');
	});
	</script>
<?php } ?>


<?php //get_sidebar(); ?>
<?php get_footer(); ?>