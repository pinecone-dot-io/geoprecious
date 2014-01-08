<?php

namespace GeoPrecious;

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