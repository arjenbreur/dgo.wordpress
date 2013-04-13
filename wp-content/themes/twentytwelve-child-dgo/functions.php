<?php
/**
 * This child theme function.php is included BEFORE the parents theme function.php
 */
 
//add_filter('pre_get_posts', 'optimized_get_posts', 100);
//function optimized_get_posts() {
//	global $wp_query, $wpdb;
//
//	if(!isset($wp_query->query_vars['posts_per_page']) || $wp_query->query_vars['posts_per_page']==0)
//		$wp_query->query_vars['posts_per_page'] = 10;
//
//	$wp_query->query_vars['no_found_rows'] = 1;
//	$wp_query->found_posts = $wpdb->get_var( "SELECT COUNT(*) FROM wp_dgo_posts WHERE 1=1 AND wp_dgo_posts.post_type = 'post' AND (wp_dgo_posts.post_status = 'publish' OR wp_dgo_posts.post_status = 'private')" );
//	$wp_query->found_posts = apply_filters_ref_array( 'found_posts', array( $wp_query->found_posts, &$wp_query ) );
//	$wp_query->max_num_pages = ceil($wp_query->found_posts / $wp_query->query_vars['posts_per_page']);
//
// 	return $wp_query;
//}


/**
 * ACTIONS AND FILTERS
 */
// Sort posts by date modified (custom DGO post types will update this value upon new comments)
add_filter( 'posts_orderby',			'DGO_orderby_post_modified', 666, 1 );
// Add theme.js
add_action('template_redirect',	 		'DGO_template_redirect_enqueu_theme_javascript', 9);
// Load more post via ajax
add_action('template_redirect', 		'DGO_template_redirect_ajax_load_more_posts', 10);
// Post comments through ajax
add_action('comment_post', 				'DGO_ajaxify_comments',20, 2);
// Set minimal time between comments to a lower value than wp default
add_action( 'init', 					'init_removecommentfloodfilter', 10);
add_filter( 'comment_flood_filter', 	'DGO_throttle_comment_flood', 10, 3);

// add dynamically driven style sheet (skin.css.php)
add_action( 'wp_enqueue_scripts', 		'twentytwelve_child_dgo_add_skin_css', 20 );
add_action( 'show_user_profile', 		'DGO_user_profile_show_skin_selector', 20 );
add_action( 'edit_user_profile', 		'DGO_user_profile_show_skin_selector', 20 );
add_action( 'personal_options_update',	'DGO_user_profile_save_skin_selector', 20 );
add_action( 'edit_user_profile_update',	'DGO_user_profile_save_skin_selector', 20 );
// load non3d complient stylesheet
add_action( 'wp_enqueue_scripts', 		'DGO_twentytwelve_child_styles', 20 );




/**
 * Sort posts by date modified
 */
function DGO_orderby_post_modified( $orderby ){
    return ' post_modified DESC, '.$orderby;
}

/**
 * Add javascript theme.js
 */
function DGO_template_redirect_enqueu_theme_javascript(){
	if( !is_admin() ){
		wp_enqueue_script( 'pbd-alp-load-posts', get_bloginfo('stylesheet_directory').'/theme.js', array('jquery'), '1.0', true );
	}
}   

function DGO_get_ago($wp_date_GMT) {
    // transform wp date to unix timestamp
    $unix_date = strtotime($wp_date_GMT);
    $delta = time() - $unix_date;

    if     (                            $delta < (60) )           $ago = strval(round(($delta))).'s';
    elseif ($delta >= (60)           && $delta < (60*60) )        $ago = strval(round(($delta/60))).'m';
    elseif ($delta >= (60*60)        && $delta < (24*60*60) )     $ago = strval(round(($delta/(60*60)))).'u';
    elseif ($delta >= (24*60*60)     && $delta < (7*24*60*60) )   $ago = strval(round(($delta/(24*60*60)))).'d';
    elseif ($delta >= (7*24*60*60)   && $delta < (30*24*60*60) )  $ago = strval(round(($delta/(7*24*60*60)))).'w';
    elseif ($delta >= (30*24*60*60)  && $delta < (365*24*60*60) ) $ago = strval(round(($delta/(30*24*60*60)))).'M';
    elseif ($delta >= (365*24*60*60) )                            $ago = strval(round(($delta/(365*24*60*60)))).'j';
    return $ago;
}
    
