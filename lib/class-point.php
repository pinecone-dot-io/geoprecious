<?

namespace geoprecious;

class Point{
	private static $wpdb;
	
	private $acc;
	private $lat;
	private $lng;
	
	private $blog_id = 1;
	private $post_id = 0;
	private $stamp;
	private $user_id = 0;
	
	/*
	*
	*/
	public function __construct(){
		if( !self::$wpdb ){
			global $wpdb;
			self::$wpdb = &$wpdb;
		}
	}
	
	/*
	*
	*/
	public function insert(){
		$sql = self::$wpdb->prepare( "INSERT INTO geoprecious
									  ( blog_id, post_id, user_id, 
									    geo )
									  VALUES
									  ( %d, %d, %d, 
									    POINT(%f, %f) )", 
									  $this->blog_id, $this->post_id, $this->user_id,
									  $this->lat, $this->lng );
		//dbug($sql);
		return self::$wpdb->query( $sql );
	}
	
	/*
	*
	*	@param float
	*	@param float
	*	@param int
	*	@return
	*/
	public function setPoint( $lat, $lng, $acc = 0 ){
		$this->lat = (float) $lat;
		$this->lng = (float) $lng;
		$this->acc = (int) $acc;
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
	public function setUserID( $user_id ){
		$this->user_id = (int) $user_id;
	}
}