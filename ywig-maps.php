<?php
/*
Plugin Name:  Ywig Map
Plugin URI:   https://youthworkgalway.ie
Description:  Show youth servces in Galway
Version:      1.0.0
Author:       moh
Author URI:   https://youthworkgalway.ie
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

if( !defined('ABSPATH')){
	exit;
}


require_once(plugin_dir_path(__FILE__).'/includes/ywig-map-scripts.php');

//concerning the widget
require_once(plugin_dir_path(__FILE__).'/includes/ywig-map-class.php');

//register custom post type
require_once(plugin_dir_path(__FILE__).'/includes/ywig-map-cpt.php');

/*
	Uncomment this to use plugin without relying on Advanced Custom Fields
	Also remove comment in ywig-map-scripts.php


//meta fields for marker post type

require_once(plugin_dir_path(__FILE__).'/includes/ywig-map-fields.php');

*/


function register_ywigmap(){

	register_widget('Ywig_Map_Widget');

}
add_action('widgets_init', 'register_ywigmap' );


