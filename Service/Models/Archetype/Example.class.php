<?php 
/**
 * 
 */
class Example extends Model {
	#Only mandatory 
	
	//public $id;
	public $param;
	//public $created_date;

	
	function __construct(){
		parent::__construct();
	}

	public function getvars($clase=""){
		return parent::getvars(__CLASS__);
	}

}

 ?>