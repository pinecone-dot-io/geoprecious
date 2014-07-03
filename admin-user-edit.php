<?php

namespace geoprecious;

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