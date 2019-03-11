<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CivilWar
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>



	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
	<section class="comments-section">
	<div class="container">
	    <div class="row">
		<h2 class="text-xs-left"><span class="fa fa-comments-o fa-lg comments-icon" aria-hidden="true"></span>Comments</h2>
			<h4 class="subheading italic" style="padding-top:5px;">
			<?php
				printf( // WPCS: XSS OK.
					esc_html( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'civilwar' ) ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			?>
			</h4>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'civilwar' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'civilwar' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'civilwar' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'civilwar' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'civilwar' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'civilwar' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php endif; // Check for comment navigation. ?>
	</section><!-- #comments -->
	<?php endif; // Check for have_comments(). ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'civilwar' ); ?></p>
	<?php endif; ?>

	

<?php if ( comments_open() == "true"  ) { ?>

<section class="comment-reply-section"> 
    
    <div class="container">
        <div class="row">
            <h2 class="text-xs-left"><span class="fa fa-pencil-square-o fa-lg comments-icon" style="color:white;" aria-hidden="true"></span>Write a Reply</h2>
            
        </div>
    </div>
 
<?php
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );

 $fields = array(
 	'author' => '<div class="input-group comment-form-author">'  .
        '<input id="author" class="form-control" name="author" type="text" placeholder="' . ( $req ? '*' : '' ) . 'Name" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' ></div>',
    'email'  => '<div class="input-group comment-form-email">' .
        '<input id="email" class="form-control" name="email" type="text" placeholder="' . ( $req ? '*' : '' ) . 'Email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"' . $aria_req . ' ></div>',
 		
//  		<div class="input-group">
//  		<input type="text" class="form-control" placeholder="Email" aria-describedby="basic-addon1">
//  		</div>
);
 
 $comments_args = array(
 	'title_reply'  => '',
 	'label_submit' => 'Post Comment',
 	'class_submit' => 'btn italic',
 		
 	'comment_field' =>  '<div class="input-group"><label for="comment">' .
    '</label><textarea id="comment" class="form-control" placeholder="Write your comment here" name="comment" cols="45" rows="8" aria-required="true">' .
    '</textarea></div>',
 		
 	'must_log_in' => '<p class="must-log-in italic">' .
 	sprintf(
 		__( 'You must be <a href="%s">logged in</a> to post a comment.' ),
 		wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
 	) . '</p>',
 		
 	'logged_in_as' => '<p class="logged-in-as italic">' .
 	sprintf(
 		__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
 		admin_url( 'profile.php' ),
 		$user_identity,
 		wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
 	) . '</p>',
 		
//  	'comment_notes_before' => '<p class="comment-notes italic">' .
//  		__( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) .
//  	'</p>',

 	'title_reply_before' => '<h2 class ="text-xs-left">' .
 							'</h2>',
 		
 	'title_reply_after' => '</h2>',
 		
 	'fields' =>  apply_filters( 'comment_form_default_fields', $fields ),
);
 ?>


 <div class="container">
 	<div class="row">
 
<!--  	<div class="input-group"> -->
<!--     	<textarea class="form-control" placeholder="Write your comment here" aria-describedby="basic-addon1"></textarea> -->
<!-- 	</div> -->
 
	<?php comment_form($comments_args); ?>

 	</div>
 </div>

</section>

<?php } ?>