function twentytwelve_child_dgo_the_excerpt($ID=null){
	// dont show except if post is password protected, but do show password field
	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}

    $ID = ($ID) ? $ID : get_the_ID();
    ?>
    <div class="entry-content">
    	<?php 
    	the_excerpt();
		?>
	</div><!-- .entry-content -->
	<?php
}
function twentytwelve_child_dgo_the_excerpt_and_comments($ID=null){
	// dont show except if post is password protected, but do show password field
	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}
    $ID = ($ID) ? $ID : get_the_ID();
    twentytwelve_child_dgo_the_excerpt($ID);

	// check if post is seen by current user
	if(class_exists('DGO_highlight_unseen')){
		global $current_user;
	    get_currentuserinfo();
	    $meta_values = get_post_meta($ID, DGO_highlight_unseen::DGOHIGHLIGHTUNSEEN_METAKEY, true);
	    if(is_array($meta_values) && !in_array($current_user->ID, $meta_values ) ){
		    // post is unseen, 
		    // check if post has comments, if so we asume that post is marked unseen because there are new comments
		    // however this could also be because the post has been editted
		    if( get_comments( array('post_id'=>$ID, 'status'=>'approve', 'count'=>true) ) > 0 ){
			    echo '<p class="excerpt_new_posts"><a href="'.get_permalink( $ID ).'">Nieuwe reacties</a></p>';
		    }
	    }
	}
}

function twentytwelve_child_dgo_the_forum_excerpt($ID=null, $maxItemNumber=null){
 	// dont show forum excepts if user is not logged in
	if ( !is_user_logged_in() ) return;
	// hide statistics for password protected posts
	if ( post_password_required() ) return;
	
    // get latest comment
    $ID = ($ID) ? $ID : get_the_ID();
	$maxItemNumber = ($maxItemNumber) ? $maxItemNumber : 5;

    $args = array(
        'post_id' => $ID,
        'number' => $maxItemNumber,
        'status' => '',
    );
    $comments = get_comments($args);
    global $current_user;
    get_currentuserinfo();
    $showAvatar = FALSE;
    
    ?>
    <div class="entry-content">
    	<ul data-maxItems="<?php echo $maxItemNumber; ?>" >
    		<?php
			if ( comments_open() ) : 
				?>    		
				<li class="writecomment hidden">
					<?php if($showAvatar):?>
	            		<span class="avatar"><?php echo get_avatar($current_user->ID, 16, NULL, $current_user->display_name);?></span>
					<?php endif;?>
					<span class="timestamp">0s</span>
					<span class="author"><?php echo $current_user->display_name; ?></span>
					<span class="excerpt">
						<form id="commentform-<?php echo $ID;?>" class="commentform" method="post" action="http://dgo.3r13.nl/wp-comments-post.php">
							<input name="comment" class="commentfield" id="commentfield-<?php echo $ID;?>" type="text" >
							<input type="hidden" id="comment_post_ID" value="<?php echo $ID ;?>" name="comment_post_ID">
							<input type="hidden" value="0" id="comment_parent" name="comment_parent">
							<?php wp_nonce_field( 'comment_nonce' ); ?>
							<?php
							if(current_user_can('manage_options')){ // = admin
								wp_nonce_field('unfiltered-html-comment_' . $ID, '_wp_unfiltered_html_comment', false, true);
							}
							?>
						</form>
					</span>
		            <span class="more" onmouseover="jQuery(this).parent('.scrollable').addClass('scroll');" onmouseout="jQuery(this).parent('.scrollable').removeClass('scroll');" >&nbsp;</span>
				</li>
			    <?php
			else:
				if ( get_comments_number() ) : 
					/* If there are no comments and comments are closed, let's leave a note.
					 * But we only want the note on posts and pages that had comments in the first place.
					 */
					?>    		
					<li class="writecomment hidden">
						<span class="nocomments"><?php _e( 'Comments are closed.' , 'twentytwelve' ); ?></span>
					</li>
					<?php 
				endif;
			endif;
			// show comments
		    foreach($comments as $comment){
				twentytwelve_child_dgo_comment_excerpt($comment, $showAvatar );
		    }?>
		</ul>
	</div><!-- .entry-content -->
	<?php
}


