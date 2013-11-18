<?php

/*
*	@param array db result
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

	//ddbug( $lngs );

	$bounds = array(
		array( min($lats), min($lngs) ), // sw
		array( max($lats), max($lngs) )  // ne
	);

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
				'coordinates' => array_reverse( $coords[0] )
			),
			'id' => (int) $r->id
		);
	}

	return $json;
}

