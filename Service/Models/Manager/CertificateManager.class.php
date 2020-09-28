<?php 
/**
 * 
 */
class CertificateManager extends ModelManager2 {

	public static $metodos = ["GET","POST","PUT","DELETE","OPTIONS"];
	
	function __construct(){
		parent::__construct();
		$this->id = 0;
		$this->table = "certificate";
		$this->class = "Certificate";
		$this->id_criteria = "id_certificate";
	}

}