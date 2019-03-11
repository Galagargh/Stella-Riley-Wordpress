<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CivilWar
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

<?php 
/*
*		Include this in here if cant use an if statement in functions
*		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/carousel.js"></script>
*/
?>

</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'civilwar' ); ?></a>

		<nav id="navbar" class="navbar navbar-fixed-top navbar-dark bg-inverse" style="background-color: rgba(127, 105, 84, 0);">

		<button class="navbar-toggler hidden-sm-up pull-xs-right"
			type="button" data-toggle="collapse" data-target="#StellaNav"
			aria-expanded="false" aria-controls="navbar">
			<span class="fa fa-bars" aria-hidden="true" style="color:white;"></span>
		</button>

		<div class="container">
			<div class="row">

			<a class="navbar-brand" href="<?php bloginfo('url')?>"><?php bloginfo('name')?></a>

			<?php
			wp_nav_menu ( array (
					'menu' => 'primary',
					'theme_location'	=> 'primary',
	            	'menu_id' 			=> 'primary-menu',
	                'depth'             => 2,
	                'container'         => 'div',
					'container_class'   => 'collapse navbar-toggleable-xs',
					'container_id'   	=> 'StellaNav',
	                'menu_class'  	 	=> 'nav navbar-nav pull-right',
	                'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
	                'walker'            => new wp_bootstrap_navwalker())
	            );
	        ?>
        
        	</div>
		</div>
		
	</nav> <!-- #site-navigation -->
	
	
	<!--class for masthead was previously site-header-->
	<?php if ( get_header_image() && is_front_page()) { ?>
	<header id="masthead" class="highlight-box" style="background-image:url(<?php bloginfo('template_url'); ?>/images/1642civilwar.jpg"); background-size:cover; padding-top:3rem; padding-bottom:0;" role="banner">
	
	
	
	<?php
	//	change this so if there is no post thumbnail have a customised gradient/colour instead
	/*else { 
	<header id="masthead" class="highlight-box" role="banner">
	 }*/ ?>
	
	<div class="container">
		<div class="row">
	
		<div class="col-lg-6 img-responsive highlight-pic pull-lg-left hidden-md-down">
        	<img src="<?php bloginfo('template_url'); ?>/images/stella.png" alt="Stella" height="40%"/>
        </div>
              
        <div class="col-lg-6 pull-lg-right highlight-description">    
		<div class="site-branding">
			<?php if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title shadow" style="color:white;"><?php bloginfo( 'name' ); ?></h1>
			<?php else : ?>
				<h1 class="site-title shadow" style="color:white;"><?php bloginfo( 'name' ); ?></h1>
			<?php endif;

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description lead shadow"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->
		
		<?php if ( is_front_page() && is_home() ) : ?>
		
		<?php else : ?>
		<div class="calltoaction row">
                   
                   <div class="col-md-4">
                       <button type="button" class="btn btn-primary btn-lg italic" onclick="window.location.href='http://www.amazon.co.uk/Stella-Riley/e/B0034PB7UU'"><span>Amazon</span></button>
                   </div>
                    
                     <div class="col-md-8">
					<div class="shadow" style="color:white;">
					<?php echo do_shortcode( '[good-reviews random=1 limit=5 cycle="fader"]' ); ?>
                    </div>
        </div>
        <?php endif; ?>
		
		</div>
		</div>
	</div>
	</header><!-- #masthead -->
	
	<?php if ( is_archive() ) { 
	$page_title = "Blog";
	 } ?>
	
	<?php if ( $page_title = "Blog" || is_archive() ) { ?>
		<script type="text/javascript">
		console.log("archive has been loaded");
		navbarScroll();
		</script>
	<?php }else{ ?>
		<script type="text/javascript">
		console.log("an other page has been loaded");
		navbarScroll("other");
		</script>
	<?php } ?>

<?php } ?><!-- if is home page -->

	<div id="content" class="site-content">
