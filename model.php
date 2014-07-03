<?php

namespace geoprecious;

/*
*	gets the sw most and ne most points from a set of points
*	@param array db result
*	@return array
*/
function bounds( $res ){
	$res = array_map( function($r){
		preg_match_all( '/[\-0-9.]+[\-0-9.]+/', $r->geo, $coords );
		return array_map( 'floatval', $coords[0] );
	}, $res );

	$lats = array_map( function($r){ 
		return $r[0];
	}, $res );

	$lngs = array_map( function($r){ 
		return $r[1];
	}, $res );

	$bounds = array( 0, 1 );
	
	if( count($lats) && count($lngs) ){
		$bounds[0] = array( min($lats), min($lngs) );	// sw
		$bounds[1] = array( max($lats), max($lngs) );	// ne
	}

	return $bounds;
}

/*
*
*	@param array db result
*/
function map_to_geojson( $res ){
	$json = array(
		'type' => 'FeatureCollection',
		'features' => array()
	);

	// http://leafletjs.com/examples/geojson.html
	foreach( $res as $r ){
		$geo = $r->geo;
		$geo = preg_match_all( '/[\-0-9.]+[\-0-9.]+/', $geo, $coords );
		$coords[0] = array_map( 'floatval', $coords[0] );

		//dbug( $coords[0] );
		
		$json['features'][] = (object) array(
			'type' => 'Feature',
			'properties' => (object) array(
				'name' => 'name',
				'amenity' => 'amenity',
				'popupContent' => 'test popup content'
			),
			'geometry' => (object) array(
				'type' => 'Point', 
				'coordinates' => $coords[0]
			),
			'id' => (int) $r->id
		);
		
		//ddbug($json);
	}

	return $json;
}

/*
*	catched api in url and routes
*	attached to `pre_get_posts` filter
*/
function pre_get_posts( \WP_Query &$wp_query ){
	$wp_query->is_geoprecious_query = FALSE;
	
	if( isset($wp_query->query_vars['controller']) && $wp_query->query_vars['controller'] == 'geo-api' )
		api( $wp_query );
	
	if( isset($wp_query->query_vars['orderby']) && $wp_query->query_vars['orderby'] == 'geocode' )
		$wp_query->is_geoprecious_query = TRUE;
			
	return $wp_query;
}
add_filter( 'pre_get_posts', __NAMESPACE__.'\pre_get_posts' );

/*
*
*/
function activation(){
	global $wpdb;
	// create table
	$sql = "SHOW TABLES LIKE 'geoprecious'";
	$exists = $wpdb->get_var( $sql );
	
	if( !$exists ){
		$schema = "CREATE TABLE `geoprecious` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`blog_id` int(11) DEFAULT NULL,
					`post_id` int(11) DEFAULT NULL,
					`user_id` int(11) DEFAULT NULL,
					`term_taxonomy_id` int(11) DEFAULT NULL,
					`geo` geometry DEFAULT NULL,
					`stamp` int(16) DEFAULT NULL,
				   PRIMARY KEY (`id`)
				   ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
		$wpdb->query( $schema );
	}
	
	// setup rewrite rule
	add_rewrite_rule( '^geo-api$', 'index.php?controller=geo-api', 'top' );
	flush_rewrite_rules();
}