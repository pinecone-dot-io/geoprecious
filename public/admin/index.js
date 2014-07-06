//http://leafletjs.com/examples/quick-start.html

/*
*	defaults:
*		api_key		string
*		bounds
*		data
*		map_id		string
*/
function geoprecious_admin( config, defaults ){
	"use strict";
	
	var map = L.map( defaults.map_id );
	
	if( defaults.data.features.length > 0 )
		map.fitBounds( [defaults.bounds[0], defaults.bounds[1]] );
	else 
		map.setView( defaults.center, 13 );
		
	var $map_points = jQuery( '#map-points' );
	
	/*
	*
	*/
	L.tileLayer( 'https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
			'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="http://mapbox.com">Mapbox</a>',
		id: config.api_key
	} ).addTo( map );
	
	/*
	*
	*	@param object
	*/
	function insert_input( value ){
		var $input = jQuery( '<input name="geoprecious_data[]"/>' );
		$input.attr( 'value', JSON.stringify(value) );
		$map_points.append( $input );
	}
	
	/*
	*
	*/
	map.on( 'click', function(e){
		var marker = L.marker( [e.latlng.lat, e.latlng.lng] ).addTo( map );
		
		var value = {
			lat: e.latlng.lat,
			lng: e.latlng.lng,
			stamp: e.originalEvent.timeStamp,
			type: 'point'
		};
		
		insert_input( value );
	} );
	
	/*
	*	add existing points to map
	*/
	for( var i in defaults.data.features ){
		var marker = L.marker( defaults.data.features[i].geometry.coordinates ).addTo( map );
		console.log('defaults.data.features[i].geometry.coordinates', defaults.data.features[i].geometry.coordinates );
		//console.log(defaults.data.features[i]);
		
		var value = {
			lat: defaults.data.features[i].geometry.coordinates[0],
			lng: defaults.data.features[i].geometry.coordinates[1],
			stamp: defaults.data.features[i].geometry.stamp,
			type: 'point'
		};
		
		insert_input( value );
	}
};

jQuery( document ).ready( function(){
	// @TODO work this out
	if( typeof(geo_data) != 'undefined' )
		new geoprecious_admin( geo_config, geo_data );
} );

