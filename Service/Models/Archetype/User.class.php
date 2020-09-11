<?php 
/**
 * 
 */
class User extends Model {
	#Only mandatory 
	
	//public $id_user;
	public $name;
	public $lastname;
	public $dni;
	public $phone;
	public $email;
	public $password;
	public $id_role;
	//public $edited_date;
	//public $created_date;

	
	function __construct(){
		parent::__construct();
	}

	public function getvars($clase=""){
		return parent::getvars(__CLASS__);
	}

}

 ?>