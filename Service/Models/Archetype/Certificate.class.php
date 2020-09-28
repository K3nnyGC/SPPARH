<?php 
/**
 * 
 */
class Certificate extends Model {
	#Only mandatory 
	
	//public $id_certificate;
	public $sign_id_sign;
	public $id_document;
	public $route_signed_file;
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