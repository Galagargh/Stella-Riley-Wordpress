<?php
/**
 * Template part for displaying posts.
*
* @link https://codex.wordpress.org/Template_Hierarchy
*
* @package CivilWar
*/

?>

<?php 

	error_reporting(0);
	ini_set('display_errors', 0);

	$paged = 1;
    if ( get_query_var('paged') ) $paged = get_query_var('paged');
    if ( get_query_var('page') ) $paged = get_query_var('page');
?>
    
<?php $post_date = get_the_date('jS F, Y'); ?>


<?php if ( has_post_thumbnail() ) { ?>

		<!-- grab current post image to send to blogimages.js -->
		<?php $mob_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
		<?php $mob_image_url = $mob_image_url[0]; ?>
		<?php $feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
		<?php $archiveImgArray[$count] = $feat_image_url[0]; ?>
		
<?php } else { ?>

		<?php $mob_image_url = get_template_directory_uri() . '/images/placeholder_bg.png'; ?>
		<?php $feat_image_url = get_template_directory_uri() . '/images/placeholder_bg.png'; ?> 
		<?php $archiveImgArray[$count] = $feat_image_url; ?>
		
<?php } ?>

		<?php 
		$content = get_post_field( 'post_content', $latest_post["ID"] );
		$content = strip_tags($content);
		$content = wp_trim_words($content);
		?>
		
		<div class="blog-post-container blog-post-image<?php echo $count; ?>">
            
            <div class="blog-head-mobile hidden-sm-up">
            
            <img src="<?php echo $mob_image_url;?>" alt="">
            
                <div class="blog-title mobile">
                    <h3 class="post-title shadow"><?php echo the_title(); ?></h3>
                    <p class="published shadow">published on:&nbsp;<i class="italic"><?php echo $post_date; ?></i></p>    
                </div>
                
            </div>
            
            <div class="container">
                <div class="row">
            
                <div class="col-lg-8 blog-description">

                    <div class="blog-title desktop">
                        <h3 class="post-title shadow"><?php echo the_title(); ?></h3>
                        <p class="published shadow">Published on:&nbsp;<i class="italic"><?php echo $post_date; ?></i></p>    
                        <?php if (get_the_category_list() != "Uncategorized") {?>
                        <p class="categories shadow">Categories:&nbsp;<i class="italic"><?php echo the_category( ', ' ); ?></i></p>       
                        <?php } ?> 
                    </div>

                    <div class="blog-post-card">

                        <div class="blog-post-card-sum">
                        <p class="blog-extract"><?php echo $content; ?><a href="<?php the_permalink(); ?>"> Read More</a></p>
                        </div>
                        
                    </div>

                    <div class="blog-btn hidden-sm-down pull-sm-right">
                           <button type="button" class="btn btn-primary btn-lg  italic" onclick="window.location.href='<?php the_permalink(); ?>'">Read Post</button>
                    </div>

                </div>

                </div>
            </div>
            
		</div>
	
	<?php $count++ ?>


