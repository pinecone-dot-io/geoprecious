<?php

namespace geoprecious;

/*
*
*	@param array
*	@param
*	@param string
*	@return
*/
function shortcode( $atts, $content = NULL, $tag = '' ){
	//dbug( func_get_args(), 'geoprecious shortcode', 100 );
	
	wp_register_style( 'geoprecious-index', GEOPRECIOUS_PLUGIN_URL.'/public/index.css', 
					   array(), GEOPRECIOUS_VERSION, 'all' );
	wp_enqueue_style( 'geoprecious-index' );
	
	$defaults = array(
		'center' => '',
		'class' => 'geoprecious-map',
		'container' => 'div',
		'id' => 'map'
	);
	
	$atts = wp_parse_args( $atts, $defaults );
	
	$vars = (object) array(
		'atts' => (object) $atts,
		'content' => $content
	);
	
	$markup = render( 'shortcode', $vars );
	return $markup;
}
add_shortcode( 'geoprecious', __NAMESPACE__.'\shortcode' );