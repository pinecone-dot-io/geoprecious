//http://leafletjs.com/examples/quick-start.html

/*
*	defaults:
*		api_key		string
*		bounds
*		data
*		map_id		string
*/
function geoprecious_admin( defaults ){
	"use strict";
	
	var map = L.map( defaults.map_id ).setView( [39.191,-96.591], 13 );
	
	L.tileLayer( 'https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
			'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="http://mapbox.com">Mapbox</a>',
		id: defaults.api_key
	} ).addTo( map );
	
	map.on( 'click', function(e){
		var marker = L.marker( [e.latlng.lat, e.latlng.lng] ).addTo( map );
		//console.log( e.latlng );
	} );
	
	/*
	L.geoJson( defaults.data, {
	    style: {
		    "color": "#ff7800",
		    "weight": 5,
		    "opacity": 0.65
		}
	} ).addTo( map );
	*/
};

