<?php

namespace GeoPrecious;

/*
*
*	@param WP_Query
*	@return
*/
function api( \WP_Query $wp_query ){
	dbug( $wp_query->query_vars, '$wp_query' );
	ddbug( $_POST, '$_POST' );
}