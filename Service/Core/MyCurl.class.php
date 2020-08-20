<?php 


/**
 * 
 */
class MyCurl{
	
	function __construct(){
		# code...
	}

	public static function go($url){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url ,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache"
		  ),
		));

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);



		if ($err) {
		  $response = "cURL Error #:" . $err;
		}

		return json_decode($response);
	}
}


 ?>