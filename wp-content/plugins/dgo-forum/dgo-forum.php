<?php
/*
Plugin Name: DGO Forum
Plugin URI: http://dgo.3r13.nl/dgoforum
Description: Adds a DGO style forum to wordpress.
Version: 1.0
Author: Arjen Breur
Author URI: http://3r13.nl
License: GPL2
*/

// DEFINE CONSTANTS FOR THIS PLUGIN
define('DGOFORUMTOPIC_CPT_NAME','dgoforumtopic');


// ADD ACTIONS
// add custom post type dgoforumtopic
add_action( 'init',             		'DGO_init_register_post_type_dgoforumtopic' );
add_action( 'publish_post',     		'DGO_publish_dgoforumtopic_add_initial_comment');
// Styling for the custom post type icon
add_action( 'admin_head', 				'DGO_admin_head_dgoforumtopic_cpt_icons' );

// ADD FILTERS
//add_filter('template_include',  'DGO_include_template_function', 1 );
// order by last comment date on all post types
//add_filter('posts_fields',      'DGO_fields_sort_by_comment_date');
//add_filter('posts_join',        'DGO_join_postcomments_to_WPQuery');
//add_filter('posts_groupby',     'DGO_groupby_postid', 10, 2 );
//add_filter('posts_orderby',     'DGO_orderby_comment_date', 10, 2 );
// order by last modified
add_action( 'wp_insert_comment',		'DGO_insert_comment_update_post_post_modified', 10, 1 );
// return to post after save, instead of edit screen
add_filter( 'redirect_post_location',	'DGO_redirect_to_dgoforumtopic_on_publish_or_save');
// Authors should only be able to edit their own comments, not all comments of their post
add_filter( 'map_meta_cap', 			'DGO_map_meta_cap_restrict_comment_editing', 10, 4 );
// Show this post type on home
add_filter( 'pre_get_posts', 			'DGO_pre_get_posts_set_posttypes', 10, 1 );

 

    
//add custom post type
function DGO_init_register_post_type_dgoforumtopic() {
    register_post_type( DGOFORUMTOPIC_CPT_NAME,
        array(
            'labels' => array(
                'name' => 'Topics',
                'singular_name' => 'Topic',
                'add_new' => 'Nieuw Topic',
                'add_new_item' => 'Nieuw topic toevoegen',
                'edit' => 'Edit',
                'edit_item' => 'Edit Topic',
                'new_item' => 'Nieuw Topic',
                'all_items' => 'Mijn topics',
                'view' => 'Bekijk',
                'view_item' => 'Bekijk Topic',
                'search_items' => 'Zoek Topics',
                'not_found' => 'Geen topics gevonden',
                'not_found_in_trash' => 'Geen topics gevonden in de prullenbak',
                'parent' => 'Parent Forum Topic',
                'parent_item_colon' => '',
                'menu_name' => 'Forum'
            ),
            'publicly_queryable'=>true,
            'rewrite' => array('slug' => 'forum'),
            'public' => true,
            'menu_position' => 5,
            'supports' => array( 'title', 'comments', 'author' ),
            'taxonomies' => array( 'category' ),
            'menu_icon' => plugins_url( 'dgoforumtopic_icon.png', __FILE__ ),
            'has_archive' => true
        )
    );
   	// Allow the category and tag taxonomy to be used for this post type as well
	register_taxonomy_for_object_type('category', DGOFORUMTOPIC_CPT_NAME);
}

    
// Styling for the custom post type icon
function DGO_admin_head_dgoforumtopic_cpt_icons() {
    ?>
    <style type="text/css" media="screen">
        #icon-edit.icon32-posts-dgoforumtopic {
            background: url(<?php echo plugins_url( 'dgoforumtopic_icon_32.png', __FILE__ ); ?> ) no-repeat;
        }
        </style>
    <?php
}


/**
 * Show this posttype on home
 */
function DGO_pre_get_posts_set_posttypes( $query ) {
    if ( $query->is_home() && $query->is_main_query()){
		$post_type = $query->get('post_type');
		$new_post_type = array();
		if( is_string($post_type) && !empty($post_types)){
			$new_post_type[] = $post_types;
		}elseif( is_array($post_type) ){
			$new_post_type = array_merge($new_post_type, $post_type);
		}
		$new_post_type[] = DGOFORUMTOPIC_CPT_NAME;
        $query->set( 'post_type', $new_post_type );
    }
    return $query;
}



// Build query for sorting dgoforumtopics by newest comments
//function DGO_fields_sort_by_comment_date($fields){
//    global $wpdb;
//    $fields .= ", GREATEST(IFNULL(UNIX_TIMESTAMP(".$wpdb->posts.".post_date_gmt),0), IFNULL(MAX(UNIX_TIMESTAMP(".$wpdb->comments.".comment_date_gmt)), 0)  ) AS greatest_date ";
//    return $fields;
//}

//function DGO_join_postcomments_to_WPQuery($join) {
//    global $wpdb;
//    $join .= "LEFT JOIN $wpdb->comments ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID ";
//    return $join;
//}


