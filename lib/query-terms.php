<?php

namespace geoprecious;

/*
*
*	@param array
*	@param array
*	@param array
*	@return array
*/
function get_terms_fields( $selects, $args, $taxonomies ){
	$selects[] = "GROUP_CONCAT( ASTEXT(geo) ) as geo";
	
	return $selects;
}
add_filter( 'get_terms_fields', __NAMESPACE__.'\get_terms_fields', 10, 3 );

/*
*
*	@param array
*	@param array
*	@param array
*	@return array
*/
function terms_clauses( $pieces, $taxonomies, $args ){
	global $blog_id, $wpdb;
	$pieces['join'] .= " LEFT JOIN geoprecious G 
						 ON tt.term_taxonomy_id = G.term_taxonomy_id 
							AND G.blog_id = $blog_id
							AND G.user_id = 0
							AND G.post_id = 0 ";
	
	$pieces['where'] .= " GROUP BY t.term_id ";
	//ddbug( func_get_args() );
	return $pieces;
}
add_filter( 'terms_clauses', __NAMESPACE__.'\terms_clauses', 10, 3 );

/*
*	sets geo data when one term is loaded via get_term()
*	@param object
*	@param string
*	@return object
*/
function get_term( $term, $taxonomy ){
	$Collection = new Collection;
	$Collection->setTermTaxonomyID( $term->term_taxonomy_id );
	
	$term->geo = $Collection->get();
	$term->geo = map_to_geojson( $term->geo );
	
	//ddbug($term->geo);
	
	return $term;
}
add_filter( 'get_term', __NAMESPACE__.'\get_term', 10, 2 );

/*
*	set the geo data from a get_terms query
*	@param array
*	@param array
*	@param array
*	@return array
*/
function get_terms( $terms, $taxonomies, $args ){
	$terms = array_map( function($term){
		$term->geo = explode( ',', $term->geo );
		//$term->geo = map_to_geojson( $term->geo );
		
		//ddbug( $term );
		return $term;
	}, $terms );
	
	return $terms;
}
add_filter( 'get_terms', __NAMESPACE__.'\get_terms', 10, 3 );

