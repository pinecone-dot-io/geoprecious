<?php

namespace GeoPrecious;

/*
*
*	@param array
*	@param
*	@param string
*	@return
*/
function shortcode( $atts, $content, $tag ){
	//dbug( func_get_args(), 'geoprecious shortcode' );
	
	$defaults = array(
		'center' => '',
		'class' => '',
		'container' => 'div',
		'id' => 'map'
	);
	
	$atts = wp_parse_args( $atts, $defaults );
	//ddbug( $atts );
	
	$markup = render( 'shortcode', $atts );
	return $markup;
}
add_shortcode( 'geoprecious', __NAMESPACE__.'\shortcode' );