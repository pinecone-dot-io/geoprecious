<?php

namespace geoprecious;

/*
*
*	@param string
*	@param WP_Query
*	@return string
*/
function posts_fields( $sql, \WP_Query $wp_query ){
	/*
	global $wpdb;
		
	$lat = 45;
	$lng = -122;
	
	$sql .= $wpdb->prepare( ", ROUND( ( 3959 * acos( cos( radians(geoprecious.lat) ) * cos( radians( %f ) ) 
							   * cos( radians(%f) - radians(geoprecious.lng)) + sin(radians(geoprecious.lat)) 
							   * sin( radians(%f)))), 1 ) AS `distance` ", $lat, $lng, $lat );
	*/
	
	$sql .= ", GROUP_CONCAT( ASTEXT(G.geo) ) as geo";
	return $sql;
}
add_filter( 'posts_fields', __NAMESPACE__.'\posts_fields', 10, 2 );

/*
*
*	@param string
*	@param WP_Query
*	@return string
*/
function posts_groupby( $sql, \WP_Query $wp_query ){
	global $wpdb;
	
	if( !trim($sql) )
		$sql = "$wpdb->posts.ID";
		
	return $sql;
}
add_filter( 'posts_groupby', __NAMESPACE__.'\posts_groupby', 10, 2 );

/*
*
*	@param string
*	@param WP_Query
*	@return string
*/
function posts_join( $sql, \WP_Query $wp_query ){
	global $blog_id, $wpdb;
	
	$sql .= " LEFT JOIN geoprecious G 
			  ON $wpdb->posts.ID = G.post_id 
			 	AND G.blog_id = $blog_id
			 	AND G.term_taxonomy_id = 0
			 	AND G.user_id = 0 ";
	return $sql;
}
add_filter( 'posts_join', __NAMESPACE__.'\posts_join', 10, 2 );

/*
*
*	@param string
*	@param WP_Query
*	@return string
*/
function posts_request( $sql, \WP_Query $wp_query ){
	//ddbug( $sql );
	
	return $sql;
}
add_filter( 'posts_request', __NAMESPACE__.'\posts_request', 10, 2 );

/*
*
*	@param string
*	@param WP_Query
*	@return string
*/
function posts_results( $posts, \WP_Query $wp_query ){
	$posts = array_map( function(\WP_Post $wp_post){
		$wp_post->geo = explode( ',', $wp_post->geo );
		
		return $wp_post;
	}, $posts );
	
	return $posts;
}
add_filter( 'posts_results', __NAMESPACE__.'\posts_results', 10, 2 );