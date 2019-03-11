<?php
/**
 * My Custom Home php
 *
 * Copied from index.php, this should redirect to this page
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CivilWar
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		$args = array(
				'post_type' => 'book',
				'post_status'=>'publish',
				'posts_per_page' => '4');
		
		$loop = new WP_Query( $args );
		
		$found_books = $loop->found_posts;
		
		?>

		<?php if ( $loop->have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>
			
			<section class="Releases">
				<div class="container">
					<div class="row">
			
					<h2 class="releases-title text-xs-center brown text-uppercase">Latest Releases</h2>
			
			
					<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
		
						<?php
						
						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part ( 'template-parts/content-books', get_post_format () );
						
						?>
		
					<?php endwhile; ?>
					
					</div>
				</div>
			</section>
			
			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>


		<?php get_template_part( 'template-parts/carouselphp' ); ?>


		</main><!-- #main -->
	</div><!-- #primary -->

<?php // get_sidebar(); ?>
<?php get_footer(); ?>
