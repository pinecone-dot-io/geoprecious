<?php

namespace geoprecious;

/*
*
*	@return array
*/
function geo_data_format( $res ){
	$lats = array_map( function($r){ 
		return $r->geo->val[0];
	}, $res );
	
	$lngs = array_map( function($r){ 
		return $r->geo->val[1];
	}, $res );
	
	$bounds = bounds( $lats, $lngs );
	$center = center( $lats, $lngs );
	
	$data = map_to_geojson( $res );
	
	return array( 'bounds' => $bounds,
				  'center' => $center,
				  'data' => $data,
				  'map_id' => 'map' );
}

/*
*
*	@return array
*/
function post_geoprecious_data(){
	$data = stripslashes_deep( $_POST['geoprecious_data'] );
	$data = array_map( 'json_decode', $data );
	
	return $data;
}

/* 
*	render a page into wherever
*	@param string filename inside /views/ directory, no trailing .php
*	@param object|array variables available to view
*	@return string html
*/
function render( $filename, $vars = array() ){
	extract( (array) $vars, EXTR_SKIP );
	
	ob_start();
	require __DIR__.'/views/'.$filename.'.php';
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}