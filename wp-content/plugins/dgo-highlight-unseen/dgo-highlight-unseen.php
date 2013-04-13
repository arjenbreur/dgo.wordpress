<?php
/*
Plugin Name: DGO Highlight Unseen
Plugin URI: http://3r13.nl/wordpress/plugins/dgo-highlight-unseen
Description: Adds a css class to posts/comments that logged in users have not yet seen.
Version: 1.0
Author: Arjen Breur
Author URI: http://3r13.nl
License: GPL2
*/

    require_once ('dgo-highlight-unseen.class.php' );
    
    DGO_highlight_unseen::init();
    
    

?>
