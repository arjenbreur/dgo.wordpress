<?php
/*
Plugin Name: DGO Emoticons
Plugin URI: http://3r13.nl/dgo-emoticons
Description: Adds javascript emoticon-insert buttons to post|comment edit forms. The global wordpress smilies array is used as smiley source. This plugin is forked from "Simple Smilies" by Dianakc (http://dianakcury.com/)
Author: Arjen Breur
Author URI: http://3r13.nl
*/


add_action( 'init', 'DGO_emoticons_init',1 );
//add_action( 'admin_init', 'DGO_emoticons_admin_init',1 );
add_filter( 'init', 'DGO_emoticons_rewrite_smiliesarray', 0, 0 );
    
function DGO_emoticons_init(){

    add_filter('get_header','DGO_emoticons_get_header_insert_script');
    
    if (is_user_logged_in()) {
        add_action('comment_form_logged_in_after', 'DGO_emoticons_insert');
    } else {
        add_action('comment_form_after_fields', 'DGO_emoticons_insert');
    }
}
    
//function DGO_emoticons_admin_init(){
//    
//    add_filter('get_header','DGO_emoticons_get_header_insert_script');
//    
//    if (is_user_logged_in()) {
//        add_action('edit_form_after_editor ', 'DGO_emoticons_insert');
//        add_action('edit_form_advanced', 'DGO_emoticons_insert');
//    }
//}


function DGO_emoticons_get_header_insert_script(){
      if ( is_singular() )
      wp_enqueue_script( 'dgo-emoticons', plugins_url() . '/dgo-emoticons/dgo-emoticons.js');
}


function DGO_emoticons_insert(){
    include('dgo-emoticons-insert.php');
}


function DGO_emoticons_get_them(){
    global $wpsmiliestrans;
    $them = '';
    foreach($wpsmiliestrans AS $code=>$filename){
        $path = includes_url().'images/smilies/'.$filename;
        $title = basename($path, ".gif");
		$them .= '<a href="javascript:grin(\''.addslashes($code).'\')" ><img src="'.$path.'"  alt="'.$title.'"  title="'.$title.'" /></a>';
    }
    return $them;
}

    
function DGO_emoticons_rewrite_smiliesarray(){
    global $wpsmiliestrans;
    $wpsmiliestrans = array(
                            /* * /
                             ':mrgreen:' => 'icon_mrgreen.gif',
                             ':neutral:' => 'icon_neutral.gif',
                             ':twisted:' => 'icon_twisted.gif',
                             ':arrow:' => 'icon_arrow.gif',
                             ':shock:' => 'icon_eek.gif',
                             ':smile:' => 'icon_smile.gif',
                             ':???:' => 'icon_confused.gif',
                             ':cool:' => 'icon_cool.gif',
                             ':evil:' => 'icon_evil.gif',
                             ':grin:' => 'icon_biggrin.gif',
                             ':idea:' => 'icon_idea.gif',
                             ':oops:' => 'icon_redface.gif',
                             ':razz:' => 'icon_razz.gif',
                             ':roll:' => 'icon_rolleyes.gif',
                             ':wink:' => 'icon_wink.gif',
                             ':cry:' => 'icon_cry.gif',
                             ':eek:' => 'icon_surprised.gif',
                             ':lol:' => 'icon_lol.gif',
                             ':mad:' => 'icon_mad.gif',
                             ':sad:' => 'icon_sad.gif',
                             '8-)' => 'icon_cool.gif',
                             '8-O' => 'icon_eek.gif',
                             ':-(' => 'icon_sad.gif',
                             ':-)' => 'icon_smile.gif',
                             ':-?' => 'icon_confused.gif',
                             ':-D' => 'icon_biggrin.gif',
                             ':-P' => 'icon_razz.gif',
                             ':-o' => 'icon_surprised.gif',
                             ':-x' => 'icon_mad.gif',
                             ':-|' => 'icon_neutral.gif',
                             ';-)' => 'icon_wink.gif',
                             // This one transformation breaks regular text with frequency.
                             //     '8)' => 'icon_cool.gif',
                             '8O' => 'icon_eek.gif',
                             ':(' => 'icon_sad.gif',
                             ':)' => 'icon_smile.gif',
                             ':?' => 'icon_confused.gif',
                             ':D' => 'icon_biggrin.gif',
                             ':P' => 'icon_razz.gif',
                             ':o' => 'icon_surprised.gif',
                             ':x' => 'icon_mad.gif',
                             ':|' => 'icon_neutral.gif',
                             ';)' => 'icon_wink.gif',
                             ':!:' => 'icon_exclaim.gif',
                             ':?:' => 'icon_question.gif',
                             /* DGO LEGACY FORUM EMOTICONS */
                            '[:)]' => 'smile.gif',
                            '[;)]' => 'wink.gif',
                            '[:*)]' => 'shiny.gif',
                            '[8)]' => 'coool.gif',
                            '[:9~]' => 'kwijl.gif',
                            '[:P]' => 'puh.gif',
                            '[:+]' => 'clown.gif',
                            '[O+]' => 'heart.gif',
                            '[O-)]' => 'hypocrite.gif',
                            '[_/-o_]' => 'worshippy.gif',
                            '[:z]' => 'sleephappy.gif',
                            '[:?]' => 'confused.gif',
                            '[:O]' => 'yawnee.gif',
                            '[:r]' => 'pukey.gif',
                            '[|:(]' => 'frusty.gif',
                            '[:X]' => 'shutup.gif',
                            "[:'(]" => 'cry.gif',
                            '[;(]' => 'sadley.gif',
                            '[:(]' => 'frown.gif',
                            '[})]' => 'cow.gif',
                            '[}:O]' => 'devilish.gif',
                            
                            );
}


;?>