<?php 
/**
 * 
 */
class ExampleManager extends ModelManager2 {

	public static $metodos = ["GET","POST","PUT","DELETE","OPTIONS"];
	
	function __construct(){
		parent::__construct();
		$this->id = 0;
		$this->table = "table_example";
		$this->class = "Example";
		$this->id_criteria = "id";
	}

}