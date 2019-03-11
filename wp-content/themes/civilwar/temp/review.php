<div class="pull-left" href="reviewers website">

<?php if ( !empty( $post_meta['img'] ) ) : ?>
	<?php echo $post_meta['img']; ?>
<?php else : ?>
	<img class="media-object circle" alt="No Author Image"  src="<?php bloginfo('template_url'); ?>/images/nopicture.jpg"/>
<?php endif; ?>

</div>





<?php if ( $post_meta['review_url'] ) : ?>
						<a class="gr-review-url" itemprop="url" href="<?php echo esc_attr( $post_meta['review_url'] ); ?>">
							<?php echo __( 'Read More', 'good-reviews-wp'); ?>
						</a>
						<?php endif;

					else :
					?>
						<a class="gr-review-url" itemprop="sameAs" href="<?php echo get_permalink(); ?>">
							<?php echo __( 'Read More', 'good-reviews-wp'); ?>
						</a>
					<?php endif; ?>
					
					
<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'civilwar' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'civilwar' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'civilwar' ), 'civilwar', '<a href="http://www.mgdesign.co.uk" rel="designer">MattG</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->