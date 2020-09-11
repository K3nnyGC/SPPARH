<?php 
/**
 * 
 */
class Vacio extends Model {
	
	function __construct(){
		parent::__construct();
	}

	public function getvars($clase=""){
		return parent::getvars(__CLASS__);
	}

}

 ?>