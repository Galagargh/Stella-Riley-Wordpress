<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package CivilWar
 */

get_header(); ?>

<script type="text/javascript">
navbarScroll("Blog");
</script>

	<section id="primary" class="content-area search">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
			<div class="container">
				<div class="row">
					<h1 class="col-lg-12 page-title shadow"><?php printf( esc_html__( 'Search Results for: %s', 'civilwar' ), '<span class="italic">' .  '"' . get_search_query() . '"' . '</span>' ); ?></h1>
				</div>	
			</div>
			</header><!-- .page-header -->

			<div class="search-results">
				<div class="container">
					<div class="row">

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
		
						<?php
						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );
						?>
		
					<?php endwhile; ?>
			
					</div>
				</div>
			</div>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
