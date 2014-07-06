<?php

namespace geoprecious;

/*
*	show map in eser edit profile.php
*	@param WP_User
*/
function user_profile( \WP_User $wp_user ){
	//ddbug( $wp_user );
	$Collection = new Collection;
	$Collection->setUserID( $wp_user->ID );
	$res = $Collection->get();
	
	wp_localize_script( 'geoprecious-admin', 'geo_data', geo_data_format($res) );
															   
	echo render( 'admin/user-edit' );
}
add_action( 'edit_user_profile', __NAMESPACE__.'\user_profile', 10, 1 );
add_action( 'show_user_profile', __NAMESPACE__.'\user_profile', 10, 1 );

function user_profile_save( $user_id ){
	$geoprecious_data = post_geoprecious_data();
	
	$Collection = new Collection;
	$Collection->setUserID( $user_id );
	$Collection->get();
	
	foreach( $geoprecious_data as $data ){
		$Collection->addPoint( $data->lat, $data->lng, $data->stamp );
	}
	
	$Collection->replace();
}
add_action( 'edit_user_profile_update', __NAMESPACE__.'\user_profile_save', 10, 1 );
add_action( 'personal_options_update', __NAMESPACE__.'\user_profile_save', 10, 1 );