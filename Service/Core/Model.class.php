<?php 

/**
 * Conection.class
 * Clase padre para la coneccion y operaciones con la base de datos.
 * @author K3nnY 
 * @version 1.0
 */

class Model {


	public function __construct(){
	}

	public function setAttr($attr,$value){
		$this->$attr=$value;
		return $this;
	}

	public function getvars($clase){
		$out=[];
		foreach (get_class_vars($clase) as $key => $value) {
			$out[] = $key;
		}
		return $out;
	}




}


 ?>