<?php

/*
*
*	@param array db result
*/
function map_to_geojson( $res ){
	$json = array();

	

	// http://leafletjs.com/examples/geojson.html
	foreach( $res as $r ){
		//dbug( $r->geo );

		$geo = $r->geo;
		$geo = preg_match_all( '/[\-0-9.]+[\-0-9.]+/', $geo, $coords );
		$coords[0] = array_map( 'floatval', $coords[0] );

		//dbug( $coords[0] );

		$json[] = (object) array(
			'type' => 'Feature',
			'properties' => (object) array(
				'name' => 'name',
				'amenity' => 'amenity',
				'popupContent' => 'test popup content'
			),
			'geometry' => (object) array(
				'type' => 'Point', 
				'coordinates' => $coords[0]
			)
		);
	}

	return $json;
}

