<?php 
/**
 * 
 */
class InstituteManager extends ModelManager2 {

	public static $metodos = ["GET","POST","PUT","DELETE","OPTIONS"];
	
	function __construct(){
		parent::__construct();
		$this->id = 0;
		$this->table = "institute";
		$this->class = "Institute";
		$this->id_criteria = "id_user";
	}

}