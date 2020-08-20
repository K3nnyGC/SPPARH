<?php 


/**
 * 
 */
class ModelManager extends Conection2 {
	
	function __construct(){
		parent::__construct();
		$this->table = "faketable";
		$this->class = "fakeClass";
		$this->vars = [];
		$this->id_criteria = "id";
	}

	//SETTINGS

	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	public function setclass($class){
		$this->class = $class;
		return $this;
	}

	//CRUD:::::::::::::::::::::::::::::::::::::::::::::::::::::

	//SELECT

	public function showByArg($col=[],$val=[]){
		$query="SELECT * FROM $this->table WHERE ";
		$and = "";
		for ($i=0; $i < count($col) ; $i++) { 
			$comilla = is_string($val[$i]) ? "'" : "";
			$query.= "$and $col[$i] = ". $comilla . $val[$i] . $comilla;
			$and = " AND "; 
		}
		//var_dump($query);
		return $this->resultArray($this->exec($query));
	}

	public function show(){
		$query="SELECT * FROM $this->table ORDER BY $this->id_criteria ASC";
		return $this->resultArray($this->exec($query));
	}

	public function findById($id){
		$comilla = is_string($id) ? "'" : "";
		$query="SELECT * FROM $this->table WHERE $this->id_criteria = {$comilla}{$id}{$comilla}";
		//var_dump($this->exec($query));
		//exit();
		return $this->resultObject($this->exec($query));
	}
	
	public function findLast(){
		$query="SELECT * FROM $this->table ORDER BY $this->id_criteria DESC LIMIT 1";
		return $this->resultObject($this->exec($query));
	}

	//INSERT

	public function create($modelo){
		$query = "INSERT INTO $this->table (";
		$vari = $modelo->getvars();
		$coma = "";
		for ($i=0; $i < count($vari) ; $i++) { 
			$query.="$coma $vari[$i]";
			$coma = ",";
		}
		$query.=") VALUES(";
		$coma = "";
		for ($i=0; $i < count($vari) ; $i++) { 
			$comilla = is_string($modelo->$vari[$i]) ? "'" : "";
			$query.="$coma $comilla" . $modelo->$vari[$i] . $comilla;
			$coma = ",";
		}
		$query.=");";
		//var_dump($query);
		//exit();
		return $this->exec($query);
	}

	//UPDATE

	public function update($modelo){
		$query = "UPDATE $this->table SET ";
		$vari = $modelo->getvars();
		$coma = "";
		for ($i=0; $i < count($vari) ; $i++) {
			if ( ($vari[$i]!=$this->id_criteria) && ($vari[$i]!="id") ) {
				$query.="$coma $vari[$i]";
				$comilla = is_string($modelo->$vari[$i]) ? "'" : "";
				$query.=" = ";
				$query.="$comilla" . $modelo->$vari[$i] . $comilla;
				$coma = ",";
			}
		}
		$comilla = is_string($modelo->{$this->id_criteria}) ? "'" : "";
		$query.=" WHERE {$this->id_criteria}="."$comilla" .$modelo->{$this->id_criteria} . $comilla;
		//var_dump($query);
		//exit();
		return $this->exec($query);
	}

	//DELETE

	public function delete($modelo){
		$comilla = is_string($modelo->{$this->id_criteria}) ? "'" : "";
		$query = "DELETE FROM $this->table WHERE {$this->id_criteria}="."$comilla" .$modelo->{$this->id_criteria} . $comilla;
		return $this->exec($query);
	}

	//FIN DE CRUD ::::::::::::::::::::::::::::::::::::::::::::::::::

	//UTILS

	public function resultArray($result){
		if ($result) {
			$matriz = [];
			while ($row = $result->fetch_assoc()) {
				$modelo = new $this->class();
				foreach ($row as $key => $value) {
						$modelo
							->setAttr("$key",$value);
					
				}
				$matriz[] = $modelo;
			}
			return $matriz;
		} else {
			return false;
		}
	}

	public function resultObject($result){
		if ($result!==false) {

			$row = $result->fetch_assoc();
			if (is_null($row)) {
				return false;
			} else {
				$modelo = new $this->class();
				foreach ($row as $key => $value) {
						$modelo
							->setAttr("$key",$value);
					
				}
				return $modelo;
			}
			
		} else {
			return false;
		}
	}

	public function filterArray($array){
		$output = [];

		foreach ($array as $key => $value) {
			if (in_array($key, $this->vars)) {
				$output[$key] = $value;

			}
		}
		return $output;
	}


	// FILTROS ESPECIALES

	public function findAttrById($attr = ["*"] ,$id){
		$coma = "";
		$crit = "";
		foreach ($attr as $key => $value) {
			$crit .= "{$coma} {$value}";
			$coma = ","; 
		}
		$comilla = is_string($id) ? "'" : "";
		$query="SELECT {$crit} FROM {$this->table} WHERE {$this->id_criteria} = {$comilla}{$id}{$comilla}";
		//var_dump($this->exec($query));
		//exit();
		return $this->resultObject($this->exec($query));
	}

	public function showAttr($attr = ["*"] , $col="id", $crite = ""){
		$crit = "";
		$coma = "";
		foreach ($attr as $key => $value) {
			$crit .= "{$coma} {$value}";
			$coma = ","; 
		}
		if (!$crite=="") {
			$crite = " WHERE " . $crite;
		}
		$query="SELECT {$crit} FROM {$this->table} {$crite} ORDER BY {$col} ASC";
		return $this->resultArray($this->exec($query));
	}



}

?>