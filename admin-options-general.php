<?php

namespace geoprecious;

/*
*	callback for `add_options_page`
*/
function options_general(){
	wp_register_style( 'geoprecious-admin-options-general', GEOPRECIOUS_PLUGIN_URL.'/public/admin/options-general.css',
						array(), GEOPRECIOUS_VERSION, TRUE );
	wp_enqueue_style( 'geoprecious-admin-options-general' );
	
	wp_register_script( 'geoprecious-admin-options-general', GEOPRECIOUS_PLUGIN_URL.'/public/admin/options-general.js',
						array(), GEOPRECIOUS_VERSION, TRUE );
	wp_enqueue_script( 'geoprecious-admin-options-general' );
	
	if( isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'geoprecious-options-general') )
		options_general_update( stripslashes_deep($_POST) );
		
	$vars = (object) array();
	
	$vars->api_key = get_option( 'geoprecious_api_key' );
	$vars->post_types = get_post_types( array(), 'object' );
	$vars->taxonomies = get_taxonomies( '', 'names' );
	$vars->wpnonce = wp_create_nonce( 'geoprecious-options-general' );
	
	echo render( 'admin/options-general', $vars );
}

/*
*	
*	@param post data unslashed
*	@return
*/
function options_general_update( $data ){
	if( isset($data['api_key']) )
		update_option( 'geoprecious_api_key', $data['api_key'] );
}