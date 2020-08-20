<?php


/**
 * 
 */
class WebController extends Controller {
    
    /**
     * 
     */
    public function __construct(){
        parent::__construct();
        $this->layout = new HomeLayout();
        $this->layout->title = APPNAME . " - " . DOMINIO;
        $this->ini();
    }

    private function ini(){
        //Genera token si no existe, la vista por defecto regenera el token, cualquier otra vista usa el token que ya existe o genera uno nuevo si no existe.
        $this->generateTokenIfNotExist();

        //Si no se esta logeado, se revisa la cookie por datos de usuairo
        //$this->tryLogin();
        //Selecciona que se va mostrar
        $this->router();
    }


    private function router(){
        $adds = Service::parseUri();
        $page = isset($adds[DEEP_ROOT - 2]) ? $adds[DEEP_ROOT - 2] : "";
        //$gets = Service::getGetValues();
        //$page = isset($gets["p"]) ? $gets["p"] : "" ;

        if ($page != "") {
            switch ($page) {
                default:
                    Auth::generateToken(); //Solo se genera en la pagina de inicio
                break;
            }
        } else {
            if (!Auth::isOnline()) {
                header("location: " . RAIZ . "login");
                exit();
            }
        }
    }



    private function validateTokenForWeb(){
        if (!$this->existToken()){
            header("Location: ../web");
            exit();
        }
    }

    private function generateTokenIfNotExist(){
        if (!$this->existToken()){
            Auth::generateToken();
        }
    }


    //Metodos de logeo, modificar segun tabla de usuarios
    private function tryLogin(){
        if (!Auth::isOnline()) {
            $this->checkCockie();
        }
    }


    private function checkCockie(){
        $galletas = Service::getCookieValues();
        $galleta = isset($galletas['datosusuario']) ? $galletas['datosusuario'] : false;
        if ($galleta != false) {
            $datos = explode("/", $galleta);
            $d1 = isset($datos[1]) ?  $datos[1] : ""; 
            $d2 = isset($datos[2]) ?  $datos[2] : "";
            $this->specialLog($d1,$d2);
        } 
    }

    private function specialLog($c,$p){
        $um = new UserManager();
        $c = md5($c);
        $u = $um->showAttr(["*"],$um->id_criteria,"md5(Nonick)='{$c}'");
        $u = count($u)>0 ? $u[0] : false; 
        if ($u != false) {
            $u = $u->Cocontrasena == $p ? $u : false;
            if ($u != false) {
                Auth::setUserOnSession($u);
                //var_dump($_SESSION);
                //exit();
                return true;
            } else {
                Service::delVarCookie('datosusuario');
                return false;
            }
        } else {
            return false;
        }
    }

}

?>