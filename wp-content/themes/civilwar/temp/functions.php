<?php
/**
 * CivilWar functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CivilWar
 */



if ( ! function_exists( 'civilwar_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function civilwar_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on CivilWar, use a find and replace
	 * to change 'civilwar' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'civilwar', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	

	//include wp navwalker
	require_once('wp_bootstrap_navwalker.php');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'civilwar' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'civilwar_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // civilwar_setup
add_action( 'after_setup_theme', 'civilwar_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function civilwar_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'civilwar_content_width', 640 );
}
add_action( 'after_setup_theme', 'civilwar_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function civilwar_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'civilwar' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'civilwar_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function civilwar_scripts() {
	
	wp_enqueue_style( 'civilwar-style', get_stylesheet_uri() );

	wp_enqueue_script( 'jquery', get_template_directory_uri() . '/bower_components/jquery/jquery.min.js', array(), '3.0.0', true );
	
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/bower_components/bootstrap/dist/js/bootstrap.js', array(), '4.0.0-alpha', true );
	
	wp_enqueue_script( 'parallax.js', get_template_directory_uri() . '/bower_components/parallax.js/parallax.min.js', array(), '1.4.2', true );
	
	wp_enqueue_script( 'tether', get_template_directory_uri() . '/bower_components/tether/dist/js/tether.min.js', array(), '1.3.2', true );

	wp_enqueue_script( 'civilwar-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	wp_enqueue_script( 'civilwar-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '100215', true );

// 	echo '<script type="text/javascript">';
// 	echo 'jquery(document).ready(function($) {';
// 	echo 'navbarScroll();';
// 	echo '});';
// 	echo '</script>';

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'civilwar_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
