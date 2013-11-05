<?
/*
Plugin Name: GeoPrecious
Plugin URI: 
Description: 
Author: 
Version: 0.0.1
Author URI:
*/

if( is_admin() )
	require dirname( __FILE__ ).'/admin.php';

function geoprecious_render( $template, $vars = array() ){
	extract( (array) $vars, EXTR_SKIP );
	require dirname( __FILE__ ).'/views/'.$template.'.php';
}

register_activation_hook( __FILE__, 'geoprecious_activation' );




