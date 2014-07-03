<?php

namespace geoprecious;

foreach( array('options-general', 'post', 'user-edit') as $page ){
	add_action( 'load-'.$page.'.php', function() use ($page){
		require __DIR__.'/admin-'.$page.'.php';
	} );
}

/*
*	registers settings menu
*	attached to `admin_menu` action
*/
function admin_menu(){ 
	add_options_page( 'GeoPrecious', 'GeoPrecious', 
					  'manage_options', 'geoprecious', __NAMESPACE__.'\options_general' );
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
	
	wp_register_script( 'geoprecious-admin-post', GEOPRECIOUS_PLUGIN_URL.'/public/admin/index.js',
						array(), GEOPRECIOUS_VERSION );
	wp_enqueue_script( 'geoprecious-admin-post' );
	
	wp_register_style( 'geoprecious-admin-post', GEOPRECIOUS_PLUGIN_URL.'/public/admin/index.css',
						array(), GEOPRECIOUS_VERSION );
	wp_enqueue_style( 'geoprecious-admin-post' );
}

/*
*
*	@param object
*	@param string
*	@TODO do this on all/config taxonomies
*/
function taxonomy_edit_form( $tag, $taxonomy ){
	register_admin_base();

	$vars = (object) array();

	echo render( 'admin/edit-tags', $vars );
}
add_action( /*$taxonomy . */ 'category_edit_form', __NAMESPACE__.'\taxonomy_edit_form', 10, 2 );

