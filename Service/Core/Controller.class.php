<?php 

/**
 * 
 */
class Controller {
	
	function __construct(){
		$this->layout = new Layout();
		Service::init();		
	}

	function deployLayout(){
		$this->layout->deploy();
	}

	function addError($arrayerror){
		foreach ($arrayerror as $key => $value) {
			$this->layout->addError($value);
		}
		
	}

	//Delega cambios a sub controler - cn controladora de negocio
    private function changeController($cn){
        $this->layout->changeView($cn->layout->view);
        $this->layout->jsFiles = $cn->layout->jsFiles;
        $this->layout->jsFilesFooter = $cn->layout->jsFilesFooter;
        $this->layout->cssFiles = $cn->layout->cssFiles;
    }

	public function validateToken(){
		//nuevo
		$tk = isset(Service::getSessionValues()['token_service']) ? Service::getSessionValues()['token_service'] : false;
		//$tkt = isset(Service::getGetValues()['ts']) ? Service::getGetValues()['ts'] : false;

		$tkt = isset(Service::getHeaders()['ts']) ? Service::getHeaders()['ts'] : false;

		if ($tk) {
			if ($tk != $tkt) {
				Auth::deleteToken();
				$this->response(401);
			} else {

			}
		} else {
			Auth::deleteToken();
			$this->response(401);
		}
		//
	}

	public function validateCaptcha(){

		$cp = isset(Service::getSessionValues()['digit']) ? Service::getSessionValues()['digit'] : false;
		$cph = isset(Service::getHeaders()['cp']) ? Service::getHeaders()['cp'] : false;

		if ($cp) {
			if ($cp != $cph) {
				$_SESSION['digit'] = "";
				$this->response(409);
			} else {
				$_SESSION['digit'] = "";
			}
		} else {
			$_SESSION['digit'] = "";
			$this->response(403);
		}

		//
	}

	protected function response($code = 400, $data = ["error" => true]){
        $rest = new Rest();
        $rest->code = $code;
        $rest->data = $data;
        $rest->response();
        exit();
    }


    public function existToken(){
		$tk = isset(Service::getSessionValues()['token_service']) ? Service::getSessionValues()['token_service'] : false;

		if ($tk === false) {
			return false;
		} else {
			return true;
		}
	}

}

?>