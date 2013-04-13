<?php
    /**
     * uninstall.php
     *
     * Will remove all stored plugin data upon uninstall of the plugin
     *
     * PHP versions 5
     *
     * @category  dgo-highlight-unseen
     * @package   dgo-highlight-unseen
     * @author    Arjen Breur
     * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
     */

    if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
       exit();

    // get access to the static class constants
    require_once ('dgo-highlight-unseen.class.php' );
    // get access to the database class
    global $wpdb;
    
    // remove all meta data from database
    $wpdb->query($wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s ", DGO_highlight_unseen::DGOHIGHLIGHTUNSEEN_METAKEY ) );
    $wpdb->query($wpdb->prepare( "DELETE FROM $wpdb->commentmeta WHERE meta_key = %s ", DGO_highlight_unseen::DGOHIGHLIGHTUNSEEN_METAKEY ) );

?>
