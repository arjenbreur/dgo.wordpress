<?php
/**
 * Plugin Name: DGO Quotes
 * Plugin URI: http://dgo.3r13.nl/dgoquotes
 * Description: Adds quotes to wordpress.
 * Version: 1.0
 * Author: Arjen Breur
 * Author URI: http://3r13.nl
 * License: GPL2
 */

// init the custom post type
DGO_quotes::init();

class DGO_quotes {
    // DEFINE CONSTANTS FOR THIS PLUGIN
    const TXT_DOMAIN 			= 'dgo_quotes';
	const CPT_NAME 				= 'dgoquote';
	const VERBOSE_NAME_SINGULAR	= 'Quote';
	const VERBOSE_NAME_PLURAL	= 'Quotes';
        
    static function init() {
	    // ADD ACTIONS AND FILTERS
	    // add custom post type
	    add_action( 'init',             		array(__CLASS__, 'init_register_post_type' ) );
	    // Styling for the custom post type icon
	    add_action( 'admin_head',               array(__CLASS__, 'admin_head_cpt_icons' ) );
	    // custom updated messages
	    add_filter( 'post_updated_messages',    array(__CLASS__, 'updated_messages' ) ) ;
	    // add custom fields to the editor
	    add_action( 'admin_init',               array(__CLASS__, 'admin_init_add_metabox' ) );
		// save metafields
//	    add_action( 'save_post',                array(__CLASS__, 'save_post_save_metafields'), 10, 2  );
		// set post name/slug on post save
	    add_filter( 'name_save_pre', 			array(__CLASS__, 'name_save_pre_set_post_name'));
		// set post title on post save
	    add_filter( 'title_save_pre', 			array(__CLASS__, 'title_save_pre_set_post_title'));
		// set post content on post save
	    add_filter( 'content_save_pre', 		array(__CLASS__, 'content_save_pre_set_post_content'));
	    // Show this post type on home
	    add_filter( 'pre_get_posts',	 		array(__CLASS__, 'pre_get_posts_set_posttypes'));
	    

    }
     
    //add custom post type
    static function init_register_post_type() {
        register_post_type( self::CPT_NAME,
            array(
                'labels' => array(
                    'name'               => self::VERBOSE_NAME_PLURAL,
                    'singular_name'      => self::VERBOSE_NAME_SINGULAR,
                    'add_new'            => 'Nieuwe '.self::VERBOSE_NAME_SINGULAR,
                    'add_new_item'       => 'Nieuwe '.self::VERBOSE_NAME_SINGULAR.' toevoegen',
                    'edit'               => 'Edit',
                    'edit_item'          => 'Bewerk '.self::VERBOSE_NAME_SINGULAR,
                    'new_item'           => 'Nieuwe '.self::VERBOSE_NAME_SINGULAR,
                    'all_items'          => 'Mijn '.self::VERBOSE_NAME_PLURAL,
                    'view'               => 'Bekijk',
                    'view_item'          => 'Bekijk '.self::VERBOSE_NAME_SINGULAR,
                    'search_items'       => 'Zoek '.self::VERBOSE_NAME_PLURAL,
                    'not_found'          => 'Geen '.self::VERBOSE_NAME_PLURAL.' gevonden',
                    'not_found_in_trash' => 'Geen '.self::VERBOSE_NAME_PLURAL.' gevonden in de prullenbak',
                    'parent'             => 'Parent '.self::VERBOSE_NAME_SINGULAR,
                    'parent_item_colon'  => '',
                    'menu_name'          => self::VERBOSE_NAME_PLURAL
                ),
                'publicly_queryable'   =>true,
                'rewrite'              => array('slug' => strtolower(self::VERBOSE_NAME_PLURAL)),
                'public'               => true,
                'menu_position'        => 5,
                'supports'             => array( '' ),
                'taxonomies'           => array( '' ),
                'menu_icon'            => plugins_url( self::CPT_NAME.'_icon.png', __FILE__ ),
                'has_archive'          => true
            )
        );
    }
    //add filter to ensure the text Book, or book, is displayed when user updates a book
    
