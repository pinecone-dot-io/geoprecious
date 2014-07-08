<?php

namespace geoprecious;

/*
*
*	@param WP_User_Query
*	@return WP_User_Query
*/
function pre_user_query( \WP_User_Query $wp_user_query ){
	$clauses = array( 'query_fields', 'query_from', 'query_where', 'query_orderby', 'query_limit' );
	
	foreach( $clauses as $clause ){
		$wp_user_query->$clause = apply_filters( $clause, $wp_user_query->$clause, $wp_user_query );
	}
	
	// only for debugging
	apply_filters( 'query_request', "SELECT $wp_user_query->query_fields 
									 		$wp_user_query->query_from 
									 		$wp_user_query->query_where 
									 		$wp_user_query->query_orderby 
									 		$wp_user_query->query_limit", $wp_user_query );
	
	return $wp_user_query;
}
add_filter( 'pre_user_query', __NAMESPACE__.'\pre_user_query', 10, 2 );

/*
*
*	@param string
*	@param WP_User_Query
*	@return string
*/
function query_fields( $sql, \WP_User_Query $wp_user_query ){
	$sql .= ", GROUP_CONCAT( ASTEXT(geo) )";
	
	return $sql;
}
add_filter( 'query_fields', __NAMESPACE__.'\query_fields', 10, 2 );

/*
*
*	@param string
*	@param WP_User_Query
*	@return string
*/
function query_from( $sql, \WP_User_Query $wp_user_query ){
	global $blog_id, $wpdb;
	
	$sql .= " LEFT JOIN geoprecious G 
			  ON $wpdb->users.ID = G.user_id 
			 	AND G.blog_id = $blog_id
			 	AND G.term_taxonomy_id = 0
			 	AND G.post_id = 0 ";
	return $sql;
}
add_filter( 'query_from', __NAMESPACE__.'\query_from', 10, 2 );

/*
*
*	@see WP_User_Query::query()
*/
function query_request(	$sql, \WP_User_Query $wp_user_query ){
	//dbug( $sql );
}
add_filter( 'query_request', __NAMESPACE__.'\query_request', 10, 2 );

/*
*
*	@param string
*	@param WP_User_Query
*	@return string
*/
function query_where( $sql, \WP_User_Query $wp_user_query ){
	global $wpdb;
	
	$sql .= " GROUP BY $wpdb->users.ID ";
	return $sql;
}
add_filter( 'query_where', __NAMESPACE__.'\query_where', PHP_INT_MAX - 1, 2 );