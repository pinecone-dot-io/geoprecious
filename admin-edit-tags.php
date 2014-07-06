<?php

namespace geoprecious;

/*
*
*/
function register_taxonomies(){
	$taxonomies = get_taxonomies( '', 'names' ); 
	
	foreach( $taxonomies as $taxonomy ){
		add_action( $taxonomy.'_edit_form', __NAMESPACE__.'\taxonomy_edit_form', 10, 2 );
		add_action( 'edit_'.$taxonomy, __NAMESPACE__.'\taxonomy_edit_form_save', 10, 2 );
	}
}
add_action( 'load-'.$file.'.php', __NAMESPACE__.'\register_taxonomies', 11 );

/*
*
*	attached to `<$taxonomy>_edit_form` action
*	@param object
*	@param string
*	@TODO do this on all/config taxonomies
*/
function taxonomy_edit_form( $tag, $taxonomy ){
	$Collection = new Collection;
	$Collection->setTermTaxonomyID( $tag->term_taxonomy_id );
	$res = $Collection->get();
	
	wp_localize_script( 'geoprecious-admin', 'geo_data', geo_data_format($res) );
	
	echo render( 'admin/edit-tags' );
}

/*
*
*	attached to `edit_<$taxonomy>` action
*	@param int
*	@param int
*	@return
*/
function taxonomy_edit_form_save( $term_id, $tt_id ){
	$geoprecious_data = post_geoprecious_data();
	
	$Collection = new Collection;
	$Collection->setTermTaxonomyID( $tt_id );
	$Collection->get();
	
	foreach( $geoprecious_data as $data ){
		$Collection->addPoint( $data->lat, $data->lng, $data->stamp );
	}
	
	$Collection->replace();
}