function twentytwelve_child_dgo_the_forum_statistics($ID=null){
	// hide statistics for password protected posts
	if ( post_password_required() ) return;
		
    $ID = ($ID) ? $ID : get_the_ID();
    $post = get_post($ID);
	$args = array( 'post_id' => $ID );
	$comments = get_comments( $args );
	$num_posts = count( $comments );
	$cohorts = 10;
	$last_post     = (count( $comments )) ? $comments[0]->comment_date_gmt : '';
	$first_post    = (count( $comments )) ? $comments[count($comments)-1]->comment_date_gmt: '';
	$topic_starter = get_the_author_meta( 'display_name', $post->post_author);
	?>
	<dl>
		<dt>Topic starter</dt><dd><?php echo $topic_starter; ?></dd>
		<dt>Eerste post</dt><dd><?php echo $first_post; ?></dd>
		<dt>Laatste post</dt><dd><?php echo $last_post; ?></dd>
		<dt>Aantal posts</dt><dd><?php echo $num_posts; ?></dd>
	</dl>
	<?php
}

function twentytwelve_child_dgo_the_access_groups($ID=null){
 	// dont show access groups if user is not logged in
	if ( !is_user_logged_in() ) return;
	// hide access groups for password protected posts
	if ( post_password_required() ) return;

    $ID = ($ID) ? $ID : get_the_ID();
    ?>
	<div class="uam_access_groups">
		<ul class="uam_group_selection">
		<?php
		
		global $current_user;
		wp_get_current_user();
		
		global $userAccessManager;
		if (isset($userAccessManager)) {
		    $uamAccessHandler = $userAccessManager->getAccessHandler();
		    $uamUserGroups = $uamAccessHandler->getUserGroups(null, false);
		    $post_type = get_post_type( );
		    $userGroupsForObject = $uamAccessHandler->getUserGroupsForObject( $post_type, $ID );
	        $groupsFormName = 'uam_usergroups';
	        // get list of all user that have seen this item
			if(class_exists('DGO_highlight_unseen')){
		        $seenbyusers =  get_post_meta($ID, DGO_highlight_unseen::DGOHIGHLIGHTUNSEEN_METAKEY, true);
			}
			foreach ($uamUserGroups as $uamUserGroup) {
			    $class = ''; 
				// get array of users id's in this group
		        $userids = array_keys($uamUserGroup->getObjectsFromType('user'));

				// set class if usergroup has access
			    $class .= (array_key_exists($uamUserGroup->getId(), $userGroupsForObject) ) ? ' hasaccess ':' noaccess ';
				// set class for type of group
			    $class .= (count($userids)==1)? 'dgo-single-user-group' : 'dgo-group';						        											
				// set class for if user has seen this item
		        if(isset($seenbyusers) && is_array($seenbyusers) && count($userids)==1){
					$class .= ( in_array($userids[0], $seenbyusers) ) ? ' seen ' : ' unseen ';
		        }
				?>
			    <li class="<?php echo $class;?>" ><?php echo $uamUserGroup->getGroupName(); ?></li>
				<?php
			}
		}						
		?>
		</ul>
	</div><!-- #uma_post_access -->    
	<?php
}




/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 * To use this function instead of the theme's default, set it as callback arg when calling wp_list_comments();
 *
 */
function twentytwelve_child_dgo_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

