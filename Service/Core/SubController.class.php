<?php 

/**
 * 
 */
class SubController {
	
	function __construct(){
		$this->layout = new Layout();		
	}

	function deployLayout(){
		$this->layout->deploy();
	}

	function addError($arrayerror){
		foreach ($arrayerror as $key => $value) {
			$this->layout->addError($value);
		}
		
	}

	public function response($code = 400, $data = ["error" => true]){
        $rest = new Rest();
        $rest->code = $code;
        $rest->data = $data;
        $rest->response();
        exit();
    }

    protected function setMethod($metodo,$options = false){
        if ($options) {
            if (Service::getAction() === "OPTIONS") {
                $this->response(200,["ok" => true, "message" => "Request Autorizado", "request" => Service::getAction()]);
            } 
        }

        if (Service::getAction() !== $metodo) {
            $this->response(400,["ok" => false, "message" => "Request Errado", "request" => Service::getAction()]);
        }
    }

    protected function validatePostParameters($variables){ // Parametros enviados por post
        $parametros = Service::getParameters();
        foreach ($variables as $key => $value) {
            $atributo = isset($parametros[$value]) ? $parametros[$value] : "";
            if ($atributo == "") {
                $this->response(200,["ok" => false, "message" => "Wrong Parameters", "parametros" => $parametros]);
            }
        }
        return $parametros;
    }

    protected function validateFormParameters($variables){
        $parametros = Service::getPostValues();
        foreach ($variables as $key => $value) {
            $atributo = isset($parametros[$value]) ? $parametros[$value] : "";
            if ($atributo === "") {
                $this->response(200,["ok" => false, "message" => "Wrong Parameters", "parametros" => $parametros]);
            }
        }
        return $parametros;
    }

    private function toAnonimusClass($obj,$params,$pseudonime){
        $newObj = new Vacio();
        foreach ($params as $key => $value) {
            $newObj->{$pseudonime[$key]} = $obj->{$value};
        }
        return $newObj;
    }

    private function limitToBrowser($enabled=true){
        if($enabled){
            if(isset($_SERVER["HTTP_REFERER"])){
                $matrix = explode("localhost:8081", $_SERVER["HTTP_REFERER"]);
                if(count($matrix)<2){
                    $this->response(404,[ "ok" => false,"sms" => "forbidden" ]);
                }
            } else {
                $this->response(404,[ "ok" => false,"sms" => "Forbidden" ]);
            }

            if(isset($_SERVER["HTTP_USER_AGENT"])){
            } else {
                $this->response(404,[ "ok" => false,"sms" => "Forbidden" ]);
            }
        }
    }

}

?>