<?php

namespace geoprecious;

/*
*	adds support `for controller` query var for api
*	attached to `init` action
*/
function init(){
	add_rewrite_tag( '%controller%', '([^&]+)' );
}
add_action( 'init', __NAMESPACE__.'\init' );

/*
*
*	@param WP_Query
*	@return
*/
function api( \WP_Query $wp_query ){
	dlog( $_POST, '$_POST', 'geo-api' );
	
	switch( $_POST['type'] ){
		case 'point':
			break;
			
		default:
			break;
	}
	
	dbug( $wp_query->query_vars, '$wp_query' );
	ddbug( $_POST, '$_POST' );
}