//	echo "<pre>";print_r($comment); echo "</pre>";
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
			// Display trackbacks differently than normal comments.
			?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
			<?php
			break;
		
		default :
			// Proceed with normal comments.
			global $post;
			// add special css classes
			$addClasses = 'comment';
			if('dgocommentform' == $comment->comment_type){
				$addClasses .= ' dgocommentform closed';
			}
			?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article <?php if($comment->comment_ID) echo 'id="comment-'.$comment->comment_ID.'"'; ?> class="<?php echo $addClasses;?> "	>
					<header class="comment-meta comment-author vcard" >
						<?php
							$avatar_size = (isset($args['avatar_size']))? intval($args['avatar_size']) : 44;
							echo get_avatar( $comment->user_id, $avatar_size, NULL, $comment->comment_author );
							printf( '<cite class="fn">%1$s %2$s</cite>',
								get_comment_author_link(),
								// If current post author is also comment author, make it known visually.
								( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'twentytwelve' ) . '</span>' : ''
							);
							printf( '<a href="%1$s"><time datetime="%2$s">%3$s %4$s (%5$s)</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ), 
								get_comment_date('d-m-Y'), 
								get_comment_time(),
								DGO_get_ago(get_comment_time('c', true)) // true:GMT
							);
						?>
					</header><!-- .comment-meta -->
		
					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
					<?php endif; ?>
		
					<?php 
					if('dgocommentform' == $comment->comment_type):
						// COMMENT FORM
						?>
						<section class="comment-content comment">
							<form id="commentform" class="commentform" method="post" action="http://dgo.3r13.nl/wp-comments-post.php">
								<?php 
									$args = array(
									    'textarea_rows' => 15,
									    'teeny' => true,
									    'quicktags' => array(
									    	'buttons' => 
									    		'strong'.
									    		',em'.
									    		//',block'.
									    		',del'.
									    		',ins'.
									    		',link'.
									    		',img'.
									    		',ul'.
									    		',ol'.
									    		',li'.
									    		//',code'.
									    		//',more'.
									    		//',spell'.
									    		',close'.
									    		//',fullscreen'.
									    		''
									    ),
								        'tinymce' => array(
									        'theme_advanced_buttons1' => 
//									        	'formatselect,'.
									        	'|,bold,italic,underline,'.
									        	'|,bullist,blockquote,'.
//									        	'|,justifyleft,justifycenter,justifyright,justifyfull,'.
									            '|,link,unlink,'.
//									            '|,spellchecker,wp_fullscreen,wp_adv'.
												''
									    )
									);
									wp_editor( $comment->comment_content, 'comment', $args );
								?>
								<p class="dgoemoticons">
									<?php if(function_exists('DGO_emoticons_get_them')) { echo DGO_emoticons_get_them(); } ?>
								</p>
								<p class="form-submit">
									<input type="submit" value="Reactie plaatsen" id="submit" name="submit">
									<input type="hidden" id="comment_post_ID" value="<?php echo $comment->comment_post_ID ;?>" name="comment_post_ID">
									<input type="hidden" value="<?php echo $comment->comment_parent ;?>" id="comment_parent" name="comment_parent">
								</p>
								<p style="display: none;">
									<?php wp_nonce_field( 'comment_nonce' ); ?>
										<?php //<input type="hidden" value="0bcd3d27e2" name="akismet_comment_nonce" id="akismet_comment_nonce"> ?>
									<?php
									if(current_user_can('manage_options')){ // = admin
										wp_nonce_field('unfiltered-html-comment_' . $post->ID, '_wp_unfiltered_html_comment', false, true);
									}
									?>
								</p>
							</form>
						</section><!-- .comment-content -->	
					<?php 
					else:
						// REAL COMMENT
					?>
						<section class="comment-content comment">
							<?php comment_text(); ?>
							<?php edit_comment_link( __( 'Edit', 'twentytwelve' ), '<p class="edit-link">', '</p>' ); ?>
						</section><!-- .comment-content -->
					<?php endif; ?>
				</article><!-- #comment-## -->
			<?php
			break;
	endswitch; // end comment_type check
	
	// add script to handle dgoforumcomment events
	if('dgocommentform' == $comment->comment_type){
		?>
		<script id="dgoforumcommenthandlers" type="text/javascript" >
		jQuery(document).ready(function($) {
			console.log('add handlers');
		       // remove click event from all articles
			jQuery('ARTICLE.dgocommentform HEADER').unbind('click.DGOBindEventHandlersToCommentFormHeader');
		       // re-add click events to all articles
			jQuery('ARTICLE.dgocommentform HEADER').bind('click.DGOBindEventHandlersToCommentFormHeader', function(){
				jQuery(this).parent().toggleClass('closed');
			console.log('add handlers');
			});
		       // remove click event from all articles
			jQuery('ARTICLE.dgocommentform SECTION.comment TEXTAREA#comment').unbind('focus.DGOBindEventHandlersToCommentFormTextarea');
		       // re-add click events to all articles
			jQuery('ARTICLE.dgocommentform SECTION.comment TEXTAREA#comment').bind('focus.DGOBindEventHandlersToCommentFormTextarea', function(){
				jQuery(this).closest('ARTICLE').removeClass('closed');
			console.log('add handlers');
		    });
		});
		</script>
		<?php
	}
}


/**
 * Returns comments as single line excerpts
 */
