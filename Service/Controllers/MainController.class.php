<?php 


/**
 * 
 */
class MainController extends Controller{
	
	function __construct(){
		parent::__construct();
		$this->action = $_SERVER['REQUEST_METHOD'];
		$this->words = $this->parseUrl();
		$this->modelId = false;
		//$this->permision = ["Trabajador","Categoria","Ot","Cronograma"];
		$this->permision = ["Document"];
		
	}


	public function startService(){

		//Restringe el uso del servicio a peticiones con token service en el head
		//$this->validateToken();

		$clase = array_keys($this->words)[0];
		$this->modelId = $this->words[$clase];

		//Validar si el endpoint tiene uso permitido
		$this->hasPermission($clase);
		
		if (class_exists($clase)) {
			$this->defineMethods($clase ."Manager");
		} else {
			$rest = new Rest();
			$rest->response();
		}
		

	}

	private function parseUrl(){
		$values = Service::parseUri();
		$output = [];
		for ($i=DEEP_ROOT; $i < count($values) ; $i++) { 
			$key = $values[$i];
			$value = isset($values[$i+1]) ? $values[$i+1] : false;
			$i+=1;
			$output[$key] = $value; 
		}
		return $output;
	}

	private function defineMethods($model){

		$rest = new Rest();
		$rest
			->setClass($model)
			->setModelId($this->modelId)
			->setParameters(Service::getParameters());
		//Valida si el metodo esta permitido
		$this->hasMethodAllowed($model,$this->action);

      	switch ($this->action) {
         case "GET":
            $rest->getMethod();
            break;
         case "POST":
            $rest->postMethod();
            break;
         case "DELETE":
            $rest->deleteMethod();
            break;
         case "PUT":
            $rest->putMethod();
            break;
         case "OPTIONS":
            $rest->optionsMethod();
            break;
         default:
            break;
      	}

      	$rest->response();
	}

	private function hasPermission($model){
		if (!in_array($model, $this->permision)) {
			$this->response(406,["forbidden" => true]);
		}
		
	}

	private function hasMethodAllowed($manager,$metodo){
		if (!in_array($metodo, $manager::$metodos)) {
			$this->response(406,["forbidden" => true]);
		}
		
	}

}



 ?>