<?php 
/**
 * 
 */
class UserManager extends ModelManager2 {

	public static $metodos = ["GET","POST","PUT","DELETE","OPTIONS"];
	
	function __construct(){
		parent::__construct();
		$this->id = 0;
		$this->table = "user";
		$this->class = "User";
		$this->id_criteria = "id_user";
	}

}