function twentytwelve_child_dgo_comment_excerpt( $comment, $showAvatar=false) {
	// replace bb codes that render higher than one line
	$ret = $comment->comment_content;
	//$ret = preg_replace('#\[b\](.+)\[\/b\]#iUs', '<b>$1</b>', $ret);
	//$ret = preg_replace('#\[link\=(.+)\](.+)\[\/link\]#iUs', '<a href="$1">$2</a>', $ret);
	//$ret = preg_replace('#\[img\](.+)\[\/img\]#iUs', '<img src="$1" alt="Image" />', $ret);
	$ret = preg_replace('/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/', '<a href="${0}">Link</a>', $ret);
			        $ret = preg_replace('#\[quote\](.+)\[\/quote\]#iUs', '<del>QUOTE</del>', $ret);
			        $ret = preg_replace('#\[img\](.+)\[\/img\]#iUs', '<del>IMG</del>', $ret);
			        $ret = preg_replace('#\[YOUTUBE\](.+)\[\/YOUTUBE\]#iUs', '<del><a href="http://www.youtube.com/watch?v=${1}" target="_blank">Youtube</a></del>', $ret);
			        // parse smileys
	global $wpsmiliestrans;
	foreach($wpsmiliestrans as $pat=>$filename){
		$ret = str_replace( $pat, "<img src='/wp-includes/images/smilies/{$filename}' />", $ret );
	}
	if ( '0' == $comment->comment_approved ) {
		$display = "***".__( 'Your comment is awaiting moderation.', 'twentytwelve' )."***";
	}else{
		$display = do_shortcode($ret);
	}
		
	?>
		            <li class="scrollable">
       					<?php if($showAvatar):?>
			            	<span class="avatar"><?php echo get_avatar($comment, 16, NULL, $comment->comment_author);?></span>
						<?php endif;?>
		                <span class="timestamp"><?php echo DGO_get_ago( $comment->comment_date_gmt );?></span>
		                <span class="author"><?php echo $comment->comment_author;?></span>
		                <span class="excerpt"><?php echo $display; ?></span>
		                <span class="more" onmouseover="jQuery(this).parent('.scrollable').addClass('scroll');" onmouseout="jQuery(this).parent('.scrollable').removeClass('scroll');" >&nbsp;</span>
		            </li>
		       	<?php
}

function DGO_template_redirect_ajax_load_more_posts() {
 	global $wp_query;
 
 	// Add code to index pages.
 	if( !is_admin() && is_home() && !is_singular() ) {			
 		// What page are we on? And what is the pages limit?
 		$max = $wp_query->max_num_pages;
 		$paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;

 		// Add some parameters for the JS.
 		wp_localize_script(
 			'pbd-alp-load-posts',
 			'pbd_alp',
 			array(
 				'startPage' => $paged,
 				'maxPages' => $max,
 				'nextLink' => next_posts($max, false)
 			)
 		);
 	}
}


/**
 * Add comments through ajax
 */ 
function DGO_ajaxify_comments($comment_ID, $comment_status){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		//If AJAX Request Then
		switch($comment_status){
			case '0':
				//notify moderator of unapproved comment
				wp_notify_moderator($comment_ID);
				// fall through:
			case '1': //Approved comment
				$commentdata=&get_comment($comment_ID, ARRAY_A);
				$post=&get_post($commentdata['comment_post_ID']);
				wp_notify_postauthor($comment_ID, $commentdata['comment_type']);
				// send back the comment, for javascript to add to the DOM
				$comment = get_comment($comment_ID);
				$isExcerpt = (isset($_POST['return_excerpt']) && $_POST['return_excerpt'] == 'true' )?true:false;
				if($isExcerpt){
					twentytwelve_child_dgo_comment_excerpt($comment);
				}else{
					$args = array();
					twentytwelve_child_dgo_comment($comment, $args, 0);
				}
				break;
			default:
				echo "error";
		}
		exit;
	}
}


/**
 * Whether comment should be blocked because of comment flood.
 *
 * This is a DGO child theme version of the original wp version
 * that sets the minimal time between comments to a lower value
 *
 * @param bool $block Whether plugin has already blocked comment.
 * @param int $time_lastcomment Timestamp for last comment.
 * @param int $time_newcomment Timestamp for new comment.
 * @return bool Whether comment should be blocked.
 */
function DGO_throttle_comment_flood($block, $time_lastcomment, $time_newcomment ){
	if ( $block ) // a plugin has already blocked... we'll let that decision stand
		return $block;
	if ( ($time_newcomment - $time_lastcomment) < 5 )
		return true;
	return false;
}
// Remove original wp filter, so it can be replaced by ours
function init_removecommentfloodfilter() {
	remove_filter( 'comment_flood_filter', 'wp_throttle_comment_flood', 10, 3);
}



