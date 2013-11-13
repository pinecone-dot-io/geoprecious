//http://leafletjs.com/examples/quick-start.html
"use strict";

// @TODO make dynamic
var apikey = '6cd994e708124b93976907fdd6e64e84';
var geojsonMarkerOptions = {
    radius: 8,
    fillColor: "#ff7800",
    color: "#000",
    weight: 1,
    opacity: 1,
    fillOpacity: 0.8
};
var map = L.map( 'map' ).setView( [37.78,-127.09], 13 ); // 45.5, -122.6

L.tileLayer( 'http://{s}.tile.cloudmade.com/'+apikey+'/997/256/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
    maxZoom: 18
} ).addTo( map );

L.geoJson( data, {
	pointToLayer: function( feature, latlng ){
		console.log( latlng );
        return L.circleMarker(latlng, geojsonMarkerOptions);
    },
	style: {
	    "color": "#ff7800",
	    "weight": 5,
	    "opacity": 0.65
	}
} ).addTo( map );