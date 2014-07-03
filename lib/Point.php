<?

namespace geoprecious;

class Point{
	private static $wpdb;
	
	private $acc;
	private $lat;
	private $lng;
	private $stamp;
	private $user_id;
	
	/*
	*
	*/
	public function __construct(){
		global $wpdb;
		self::$wpdb = &$wpdb;
	}
	
	/*
	*
	*/
	public function insert(){
		$sql = self::$wpdb->prepare( "INSERT INTO geoprecious
									  ( user_id )
									  VALUES
									  ( %d )", $this->user_id );
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
	public function setUserID( $user_id ){
		$this->user_id = (int) $user_id;
	}
}