<?php

namespace geoprecious;

require __DIR__.'/lib/class-point.php';

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
*
*	@param int
*	@param WP_Post
*	@return
*/
function save_post( $post_id, \WP_Post $wp_post ){
	$geoprecious_data = $_POST['geoprecious_data'];
	
	foreach( $geoprecious_data as $data ){
		$data = json_decode( stripslashes($data) );
		//dbug($data,'$data');
		
		$Point = new Point;
		$Point->setPoint( $data->lat, $data->lng );
		$Point->setPostID( $post_id );
		$Point->insert();
	}
	
	//ddbug();
}
add_action( 'save_post', __NAMESPACE__.'\save_post', 10, 2 );