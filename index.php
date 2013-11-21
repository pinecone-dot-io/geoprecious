<?
/*
Plugin Name: GeoPrecious
Plugin URI: 
Description: 
Author: 
Version: 0.0.4
Author URI:
*/

// @TODO PHP version check
namespace GeoPrecious;

define( 'GEOPRECIOUS_VERSION', '0.0.4' );

// no trailing slash
if( !defined('GEOPRECIOUS_PLUGIN_DIR') )
	define( 'GEOPRECIOUS_PLUGIN_DIR', dirname(__FILE__) );
	
if( !defined('GEOPRECIOUS_PLUGIN_URL') )
	define( 'GEOPRECIOUS_PLUGIN_URL', plugins_url('', __FILE__) );
	
if( is_admin() )
	require GEOPRECIOUS_PLUGIN_DIR.'/admin.php';

// @TODO look into loading these on demand
require GEOPRECIOUS_PLUGIN_DIR.'/api.php';
require GEOPRECIOUS_PLUGIN_DIR.'/model.php';
require GEOPRECIOUS_PLUGIN_DIR.'/shortcode.php';
require GEOPRECIOUS_PLUGIN_DIR.'/sql.php';

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
function pre_get_posts( \WP_Query &$wp_query ){
	$wp_query->is_geoprecious_query = FALSE;
	
	if( isset($wp_query->query_vars['controller']) && $wp_query->query_vars['controller'] == 'geo-api' )
		api( $wp_query );
	
	if( isset($wp_query->query_vars['orderby']) && $wp_query->query_vars['orderby'] == 'geocode' )
		$wp_query->is_geoprecious_query = TRUE;
			
	return $wp_query;
}
add_filter( 'pre_get_posts', __NAMESPACE__.'\pre_get_posts' );

/*
*
*	@param string
*	@param array
*	@return
*/
function render( $template, $vars = array() ){
	extract( (array) $vars, EXTR_SKIP );
	require GEOPRECIOUS_PLUGIN_DIR.'/views/'.$template.'.php';
}
	
register_activation_hook( __FILE__, 'geoprecious_activation' );