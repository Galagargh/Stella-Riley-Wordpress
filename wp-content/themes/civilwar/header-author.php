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



		<nav class="navbar navbar-fixed-top navbar-dark bg-inverse" style="background-color: rgba(127, 105, 84, 0);">

		<button class="navbar-toggler hidden-sm-up pull-xs-right"
			type="button" data-toggle="collapse" data-target="#StellaNav"
			aria-expanded="false" aria-controls="navbar">
			<span class="fa fa-bars" aria-hidden="true"></span>
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
		
	</nav>
	
<div id="content" class="site-content">