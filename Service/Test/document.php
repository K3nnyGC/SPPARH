<?php
include_once("../Core/autoload.php");

$tc = new TestController(RAIZ . "api/v2/documents");

TestController::printMaterialize();

$tc->getAll();
$tc->get(2);

$tc->create(
	[
		"name" => "Certificado TI",
		"id_institute" => 2,
		"notes" => "Este es un certificado",
		"emited_date" => "2020-09-01",
		"file" => FILE_BASE . "Assets/files/documents/b9f5094268c63f7a547ea07551565ad703_30-54-1-SM.pdf.tmp"
	]
);

$tc->update(6,
	[
		"name" => "Certificado Test",
		"id_institute" => 2,
		"notes" => "Ducumento generado en test",
		"emited_date" => "2020-09-12"
	]
);

$tc->delete(2);


TestController::initMaterialize();


?>