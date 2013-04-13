<?php
/**
 * Plugin Name: DGO Posts
 * Plugin URI: http://dgo.3r13.nl/dgoposts
 * Description: Adds DGO behaviour to standard wordpress posts
 * Version: 1.0
 * Author: Arjen Breur
 * Author URI: http://3r13.nl
 * License: GPL2
 */

// init the custom post type
DGO_posts::init();

class DGO_posts {
    // DEFINE CONSTANTS FOR THIS PLUGIN
    const TXT_DOMAIN 			= 'dgo_posts';
	const CPT_NAME 				= 'post';
	const VERBOSE_NAME_SINGULAR	= 'Bericht';
	const VERBOSE_NAME_PLURAL	= 'Berichten';
        
    static function init() {
	    // ADD ACTIONS AND FILTERS
	    // Show this post type on home
	    add_filter( 'pre_get_posts',	 		array(__CLASS__, 'pre_get_posts_set_posttypes'));
    }

	/**
	 * Show this posttype on home
	 */
	static function pre_get_posts_set_posttypes( $query ) {
	    if ( $query->is_home() && $query->is_main_query()){
			$post_type = $query->get('post_type');
			$new_post_type = array();
			if( is_string($post_type) && !empty($post_types)){
				$new_post_type[] = $post_types;
			}elseif( is_array($post_type) ){
				$new_post_type = array_merge($new_post_type, $post_type);
			}
			$new_post_type[] = self::CPT_NAME;
	        $query->set( 'post_type', $new_post_type );
	    }
	    return $query;
	}

}
?>
