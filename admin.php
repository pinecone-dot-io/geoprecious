<?php

namespace GeoPrecious;

add_action( 'admin_menu', __NAMESPACE__.'\settings_register' );
add_action( 'add_meta_boxes', __NAMESPACE__.'\meta_box_register' );

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

/*
*	callback for `meta_box_register`
*	post.php meta box
*/
function meta_box_render(){
	geoprecious_register_admin_base();
	
	global $wpdb;
	$sql = $wpdb->prepare( "SELECT id, ASTEXT( geo ) AS `geo`, `stamp` 
							FROM geoprecious 
							WHERE blog_id = %d 
							AND post_id = %d
							ORDER BY stamp DESC, id DESC", 1, 1 );
	$res = $wpdb->get_results( $sql );

	$bounds = bounds( $res );
	$data = map_to_geojson( $res );

	$vars = (object) array(
		'bounds' => $bounds,
		'data' => $data
	);
	echo geoprecious_render( 'admin/post', $vars );
}

/*
*	show map in eser edit profile.php
*	@param WP_User
*/
function geoprecious_profile( WP_User $wp_user ){
	//ddbug( $profileuser );
	
	geoprecious_register_admin_base();
	
	$vars = (object) array(
		'data' => $wp_user->data
	);
	echo geoprecious_render( 'admin/profile', $vars );
}
add_action( 'show_user_profile', 'geoprecious_profile', 10, 1 );

/*
*
*/
function geoprecious_register_admin_base(){
	wp_register_script( 'geoprecious-leaflet', 'http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js' );
	wp_enqueue_script( 'geoprecious-leaflet' );
	
	wp_register_style( 'geoprecious-leaflet', 'http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css' );
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

/*
*
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
	
	echo geoprecious_render( 'admin/options-general', $vars );
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
	//dbug( func_get_args() );
	geoprecious_register_admin_base();

	$vars = (object) array();

	echo geoprecious_render( 'admin/edit-tags', $vars );
}
add_action( /*$taxonomy . */ 'category_edit_form', __NAMESPACE__.'\taxonomy_edit_form', 10, 2 );

/*
*
*/
function geoprecious_activation(){
	global $wpdb;
	// create table
	$sql = "SHOW TABLES LIKE 'geoprecious'";
	$exists = $wpdb->get_var( $sql );
	
	if( !$exists ){
		$schema = "CREATE TABLE `geoprecious` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`blog_id` int(11) DEFAULT NULL,
					`post_id` int(11) DEFAULT NULL,
					`user_id` int(11) DEFAULT NULL,
					`term_taxonomy_id` int(11) DEFAULT NULL,
					`geo` geometry DEFAULT NULL,
					`stamp` int(16) DEFAULT NULL,
				   PRIMARY KEY (`id`)
				   ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
		$wpdb->query( $schema );
	}
	
	// setup rewrite rule
	add_rewrite_rule( '^geo-api$', 'index.php?controller=geo-api', 'top' );
	flush_rewrite_rules();
}