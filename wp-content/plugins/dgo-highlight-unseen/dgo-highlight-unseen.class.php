<?php
    /**
     * dgo-highlight-unseen.class.php
     *
     * The DGO_highlight_unseen class file.
     *
     * PHP versions 5
     *
     * @category  dgo-highlight-unseen
     * @package   dgo-highlight-unseen
     * @author    Arjen Breur
     * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
     */
    
class DGO_highlight_unseen {
        static $add_style;
        const DGOHIGHLIGHTUNSEEN_METAKEY = 'dgohighlightunseen-seenbyusers';
        const DGOHIGHLIGHTUNSEEN_CSSCLASS = 'dgohighlightunseen';
        const DGOHIGHLIGHTUNSEEN_AJAXNONCEID = 'dgohighlightunseen_ajaxnonce';
        const DGOHIGHLIGHTUNSEEN_GROUP_BY_TYPE = FALSE;        
        
        static function init() {
            add_action('init',			 				array(__CLASS__, 'register_style') );
            add_action('admin_init', 					array(__CLASS__, 'register_style') );
            add_action('wp_footer', 					array(__CLASS__, 'print_style') );
            add_action('admin_head',				 	array(__CLASS__, 'print_style') );
            
            add_action('wp_insert_comment', 			array(__CLASS__, 'insert_comment_update_post_unseen'), 10, 1);

            add_filter('comment_class', 				array(__CLASS__, 'comment_class_seenbyuser' ), 10);
            add_filter('post_class', 					array(__CLASS__, 'post_class_seenbyuser'), 10);
            
            // Upon new post insert, add a meta key so queries will include this post as unseen
            add_action( 'wp_insert_post', 				array(__CLASS__, 'DGO_insert_post_init_unseen_metakey' ) );
            
            // setup ajax notifications
            // - add the javascript to do/manage the ajax requests
            add_action('wp_enqueue_scripts', 			array(__CLASS__, 'enqueue_script_my_ajax_request') );
            add_action('admin_enqueue_scripts',			array(__CLASS__, 'enqueue_script_my_ajax_request') );
            // - set the callback functions, used for the ajax requests
            add_action('wp_ajax_nopriv_get_unseen', 	array(__CLASS__, 'wp_ajax_get_unseen' ) );   // For non-logged in visitors
            add_action('wp_ajax_get_unseen', 			array(__CLASS__, 'wp_ajax_get_unseen' ) );          // For logged in users
            add_action('wp_ajax_nopriv_set_unseen', 	array(__CLASS__, 'wp_ajax_set_unseen' ) );   // For non-logged in visitors
            add_action('wp_ajax_set_unseen', 			array(__CLASS__, 'wp_ajax_set_unseen' ) );          // For logged in users
            add_action('wp_ajax_nopriv_set_seen',		array(__CLASS__, 'wp_ajax_set_seen' ) );   // For non-logged in visitors
            add_action('wp_ajax_set_seen', 				array(__CLASS__, 'wp_ajax_set_seen' ) );          // For logged in users
            
            // admin bar menu
            add_action('admin_bar_menu', 				array(__CLASS__, 'DGO_admin_bar_menu_unseen'), 20); // the priority number will set the position in the admin bar

        }
        
        
        static function register_style() {
            wp_register_style('dgo-highlight-unseen', plugins_url('dgo-highlight-unseen.css', __FILE__), false, false, false);
            if(is_admin_bar_showing())
            	self::$add_style = true;
        }
        
        static function print_style() {
            if ( ! self::$add_style )
                return;
            wp_enqueue_style('dgo-highlight-unseen');
        }
        
        static function comment_class_seenbyuser($classes) {
            $comment_ID = get_comment_ID();
            return self::seenbyuser($classes, 'comment', $comment_ID);
        }
        static function post_class_seenbyuser($classes) {
            $post_ID = get_the_ID();
            return self::seenbyuser($classes, 'post', $post_ID);
        }
        
