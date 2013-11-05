<?php

add_action( 'admin_menu', 'geoprecious_settings_register' );
add_action( 'add_meta_boxes', 'geoprecious_meta_box_register' );

/*
*
*/
function geoprecious_meta_box_register() {
	foreach( get_post_types() as $screen ) {
        add_meta_box(
            'geoprecious_metabox',
            'GeoPrecious',
            'geoprecious_meta_box_render',
            $screen
        );
    }
}

function geoprecious_meta_box_render(){
	wp_register_script( 'geoprecious-leaflet', 'http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js' );
	wp_enqueue_script( 'geoprecious-leaflet' );
	
	wp_register_style( 'geoprecious-leaflet', 'http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css' );
	wp_enqueue_style( 'geoprecious-leaflet' );
	
	wp_register_script( 'geoprecious-admin-post', plugins_url('admin-post.js', __FILE__) );
	wp_enqueue_script( 'geoprecious-admin-post' );
	
	wp_register_style( 'geoprecious-admin-post', plugins_url('admin-post.css', __FILE__) );
	wp_enqueue_style( 'geoprecious-admin-post' );
	
	$vars = (object) array();
	echo geoprecious_render( 'admin-post', $vars );
}

/*
*	attached to `admin_menu` action
*/
function geoprecious_settings_register() {
	add_options_page( 'GeoPrecious', 'GeoPrecious', 
					  'manage_options', 'geoprecious', 'geoprecious_settings_page' );
}

/*
*
*/
function geoprecious_settings_page(){
	$vars = (object) array();
	
	$vars->post_types = get_post_types( array(), 'object' );
	
	echo geoprecious_render( 'admin-settings', $vars );
}

/*
*
*/
function geoprecious_activation(){
	global $wpdb;
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