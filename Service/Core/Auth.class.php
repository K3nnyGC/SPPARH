<?php 

/**
 * 
 */
class Auth {
	
	function __construct(){
		parent::__construct();
	}

	public function validate(){
		if (!isset($_SESSION['token_log']) || !isset($_SESSION['user'])) {
			return false;
		} else {
			return true;
		}
	}

	public function next(){
		if (!$this->validate()) {
			exit();
		} else {
			return true;
		}
	}

	public function redir(){
		header("location: " . RAIZ);
	}

	//Token

	static public function generateToken($user){
        $sessionVar = Service::getSessionValues();
        $ip = Service::getIp();
        $token = base64_encode($user->email . ':' .md5($user->id_user . $ip . $user->email . SECRETE));
        //Service::setVarCookie('tokenUser',$token);
        return isset($sessionVar['tokenUser']) ? $sessionVar['tokenUser'] : $token;
    }

    static public function getUserFromToken(){
    	if(!isset($_SERVER['PHP_AUTH_USER'])){
    		SubController::responseSt(401, ["ok"=>false, "message" => "Missing or wrong Token"]);
    	}
		$mail = $_SERVER['PHP_AUTH_USER'];
		$mail = md5($mail);
		$tk = $_SERVER['PHP_AUTH_PW'];
		$um = new UserManager();
		$result = $um->showAttr(["*"],$um->id_criteria,"md5(email) = '{$mail}'");
		if(count($result) == 1){
			return $result[0];
		} else {
			SubController::responseSt(401, ["ok"=>false, "message" => "Forbidden Request"]);
		}
    }

	static public function checkToken($token){
		$t = self::getToken();
		if ($t === $token) {
			self::generateToken();
			return true;
		} else {
			return false;
		}
	}

	static public function getToken(){
		$t = isset(Service::getSessionValues()['token_service']) ? Service::getSessionValues()['token_service'] : false;
		return $t;
	}

	static public function deleteToken(){
		Service::setVarSession("token_service",false);
	}

	//Token Admin

	static public function generateTokenAdmin(){
		$dat = md5("Cifrado Flush Admin" . rand(189799765,989799765));
		Service::setVarSession("token_service_admin",$dat);
		return $dat;
	}

	static public function deleteTokenAdmin(){
		Service::setVarSession("token_service_admin",false);
	}

	static public function isAdmin($tokenheader){
		$sesion = Service::getSessionValues();
		$ta = isset($sesion['token_service_admin']) ? $sesion['token_service_admin'] : false;
		if (($tokenheader == $ta) && ($tokenheader != false) && ($ta != false) ) {
			return true;
		} else {
			return false;
		}
	}

	//Saltar captcha

	static public function resetPermisionCaptcha($models = []){
		$permisos = array();
		foreach ($models as $key => $value) {
			$permisos["{$value}"] = true;
		}
		Service::setVarSession("permisos",$permisos);
		
	}

	static public function skipcaptcha($model){
		$session = Service::getSessionValues();
		$permisos = isset($session["permisos"]) ? $session["permisos"] : false;
		if ($permisos) {
			foreach ($permisos as $key => $value) {
				if (($key == $model) && $value) {
					return true;
				}
			}
			return false;
		} else {
			return false;
		}
		
	}


	//Setear usuario

	static public function setUserOnSession($usuario){
		$user = array();
		$user['usuario'] = $usuario->usuario;
		$user['pass'] = $usuario->pass;
		Service::setVarSession("user_session",$user);
		//Service::setVarCookie("datosusuario",""."true/". $usuario->Nonick ."/" . $usuario->Cocontrasena);
	}

	static public function deleteUserSession(){
		Service::setVarSession("user_session",false);
		Service::delVarCookie("datosusuario");
	}

	static public function isOnline(){
		$session = Service::getSessionValues();
		$us = isset($session["user_session"]) ? $session["user_session"] : false;
		return $us == false ? false : true ;
	}

	static public function getUser(){
		if (self::isOnline()) {
			$session = Service::getSessionValues();
			return $session["user_session"];
		} else {
			return false;
		}
	}


}

?>