        static function seenbyuser($classes, $item_type, $item_ID) {
            $current_user = wp_get_current_user();
            // only for loggedin users
            if( 0 != $current_user->ID && $item_ID){
				$item_seenbyusers = self::get_seenbyusers($item_type, $item_ID);
                // check if current user id is in the array of userids that have seen this item
                if (!in_array( $current_user->ID, $item_seenbyusers ) ) {
                    // user hasn't seen this item, add 'unseen' css class to current item's classes
                    $classes[] = self::DGOHIGHLIGHTUNSEEN_CSSCLASS;
                    // set flag to include the highlight-unseen stylesheet
                    self::$add_style = true;
                    //only update posts if user is actually viewing the post content, and not just a list of posts
                    if('comment' == $item_type || is_single() || is_page($item_ID) )
						self::set_seenbyuser($item_type, $item_ID, $current_user->ID);
                }
            }
            return $classes;
        }
        
        static function set_seenbyuser($item_type, $item_ID, $user_id){
			// check if item type is valid
			if(!in_array($item_type, array('post','comment')) )
				return false;
        	$item_seenbyusers = self::get_seenbyusers($item_type, $item_ID);
            // add current user to seenbyusers array
            $item_seenbyusers[]=$user_id;
            // clean the array
            $item_seenbyusers = array_unique($item_seenbyusers);
            // save the array in the meta table
            if( 'comment' == $item_type ){
                update_comment_meta( $item_ID, self::DGOHIGHLIGHTUNSEEN_METAKEY, $item_seenbyusers);
            }
            if( 'post' == $item_type ){
                update_post_meta( $item_ID, self::DGOHIGHLIGHTUNSEEN_METAKEY, $item_seenbyusers);
            }
            return true;
        }
        static function set_unseenbyuser($item_type, $item_ID, $user_id){
			// check if item type is valid
			if(!in_array($item_type, array('post','comment')) )
				return false;
        	$item_seenbyusers = self::get_seenbyusers($item_type, $item_ID);
            // remove current user from seenbyusers array
			if(($key = array_search($user_id, $item_seenbyusers)) !== false) {
			    unset($item_seenbyusers[$key]);
			}
            // save the array in the meta table
            if( 'comment' == $item_type ){
                update_comment_meta( $item_ID, self::DGOHIGHLIGHTUNSEEN_METAKEY, $item_seenbyusers);
            }
            if( 'post' == $item_type ){
                update_post_meta( $item_ID, self::DGOHIGHLIGHTUNSEEN_METAKEY, $item_seenbyusers);
            }
            return true;
        }
        
        static function get_seenbyusers($item_type, $item_ID){
            // get 'unseen' meta value from meta table
            if( 'comment' == $item_type)
                $item_seenbyusers = get_comment_meta( $item_ID, self::DGOHIGHLIGHTUNSEEN_METAKEY, true );
            if( 'post' == $item_type)
                $item_seenbyusers = get_post_meta( $item_ID, self::DGOHIGHLIGHTUNSEEN_METAKEY, true );
            if( !is_array($item_seenbyusers) )
            	$item_seenbyusers = array();
	       	return $item_seenbyusers;
        }
        
        // Upon new post insert, add a meta key so queries will include this post as unseen  
        static function DGO_insert_post_init_unseen_metakey($post_id) {

            /* check whether anything should be done */
            //verify post is not a revision
            if ( wp_is_post_revision( $post_id ) )
                return;

            // check if values are set
            if(! isset($_POST['post_type']) || !in_array($_POST['post_type'], self::getPostTypes()) )
                return;
            
            /* Request passes all checks; update the post's metadata */
            update_post_meta( $post_id, self::DGOHIGHLIGHTUNSEEN_METAKEY, array() );
        }
        
        //Upon new comment insert, reset post unseen meta value
        static function insert_comment_update_post_unseen($comment_ID){
            // if a new comment is inserted, mark the post as unseen for all users (empty array)
            $comment = get_comment($comment_ID);
            update_post_meta( $comment->comment_post_ID, self::DGOHIGHLIGHTUNSEEN_METAKEY, array() );
        }
        
        // get posttypes that dgohighlightunseen should interact with
        static function getPostTypes(){
        	$postTypeObjects = get_post_types(array(), 'objects');
        	$postTypes = array();
        	
        	foreach ($postTypeObjects as $postTypeObject) {
       			$postTypes[] = $postTypeObject->name;
        	}
        	return $postTypes;
        }
        
        
        /**
         * ADMIN BAR MENU
         */        

