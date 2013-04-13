<?php 
/*
Plugin Name: DGO Tweaks
Plugin URI: http://dgo.3r13.nl/
Description: Several tweaks like hiding unused menu items
Version: 1.0
Author: Arjen Breur
Author URI: http://3r13.nl
License: GPL2
*/

/**
 * Add filters and actions
 */

// login
add_filter('login_redirect', 'DGO_login_redirect_to_front_page', 10, 1);
// admin panel
add_action('admin_menu', 'DGO_change_post_menu_label' );
//add_action('init', 'DGO_change_post_object_label' );
add_action('admin_menu', 'DGO_remove_menus');
add_action('wp_dashboard_setup', 'DGO_remove_dashboard_widgets' );
// admin bar
// admin bar menu
//add_action('admin_bar_menu','DGO_admin_bar_add_icon', 20); // the priority number will set the position in the admin bar
add_action('wp_before_admin_bar_render', 'DGO_admin_bar_tweak', 10  );
// profile page
add_filter('user_contactmethods','DGO_tweaks_user_contactmethods_remove',10,1);
add_action('admin_head', 'DGO_tweaks_user_profile_css_hacks');
// wp editor - disable wysiwyg editor
add_filter( 'user_can_richedit', 'DGO_user_can_richedit_disable' );


// NOW FIXED BY HACKING THE CORE, SHOULD BE PLUGGABLE/ACTIONABLE IN WP3.6
// function to filter: wp_link_query()
// add_action('wp_footer','DGO_remove_action_wp_ajax-wp-link-ajax');
// function DGO_remove_action_wp_ajax_wp_link_ajax(){
// 	// remove link to existing content from add_link_dialog
// 	// because it shows all content, even stuff that should be hidden for users
// 	$post_action = 'wp-link-ajax';
// 	remove_action( 'wp_ajax_' . $post_action, 'wp_ajax_' . str_replace( '-', '_', $post_action ), 1 );
// }

/** 
 * LOGIN
 */
// Redirect users to front page after login
function DGO_login_redirect_to_front_page( $redirect_to ) {
    //if (!isset($_GET['redirect_to']))
    $redirect_to = home_url();

    return $redirect_to;
}

    
/**
 * ADMIN PANEL
 */

// Remove unused menus from the admin panel
function DGO_remove_menus () {
    // only for non-admins
    if ( ! current_user_can('manage_options')){
		remove_menu_page('tools.php');          // "Extra"
		remove_menu_page('link-manager.php');   // "Links"
		remove_menu_page('edit-comments.php');  // "Reacties"
//	    remove_menu_page('upload.php');         // "Media"
    }
}

// Change default "Alle berichten" to "Mijn berichten",
// because User Access Manager plugin filters the list to only show users own berichten
function DGO_change_post_menu_label() {
    global $menu;
    global $submenu;
    $submenu['edit.php'][5][0] = 'Mijn berichten';
    if( !current_user_can('edit_users') ){
        $menu[70][0] = 'Je Profiel';
        $submenu['profile.php'][5][0] = 'Je Profiel';
    }
}
//function DGO_change_post_object_label() {
//    if( is_admin() ){
//        global $wp_post_types;
//        $labels = &$wp_post_types['post']->labels;
//        $labels->name = 'Mijn berichten';
//    }
//}

// remove default widgets from the dashboard
function DGO_remove_dashboard_widgets() {
	global $wp_meta_boxes;

    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    
}

/**
 * ADMIN BAR
 */
// // Add icon site link to admin bar
// function DGO_admin_bar_add_icon() {
// 	global $wp_admin_bar;
// 	if ( !is_admin_bar_showing() )
// 		return;
// 	$id = 'dgoiconsitelink';
// 	$icon = '<img src="'.plugins_url('dgo-icon.png', __FILE__).'" style="left:-13px;position:relative;" alt="Home" title="Home" />';
// 	$wp_admin_bar->add_menu( array('id' => $id, 'title' => $icon, 'href' => '/'  ) );
// }


// remove defaults items from the admin bar 
function DGO_admin_bar_tweak() {
    global $wp_admin_bar;

   	if ( !is_admin_bar_showing() )
   		return;

   	// remove sitename menu for non admins    
	if( !is_admin() && !current_user_can('manage_options') ) {
		$wp_admin_bar->remove_menu('site-name');
	}
		
	// 'About' menu
    $wp_admin_bar->remove_menu('wp-logo');
    // 'Comments' menu
    if( ! current_user_can( 'moderate_comments' ) ) {
        $wp_admin_bar->remove_menu('comments');
    }
    // 'New content' dropdown menu
    //$wp_admin_bar->remove_menu('new-content'); // This removes the complete menu “Add New”. You will not require the below “remove_menu” if you using this line.
    //$wp_admin_bar->remove_menu('new-post'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Post”.
    //$wp_admin_bar->remove_menu('new-page'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Page”.
    $wp_admin_bar->remove_menu('new-media'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Media”.
    $wp_admin_bar->remove_menu('new-link'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Link”.
    $wp_admin_bar->remove_menu('new-user'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “User”.
    $wp_admin_bar->remove_menu('new-theme'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Theme”.
    $wp_admin_bar->remove_menu('new-plugin'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Plugin”.
}

// Change "Howdy" into "Welcome back" (source: http://wpmu.org/daily-tip-how-to-change-the-wordpress-howdy-message-to-a-custom-welcome/)
function change_howdy($translated, $text, $domain) {
	if (false !== strpos($text, 'Howdy, '))
		return str_replace('Howdy, ', '', $text);
	return $translated;
}
add_filter('gettext', 'change_howdy', 10, 3);


    
/**
 * PROFILE PAGE
 */
// remove unused fields
function DGO_tweaks_user_contactmethods_remove( $contactmethods ) {
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);
// werkt niet ?    unset($contactmethods['website']);
    return $contactmethods;
}
// removes the `profile.php` admin color scheme options
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

function DGO_tweaks_user_profile_css_hacks() {
   echo '<style type="text/css">
		FORM#your-profile TABLE.form-table:nth-of-type(4) TR:nth-of-type(1), /* biography */
		FORM#your-profile TABLE.form-table:nth-of-type(3) TR:nth-of-type(2), /* website */
		FORM#your-profile TABLE.form-table:nth-of-type(1) TR:nth-of-type(2){ /* Admin colorscheme selector */
			display:none;
		}
		FORM#your-profile H3{ /* hide all headers */
			display:none;
		}
		FORM#your-profile H3:nth-of-type(1){ /* show first header */
			display:block;
		}

         </style>';
}


/**
 * Wp Editor
 * disable wysiwyg editor
 */
function DGO_user_can_richedit_disable( $default ) {
	global $post;
//	if ( 'movie' == get_post_type( $post ) )
		return false;
//	return $default;
}





?>
