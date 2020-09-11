<?php 
/**
 * 
 */
class StudentManager extends ModelManager2 {

	public static $metodos = ["GET","POST","PUT","DELETE","OPTIONS"];
	
	function __construct(){
		parent::__construct();
		$this->id = 0;
		$this->table = "student";
		$this->class = "Student";
		$this->id_criteria = "id_user";
	}

}