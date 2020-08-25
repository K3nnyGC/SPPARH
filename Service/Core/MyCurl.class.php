<?php 


/**
 * 
 */
class MyCurl{

	public $api;
	public $method;
	
	function __construct($api,$method="GET"){
		$this->api = $api;
		$this->method = $method;
	}

	public function go(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->api ,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => $this->method,
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache"
		  ),
		));

		$output =  $this->response($curl);
		curl_close($curl);
		return $output;
	}

	public function postForm($data=[]){
		if(isset($data['file'])){
			//$data['file'] = new CURLFile ( $data['file'] );
			$data['file'] = '@' . $data['file'];
		}
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->api ,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => $this->method,
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "Conten-Type: multipart/form-data"
		  ),
		  CURLOPT_POSTFIELDS => $data 
		));

		$output =  $this->response($curl);
		curl_close($curl);
		return $output;
	}


	public function postData($data=[]){
		$st = json_encode($data);
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->api ,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => $this->method,
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "Conten-Type: multipart/form-data"
		  ),
		  CURLOPT_POSTFIELDS => $st 
		));

		$output =  $this->response($curl);
		curl_close($curl);
		return $output;
	}

	public function response($curl){
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		$info = curl_getinfo($curl);

		if ($err) {
			$response = "cURL Error #:" . $err;
		}

		$output = json_encode([
			"info" => $info,
			"response" => json_decode($response)
		]);

		return json_decode($output);
	}
}


 ?>