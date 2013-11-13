<?
/*
Plugin Name: GeoPrecious
Plugin URI: 
Description: 
Author: 
Version: 0.0.3
Author URI:
*/

// @TODO PHP version check
namespace GeoPrecious;

if( !defined('GEOPRECIOUS_PLUGIN_DIR') )
	define( 'GEOPRECIOUS_PLUGIN_DIR', plugins_url('', __FILE__) );

if( is_admin() )
	require dirname( __FILE__ ).'/admin.php';

require dirname( __FILE__ ).'/model.php';

/*
*
*/
function geoprecious_api( WP_Query $wp_query ){
	ddbug( $_POST );
}

/*
*	adds support `for controller` query var for api
*	attached to `init` action
*/
function init(){
	add_rewrite_tag( '%controller%', '([^&]+)' );
}
add_action( 'init', __NAMESPACE__.'\init' );

/*
*	catched api in url and routes
*	attached to `pre_get_posts` filter
*/
function pre_get_posts( \WP_Query $wp_query ){
	if( isset($wp_query->query_vars['controller']) && $wp_query->query_vars['controller'] == 'geo-api' )
		geoprecious_api( $wp_query );
		
	return $wp_query;
}
add_filter( 'pre_get_posts', __NAMESPACE__.'\pre_get_posts' );

/*
*
*	@param string
*	@param array
*	@return
*/
function geoprecious_render( $template, $vars = array() ){
	extract( (array) $vars, EXTR_SKIP );
	require dirname( __FILE__ ).'/views/'.$template.'.php';
}
	
register_activation_hook( __FILE__, 'geoprecious_activation' );