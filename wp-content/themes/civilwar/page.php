<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CivilWar
 */

get_header(); 
?>

<script type="text/javascript">
navbarScroll();
</script>

<?php
/*
*		PARALLAX HEADER
*/
?>

<?php if ( has_post_thumbnail() ); { ?>

<?php $feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>

<section id="hero" class="page-hero" data-image-src="<?php echo $feat_image_url[0]; ?>" data-parallax="scroll">
   <div class="container">
      <div class="row">
        <h1 class="text-uppercase col-xs-12 shadow"><?php the_title(); ?></h1>
       </div>
    </div>
</section>

<?php } ?>

<?php 
/*
*		POST BOOKS IF LIBRARY PAGE
*/
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php if( is_page('library') ) { ?>
			
				<?php get_template_part( 'template-parts/library'); ?>
				
			<?php }elseif ( is_page_template( 'Author' ) ) { ?>
			
				<?php get_header( 'author' ); ?>
				
			<?php }else { ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php } ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
