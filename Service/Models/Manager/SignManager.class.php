<?php 
/**
 * 
 */
class SignManager extends ModelManager2 {

	public static $metodos = ["GET","POST","PUT","DELETE","OPTIONS"];
	
	function __construct(){
		parent::__construct();
		$this->id = 0;
		$this->table = "sign";
		$this->class = "Sign";
		$this->id_criteria = "id_sign";
	}

}