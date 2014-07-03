<?php

namespace geoprecious;

/* 
*	render a page into wherever
*	@param string filename inside /views/ directory, no trailing .php
*	@param object|array variables available to view
*	@return string html
*/
function render( $filename, $vars = array() ){
	extract( (array) $vars, EXTR_SKIP );
	
	ob_start();
	require GEOPRECIOUS_PLUGIN_DIR.'/views/'.$filename.'.php';
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}