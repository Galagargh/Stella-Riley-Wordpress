
<?php
/**
 * Template part for displaying posts.
*
* @link https://codex.wordpress.org/Template_Hierarchy
*
* @package CivilWar
*/

?>

<?php
/*
*	add increasing number onto each book to use as link for javascript
*
*	also each book has a container and row, not right
*/
?>

<?php $feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
<?php $image_size = getimagesize( $feat_image_url[0] ); ?>
<?php $bookreviews = get_field('book-review'); ?>
<?php $other_retailers = get_field('other-retailers'); ?>
<?php $amazon_link = get_field('amazon_link'); ?>

<?php 
/*
*		IMAGE SIZE MUST BE BIGGER THAN 440
*		FOR BOOK TO BE DISPLAYED
*/
?>

<?php if ( has_post_thumbnail() && $image_size[0] >= 140 ) { ?>

	<?php if ( is_front_page() ) { ?>
	
			<article id="book-<?php the_ID(); ?>" class="col-xs-6 col-sm-6 col-md-3 col-lg-3 text-lg-center releases-book">
			
			<a href="<?php echo get_page_link( get_page_by_title( "Library" )->ID )."#".$tax_term->name."-".get_the_title(); ?>" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'post-book-thumbnail' ); ?>
			</a>
			
			<p><?php echo the_excerpt(); ?></p>
			
			
			
		<?php } else { ?>
		
	    	<article id="<?php echo $tax_term->name;?>-<?php echo get_the_title(); ?>" class="book-release-container <?php echo $tag; ?>">
	    	
		    	<div class="book-description"><h2><?php the_title_attribute(); ?></h2>
			
					<div class="text-xs-center">
						<?php the_post_thumbnail( 'post-book-thumbnail' ); ?>
					</div>
					
					<p><?php the_content(); ?></p>
					
				</div><!-- Book Description -->
			
			
			<?php if ($bookreviews != null) { ?>
			<div class="review-container">
				<h2>Reviews</h2>
		
				<ul>
					<?php foreach ($bookreviews as $bookreview) { ?>
						<li><?php echo do_shortcode( "[good-reviews review=$bookreview]" ); ?></li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
			
		<?php } ?>
		
		<div class="buylink">
		<?php if ( is_front_page() ) { ?>
			<button type="button" class="hidden-xs-down btn brown-btn italic" onclick="location.href='<?php echo get_page_link( get_page_by_title( "Library" )->ID ). "#" . $tax_term->name . "-" .get_the_title(); ?>' ">Read More</button>	
		<?php }else { ?>
			<?php if ($amazon_link != null ) { ?>
			<button type="button" class="btn brown-btn italic" onclick="location.href='<?php echo $amazon_link; ?>' ">Amazon</button>
			<?php } ?>
			<?php if ($other_retailers != null) { ?>
			<div class="other-retailers"><p>Also available from: <?php echo $other_retailers; ?></p></div>
			<?php } ?>
		<?php } ?>
		</div>

		
	</article>
	
	

<?php } ?> <!-- If has post thumbnail -->