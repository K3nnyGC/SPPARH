<?php 


/**
 * 
 */
class ModelManager2 extends Conection2 {

	public static $metodos = ["GET","POST","PUT","DELETE"];
	
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
	#Inseguro
	public function showByArg($col=[],$val=[]){
		$query="SELECT * FROM $this->table WHERE ";
		$and = "";
		for ($i=0; $i < count($col) ; $i++) { 
			$comilla = is_string($val[$i]) ? "'" : "";
			$query.= "$and $col[$i] = ". $comilla . $val[$i] . $comilla;
			$and = " AND "; 
		}
		return $this->resultArray($this->exec($query));
	}
	#Inseguro
	public function show(){
		$query="SELECT * FROM $this->table ORDER BY $this->id_criteria ASC";
		return $this->resultArray($this->exec($query));
	}
	#Inseguro
	/*public function findById($id){
		$comilla = is_string($id) ? "'" : "";
		$query="SELECT * FROM $this->table WHERE $this->id_criteria = {$comilla}{$id}{$comilla}";
		return $this->resultObject($this->exec($query));
	}*/
	#Seguro
	public function findById($id){
		$idn = md5($id);
		$query="SELECT * FROM $this->table WHERE md5({$this->id_criteria}) = '{$idn}'";
		return $this->resultObject($this->exec($query));
	}
	#Inseguro
	public function findLast(){
		$query="SELECT * FROM $this->table ORDER BY $this->id_criteria DESC LIMIT 1";
		return $this->resultObject($this->exec($query));
	}
	#Seguro
	public function showLike($atr,$valor){
		$query = "SELECT * FROM $this->table WHERE {$atr} LIKE ?";
		$cadena = ["s"];
		$valor = str_replace("%", " ", $valor);
		$valor = str_replace("_", " ", $valor);
		$value = "%{$valor}%";
		$values = [$value];
		return $this->resultArray($this->ResultPrepare($cadena,$values,$query));
	}

	//INSERT
	#Seguro
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
		$cadena = array();
		$values = array();
		for ($i=0; $i < count($vari) ; $i++) {
			if (is_float($modelo->{$vari[$i]})){
				$cadena[] = 'd';
			} elseif (is_integer($modelo->{$vari[$i]})){
				$cadena[] = 'i';
			} elseif (is_string($modelo->{$vari[$i]})) {
				$cadena[] = 's';
			} else {
				$cadena[] = 'b';
			}                                        
			$values[] = $modelo->{$vari[$i]};
			$query.="$coma ?";
			$coma = ",";
		}
		$query.=")";
		return $this->ExecPrepare($cadena,$values,$query);
	}

	//UPDATE
	#Seguro
	public function update($modelo){
		$query = "UPDATE $this->table SET ";
		$vari = $modelo->getvars();
		$coma = "";
		$cadena = array();
		$values = array();
		for ($i=0; $i < count($vari) ; $i++) {
			if ( ($vari[$i]!=$this->id_criteria) && ($vari[$i]!="id") ) {
				$cadena[] = is_string($modelo->{$vari[$i]}) ? "s" : "i";
				$values[] = $modelo->{$vari[$i]};
				$query.="$coma $vari[$i]";
				$comilla = is_string($modelo->{$vari[$i]}) ? "'" : "";
				$query.=" = ";
				$query.= " ? ";
				$coma = ",";
			}
		}
		$cadena[] = is_string($modelo->{$this->id_criteria}) ? "s" : "i";
		$values[] = $modelo->{$this->id_criteria};
		$query.=" WHERE {$this->id_criteria}= ?";
		return $this->ExecPrepare($cadena,$values,$query);
	}

	//DELETE
	#Inseguro
	/*public function delete($modelo){
		$comilla = is_string($modelo->{$this->id_criteria}) ? "'" : "";
		$query = "DELETE FROM $this->table WHERE {$this->id_criteria}="."$comilla" .$modelo->{$this->id_criteria} . $comilla;
		return $this->exec($query);
	}*/
	#Seguro
	public function delete($modelo){
		$query = "DELETE FROM $this->table WHERE {$this->id_criteria} = ?";
		$cadena[] = is_string($modelo->{$this->id_criteria}) ? "s" : "i";
		$values[] = $modelo->{$this->id_criteria};
		return $this->ExecPrepare($cadena,$values,$query);
	}

	//FIN DE CRUD ::::::::::::::::::::::::::::::::::::::::::::::::::

	//UTILS - Adapters

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

	//Metodos seguros
	//ExecPrepare solo retorna true o false
	public function ExecPrepare($tipos,$valores,$query){
		if (!$this->startconection()) {
			return false;
		}
		$stmnt = $this->con->prepare($query);
		$a_params = array();
		$param_type = '';
		$n = count($tipos);
		for($i = 0; $i < $n; $i++) {
		  $param_type .= $tipos[$i];
		}
		$a_params[] = & $param_type;
		 
		for($i = 0; $i < $n; $i++) {
		  $a_params[] = & $valores[$i];
		}
		call_user_func_array(array($stmnt, 'bind_param'), $a_params);
		$result = $stmnt->execute();
		if (!$result) {
			Service::setErrorMsg("Error: ".$this->con->error);
		}
		$this->closeconection();
		return $result;
	}
	//ResultPrepare retorna el resultado en caso de ser true, sino false
	public function ResultPrepare($tipos,$valores,$query){
		if (!$this->startconection()) {
			return false;
		}
		$stmnt = $this->con->prepare($query);
		$a_params = array();
		$param_type = '';
		$n = count($tipos);
		for($i = 0; $i < $n; $i++) {
		  $param_type .= $tipos[$i];
		}
		$a_params[] = & $param_type;
		 
		for($i = 0; $i < $n; $i++) {
		  $a_params[] = & $valores[$i];
		}
		call_user_func_array(array($stmnt, 'bind_param'), $a_params);
		$result = $stmnt->execute();
		$salida = [];
		if (!$result) {
			Service::setErrorMsg("Error: ".$this->con->error);
		} else{
			$salida = $stmnt->get_result();
		}
		$this->closeconection();
		return $salida;
	}

	//Soporte para SP

	public function execSP($sp,$params){
		$query = "CALL {$sp}(";
		$coma = "";
		foreach ($params as $key => $value) {
			$query.= $coma . '?';
			$coma = ",";
		}
		$query.= ")";

		$vari = $params;
		$cadena = array();
		$values = array();
		for ($i=0; $i < count($vari) ; $i++) {
			$cadena[] = is_string($vari[$i]) ? "s" : "i";
			$values[] = $vari[$i];
		}

		/*var_dump($cadena);
		var_dump($values);
		var_dump($query);
		exit();*/

		$result = $this->ResultPrepare($cadena,$values,$query);

		if ($result) {
			$matriz = [];
			while ($row = $result->fetch_assoc()) {
				if(isset($row['Level'])){
					$modelo = new Vacio();
				} else {
					$modelo = new $this->class();
				}
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

}

?>