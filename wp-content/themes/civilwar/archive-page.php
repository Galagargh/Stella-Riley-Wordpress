<?php
/*
 Template Name: Archives
*/

get_header(); 

?>

	<?php $page_title = $post->post_title; ?>

	<script type="text/javascript">
	navbarScroll(<?php echo '"'.$page_title.'"' ?>);
	</script>

	<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>

	<?php $args = array(
	    'posts_per_page'   => 4,
		'paged'            => $paged,
	    'orderby'          => 'post_date',
	    'order'            => 'DESC',
	    'post_type'        => 'post',
	    'post_status'      => 'publish',
	    'suppress_filters' => false );
	
		$wp_query = new WP_Query( $args );  
	?>

	<?php $latest_posts = wp_get_recent_posts( $args ); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<section id="blog" class="blog-container">
		
			<section class="blog-latest">
			
			<?php if ( have_posts() ) : ?>
			
				<?php $archiveImgArray = array(); ?>			
				<?php $count = 0; ?>
				<?php $post_number = $wp_query->post_count; ?>

			
				<?php while ( have_posts() ) : the_post(); ?>
				
				<?php
				
						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						//get_template_part( 'template-parts/content-archive', get_post_format() );
				
						include( locate_template( 'template-parts/content-archive.php' ) );
						
					?>
				
				<?php endwhile; ?>
				
				<script type="text/javascript">
				blogImages(<?php echo $post_number; ?>, <?php echo json_encode($archiveImgArray); ?>);
				</script>
	
			<?php else : ?>
	
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
	
			<?php endif; ?>
		
			
			</section>
			
			<section id="archive" class="col-lg-4 blog-archive">
            
            <div class="container">
                <div class="row">
            
                   <div class="archive hidden-md-down">
                   
                   	<div class="category-title shdadow hidden-sm-down"><h2 class="italic"><?php echo 'Latest Posts'; ?></h2>
					<?php if ($post_number > 0) {?>
					<?php }else {?>
					<h2 class="italic"> no posts </h2>
					<?php } ?>
					</div>
                   
                    <h2 class="archive-title"><span class="fa fa-archive fa-lg" aria-hidden="true"></span>Archive</h2>
                    <?php //var_dump( $page ); ?>
                    <?php //echo do_shortcode( '[archives type="monthly" limit="12" showcount="true"]' ); ?>
                    <?php $calender_args = array(
                    	'different_theme' => 1,
                    	'theme' => 'twentyfourteenlight'
                   		 );
                    ?>
                    
                 
                    <?php echo archive_calendar( $calender_args ); ?>
                 
                 	<div class="post-navigation">
	                    <div class="container">
	           				<div class="row">
	  
	             				
	             				<div class="col-xs-6"><?php next_posts_link( '<< Older posts' ); ?></div>
	                    
	                    		<div class="col-xs-6"><?php previous_posts_link( 'Newer posts >>' ); ?></div>
	          
	          					<?php //the_posts_navigation(); ?>
	          
	             
	             			</div>
	       				</div>
                    </div>
                    
                   </div>
                  
                </div>  
            </div>
            
            </section>
		
		</section>
		
		</main><!-- #main -->
	</div><!-- #primary -->

<?php wp_reset_postdata(); ?>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>