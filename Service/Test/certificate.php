<?php
include_once("../Core/autoload.php");

$tc = new TestController("http://localhost:3000/certificates");

TestController::printMaterialize();


//$tc->get("jhkjhjkhjkhjkh");
//$tc->get("0x5ad3357ccc6c273d8c21efb9b095d09d4555e7fe32d32808c6795491e9304efd");

$tc->createByJson(
	[
		"name" => "Certificado Test",
		"student" => "Mi nombre",
		"hash" => "Ducumento generado en test",
		"date" => "2020-09-12"
	]
);



TestController::initMaterialize();


?>