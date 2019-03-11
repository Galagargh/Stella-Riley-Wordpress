<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package CivilWar
 */

get_header(); ?>

<script type="text/javascript">
navbarScroll("Blog");
</script>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
			
				<header class="page-header">
				<div class="container">
					<div class="row">
						<h1 class="page-title shadow"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'civilwar' ); ?></h1>
					</div>
				</div>
				</header><!-- .page-header -->
				

				<div class="page-content not-found-content">
				<div class="container">
					<div class="row">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'civilwar' ); ?></p>

					<?php get_search_form(); ?>

					<?php /* comment out this garbage ?>
					<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

					<?php if ( civilwar_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>
					<div class="widget widget_categories">
						<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'civilwar' ); ?></h2>
						<ul>
						<?php
							wp_list_categories( array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							) );
						?>
						</ul>
					</div><!-- .widget -->
					<?php endif; ?>

					<?php
						
						$archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'civilwar' ), convert_smilies( ':)' ) ) . '</p>';
						the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
					?>

					<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
					
					<?php done */ ?>
					</div>
				</div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
