<?php
/**
 * 
 */
class AjaxController extends Controller {
    
    /**
     * 
     */
    public function __construct(){
        parent::__construct();
        $this->ini();
    }

    private function ini(){
        //Restringe el uso del servicio a peticiones con token service en el head
    	//$this->validateToken();

        $this->router();
    }

    private function router(){
        $t = $this->getService();
        $f = $this->getFunction();
        //Elegir servicio (CN)
        if ($t !== false) {
            switch ($t) {
                case 'documents':
                    new ApiDocumentSubController($f);
                    break;
                case 'users':
                    new ApiUserSubController($f);
                    break;
                default:
                    $this->response(200,["ok" => false , "message" => "Api Invalida" ]);
                    break;
            }
        }

        $this->response();
    }

    private function getService(){
        $values = Service::parseUri();
        if (isset($values[DEEP_ROOT])) {
            return $values[DEEP_ROOT];
        } else {
            $this->response(200,["ok" => false , "message" => "Url Incompleta" ]);
        }
    }

     private function getFunction(){
        $values = Service::parseUri();
        if (isset($values[DEEP_ROOT+1])) {
            return $values[DEEP_ROOT+1];
        } else {
            //$this->response(200,["ok" => false , "message" => "No se ha indicado accion" ]);
            return "";
        }
    }

}

?>