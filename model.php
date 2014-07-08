<?php

namespace geoprecious;

/*
*	gets the sw most and ne most points from a set of points
*	@param array
*	@param array
*	@return array
*/
function bounds( $lats, $lngs ){
	$bounds = array( 0, 1 );
	
	if( count($lats) && count($lngs) ){
		$bounds[0] = array( min($lats), min($lngs) );	// sw
		$bounds[1] = array( max($lats), max($lngs) );	// ne
	}

	return $bounds;
}

/*
*	
*	@param array
*	@param array
*	@return array
*/
function center( $lats, $lngs ){
	if( !count($lats) || !count($lngs) )
		return array(
			39.191, -96.591
		);
	else
		return array(
			array_sum( $lats ) / count( $lats ),
			array_sum( $lngs ) / count( $lngs ),
		);
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
		//ddbug( $r->geo );
		
		$json['features'][] = (object) array(
			'type' => 'Feature',
			'properties' => (object) array(
				'name' => 'name',
				'amenity' => 'amenity',
				'popupContent' => 'test popup content'
			),
			'geometry' => (object) array(
				'type' => 'Point', 
				'coordinates' => $r->geo->val,
				'stamp' => $r->stamp
			),
			'id' => (int) $r->id
		);
	}

	return $json;
}

/*
*	catched api in url and routes
*	attached to `pre_get_posts` filter
*/
function pre_get_posts( \WP_Query &$wp_query ){
	$wp_query->is_geoprecious_query = FALSE;
	
	//if( isset($wp_query->query_vars['controller']) && $wp_query->query_vars['controller'] == 'geo-api' )
	//	api( $wp_query );
	
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
					  `blog_id` int(11) NOT NULL DEFAULT '0',
					  `post_id` int(11) NOT NULL DEFAULT '0',
					  `user_id` int(11) NOT NULL DEFAULT '0',
					  `term_taxonomy_id` int(11) NOT NULL DEFAULT '0',
					  `stamp` int(16) NOT NULL DEFAULT '0',
					  `geo` geometry DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		$wpdb->query( $schema );
	}
	
	// setup rewrite rule
	//add_rewrite_rule( '^geo-api$', 'index.php?controller=geo-api', 'top' );
	//flush_rewrite_rules();
}