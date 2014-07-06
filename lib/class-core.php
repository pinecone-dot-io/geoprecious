<?php

namespace geoprecious;

class Core{
	protected $blog_id = 1;
	protected $post_id = 0;
	protected $stamp;
	protected $term_taxonomy_id = 0;
	protected $user_id = 0;
	
	protected static $wpdb = NULL;
	
	public function __construct(){
		if( !self::$wpdb )
			self::$wpdb = &$GLOBALS['wpdb'];
	}
	
	/*
	*
	*	@param int
	*	@return
	*/
	public function setBlogID( $blog_id ){
		$this->blog_id = (int) $blog_id;
	}
	
	/*
	*
	*	@param int
	*	@return
	*/
	public function setPostID( $post_id ){
		$this->post_id = (int) $post_id;
	}
	
	/*
	*
	*	@param int
	*	@return
	*/
	public function setTermTaxonomyID( $tt_id ){
		$this->term_taxonomy_id = (int) $tt_id;
	}
	
	/*
	*
	*	@param int
	*	@return
	*/
	public function setUserID( $user_id ){
		$this->user_id = (int) $user_id;
	}
}