<?php 
/**
 * 
 */
class Sign extends Model {
	#Only mandatory 
	
	public $id_user;
	public $route_key_public;
	public $route_key_private;
	public $due_date;

	
	function __construct(){
		parent::__construct();
	}

	public function getvars($clase=""){
		return parent::getvars(__CLASS__);
	}

}

 ?>