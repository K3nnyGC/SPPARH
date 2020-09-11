<?php 
/**
 * 
 */
class Student extends User {
	#Only mandatory 
	
	//public $id_user;
	public $birthdate;

	
	function __construct(){
		parent::__construct();
	}

	public function getvars($clase=""){
		return parent::getvars(__CLASS__);
	}

}

 ?>