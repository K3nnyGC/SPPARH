<?php 
/**
 * 
 */
class Document extends Model {
	#Only mandatory 
	
	//public $id_document;
	public $id_institute;
	//public $id_student;
	public $status;
	public $name;
	public $notes;
	public $route_file;
	public $emited_date;
	//public $id_document_before;
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