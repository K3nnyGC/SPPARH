<?php 


/**
 * 
 */
class Rest{
	
	function __construct(){
		
		$this->data = null;
		$this->code = 500;
		$this->message = "No implementado";

		$this->class = "";
		$this->instance = null;
		$this->parameters = [];
	}

		// SERVICIOS REST

	public function getMethod(){
		//var_dump($this->modelId);
		//exit();
		if ($this->modelId!==false) {
			$this->data = $this->instance->findById($this->modelId);
			$this->data === false ? $this->message = "No existe" : "";
			$this->data === false ? $this->data = NULL : "";
			$this->code = $this->data ? 200 : 400;
		} else {
			$this->data = $this->instance->show();
			$this->code = $this->data ? 200 : 404;
		}
		
	}

	public function postMethod(){
		if ($this->modelId!==false) {
			$this->message = "Uso incorrecto";
			$this->data = NULL;
			$this->code = 400;
		} else {
			$obj = $this->adapterParameters();
			if ($obj) {
				//$this->data = $this->instance->create($obj) ? $this->instance->findById($obj->{$this->instance->id_criteria}) : NULL;
				$this->data = $this->instance->create($obj) ? $this->instance->findLast() : NULL;
				$this->code = $this->data ? 200 : 409;
				$this->data === NULL ? $this->message = "El {$this->instance->id_criteria} ya existe" : "";
				
			} else {
				$this->message = "Parametros incorrectos";
				$this->data = NULL;
				$this->code = 400;
			}
			
		}
		
	}

	public function deleteMethod(){
		if ($this->modelId!==false) {
			$obj = new $this->instance->class();
			$obj->{$this->instance->id_criteria} = $this->modelId;
			$this->data = $this->instance->delete($obj);
			$this->data === false ? $this->message = "No existe" : "";
			$this->data === false ? $this->data = NULL : $this->data = ["deleted"=>true];
			$this->code = $this->data ? 200 : 404;
		} else {
			$this->code = $this->data ? 200 : 400;
			$this->data = ["deleted"=>false];
		}
	}

	public function putMethod(){
		if ($this->modelId!==false) {
			$obj = $this->adapterParameters();
			if ($obj) {
				$obj->{$this->instance->id_criteria} = $this->modelId;
				$this->data = $this->instance->update($obj) ? $this->instance->findById($obj->{$this->instance->id_criteria}) : NULL;
				$this->code = $this->data ? 200 : 409;
				$this->data === NULL ? $this->message = "El {$this->instance->id_criteria} ya existe" : "";
				$this->data === false ? $this->message = "El {$this->instance->id_criteria} no existe" : "";
				$this->data === false ? $this->data = ["updated"=>false] : "";
			} else {
				$this->message = "Parametros incorrectos";
				$this->data = NULL;
				$this->code = 400;
			}
			
		} else {
			
			$this->code = $this->data ? 200 : 404;
		}
	}

	public function optionsMethod(){
		$this->code = 200;
		$this->data = [];
	}

	public function response(){
		header("Content-Type:application/json");
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: t, t2, dif, st, hash, hora, *");
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, *");
		header("X-Powered-By: K3nnY-G");header("Content-Type:application/json");
		header("HTTP/1.1 ".$this->code);
		$response=[
			'status' => $this->code,
			'status_message' => $this->message
		];

		$response['data']=$this->data;
	
		//$json_response = json_encode($response);
		$this->data === NULL ? $this->data = ['Message' => $this->message] : "";
		$json_response = json_encode($this->data);
		echo $json_response;
	}


	public function setClass($model){
		$this->class = $model;
		$this->instance = new $model();
		return $this;
	}

	public function setModelId($id){
		$this->modelId = $id;
		return $this;
	}
	
	public function setParameters($parameters){
		$this->parameters = $parameters;
		return $this;
	}
	
	//funciones internas
	
	private function adapterParameters(){
		$obj = new $this->instance->class();
		$vars = $obj->getVars();
		for ($i = 0; $i < count($vars); $i++) {
			 if (isset($this->parameters[$vars[$i]])) {
			 	$obj->setAttr($vars[$i],$this->parameters[$vars[$i]]);
			 } else {
			 	return false;
			 }
		}
		
		return $obj;
	}
}




?>