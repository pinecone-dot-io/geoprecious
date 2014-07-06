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
	$Collection = new Collection;
	$Collection->setPostID( $post->ID );
	$res = $Collection->get();

	wp_localize_script( 'geoprecious-admin', 'geo_data', geo_data_format($res) );
															   
	echo render( 'admin/post-meta-box' );
}

/*
*
*	@param int
*	@param WP_Post
*	@return
*/
function save_post( $post_id, \WP_Post $wp_post ){
	$geoprecious_data = post_geoprecious_data();
	
	$Collection = new Collection;
	$Collection->setPostID( $post_id );
	$Collection->get();
	
	foreach( $geoprecious_data as $data ){
		$Collection->addPoint( $data->lat, $data->lng, $data->stamp );
	}
	
	$Collection->replace();
	
	//ddbug();
}
add_action( 'save_post', __NAMESPACE__.'\save_post', 10, 2 );