    static function updated_messages( $messages ) {
        if( self::CPT_NAME == get_post_type() ){

            global $post, $post_ID;
            
            $messages[self::CPT_NAME] = array(
                0 => '', // Unused. Messages start at index 1.
                1 => sprintf( __(self::VERBOSE_NAME_SINGULAR.' updated. <a href="%s">View '.self::VERBOSE_NAME_SINGULAR.'</a>', self::TXT_DOMAIN), esc_url( get_permalink($post_ID) ) ),
                2 => __('Custom field updated.', self::TXT_DOMAIN),
                3 => __('Custom field deleted.', self::TXT_DOMAIN),
                4 => __(self::VERBOSE_NAME_SINGULAR.' updated.', self::TXT_DOMAIN),
                /* translators: %s: date and time of the revision */
                5 => isset($_GET['revision']) ? sprintf( __(self::VERBOSE_NAME_SINGULAR.' restored to revision from %s', self::TXT_DOMAIN), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
                6 => sprintf( __(self::VERBOSE_NAME_SINGULAR.' published. <a href="%s">View '.self::VERBOSE_NAME_SINGULAR.'</a>', self::TXT_DOMAIN), esc_url( get_permalink($post_ID) ) ),
                7 => __(self::VERBOSE_NAME_SINGULAR.' saved.', self::TXT_DOMAIN),
                8 => sprintf( __(self::VERBOSE_NAME_SINGULAR.' submitted. <a target="_blank" href="%s">Preview '.self::VERBOSE_NAME_SINGULAR.'</a>', self::TXT_DOMAIN), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
                9 => sprintf( __(self::VERBOSE_NAME_SINGULAR.' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview '.self::VERBOSE_NAME_SINGULAR.'</a>', self::TXT_DOMAIN),
                           // translators: Publish box date format, see http://php.net/date
                           date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
                10 => sprintf( __(self::VERBOSE_NAME_SINGULAR.' draft updated. <a target="_blank" href="%s">Preview '.self::VERBOSE_NAME_SINGULAR.'</a>', self::TXT_DOMAIN), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            );
        }
        
        return $messages;
    }
 
    
    // Styling for the custom post type icon
    static function admin_head_cpt_icons() {
        ?>
        <style type="text/css" media="screen">
            #icon-edit.icon32-posts-dgoquote {
                background: url(<?php echo plugins_url( self::CPT_NAME.'_icon_32.png', __FILE__ ); ?> ) no-repeat;
            }
        </style>
        <?php
    }
    
    
    // Add custom fields to edit screen
    static function admin_init_add_metabox(){
        // Add the Author metabox
        add_meta_box( self::CPT_NAME.'_meta_box',
                     __( self::VERBOSE_NAME_SINGULAR, self::TXT_DOMAIN ),
                     array(__CLASS__, 'meta_box_display'),
                     self::CPT_NAME,
                     'normal',
                     'high'
        );
    }
    static function meta_box_display( $post ) {
        // Retrieve current custom field values, based on quote ID
        $cpt_author = esc_html( $post->post_title );
        $cpt_content= $post->post_content;
        ?>
        <table>
            <tr>
                <td>
                    <?php _e('By whom', self::TXT_DOMAIN);?>
                </td>
                <td>
                    <input type="text" size="80" name="<?php echo self::CPT_NAME;?>_author" value="<?php echo $cpt_author; ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <?php _e('The '.self::VERBOSE_NAME_SINGULAR, self::TXT_DOMAIN);?>
                </td>
                <td>
                    <textarea name="<?php echo self::CPT_NAME;?>_content" cols="80" rows="5" ><?php echo $cpt_content; ?></textarea>
                </td>
            </tr>
        </table>
        <?php
    }
            
    static function save_post_save_metafields( $ID, $post ) {
//        if( self::CPT_NAME == get_post_type() ){
//
//            if ( isset( $_POST[self::CPT_NAME.'_author'] ) && $_POST[self::CPT_NAME.'_author'] != '' ) {
//                //update_post_meta( $ID, 'quote_author', $_POST['quote_author'] );
//                $post->post_title = $_POST[self::CPT_NAME.'_author'];
//            }
//            if ( isset( $_POST[self::CPT_NAME.'_content'] ) && $_POST[self::CPT_NAME.'_content'] != '' ) {
//                $post->post_content = $_POST[self::CPT_NAME.'_content'];
//            }
//            //If calling wp_update_post, unhook this function so it doesn't loop infinitely
//            remove_action('save_post', array(__CLASS__, 'save_post_save_metafields') );
//            // do the update
//            wp_update_post( $post );
//            // re-hook this function
//            add_action('save_post', array(__CLASS__, 'save_post_save_metafields') );
//        }
    }

    // change post name on post save
	static function name_save_pre_set_post_name($post_name) {
        if ( isset($_POST['post_type'] ) && self::CPT_NAME == $_POST['post_type'] ){
        	if(empty($post_name)){
	            if ( isset( $_POST[self::CPT_NAME.'_author'] ) && $_POST[self::CPT_NAME.'_author'] != '' ) {
					$post_name = $_POST[self::CPT_NAME.'_author'];
	            }else{
					$post_name = strtolower(self::VERBOSE_NAME_SINGULAR).'_'.$_POST['post_ID'];
	            }
        	}
        };
        return $post_name;
	}
	// set post title on post save
	static function title_save_pre_set_post_title($post_title) {
        if ( isset($_POST['post_type'] ) && self::CPT_NAME == $_POST['post_type'] ){
            if ( isset( $_POST[self::CPT_NAME.'_author'] ) && $_POST[self::CPT_NAME.'_author'] != '' ) {
				$post_title = $_POST[self::CPT_NAME.'_author'];
        	}
        };
        return $post_title;
	}
	// set post content on post save
	static function content_save_pre_set_post_content($post_content) {
        if ( isset($_POST['post_type'] ) && self::CPT_NAME == $_POST['post_type'] ){
            if ( isset( $_POST[self::CPT_NAME.'_content'] ) && $_POST[self::CPT_NAME.'_content'] != '' ) {
				$post_content = $_POST[self::CPT_NAME.'_content'];
        	}
        };
        return $post_content;
	}            
            
// filter by post type, if requested post type is quote
//	    add_filter( 'posts_where',       		array(__CLASS__, 'where_posttype' ), 10, 2  );
//    static function where_posttype( $where, &$wp_query ){
//        if( self::CPT_NAME == get_post_type() || is_post_type_archive( self::CPT_NAME ) ){
//            global $wpdb;
//            $pattern = "/(post_type\s*=\s*')(\w+)(')/";
//            $replacement = '${1}'.self::CPT_NAME.'$3';
//            $replaced = preg_replace($pattern, $replacement, $where);
//            if( $replaced==$where ){
//                 no replacement, so pattern is not found, add it!
//                $replaced .= ' AND ' . $wpdb->posts . ".post_type = '".self::CPT_NAME."' ";
//            }
//            $where = $replaced;
//        }
//        return $where;
//    }

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
