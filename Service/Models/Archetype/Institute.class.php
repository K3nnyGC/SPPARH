<?php 
/**
 * 
 */
class Institute extends User {
	#Only mandatory 
	
	public $id_user;
	public $RUC;
	public $legal_name;
	public $comercial_name;
	public $address;

	
	function __construct(){
		parent::__construct();
	}

	public function getvars($clase=""){
		return parent::getvars(__CLASS__);
	}

}

 ?>