        // Add unseen menu to admin bar
        static function DGO_admin_bar_menu_unseen() {
            global $wp_admin_bar;
            if ( !is_admin_bar_showing() )
                return;
            $id = 'dgohighlightunseen';
            $icon = '';
            //$icon = '<img src="'.plugins_url('dgo-icon.png', __FILE__).'" alt="'.__('Unseen','dgohighlightunseen').'" title="'.__('Unseen','dgohighlightunseen').'" />';
            $wp_admin_bar->add_menu( array('id' => $id, 'title' => $icon . '<span id="dgohighlightunseen-adminbar-count" class="ab-label" ></span>', 'href' => '/'  ) );

            // add empty submenu, to prepair for population by javascript
            // TODO: do this nicer, by building it in javascript...
            $wp_admin_bar->add_menu( array('parent' => $id, 'title' => '', 'id' => 'dgohighlightunseen-dummy',) );
        }


        /**
         * AJAX NOTIFICATIONS
         */
        static function enqueue_script_my_ajax_request(){

            if ( !is_admin_bar_showing() )
                return;
            
            // embed the javascript file that makes the AJAX request
            wp_enqueue_script( 'my-ajax-request', plugin_dir_url( __FILE__ ) . 'ajax.js', array( 'jquery' ) );
            
            // add namespace with variables
            wp_localize_script( 'my-ajax-request', 'dgohighlightunseen',
                               array(
                                     'ajaxurl' => admin_url( 'admin-ajax.php' ), 
                                     'ajaxnonce' => wp_create_nonce( self::DGOHIGHLIGHTUNSEEN_AJAXNONCEID )
                                     )
                               );
        }
        
        static function wp_ajax_set_seen() {
            $current_user = wp_get_current_user();
            // only for loggedin users
            if( 0 != $current_user->ID && isset($_POST['item_ID']) && isset($_POST['item_type']) ){
	        	self::set_seenbyuser($_POST['item_type'], $_POST['item_ID'], $current_user->ID);
	        	self::wp_ajax_get_unseen();
            }
			exit;        
        }
        static function wp_ajax_set_unseen() {
            $current_user = wp_get_current_user();
            // only for loggedin users
            if( 0 != $current_user->ID && isset($_POST['item_ID']) && isset($_POST['item_type']) ){
	        	self::set_unseenbyuser($_POST['item_type'], $_POST['item_ID'], $current_user->ID);
	        	self::wp_ajax_get_unseen();
            }
			exit;        
        }

