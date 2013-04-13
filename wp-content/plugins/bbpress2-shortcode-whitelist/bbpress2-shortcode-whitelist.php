<?php /*

**************************************************************************

Plugin Name:  bbPress2 shortcode whitelist
Plugin URI:   http://wp.antonchanning.com/bbpress-shortcode-whitelist
Description:  Adds a whitelist of safe shortcodes to user content
Version:      2.2
Author:       Anton Channing
Author URI:   http://wp.antonchanning.com

**************************************************************************

Copyright (C) 2013 Anton Channing

***** GPL3 *****
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************/

define('BBPSCWL_PATH', WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)) );

//Admin options
include(BBPSCWL_PATH.'/bbpress2-shortcode-whitelist-admin.php');

//Functions
include(BBPSCWL_PATH.'/bbpress2-shortcode-whitelist-functions.php');

//Classes
include(BBPSCWL_PATH.'/class_bbpress-shortcode-whitelist.php');

$bbp_sc_whitelist = new bbPressShortcodeWhitelist();

// Start this plugin once all other plugins are fully loaded,
// and after VVQ class has been created if that plugin was installed.
add_action( 'init', create_function( '', 'global $bbp_sc_whitelist; $bbp_sc_whitelist->add_filters();' ) );
?>
