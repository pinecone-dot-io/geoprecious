<?php

namespace geoprecious;

function admin_bootstrap(){
	global $pagenow;
	//ddbug( $pagenow );
	
	$pages = array(
		'edit-tags.php' => 'edit-tags', 
		//'options-general.php' => 'options-general', // settings_page_geoprecious
		'post.php' => 'post', 
		'post-new.php' => 'post', 
		'profile.php' => 'user-edit',
		'user-edit.php' => 'user-edit'
	);
	
	if( isset($pages[$pagenow]) ){
		$file = $pages[$pagenow];
		
		add_action( 'load-'.$pagenow, function() use ($file){
			require __DIR__.'/admin-'.$file.'.php';
			
			register_admin_base();
		} );
	}
	
	// 
	require __DIR__.'/admin-options-general.php';
}
add_action( 'plugins_loaded', __NAMESPACE__.'\admin_bootstrap' );

/*
*	registers settings menu
*	attached to `admin_menu` action
*/
function admin_menu(){ 
	add_options_page( 'GeoPrecious', 'GeoPrecious', 'manage_options', 
					  'geoprecious', __NAMESPACE__.'\options_general' );
}
add_action( 'admin_menu', __NAMESPACE__.'\admin_menu' );

/*
*
*/
function register_admin_base(){
	wp_register_script( 'geoprecious-leaflet', 'http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js' );
	wp_enqueue_script( 'geoprecious-leaflet' );
	
	wp_register_style( 'geoprecious-leaflet', 'http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css' );
	wp_enqueue_style( 'geoprecious-leaflet' );
	
	wp_register_script( 'geoprecious-admin', GEOPRECIOUS_PLUGIN_URL.'/public/admin/index.js',
						array(), GEOPRECIOUS_VERSION, TRUE );
	wp_localize_script( 'geoprecious-admin', 'geo_config', array('api_key' => get_option('geoprecious_api_key')) );
	wp_enqueue_script( 'geoprecious-admin' );
	
	wp_register_style( 'geoprecious-admin', GEOPRECIOUS_PLUGIN_URL.'/public/admin/index.css',
						array(), GEOPRECIOUS_VERSION );
	wp_enqueue_style( 'geoprecious-admin' );
}



