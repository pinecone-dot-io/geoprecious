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

	var map = L.map( defaults.map_id ).fitBounds( defaults.bounds );
	//var map = L.map( 'map' ).setView( [37.78,-127.09], 13 ); // 45.5, -122.6

	L.tileLayer( 'http://{s}.tile.cloudmade.com/'+( defaults.api_key )+'/997/256/{z}/{x}/{y}.png', {
	    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
	    maxZoom: 18
	} ).addTo( map );

	L.geoJson( defaults.data, {
	    style: {
		    "color": "#ff7800",
		    "weight": 5,
		    "opacity": 0.65
		}
	} ).addTo( map );
};