		static function wp_ajax_get_unseen(){
                    $nonce = ( isset( $_POST['ajaxnonce'] ) )? $_POST['ajaxnonce'] : NULL;
                    
                    // check to see if the submitted nonce matches with the
                    // generated nonce we created earlier
                    if ( ! wp_verify_nonce( $nonce, self::DGOHIGHLIGHTUNSEEN_AJAXNONCEID ) ){
                        die ( 'Busted!');
                    }       
            
                    // ignore the request if the current user doesn't have
                    // sufficient permissions
                    if ( current_user_can( 'edit_posts' ) ) {
                        // get the submitted parameters
                        //$postID = $_POST['postID'];
                        
                        // get unseen posts
                        $current_user = wp_get_current_user();
                        //global $wpdb;
                        
                        // get only the posts for this user that he hasn't seen yet
                        // NOTE: should exclude posts that are hidden for the current user (i.e. by means of plugins)
                        // so filters should NOT be suppressed
                        $post_array = get_posts(
                            array(
                                'post_type' => self::getPostTypes(),
                                'numberposts' => 101,
                                'meta_query' => array(
                                    array(
                                        'key' => self::DGOHIGHLIGHTUNSEEN_METAKEY,
                                        'value' => 'i:'.$current_user->ID.';',
                                        'compare' => 'NOT LIKE'
                                    )
                                ),
                                'suppress_filters' => false // IMPORTANT: to exclude hidden posts (i.e. by user access plugins)
                            )
                        );
                        
//                        echo "<pre>";
//                        print_r($post_array);
//                        echo "</pre>";
//                        die();
                        
                        $results = array();
                        if(self::DGOHIGHLIGHTUNSEEN_GROUP_BY_TYPE){
                            // build result array, group items by post type
                            foreach($post_array as $post){
                                // init
                                if(!isset($results[$post->post_type]['posts']))
                                    $results[$post->post_type]['posts'] = array();
                                $post->meta = get_post_meta($post->ID);
                                // add values to arrays
                                $results[$post->post_type]['posts'][]=$post;
                            }
                            // add labels and hrefs to each group item
                            foreach($results as $type=>$r){
                                // set label to show in menu (allow for singular/plural translation)
                                $postobject = get_post_type_object( $type );
                                $type_label = _n( $postobject->labels->singular_name, $postobject->labels->name, count( $results[$type]['posts'] ), 'dgohighlightunseen' );
                                $results[$type]['type'] 				= $type;
                                $results[$type]['label'] 				= count( $results[$type]['posts'] ) . ' ' . $type_label;
                                $results[$type]['notificationTitle'] 	= $type_label;
                                $results[$type]['notificationContent'] 	= count( $results[$type]['posts'] ) . ' ' . $type_label;
                                // set url to item(s)
                                if( 1 == count( $results[$type]['posts'] ) ){
                                    $results[$type]['href'] = get_permalink($results[$type]['posts'][0]->ID);
                                    // add item title to the label
                                    // $results[$type]['label'] .= '&nbsp;' . $results[$type]['posts'][0]->post_title;
                                }else{
                                    $results[$type]['href'] = get_permalink($results[$type]['posts'][0]->ID);
                                }
                                $results[$type]['notificationHref'] = $results[$type]['href'];
                            }

                        }else{
                            // Add menu_icon urls to all post types, to show in unseen submenu
                            // default wordpress posttypes don't have an icon url set, but rely on css to show the correct icon in the menu
                            // this plugin needs an icon url for each post type. Here defaults are set. If these are not the desired ones,
                            // please set them through an action hook, before this hook is called.
                            global $wp_post_types;
                            foreach ($wp_post_types AS $type=>&$values){
                                if( empty( $values->menu_icon ) ){
                                    switch($type){
                                        case 'post':
                                        case 'page':
                                            $values->menu_icon = plugins_url($type.'_icon.png', __FILE__);
                                            break;
                                        default:
                                            $values->menu_icon = plugins_url('default_icon.png', __FILE__);
                                    }
                                }
                            }
                            // build result array, ungrouped
                            foreach($post_array as $post){
                                $post->meta = get_post_meta($post->ID);
                                $postobject = get_post_type_object( $post->post_type );
                                // add values to array
                                $tmp = array();
                                $tmp['posts'][]				=$post;
                                $tmp['type'] 				= $post->post_type;
                                $tmp['label'] 				= '<img src="'.$postobject->menu_icon.'" alt="" />&nbsp;' . $post->post_title;
                                $tmp['href'] 				= get_permalink($post->ID);
                                $tmp['notificationTitle'] 	= $post->post_title;
                                $tmp['notificationContent'] =  ('dgoforumtopic'==$post->post_type)?'Nieuwe reacties':'Gewijzigd';
                                $tmp['notificationHref'] 	=  $tmp['href'];

                                $results[]=$tmp;
                            }
                        }
//                        echo "<pre>";
//                        print_r($results);
//                        echo "</pre>";
//                        die();
                        
                        // build the response
                        // - root
                        $first = reset($results);
                        $response = array(
                            'success' 				=> true,
                            'a_title' 				=> __( 'Unseen', 'dgohighlightunseen' ),
                            'a_href' 				=> ($first['href'])? $first['href'] : get_bloginfo('wpurl'),
                            'a_label' 				=> count($post_array),
                            'notificationTitle' 	=>  (count($post_array)==1)? '1 ongezien item': count($post_array). ' ongeziene items',
                            'notificationContent' 	=> '',
                            'notificationHref' 		=> ($first['href'])? $first['href'] : get_bloginfo('wpurl'),
                            'items'   				=> array()
                        );
                        // - per post type
                        foreach($results as $r){
                            $response['items'][] = array(
                                'type'          		=> $r['type'],
                                'a_href'        		=> $r['href'],
                                'a_label'       		=> $r['label'],
	                            'notificationTitle' 	=> $r['notificationTitle'],
	                            'notificationContent' 	=> $r['notificationContent'],
	                            'notificationHref' 		=> $r['notificationHref']
                            );
                        }
                        //$response['values'] = $results;
                        $json_response = json_encode($response);
                        
                        // response output
                        header( "Content-Type: application/json" );
                        echo $json_response;
                    }else{
                        //echo "Silence is golden";
                    }
            
                    // IMPORTANT: don't forget to "exit"
                    exit;
        }
        
    
    }

?>
