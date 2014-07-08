<?php

namespace geoprecious;

class Collection extends Core{
	private $ids = array();			// stored ids of geoprecious records
	private $points = array();
	
	public function __construct(){
		parent::__construct();
	}
	
	/*
	*
	*	@param float
	*	@param float
	*	@return
	*/
	public function addPoint( $lat, $lng, $stamp = -1 ){
		if( $stamp < 0 )
			$stamp = time();
			
		$this->points[] = new Point( $lat, $lng, $stamp );
	}
	
	/*
	*
	*	@return array
	*/
	public function get(){
		$sql = parent::$wpdb->prepare( "SELECT *, ASTEXT(geo) AS `geo` FROM geoprecious 
										WHERE blog_id = %d
											AND post_id = %d
											AND user_id = %d
											AND term_taxonomy_id = %d
										ORDER BY stamp ASC", 
										$this->blog_id, $this->post_id, $this->user_id, $this->term_taxonomy_id );
		$res = parent::$wpdb->get_results( $sql );
		
		$this->ids = array_map( function($r){
			return (int) $r->id;
		}, $res );
		
		$res = array_map( function($r){
			preg_match_all( '/[\-0-9.]+[\-0-9.]+/', $r->geo, $coords );
			$r->geo = (object) array(
				'type' => 'point',
				'val' => array_map( 'floatval', $coords[0] )
			);
			return $r;
		}, $res );
		
		//dbug( $res, $sql );
		return $res;
	}
	
	/*
	*
	*/
	public function replace(){
		$ids = count( $this->ids ) ? implode( ', ', $this->ids ) : "''";
		$sql = "DELETE FROM geoprecious WHERE id IN ( $ids )";
		parent::$wpdb->query( $sql );
		//ddbug($this->points);
		
		foreach( $this->points as $point ){
			$data = $point->getInsertSql();
			
			$sql = parent::$wpdb->prepare( "INSERT INTO geoprecious
										  	( blog_id, post_id, user_id, term_taxonomy_id,
										 	  geo, stamp )
											VALUES
											( %d, %d, %d, %d,
											  {$data['geo']}, {$data['stamp']} )", 
											$this->blog_id, $this->post_id, $this->user_id, $this->term_taxonomy_id );
										 	
			//ddbug($sql);
			parent::$wpdb->query( $sql );
		}
	}
}