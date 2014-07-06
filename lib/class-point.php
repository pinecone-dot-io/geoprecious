<?php

namespace geoprecious;

class Point extends Core{
	private $lat;
	private $lng;
	
	/*
	*
	*/
	public function __construct( $lat, $lng, $stamp ){
		$this->lat = (float) $lat;
		$this->lng = (float) $lng;
		$this->stamp = (int) $stamp;
		
		parent::__construct();
	}
	
	/*
	*
	*/
	public function getInsertSql(){
		$return = array(
			'geo' => parent::$wpdb->prepare( "POINT(%f, %f)", $this->lat, $this->lng ),
			'stamp' => parent::$wpdb->prepare( "%d", $this->stamp )
		);
		
		return $return;
	}
}