/*
 * Loads non3d complient stylesheets (IE<9, WinXPChome)
*/
// Loads the class
require 'Browscap.php';
// The Browscap class is in the phpbrowscap namespace, so import it
use phpbrowscap\Browscap;
function DGO_get_browser(){
	static $DGO_current_browser;
	// Create a new Browscap object (loads or creates the cache)
	if(!$DGO_current_browser){
		$bc = new Browscap('./');
		// Get information about the current browser's user agent
		$DGO_current_browser = $bc->getBrowser();
	}
	return $DGO_current_browser;
}

function DGO_twentytwelve_child_styles(){	
	global $wp_styles;
	// conditional stylesheet for IE
	wp_enqueue_style( 'twentytwelve-child-dgo-ie',  get_stylesheet_directory_uri()  . '/ie.css' );
	$wp_styles->add_data( 'twentytwelve-child-dgo-ie', 'conditional', 'lt IE 10' );
	
	// extra non-complient useragents
	$browser = DGO_get_browser();
			
	if(
		($browser->Platform=="WinXP" && $browser->Browser=="Chrome"		&& $browser->Version=="25.0")
	||	($browser->Platform=="WinXP" && $browser->Browser=="Chrome" 	&& $browser->Version=="26.0")
	||	(								$browser->Browser=="Android"	&& $browser->Version=="4.0")
	||	(								$browser->Browser=="IE"			&& $browser->MajorVer<10)
	){
		wp_register_style( 'twentytwelve-child-dgo-non3d', get_stylesheet_directory_uri() . '/non3d.css');
		wp_enqueue_style( 'twentytwelve-child-dgo-non3d');
	}
	
}




/**
 * Add dynamic php driven stylesheet, to handle skin style declarations 
 * save the request as a user preference, for logged in users
 * if no skin requested, use the one stored as preference
 * Also adds skin selector in the user profile page
 */ 
define('DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY', 'DGOtwentytwelveChildtheme_skin');

function twentytwelve_child_dgo_add_skin_css() {
	global $current_user;
	$current_user = wp_get_current_user();
	// if user is logged in, get skin preference
	$skin = 'default';
	if($current_user && $current_user->ID){
    	$skin_old = get_user_meta( $current_user->ID, DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY, true );
	    if(isset($_REQUEST['skin']) ){
	    	$skin = $_REQUEST['skin'];
	    	// save skin as user preference
	    	update_user_meta( $current_user->ID, DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY, $skin, $skin_old );
	    }else{
	    	$skin = ( !empty($skin_old) )?$skin_old : $skin;
	    }
    }
    // we (ab)use the 'version' parameter to pass the skin to the stylesheet.
    wp_register_style( 'twentytwelve_child_dgo_skin_css', get_stylesheet_directory_uri()  . '/skin.css.php', array(), $skin, 'all' );
    wp_enqueue_style( 'twentytwelve_child_dgo_skin_css' );
}
function DGO_user_profile_show_skin_selector( $user ) {
	$skins = array();
	$skins[] = array('value'=>'2k13', 			'verbose'=>'2k13 (default)');
	$skins[] = array('value'=>'madagascar', 	'verbose'=>'Madagascar');
	$skins[] = array('value'=>'tiger', 			'verbose'=>'Tiger');
	$skins[] = array('value'=>'nightly', 		'verbose'=>'Nightly');
	$skins[] = array('value'=>'dark', 			'verbose'=>'Dark');
	$skins[] = array('value'=>'dgo', 			'verbose'=>'Dgo');
	?>
    <h3><?php _e('Theme opties', 'DGO'); ?></h3>
    
    <table class="form-table">
        <tr>
            <th>
                <label for="<?php echo DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY;?>"><?php _e('Skin', 'DGO'); ?>
            </label></th>
            <td>
            	<select  name="<?php echo DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY;?>" id="<?php echo DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY;?>" >
            		<?php 
            		$pref = get_the_author_meta( DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY, $user->ID );
            		foreach($skins as $skin){
            			$selected = ( $skin['value'] == $pref )?'selected="selected"':'';
            			?>
            			<option value="<?php echo $skin['value'];?>" <?php echo $selected;?> ><?php echo $skin['verbose'];?></option>
            			<?php 
            		}
            		?>
            	</select>
                <span class="description"><?php _e('Kies een skin', 'DGO'); ?></span>
            </td>
        </tr>
    </table>
<?php }
 
function DGO_user_profile_save_skin_selector( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return FALSE;
    
    update_user_meta($user_id, DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY, $_POST[DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY], get_the_author_meta( DGOTWENTYTWELVECHILDTHEME_SKINPREFERENCEKEY, $user_id ) );
}

?>