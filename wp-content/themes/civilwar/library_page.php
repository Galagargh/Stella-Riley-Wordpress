<?php
/*
 * 		Template Name: Library
 */
?>

<?php
$taxonomy = 'book_category';
$tax_terms = get_terms($taxonomy);

foreach($tax_terms as $tax_term){
	wp_reset_query();
		
	$args = array(
			'post_type' => 'book',
			'post_status'=>'publish',
			'tax_query' => array(
					array(
							'taxonomy' => 'book_category',
							'field'    => 'slug',
							'terms'    => $tax_term->slug,
					),
			),
	);

	$loop = new WP_Query( $args );

		
	// 					if($loop->have_posts()) {

	// 						echo '<h2>'.$tax_term->name.'</h2>';
		
	// 						while($loop->have_posts()) : $loop->the_post();
	// 							echo '<p><a href="'.get_permalink().'">'.get_the_title().'</a></p>';
	// 						endwhile;
	// 					}

	
	$book_category = 5;
	
	if ($book_category % 3 == 1)
	
	switch ($book_category % 3) {
    case 1:
        $number = $varA;
        break;
    case 2:
        $number = $varB;
        break;
    case 0:
        $number = $varC;
        break;
	}
	
	?>

		<?php if ( $loop->have_posts() ) : ?>
		
			<section class="library <?php echo $tax_term->name; ?>">
			
				<h1 class="book-header text-uppercase"><?php echo $tax_term->name; ?></h1><!-- Category Header -->
				
				<div class="container">
				
					<div class="row">
					
						<div id="book-list-wrapper" class="col-xs-12"><!-- Book list for mobile devices -->
						
							<ul class="hidden-sm-up text-xs-center text-lg-left">
							
								<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
							
								<li><a class="book-link italic" href="#book-<?php echo get_the_title();?>" ><?php echo get_the_title();?></a></li>
							
								<?php endwhile;?>
								
							</ul>
							
						</div>

						<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
					
								<?php //get_template_part( 'template-parts/content-books', get_post_format() ); ?>
								
								<?php include( locate_template( 'template-parts/content-books.php' ) ); ?>
		
						<?php endwhile; // End of the loop. ?>
				
					</div><!-- row -->
				
				</div><!-- container -->
				
			</section><!-- Library -->
			
	<?php endif ?>
	
<?php } ?>
				