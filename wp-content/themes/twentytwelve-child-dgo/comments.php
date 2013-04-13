<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to twentytwelve_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child_DGO
 * @since Twenty Twelve 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">
	<?php 
	// build fake comment object, to serve as wrapper for the comment form
	global $current_user;
	get_currentuserinfo();	
	$comment = new stdClass();
	$comment->comment_type = 'dgocommentform';
	$comment->comment_ID = NULL;
	$comment->comment_post_ID = get_the_id();
	$comment->user_id = $current_user->ID;
	$comment->comment_author = $current_user->display_name;
	$comment->comment_author_email = $current_user->user_email;
	$comment->comment_date = current_time('mysql',0);
	$comment->comment_date_gmt = current_time('mysql',1);
	$comment->comment_author_url = '';
	$comment->comment_author_IP = '';
	$comment->comment_approved = 1;
	$comment->comment_karma = 0;
	$comment->comment_agent = '';
	$comment->comment_parent = 0;
	$comment->comment_content = '';
//	$comment->comment_content = '<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true"></textarea></p>';
////	$comment->comment_content .= '<ul><li><textarea style="width:100%;" class="tinymce_data" id="elm1" name="tinymce_data1"></textarea></li><li><textarea style="width:100%;" class="tinymce_data" id=" elem2" name="tinymce_data2"></textarea></li></ul>';
//	// add emoticons
//    if(function_exists('DGO_emoticons_get_them')){
//		$comment->comment_content .= '<p class="dgoemoticons" >'; 	
//	    $comment->comment_content .= DGO_emoticons_get_them();
//		$comment->comment_content .= '</p>'; 	
//    }
//	// end emoticons
	    
	// set preferences for listing the comments
	$list_comments_args = array( 
		'callback' => 'twentytwelve_child_dgo_comment', 
		'style' => 'ol',
		'avatar_size' => 66,
		'reverse_top_level' => true,
	);    	
	?>

	<ol class="commentlist">
		<?php
		if ( comments_open() ) : 
			// show comment form in the form of a comment
			twentytwelve_child_dgo_comment($comment, $list_comments_args, 0);
		else:
			/* If there are no comments and comments are closed, let's leave a note.
			 * But we only want the note on posts and pages that had comments in the first place.
			 */
			if ( get_comments_number() ) : 
				?>
				<p class="nocomments"><?php _e( 'Comments are closed.' , 'twentytwelve' ); ?></p>
				<?php 
			endif;
		endif;
		
		if ( have_comments() ) :
			// show list of comments comments
			wp_list_comments( $list_comments_args ); 
		endif;
		?>
	</ol><!-- .commentlist -->

	<?php if ( have_comments() ) : ?>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'twentytwelve' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentytwelve' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentytwelve' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

</div><!-- #comments .comments-area -->
