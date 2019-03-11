<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CivilWar
 */

?>

	</div><!-- #content -->
	
<!-- 	<section class="footer"> -->
      
       
<!-- <div class="container footer-links"> -->
<!--     <div class="row"> -->
<!--         <div class="col-lg-4"> -->
<!--             <h3>Latest Posts</h3> -->
<!--             <ul> -->
<!--                 <li class="post-link"> -->
                    
                    
<!--                     <a class="media-left" href="#"> -->
<!--                        <div class="circle-bg"> -->
<!--                         <img class="media-object circle" alt="Generic placeholder image" src="images/ripper.png"> -->
<!--                         </div> -->
<!--                       </a> -->
<!--                     <div class="media-body"> -->
<!--                         <h4 class="media-heading">Yorkshire Ripper</h4> -->
<!--                         <p>Published 19th January</p> -->
<!--                       </div> -->
                    
                    
<!--                 </li> -->
<!--                 <li class="post-link"> -->
                    
<!--                     <a class="media-left" href="#"> -->
<!--                        <div class="circle-bg"> -->
<!--                         <img class="media-object circle" alt="Generic placeholder image" src="images/darcy.png"> -->
<!--                         </div> -->
<!--                       </a> -->
<!--                     <div class="media-body"> -->
<!--                         <h4 class="media-heading">Ballad Of John Darcy</h4> -->
<!--                         <p>Published 25th December</p> -->
<!--                       </div> -->
                    
<!--                 </li> -->
<!--                 <li class="post-link"> -->
                    
<!--                     <a class="media-left" href="#"> -->
<!--                        <div class="circle-bg"> -->
<!--                         <img class="media-object circle" alt="Generic placeholder image" src="images/fire.png"> -->
<!--                         </div> -->
<!--                       </a> -->
<!--                     <div class="media-body"> -->
<!--                         <h4 class="media-heading">The Great Fire</h4> -->
<!--                         <p>Published 7th December</p> -->
<!--                       </div> -->
<!--                 </li> -->
<!--             </ul> -->
<!--         </div> -->
<!--         <div class="col-lg-4"> -->
<!--             <h3>Social</h3> -->
<!--             <ul> -->
<!--                 <li class="social-link"> -->
<!--                     <a class="media-left" href="https://twitter.com/rileystella"> -->
<!--                        <div class="circle-outline"> -->
<!--                         <div class="media-object circle" alt="Generic placeholder image"> -->
<!--                         <span class="fa fa-twitter"></span> -->
<!--                         </div> -->
<!--                         </div> -->
<!--                       </a> -->
<!--                     <div class="media-body"> -->
<!--                         <p class="social-name">Twitter</p> -->
<!--                       </div> -->
<!--                 </li> -->
                
<!--                 <li class="social-link"> -->
<!--                     <a class="media-left" href="#"> -->
<!--                        <div class="circle-outline"> -->
<!--                         <div class="media-object circle" alt="Generic placeholder image"> -->
<!--                         <span class="fa fa-youtube-play"></span> -->
<!--                         </div> -->
<!--                         </div> -->
<!--                       </a> -->
<!--                     <div class="media-body"> -->
<!--                         <p class="social-name">Youtube</p> -->
<!--                       </div> -->
<!--                 </li> -->
                
<!--                 <li class="social-link"> -->
<!--                     <a class="media-left" href="http://www.goodreads.com/author/show/1325505.Stella_Riley"> -->
<!--                        <div class="circle-outline"> -->
<!--                         <div class="media-object circle" alt="Generic placeholder image"> -->
<!--                         <span class="fa fa-book"></span> -->
<!--                         </div> -->
<!--                         </div> -->
<!--                       </a> -->
<!--                     <div class="media-body"> -->
<!--                         <p class="social-name">Goodreads</p> -->
<!--                       </div> -->
<!--                 </li> -->
<!--             </ul> -->
<!--         </div> -->
<!--         <div class="col-lg-4"> -->
<!--             <h3>Contact</h3> -->
<!--             <ul> -->
               
<!--                <li class="social-link"> -->
<!--                     <a class="media-left" href="#"> -->
<!--                        <div class="circle-outline"> -->
<!--                         <div class="media-object circle" alt="Generic placeholder image"> -->
<!--                         <span class="fa fa-envelope-o"></span> -->
<!--                         </div> -->
<!--                         </div> -->
<!--                       </a> -->
<!--                     <div class="media-body"> -->
<!--                         <p class="social-name">StellaRiley@Gmail.com</p> -->
<!--                       </div> -->
<!--                 </li> -->

<!--             </ul> -->
<!--         </div> -->
<!--     </div> -->
<!-- </div> -->
        

<!--     </section> -->


<!-- <div id="footer-sidebar" class="secondary"> -->
<!-- 	<div id="footer-sidebar1"> -->

<section class="footer">
	<div class="container footer-links">
    	<div class="row">
			<div id="footer-sidebar1">
			<?php
			if (is_active_sidebar ( 'footer-sidebar-1' )) {
				echo '<div class="col-lg-4">';
				dynamic_sidebar ( 'footer-sidebar-1' );
				echo '</div>';
			}
			?>
			</div>
			<div id="footer-sidebar2">
			<?php
			if (is_active_sidebar ( 'footer-sidebar-2' )) {
				echo '<div class="col-lg-4">';
				dynamic_sidebar ( 'footer-sidebar-2' );
				echo '</div>';
			}
			?>
			</div>
			<div id="footer-sidebar3">
			<?php
			if (is_active_sidebar ( 'footer-sidebar-3' )) {
				echo '<div class="col-lg-4">';
				dynamic_sidebar ( 'footer-sidebar-3' );
				echo '</div>';
			}
			?>
			</div>
		</div>
	</div>
	
<div class="footer-bottom">
           <div class="container">
           <div class="row">
            <p class="col-lg-6 text-md-left copyright">Copyright Stella Riley, All rights reserved</p>
    <p class="col-lg-6 text-md-right">Design by: <a href="http://www.mgdesignuk.co.uk" style="text-decoration: none; color: white;"><img src="<?php bloginfo('template_url'); ?>/images/mglogogrey.png" style="height: 50px; width:50px;"/> Matthew Gallagher</p></a>
            </div>
    
    </div>
</div>
	
	
</section>

	
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
