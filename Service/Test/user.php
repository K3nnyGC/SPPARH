<?php
include_once("../Core/autoload.php");

$tc = new TestController(RAIZ . "api/v2/users");

TestController::printMaterialize();

$tc->getAll();
$tc->get(2);

$tc->createByJson(
	[
     	'RUC' => '00000000005',
    	'legal_name' => 'nombre legal',
    	'comercial_name' => 'nombre comercial',
    	'address' => 'mi direccion',
    	'name' => 'Kenny',
    	'lastname' => 'gonzales',
    	'dni' => '00000005',
    	'phone' => '999876789',
    	'email' => 'correo2@mail.com',
    	'password' => '123',
    	'type' => 1
	]
);

$tc->update(7,
	[
		'RUC' => '00000000003',
    	'legal_name' => 'nombre legal',
    	'comercial_name' => 'nombre comercial',
    	'address' => 'mi direccion',
    	'name' => 'Kenny',
    	'lastname' => 'gonzales',
    	'dni' => '00000004',
    	'phone' => '999876789',
    	'email' => 'correo2@mail.com',
    	'password' => 'd9b1d7db4cd6e70935368a1efb10e377',
    	'type' => 1
	]
);

$tc->delete(2);


TestController::initMaterialize();


?>