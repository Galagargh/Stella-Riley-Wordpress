<?php

// Create out post type
add_action( 'init', 'create_post_type' );
function create_post_type() {
	$args = array(
			'labels' => post_type_labels( 'Book' ),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title',
					'editor',
					'author',
					'thumbnail',
					'excerpt',
					'category',
					'comments'
			)
	);

	register_post_type( 'book', $args );
}

add_action( 'init', 'book_category_taxonomy', 0 );
function book_category_taxonomy() {
	$labels = array(
			'name'              => _x( 'Book Categories', 'taxonomy general name', 'text_domain' ),
			'singular_name'     => _x( 'Book Category', 'taxonomy singular name', 'text_domain' ),
			'search_items'      => __( 'Search Book Categories', 'text_domain'  ),
			'all_items'         => __( 'All Book Categories', 'text_domain'  ),
			'parent_item'       => __( 'Parent Book Category', 'text_domain'  ),
			'parent_item_colon' => __( 'Parent Book Category:', 'text_domain'  ),
			'edit_item'         => __( 'Edit Book Category', 'text_domain'  ),
			'update_item'       => __( 'Update Book Category', 'text_domain'  ),
			'add_new_item'      => __( 'Add New Book Category', 'text_domain'  ),
			'new_item_name'     => __( 'New Book Category', 'text_domain'  ),
			'menu_name'         => __( 'Book Categories', 'text_domain'  ),
	);
	$args = array(
			'labels' => $labels,
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column'	=> true,
			'show_in_nav_menus'	=> true
	);
	register_taxonomy( 'book_category', 'book', $args );

}

// A helper function for generating the labels
function post_type_labels( $singular, $plural = '' )
{
	if( $plural == '') $plural = $singular .'s';

	return array(
			'name' => _x( $plural, 'post type general name' ),
			'singular_name' => _x( $singular, 'post type singular name' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add New '. $singular ),
			'edit_item' => __( 'Edit '. $singular ),
			'new_item' => __( 'New '. $singular ),
			'view_item' => __( 'View '. $singular ),
			'search_items' => __( 'Search '. $plural ),
			'not_found' => __( 'No '. $plural .' found' ),
			'not_found_in_trash' => __( 'No '. $plural .' found in Trash' ),
			'parent_item_colon' => ''
	);
}

//add filter to ensure the text Book, or book, is displayed when user updates a book
add_filter('post_updated_messages', 'post_type_updated_messages');
function post_type_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['book'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Book updated. <a href="%s">View book</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __('Book updated.'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Book restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Book published. <a href="%s">View book</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Book saved.'),
			8 => sprintf( __('Book submitted. <a target="_blank" href="%s">Preview book</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Book scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview book</a>'),
			// translators: Publish box date format, see php.net/date
					date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Book draft updated. <a target="_blank" href="%s">Preview book</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	return $messages;
}


?>