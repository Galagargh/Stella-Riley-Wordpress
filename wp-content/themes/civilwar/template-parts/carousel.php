<?php
/**
 * The carousel displaying all featured blog posts, currently displays placeholder content
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CivilWar
 */

?>

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

		
		foreach( $stickied_posts as $post )
		{
// 			if (has_post_thumbnail( $post["ID"] ) ){
			
			$content = get_post_field( 'post_content', $post["ID"] );
			$content = strip_tags($content);
			$content = wp_trim_words($content);
				
			echo get_the_post_thumbnail( $post["ID"], 'full', array( 'style' => 'object-fit:cover') );
			echo '<h3>' . get_the_title($post["ID"]) . '</h3>';
			echo '<p class="published">published on: ' . '<span class="italic">' . get_the_date('jS F, Y', $post["ID"]) . '</span></p>';
			echo '<p class="blog-post-teaser">' . $content . '</p>';
			
			wp_reset_postdata();
			
		}
		
		
	}

?>

</section>


/*
*		Carousel HTML
*		Convert this to PHP
*
*/

<section class="Latest-Blog">

                     
  <div id="blog_posts" class="carousel slide" data-ride="carousel">
  
  <ol class="carousel-indicators">
    <li data-target="#blog_posts" data-slide-to="0" class="active"></li>
    <li data-target="#blog_posts" data-slide-to="1"></li>
    <li data-target="#blog_posts" data-slide-to="2"></li>
    <li data-target="#blog_posts" data-slide-to="3"></li>
  </ol>
  
  <button type="button" class="btn btn-primary btn-lg italic view-post-btn" role="button">View Post</button>
  
  <div class="carousel-inner" role="listbox">
   
    <div class="carousel-item active">
      <img src="<?php bloginfo('template_url'); ?>/temp/AnthonyVanDyck_Large.jpg" alt="Anthony Van Dyck" style="object-fit:cover">
         
          <div class="container">
              <div class="carousel-caption shadow">
                <h3>Anthony Van Dyck</h3>
                <p class="published">published on: <i class="italic">Date Goes Here</i></p>
                <p class="blog-post-teaser">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad ipsa suscipit ratione dolorum, sint, praesentium nesciunt provident eaque, quae ducimus incidunt facilis fugiat? Sit numquam officiis, quis corporis deleniti nulla, quidem fugit beatae veniam nobis odit reprehenderit eius perferendis, commodi quod rerum ratione nam. Velit non sint assumenda ipsa ab.</p>
              </div>
          </div>
    
    
    </div>
          
    <div class="carousel-item">
      <img src="<?php bloginfo('template_url'); ?>/temp/Charles_Landseer_Cromwell_Battle_of_Naseby(large).JPG" alt="Cromwell" style="object-fit:cover">
      
      <div class="container">
          <div class="carousel-caption shadow">
            <h3>Cromwell & The Battle of Naseby</h3>
            <p class="published">published on: <i class="italic">Date Goes Here</i></p>
            <p class="blog-post-teaser">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad ipsa suscipit ratione dolorum, sint, praesentium nesciunt provident eaque, quae ducimus incidunt facilis fugiat? Sit numquam officiis, quis corporis deleniti nulla, quidem fugit beatae veniam nobis odit reprehenderit eius perferendis, commodi quod rerum ratione nam. Velit non sint assumenda ipsa ab.</p>
        </div>
      </div>
      
    </div>
    
    <div class="carousel-item">
      <img src="<?php bloginfo('template_url'); ?>/temp/The_Plunering_of_Basing_House.jpg" alt="The Plundering Of Basing" style="object-fit:cover">
      
      <div class="container">
            <div class="carousel-caption shadow">
                <h3>The Plundering of Basing House</h3>
                <p class="published">Published on: <i class="italic">Date Goes Here</i></p>
                <p class="blog-post-teaser">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad ipsa suscipit ratione dolorum, sint, praesentium nesciunt provident eaque, quae ducimus incidunt facilis fugiat? Sit numquam officiis, quis corporis deleniti nulla, quidem fugit beatae veniam nobis odit reprehenderit eius perferendis, commodi quod rerum ratione nam. Velit non sint assumenda ipsa ab.</p>
            </div>
          </div>
      </div>
      
      

       
        
  </div>
  
  <a class="left carousel-control" href="#blog_posts" role="button" data-slide="prev">
    <!--<span class="icon-prev" aria-hidden="true"></span>-->
    <span class="icon-prev" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#blog_posts" role="button" data-slide="next">
    <span class="icon-next" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
  
</div>
                    
<script>
      jQuery(document).ready(function($){
        $('.carousel').carousel({
          interval: 90000
        });
      });
</script>


</section>