// filter by post type, if requested post type is dgoforumtopic
//add_filter('posts_where',       'DGO_where_posttype_dgoforumtopic', 10, 2 );
//function DGO_where_posttype_dgoforumtopic( $where, &$wp_query ){
//    if( get_post_type()==DGOFORUMTOPIC_CPT_NAME || is_post_type_archive( DGOFORUMTOPIC_CPT_NAME ) ){
//        global $wpdb;
//        $pattern = "/(post_type\s*=\s*')(\w+)(')/";
//        $replacement = '${1}'.DGOFORUMTOPIC_CPT_NAME.'$3';
//        $replaced = preg_replace($pattern, $replacement, $where);
//        if( $replaced==$where ){
//            // no replacement, so pattern is not found, add it!
//            $replaced .= ' AND ' . $wpdb->posts . ".post_type = '".DGOFORUMTOPIC_CPT_NAME."' ";
//        }
//        $where = $replaced;
//    }
//    return $where;
//}


//function DGO_groupby_postid( $groupby, &$wp_query ){
//    global $wpdb;
//    if(trim($groupby)!=$wpdb->posts.'.ID'){
//        $groupby = $wpdb->posts.'.ID ';
//    }
//    return $groupby;
//}

//function DGO_orderby_comment_date( $orderby, &$wp_query ){
//    global $wpdb;
//    // concat comments order as first, and original orderby as second in priority
////        $orderby_first = $wpdb->comments . '.comment_date_gmt DESC ';
//    $orderby_first = ' greatest_date DESC ';
//    $orderby = $orderby_first.', '.$orderby;
//    return $orderby;
//}


//Upon new comment insert, update post date modified, to posts can easily be sorted by last comment
function DGO_insert_comment_update_post_post_modified($comment_ID){
    $comment = get_comment($comment_ID);
    if( DGOFORUMTOPIC_CPT_NAME == get_post_type($comment->comment_post_ID) ){
	$my_post = array();
	$my_post['ID'] 				= $comment->comment_post_ID ;
	$my_post['post_modified'] 	= $comment->comment_date;
	wp_update_post( $my_post );
    }
}




function DGO_publish_dgoforumtopic_add_initial_comment($post_id) {
    // set post array keys and optional required values
    $post_required_values = array(
        'post_type'     =>DGOFORUMTOPIC_CPT_NAME,
        'post_author'   =>NULL,
        'post_ID'       =>NULL,
        'content'       =>NULL,
    );
    
    // check if required keys have (correct) values
    foreach($post_required_values AS $key=>$required_value){
        if( empty( $_POST[ $key ] ) ) return;
        if( !empty( $required_value ) && $_POST[$key]!=$required_value ) return;
    }
    /* check if user has permission to edit */
    if( current_user_can( 'edit_post', $post_id ) ){
        $success = DGO_insert_innitial_comment( $_POST['post_ID'], $_POST['post_author'], $_POST['content'] );
        if($success){
            // update post to remove content, which is now saved as a comment
            $post = array(
                'ID'            =>$_POST['post_ID'],
                'post_content'  => ''
            );
            // update shoudn cause a loop in this function, because content is now empty
            // nevertheless we remove the action to be neat and clean
            remove_action( 'publish_post', 'DGO_publish_dgoforumtopic_add_initial_comment');
            wp_update_post( $post );
            add_action( 'publish_post', 'DGO_publish_dgoforumtopic_add_initial_comment');
        }
    }
}
function DGO_insert_innitial_comment($post_id, $post_author, $content){
   
    $time = current_time('mysql');
    $user_id = $post_author;

    $data = array(
        'comment_post_ID' => $post_id,
        'comment_author' => '',
        'comment_author_email' => '',
        'comment_author_url' => '',
        'comment_content' => $content,
        'comment_type' => '',
        'comment_parent' => 0,
        'user_id' => $user_id,
        'comment_author_IP' => '',
        'comment_agent' => '',
        'comment_date' => $time,
        'comment_approved' => 1,
    );

    $new_comment_id = wp_insert_comment($data);
    return is_numeric( $new_comment_id );
}
function DGO_redirect_to_dgoforumtopic_on_publish_or_save($location){
    if($_POST['post_type'] == DGOFORUMTOPIC_CPT_NAME ){
        if (isset($_POST['save']) || isset($_POST['publish'])) {
            if (preg_match("/post=([0-9]*)/", $location, $match)) {
                $pl = get_permalink($match[1]);
                if ($pl) {
                    wp_redirect($pl);
                }
            }
        }
    }else{
        wp_redirect($location);
    }
}

// Authors should only be able to edit their own comments, even on their own posts
function DGO_map_meta_cap_restrict_comment_editing( $caps, $cap, $user_id, $args ) {
    if ( 'edit_comment' == $cap ) {
        $comment = get_comment( $args[0] );
        if ( $comment->user_id != $user_id )
            $caps[] = 'moderate_comments';
    }
    return $caps;
}

?>
