<?php 
/**
 * 
 */
class DocumentManager extends ModelManager2 {

	public static $metodos = ["GET","POST","PUT","DELETE","OPTIONS"];
	
	function __construct(){
		parent::__construct();
		$this->id = 0;
		$this->table = "document";
		$this->class = "Document";
		$this->id_criteria = "id_document";
	}

}