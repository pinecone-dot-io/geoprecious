<?php

namespace geoprecious;

/*
*
*	@param string
*	@param WP_Query
*	@return string
*/
function posts_fields( $sql, \WP_Query $wp_query ){
	global $wpdb;
	
	if( !$wp_query->is_geoprecious_query )
		return $sql;
		
	$lat = 45;
	$lng = -122;
	
	if( $wp_query->query_vars['orderby'] == 'geocode' )
		$sql .= $wpdb->prepare( ", ROUND( ( 3959 * acos( cos( radians(geoprecious.lat) ) * cos( radians( %f ) ) 
								   * cos( radians(%f) - radians(geoprecious.lng)) + sin(radians(geoprecious.lat)) 
								   * sin( radians(%f)))), 1 ) AS `distance` ", $lat, $lng, $lat );
		
	return $sql;
}

/*
*
*	@param string
*	@param WP_Query
*	@return string
*/
function posts_join( $sql, \WP_Query $wp_query ){
	global $wpdb;
	
	if( !$wp_query->is_geoprecious_query )
		return $sql;
		
	if( $wp_query->query_vars['orderby'] == 'geocode' )
		$sql .= " LEFT JOIN geoprecious ON $wpdb->posts.ID = geoprecious.post_id ";
		
	return $sql;
}

/*
*
*	@param string
*	@param WP_Query
*	@return string
*/
function posts_orderby( $sql, \WP_Query $wp_query ){
	if( !$wp_query->is_geoprecious_query )
		return $sql;
		
	if( $wp_query->query_vars['orderby'] == 'geocode' )
		$sql = " `distance` ASC, ".$sql;
		
	return $sql;
}

/*
*
*	@param string
*	@param WP_Query
*	@return string
*/
function posts_request( $sql, \WP_Query $wp_query ){
	if( !$wp_query->is_geoprecious_query )
		return $sql;
		
	var_dump( $sql );
		
	return $sql;
}

add_filter( 'posts_fields', __NAMESPACE__.'\posts_fields', 10, 2 );
add_filter( 'posts_join', __NAMESPACE__.'\posts_join', 10, 2 );
add_filter( 'posts_orderby', __NAMESPACE__.'\posts_orderby', 10, 2 );
add_filter( 'posts_request', __NAMESPACE__.'\posts_request', 10, 2 );

/*
add_action( 'init', function(){
	$geo_posts = new \WP_Query( array(
		'post_type' => 'any',
		'orderby' => 'geocode'
	) );
	
	var_dump( $geo_posts );
	die();
} );
*/