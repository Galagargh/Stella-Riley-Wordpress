<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CivilWar
 */
?>

<script type="text/javascript">
	navbarScroll("other");
</script>

<?php $blockquote = get_field('quote'); ?>
<?php $quoter = get_field('quoter'); ?>


<?php if ( has_post_thumbnail() ) { ?>

<?php $feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
<?php $feat_image_url = $feat_image_url[0]; ?>

<?php }else { ?>

<?php $feat_image_url = get_template_directory_uri() . '/images/placeholder_bg.png'; ?>

<?php }?>

<section id="hero" class="blog-hero" data-parallax="scroll" data-image-src="<?php echo $feat_image_url; ?>">
   <div class="container">
      <div class="row">
        <h2 class="blog-hero-heading text-uppercase text-xs-center shadow"><?php the_title(); ?></h2>
       </div>
    </div>
    <div class="author-bar">
           <div class="container">
               <div class="row text-xs-center">
                   <?php echo get_avatar( get_the_author_meta( 'ID' ), 60 ); ?>
                   <!-- apply_filters( array( 'class' => 'img-circle' ) ) -->
                   <p class="shadow italic">by <?php the_author(); ?></p>
                   <p class="shadow italic"><?php the_date(); ?></p>
               </div>
           </div>
       </div>
</section>




    <section id="blog" class="blog-body">
      
      <?php /* ?>
      <div id="share" class="share-flag">
         
         <table class="table table-hover">
             <thead>
                 <tr>
                     <th>
                         <!-- <span class="fa fa-share-alt share-icon" aria-hidden="true"></span> -->
                         
                     </th>
                 </tr>
             </thead>
         <tbody>
             <tr>
                 <th scope="row">
                     <span class="fa fa-facebook" aria-hidden="true"></span>
                 </th>
             </tr>
         </tbody>
         <tr>
             <th scope="row">
                 <span class="fa fa-google-plus" aria-hidden="true"></span>
             </th>
         </tr>
         <tr>
             <th scope="row">
                 <span class="fa fa-reddit-alien" aria-hidden="true"></span>
             </th>
         </tr>
          </table>
          
      </div>
      <?php */ ?>
      
       <div class="container">
            <div class="row">
                <div class="blog-text">
                <?php the_content(); ?>
                </div>
            </div>
       </div>
       
</section>

<section class="share-section">
	<div class="share-mobile">
		<div class="container">
			<div class="row col-xs-12">
				<h2>
					<span class="fa fa-share-alt fa-lg share-icon-mobile"
						aria-hidden="true"></span>Share
				</h2>
				<div class="social-icons">
				
					<?php echo do_shortcode( '[addtoany]' ); ?>

					<?php /*?>

					<a href="#"> <span class="fa fa-facebook-square fa-3x"
						aria-hidden="true"></span>
					</a> <a href="#"> <span class="fa fa-twitter-square fa-3x"
						aria-hidden="true"></span>
					</a> <a href="#"> <span class="fa fa-reddit-square fa-3x"
						aria-hidden="true"></span>
					</a> <a href="#"> <span class="fa fa-google-plus-square fa-3x"
						aria-hidden="true"></span>
					</a>
					
					<?php */ ?>

				</div>
			</div>
		</div>
	</div>
</section>

<?php if ( $blockquote != null ) { ?>
<section id="blockquote" class="blog-blockquote">

	<div id="blog-blockquote-container">
		<div class="container">
			<div class="row">

				<p class="quote italic shadow">"<?php echo $blockquote; ?>"</p>

				<p class="quoter shadow text-xs-right"><?php echo $quoter; ?></p>

			</div>
		</div>
	</div>
</section>
<?php } ?>

    