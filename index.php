<?
/*
Plugin Name: GeoPrecious
Plugin URI: 
Description: 
Author: 
Version: 0.0.2
Author URI:
*/

if( is_admin() )
	require dirname( __FILE__ ).'/admin.php';

/*
*
*/
function geoprecious_api(){
	ddbug( $_POST );
}

/*
*
*	attached to `init` action
*/
function geoprecious_init(){
	add_rewrite_tag( '%controller%', '([^&]+)' );
}
add_action( 'init', 'geoprecious_init' );

/*
*
*	attached to `pre_get_posts` filter
*/
function geoprecious_pre_get_posts( WP_Query $wp_query ){
	if( isset($wp_query->query_vars['controller']) && $wp_query->query_vars['controller'] == 'geo-api' )
		geoprecious_api();
		
	return $wp_query;
}
add_filter( 'pre_get_posts', 'geoprecious_pre_get_posts' );

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