<!-- if (has_post_thumbnail( $post["ID"] ) ){ -->


<section class="Latest-Blog">

<?php stickycarousel(); ?>

<?php

	function stickyCarousel() {
		
		$sticky = get_option( 'sticky_posts' );
		$args = array(
				'posts_per_page' => 5,
				'post__in'  => $sticky,
				'meta_key'    => '_thumbnail_id',
				'ignore_sticky_posts' => 1
		);
		$stickied_posts = wp_get_recent_posts( $args );
		$count_posts = query_posts( $args );
		
		global $wp_query;
		$carousel_posts = $wp_query->found_posts;

		echo '<div id="blog_posts" class="carousel slide" data-ride="carousel">' . "\n";
		
		echo '<ol class="carousel-indicators">'. "\n";
		
		
		$buildLiString = function($i, $isActive)
		{
			$liString ='<li %s data-target="#blog_posts" data-slide-to="%d">'. "\n";
			$activeClass = 'class="active"';
				
			if ($isActive) {
				return sprintf($liString, $activeClass, $i);
			} else {
				return sprintf($liString, '', $i);}
		};
		
			for ( $count=0; $count < $carousel_posts; $count++ ) {

				$liString = $buildLiString($count, $count == 0);
				echo $liString;
				
			};
			
		
		echo '</ol>'. "\n";
		
		echo '<button type="button" class="btn btn-primary btn-lg italic view-post-btn" role="button">View Post</button>'. "\n";
		
		echo '<div class="carousel-inner" role="listbox">'. "\n";
		

		$count = 0;
		$postLinkArray = array();
		
		foreach( $stickied_posts as $post )
		{
			//create post URL link array
			$currentURLlink = esc_url( get_permalink($post["ID"]) );
			$postLinkArray[] = $currentURLlink;
			
			//assign class active to the first post
			if ($count == 0){
				$isactive = ' active';
			} elseif ($count > 0) {
				$isactive = '';
			}
			
			//display first 30 words of the content
			$content = get_post_field( 'post_content', $post["ID"] );
			$content = strip_tags($content);
			$content = wp_trim_words($content);

			echo '<div class="carousel-item' . $isactive . '">'. "\n";
				echo get_the_post_thumbnail( $post["ID"], 'full', array( 'style' => 'object-fit:cover; max-width:100%;') );
				
				echo '<div class="container">'. "\n";
					echo '<div class="carousel-caption shadow">'. "\n";
						echo '<h3>' . get_the_title($post["ID"]) . '</h3>'. "\n";
						echo '<p class="published">published on: ' . '<span class="italic">' . get_the_date('jS F, Y', $post["ID"]) . '</span></p>'. "\n";
						echo '<p class="blog-post-teaser">' . $content . '</p>'. "\n";
					echo '</div>'. "\n";
				echo '</div>'. "\n";
			echo '</div>'. "\n";
			
			$count++;
			
		}
		

		echo '</div>'. "\n";
		
		echo '<a class="left carousel-control" href="#blog_posts" role="button" data-slide="prev">' . '<span class="icon-prev" aria-hidden="true"></span>' . '<span class="sr-only">Previous</span>' . '</a>'. "\n";
		echo '<a class="right carousel-control" href="#blog_posts" role="button" data-slide="next">' . '<span class="icon-next" aria-hidden="true"></span>' . '<span class="sr-only">Next</span>' . '</a>'. "\n";
		
		echo '</div>'. "\n";
		
		wp_reset_postdata();
?>
		<script type="text/javascript">
		carouselButton(<?php echo $carousel_posts ?>, <?php echo json_encode($postLinkArray) ?>);
		</script>
<?php	
	}

?>

</section>
