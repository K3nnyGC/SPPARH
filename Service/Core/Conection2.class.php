<?php 

/**
 * Conection.class
 * Clase padre para la coneccion y operaciones con la base de datos.
 * @author K3nnY 
 * @version 1.0
 */

class Conection2 {


	public function __construct(){
		$this->con = false;
		$this->conected = false;
		$this->table = "faketable";
	}

	public function startconection(){
		@$this->con = mysqli_connect(HOST_NAME , USER_DB , PASSWORD, DATABASE);
		$this->conected = mysqli_connect_errno() ? false : true;
		$this->conected ? $this->con->set_charset("utf8") : "";
		return $this->conected;
	}

	public function closeconection(){
		if ($this->conected) {
			mysqli_close($this->con);
			$this->conected = false;
		}
	}

	public function exec($query){
		if (!$this->startconection()) {
			return false;
		}
		$result =  $this->con->query($query);
		if (!$result) {
			Service::setErrorMsg("Error: ".$this->con->error);
		}
		//var_dump($query);
		//exit();
		//var_dump($result->num_rows);
		//var_dump($this->con->error);
		//exit();
		//$this->saveLog($query,$this->con);
		$this->closeconection();
		return $result;
	}
	
	public function saveLog($query,$conection){
		$stmnt = $conection->prepare("INSERT INTO log(consulta) VALUES (?)");
		
		$stmnt->bind_param("s", $query);
		$stmnt->execute();
	}

}


 ?>