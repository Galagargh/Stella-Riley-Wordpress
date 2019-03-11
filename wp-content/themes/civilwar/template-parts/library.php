<?php
/*
 * 		Library Template
 */
?>

<?php
$taxonomy = 'book_category';
$tax_terms = get_terms($taxonomy);
$bookcount = 1;

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

	
	
	$book_category = $loop->found_posts;
	
	//if ($book_category % 3 == 1)
	
		switch ($book_category % 3) {
			case 1:
				$tag = "col-md-12";
				break;
			case 2:
				$tag = "col-md-6";
				break;
			case 0:
				$tag = "col-md-4";
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
							
								<li><a class="book-link italic" href="<?php echo "#" . $tax_term->name . "-" . get_the_title(); ?>"><?php echo get_the_title();?></a></li>
							
								<?php endwhile;?>
								
							</ul>
							
						</div>

						<?php $count = 1; ?>
						
						<?php 
						
						 ?>
						
						<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
								
								<?php include( locate_template( 'template-parts/content-books.php' ) ); ?>
								
								<?php
								
								// rows
								$category_row = floor($book_category / 3);
								
								// remainder
								$remainder = $book_category % 3;
								
								if ( $count == 3 && $category_row > 1 && $tag != "col-md-6") {
										
									//if there are full rows left the tag is set
									if ( $category_row > 1 ) {
										$book_category == $book_category - 3;
										//$category_row == $category_row - 1;
										$tag = "col-md-4";
										$count = 0;
											
									} else {
								
										switch ($book_category % 3) {
											case 1:
												$tag = "col-md-12";
												break;
											case 2:
												$tag = "col-md-6";
												break;
											case 0:
												$tag = "col-md-4";
												break;
								
												$count = 0;
								
										}
								
									}
										
								} elseif ( $count < 3 && $category_row < 1 && $count < $remainder && $tag != "col-md-6") {
										
									switch ($book_category % 3) {
										case 1:
											$tag = "col-md-12";
											break;
										case 2:
											$tag = "col-md-6";
											break;
										case 0:
											$tag = "col-md-4";
											break;
												
											$count = 0;
												
									}
										
								} elseif ($tag == "col-md-6"){
										
									$tag = "col-md-6";
									$count = 0;
										
								}else {
										
									$tag = "col-md-4";
									$count++;
								}
								
								?>
								
						<?php endwhile; // End of the loop. ?>
				
					</div><!-- row -->
				
				</div><!-- container -->
				
			</section><!-- Library -->
			
	<?php endif ?>
	
<?php } ?>
				