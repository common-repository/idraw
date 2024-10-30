<?php
/**
 * @package IDraw
 * @version 1.0
 */
/*
Plugin Name: I Draw
Plugin URI: http://wordpress.org/extend/plugins/idraw/
Description: idraw allows you to create beautiful diagrams and insert them into your posts or pages directly. Draw all kinds of diagrams: flowcharts, UML, circuit diagrams, formula, etc. It is fast to load and easy to use. It is free and without registration.
Author: XianJim
Version: 1.0
Author URI: http://wwww.5idraw.com/index.htm
*/

function idraw_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_idraw_tinymce_plugin");
     add_filter('mce_buttons', 'register_idraw_button');
   }
}
 
function register_idraw_button($buttons) {
   array_push($buttons, "separator", "idraw");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_idraw_tinymce_plugin($plugin_array) {
   $site_url = get_option('siteurl');
   $idraw_url = $site_url . '/wp-content/plugins/idraw/tinymce/editor_plugin.js';
   $plugin_array['idraw'] = $idraw_url;
   return $plugin_array;
}
 
// init process for button control
add_action('init', 'idraw_addbuttons');

function idraw_image_substitute(&$match)
{
    return "alt=\"Diagrams created with www.5idraw.com";
}

function idraw_image_before_filter($content)
{
    return preg_replace_callback(
        "|alt=\"%7B%22w%22:[^\"]*|",
        "idraw_image_substitute",
        $content
    );
}

add_filter('the_content', 'idraw_image_before_filter', 0);

?>
