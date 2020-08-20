<?php 

/**
 * 
 */
class View {
	
	public static $leveluser = 100;
	public static $id 		 = 0;
	
	function __construct(){
		$this->accesslevel = 100;
		self::$id+= 1;
		$this->idview = self::$id;
	}

	public function printview(){
		?>
		<p>Vista no implementada!</p>
		<?php
	}

	public function checkAccess(){
		//var_dump($this->accesslevel);
		//var_dump(self::$leveluser);
		return $this->accesslevel >= self::$leveluser ? true : false;		
	}

	public function changeAccessLevel($accesslevel){
		$this->accesslevel = $accesslevel;
		return $this;
	}

	public static function setLevelUser($leveluser){
		self::$leveluser = $leveluser;
	}

}

?>