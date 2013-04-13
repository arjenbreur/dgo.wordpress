<?php
/**
 * Plugin Name: DGO Albums
 * Plugin URI: http://dgo.3r13.nl/dgoalbums
 * Description: Adds albums to wordpress.
 * Version: 1.0
 * Author: Arjen Breur
 * Author URI: http://3r13.nl
 * License: GPL2
 */

// init the custom post type
DGO_albums::init();

class DGO_albums {
    // DEFINE CONSTANTS FOR THIS PLUGIN
    const TXT_DOMAIN 			= 'dgo_albums';
	const CPT_NAME 				= 'dgoalbum';
	const VERBOSE_NAME_SINGULAR	= 'Album';
	const VERBOSE_NAME_PLURAL	= 'Albums';
        
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

    }
     
    //add custom post type
    static function init_register_post_type() {
        register_post_type( self::CPT_NAME,
            array(
                'labels' => array(
                    'name'               => self::VERBOSE_NAME_PLURAL,
                    'singular_name'      => self::VERBOSE_NAME_SINGULAR,
                    'add_new'            => 'Nieuw '.self::VERBOSE_NAME_SINGULAR,
                    'add_new_item'       => 'Nieuw '.self::VERBOSE_NAME_SINGULAR.' toevoegen',
                    'edit'               => 'Edit',
                    'edit_item'          => 'Bewerk '.self::VERBOSE_NAME_SINGULAR,
                    'new_item'           => 'Nieuw '.self::VERBOSE_NAME_SINGULAR,
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
        // Add the title metabox
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
        $cpt_title = esc_html( $post->post_title );
        $cpt_content= $post->post_content;
        ?>
        <table>
            <tr>
                <td>
                    <?php _e('Title', self::TXT_DOMAIN);?>
                </td>
                <td>
                    <input type="text" size="80" name="<?php echo self::CPT_NAME;?>_title" value="<?php echo $cpt_title; ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <?php _e('Content', self::TXT_DOMAIN);?>
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
//        }
    }
    
	// change post name on post save
	static function name_save_pre_set_post_name($post_name) {
        if ( isset($_POST['post_type'] ) && self::CPT_NAME == $_POST['post_type'] ){
        	if(empty($post_name) && isset($_POST['post_ID']) ){
				$post_name = strtolower(self::VERBOSE_NAME_SINGULAR).'_'.$_POST['post_ID'];
        	}
        };
        return $post_name;
	}
	// set post title on post save
	static function title_save_pre_set_post_title($post_title) {
        if ( isset($_POST['post_type'] ) && self::CPT_NAME == $_POST['post_type'] ){
            if ( isset( $_POST[self::CPT_NAME.'_title'] ) && $_POST[self::CPT_NAME.'_title'] != '' ) {
				$post_title = $_POST[self::CPT_NAME.'_title'];
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
    
}
?>
