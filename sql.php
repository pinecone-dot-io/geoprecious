<?php

function geo_posts_fields( $sql, WP_Query $wp_query ){
	global $wpdb;
	
	$lat = 45;
	$lng = -122;
	
	if( $wp_query->query_vars['orderby'] == 'geocode' )
		$sql .= $wpdb->prepare( ", ROUND( ( 3959 * acos( cos( radians(geo_db.lat) ) * cos( radians( %f ) ) 
								   * cos( radians(%f) - radians(geo_db.lng)) + sin(radians(geo_db.lat)) 
								   * sin( radians(%f)))), 1 ) AS `distance` ", $lat, $lng, $lat );
		
	return $sql;
}

function geo_posts_join( $sql, WP_Query $wp_query ){
	global $wpdb;
	
	if( $wp_query->query_vars['orderby'] == 'geocode' )
		$sql .= " LEFT JOIN geo_db ON $wpdb->posts.ID = geo_db.post_id ";
		
	return $sql;
}

function geo_posts_orderby( $sql, WP_Query $wp_query ){
	if( $wp_query->query_vars['orderby'] == 'geocode' )
		$sql = " `distance` ASC, ".$sql;
		
	return $sql;
}

function geo_posts_request( $sql, WP_Query $wp_query ){
	// just to see whats going on
	var_dump( $sql );
	return $sql;
}

add_filter( 'posts_fields', 'geo_posts_fields', 10, 2 );
add_filter( 'posts_join', 'geo_posts_join', 10, 2 );
add_filter( 'posts_orderby', 'geo_posts_orderby', 10, 2 );
add_filter( 'posts_request', 'geo_posts_request', 10, 2 );

add_action( 'init', function(){
	$geo_posts = new WP_Query( array(
		'post_type' => 'any',
		'orderby' => 'geocode'
	) );
	
	//var_dump( $geo );
	die();
} );