<?php

namespace geoprecious;

/*
*
*/
function meta_box_register() {
	foreach( get_post_types() as $screen ) {
        add_meta_box(
            'geoprecious_metabox',
            'GeoPrecious',
            __NAMESPACE__.'\meta_box_render',
            $screen
        );
    }
}
add_action( 'add_meta_boxes', __NAMESPACE__.'\meta_box_register' );

/*
*	callback for `meta_box_register`
*	post.php meta box
*	@param WP_Post
*	@param array
*	@return
*/
function meta_box_render( \WP_Post $post, array $args){
	register_admin_base();
	
	global $blog_id, $wpdb;
	$sql = $wpdb->prepare( "SELECT id, ASTEXT( geo ) AS `geo`, `stamp` 
							FROM geoprecious 
							WHERE blog_id = %d 
							AND post_id = %d
							ORDER BY stamp DESC, id DESC", $blog_id, $post->ID );
	$res = $wpdb->get_results( $sql );

	$bounds = bounds( $res );
	$data = map_to_geojson( $res );

	$vars = (object) array(
		'api_key' => get_option( 'geoprecious_api_key' ),
		'bounds' => $bounds,
		'data' => $data
	);
	echo render( 'admin/post', $vars );
}

/*
*	show map in eser edit profile.php
*	@param WP_User
*/
function show_user_profile( WP_User $wp_user ){
	//ddbug( $profileuser );
	
	register_admin_base();
	
	$vars = (object) array(
		'data' => $wp_user->data
	);
	echo render( 'admin/profile', $vars );
}
add_action( 'show_user_profile', __NAMESPACE__.'\show_user_profile', 10, 1 );

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
*	attached to `admin_menu` action
*/
function settings_register() { 
	add_options_page( 'GeoPrecious', 'GeoPrecious', 
					  'manage_options', 'geoprecious', __NAMESPACE__.'\options_general' );
}
add_action( 'admin_menu', __NAMESPACE__.'\settings_register' );

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

