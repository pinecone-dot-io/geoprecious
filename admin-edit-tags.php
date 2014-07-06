<?php

namespace geoprecious;

/*
*
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
add_action( /*$taxonomy . */ 'category_edit_form', __NAMESPACE__.'\taxonomy_edit_form', 10, 2 );

/*
*
*	@param int
*	@param string
*	@return
*/
function taxonomy_edit_form_save( $tt_id, $taxonomy ){
	$geoprecious_data = post_geoprecious_data();
	
	$Collection = new Collection;
	$Collection->setTermTaxonomyID( $tt_id );
	$Collection->get();
	
	foreach( $geoprecious_data as $data ){
		$Collection->addPoint( $data->lat, $data->lng, $data->stamp );
	}
	
	$Collection->replace();
}
add_action( 'edit_term_taxonomy', __NAMESPACE__.'\taxonomy_edit_form_save', 10, 2 );