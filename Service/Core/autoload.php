<?php
include_once("env.php");
spl_autoload_register(function ($nombre_clase) {
	$archivo = FILE_BASE . "Core/".$nombre_clase . ".class.php";
	if (file_exists($archivo)) {
		include_once $archivo;
	} else {
		return false;
	}

    
});


spl_autoload_register(function ($nombre_clase) {
    $archivo = FILE_BASE . "Models/Archetype/".$nombre_clase . ".class.php";
	if (file_exists($archivo)) {
		include_once $archivo;
	} else {
		return false;
	}
});

spl_autoload_register(function ($nombre_clase) {
    $archivo = FILE_BASE . "Models/Manager/".$nombre_clase . ".class.php";
	if (file_exists($archivo)) {
		include_once $archivo;
	} else {
		return false;
	}
});


spl_autoload_register(function ($nombre_clase) {
	$archivo = FILE_BASE . "Controllers/".$nombre_clase . ".class.php";
	if (file_exists($archivo)) {
		include_once $archivo;
	} else {
		return false;
	}

    
});

spl_autoload_register(function ($nombre_clase) {
	$archivo = FILE_BASE . "Controllers/SubControllers/".$nombre_clase . ".class.php";
	if (file_exists($archivo)) {
		include_once $archivo;
	} else {
		return false;
	}

    
});



spl_autoload_register(function ($nombre_clase) {
	$archivo = FILE_BASE . "Views/Layout/".$nombre_clase . ".class.php";
	if (file_exists($archivo)) {
		include_once $archivo;
	} else {
		return false;
	}

    
});

spl_autoload_register(function ($nombre_clase) {
	$archivo = FILE_BASE . "Views/Pages/".$nombre_clase . ".class.php";
	if (file_exists($archivo)) {
		include_once $archivo;
	} else {
		return false;
	}

    
});

spl_autoload_register(function ($nombre_clase) {
	$archivo = FILE_BASE . "Views/Pages/Parts/".$nombre_clase . ".class.php";
	if (file_exists($archivo)) {
		include_once $archivo;
	} else {
		return false;
	}

    
});


class Facilitador {
    
    public static $headers = [];
    function __construct(){
        # code...
    }

    public static function setHeaders($headers){
        self::$headers = $headers;
    }
}

if (!function_exists('apache_request_headers')) {
    foreach($_SERVER as $key=>$value) {
        if (substr($key,0,5)=="HTTP_") {
            $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5)))));
            $out[strtolower($key)]=$value;
        }else{
            $out[strtolower($key)]=$value;
        }
    }

    Facilitador::setHeaders($out);

    function apache_request_headers() {
        return Facilitador::$headers;        
    }
} 




 ?>