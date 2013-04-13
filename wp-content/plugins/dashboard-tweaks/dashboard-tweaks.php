<?php
/*
Plugin Name: Dashboard Tweaks
Plugin URI: http://wpti.ps?p=150
Description: A Collection of Tweaks (Functions & CSS) for WordPress version 3.3 and higher. 
Version: 1.0.9
Author: Piet Bos
Author URI: http://wpti.ps
License: GPLv2
*/

/*  Copyright 2011  Piet Bos  (email: senlinonline@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// For debugging purposes
//error_reporting(E_ALL);
//ini_set("display_errors", 1); 
//define('WP-DEBUG', true);

register_activation_hook( __FILE__, 'sl_dashboard_tweaks_install' );

// If < WP 3.3 die nicely (source: http://www.presscoders.com/2011/11/deactivate-wordpress-plugin-automatically/)
function sl_requires_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );
 
	if ( version_compare($wp_version, "3.3", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.3 or higher! Deactivating Plugin.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}
add_action( 'admin_init', 'sl_requires_wordpress_version' );

add_action( 'init', 'sl_dashboard_tweaks_init' );

function sl_dashboard_tweaks_init() { load_plugin_textdomain( 'sl_dashtweaks' ); }

function sl_dashboard_tweaks() {
	echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('css/wp-admin.css', __FILE__). '">';
}
add_action('admin_head', 'sl_dashboard_tweaks');

/*
	BELOW HERE YOU CAN EDIT AS YOU LIKE. You can for example take out more menus, add menus and/or change the texts.
*/

// Remove WordPress sub-menu from the toolbar, add custom admin logo instead and remove "Visit Site" sub-menu under site-name.
function sl_dashboard_tweaks_render() {
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array(
		'id'    => 'wp-logo',
		'title' => '<span class="sl-dashboard-logo"></span>',
		'href'  => is_admin() ? home_url( '/' ) : admin_url(),
		'meta'  => array(
			'title' => __('Visit the Frontend of your website'),
		),
	) );
	$wp_admin_bar->remove_menu('about');
	$wp_admin_bar->remove_menu('wporg');
	$wp_admin_bar->remove_menu('documentation');
	$wp_admin_bar->remove_menu('support-forums');
	$wp_admin_bar->remove_menu('feedback');
	$wp_admin_bar->remove_menu('view-site');
	$wp_admin_bar->remove_menu('comments'); // optional, delete comments as many websites don't even have those enabled.
}
add_action( 'wp_before_admin_bar_render', 'sl_dashboard_tweaks_render' );

// Change "Howdy" into "Welcome back" (source: http://wpmu.org/daily-tip-how-to-change-the-wordpress-howdy-message-to-a-custom-welcome/)
function change_howdy($translated, $text, $domain) {
    if (!is_admin() || 'default' != $domain)
        return $translated;
    if (false !== strpos($translated, 'Howdy'))
        return str_replace('Howdy', 'Welcome back', $translated);
    return $translated;
}
add_filter('gettext', 'change_howdy', 10, 3);

// Rebrand the title at the top of all pages in wp-admin (source: http://www.artofblog.com/white-labeling-wordpress/#comment-32486)
function sl_new_wp_title() {
$new_title= __('Dashboard of ', 'sl_dashtweaks') . get_option('blogname');
return $new_title;
}
add_filter('admin_title', 'sl_new_wp_title');

// Customize admin footer text
function sl_admin_footer() {
	echo __('The Dashboard Tweaks plugin has been developed by Piet Bos of <a href="http://wpti.ps">WP TIPS</a>. For any issues, please open a thread in the Wordpress plugin forum.', 'sl_dashtweaks');
} 
add_filter('admin_footer_text', 'sl_admin_footer');

// Add a favicon for your admin
function sl_admin_favicon() {
	echo '<link rel="Shortcut Icon" type="image/x-icon" href="' .plugins_url('images/dashboard.png', __FILE__). '" />';
}
add_action('admin_head', 'sl_admin_favicon');

/** The code below has been adapted "Icon for Toolbar Site Link" (http://wpengineer.com/?p=2366) Author Sergej Müller (http://ebiene.de) License GPLv3
 */
// If the function already exists we don't do anything.
if ( ! function_exists( 'sl_add_toolbar_site_icon' ) ) {
	// add to admin area, inside head
	add_action( 'admin_head', 'sl_add_toolbar_site_icon' );
	// add to frontend, inside head, uncomment to make available
	//add_action( 'wp_head', 'sm_add_adminbar_site_icon' );
	function sl_add_toolbar_site_icon() {
		if ( ! is_admin_bar_showing() ) {
			return;
		}
		echo '<style>
			#wp-admin-bar-site-name > a.ab-item:before {
				float: left;
				width: 16px;
				height: 16px;
				margin: 5px 5px 0 -1px;
				display: block;
				content: "";
				opacity: 0.4;
				background: #000 url("http://www.google.com/s2/u/0/favicons?domain=' . parse_url( home_url(), PHP_URL_HOST ). '");
				border-radius: 16px;
			}
			#wp-admin-bar-site-name:hover > a.ab-item:before {
				opacity: 1;
			}
		</style>';
	}
}
