<?php
#Todas las funciones relacionadas con las variables del sistema GET POST SESSION
class Service{
    public static $parameters = null;
    private static $_instance;

    private function __construct(){
        $this->init();
    }

    private static function run(){
        if (!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function init(){
        session_start();
        setlocale(LC_TIME, "es_ES.utf8");
        date_default_timezone_set('America/Lima');
    }

    public static function getPostValues(){
        if (isset($_POST)) {
            return $_POST;
        } else {
            return false;
        }

    }

    public static function getGetValues(){
        if (isset($_GET)) {
            return $_GET;
        } else {
            return false;
        }
    }

    public static function getSessionValues(){
        if (isset($_SESSION)) {
            return $_SESSION;
        } else {
            return false;
        }
    }

    public static function getCookieValues(){
        if (isset($_COOKIE)) {
            return $_COOKIE;
        } else {
            return false;
        }
    }


    public static function resetService(){
        $error = isset($_SESSION['errormsg']) ? $_SESSION['errormsg'] : [];
        unset($_SESSION);
        unset($_POST);
        unset($_GET);
        session_destroy();
        self::init();
        $_SESSION['errormsg'] = $error;
    }


    public static function setErrorMsg($error){
        $_SESSION['errormsg'][] = $error; 
    }

    public static function getErrorMsg(){
        $salida = $_SESSION['errormsg'];
        $_SESSION['errormsg'] = [];
        return $salida;
    }


    public static function setVarSession($key,$value){
        $_SESSION[$key] = $value;
    }


    public static function setVarCookie($key,$value){
	    setcookie($key,$value , time() + 365 * 24 * 60 * 60, '/');
    }

    public static function delVarSession($key){
        $_SESSION[$key] = null;
        unset($_SESSION[$key]);
    }

    public static function delVarCookie($key){
        unset($_COOKIE[$key]);
        setcookie($key, '', time() - 3600, '/');
        setcookie($key, '', time() - 3600);
    }

    //Funciones especificas de un rest

    public static function parseUri(){
        $clean = explode("?", $_SERVER['REQUEST_URI']);
        $uri = $clean[0];
        $parts = explode("%40", $uri);
        $uri = implode("@",$parts);
        return explode("/", $uri);
    }

    public static function getAction(){
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function validate(){
        $vector = self::parseUri();
        return isset($vector[DEEP_ROOT+4]) ? false : true;
    }

    public static function defineVals(){
        $vector = self::parseUri();
        foreach ($vector as $key => $value) {
            if (class_exists($value)) {
                # code...
            }
        }
    }
    
    public static function getParameters(){
        self::$parameters = self::$parameters ? self::$parameters : json_decode(file_get_contents('php://input'),true);
        return self::$parameters;
    }

    public static function getHeaders(){
        //return apache_request_headers();
        $h = apache_request_headers();
        $nh = array();
        foreach ($h as $key => $value) {
            $nh[strtolower($key)] = $value;
        }
        return $nh;
    }

    public static function getIp(){
        return $_SERVER['REMOTE_ADDR'];
    }
    
}



?>