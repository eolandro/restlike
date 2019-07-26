<?php

// Kickstart the framework
$f3=require('lib/base.php');
$f3->route('GET /',
    function() {
        echo 'Hola, mundo!';
    }
);
$f3->route('GET /saludo',
    function() {
        echo 'Saludos!';
    }
);
$f3->route('GET /crearBaseDatos',
    function() {
        $db=new DB\SQL('sqlite:/var/www/html/restlike/basedatos/database.sqlite');
        $db->exec('Create Table if not exists registro(Personas int, fecha TEXT)');
        echo 'hecho';
    }
);
$f3->route('GET /info',
    function() {
        echo phpinfo();
    }
);
$f3->route('GET /jsonencode',
    function() {
		header('Content-Type: application/json');
		$a = [];
		$a["clave"] = "valor";
        echo json_encode($a);
    }
);

$f3->route('POST /jsondecode',
    function($f3) {
		header('Content-Type: application/json');
		$CUERPO = $f3->get('BODY');
		if (empty($CUERPO)){
			echo '{"R":400,"D":"Cuerpo Vacio"}';
		}
		$JSON = json_decode($CUERPO,true);
		$a = [ "R" => 200, "D" => $JSON];
        echo json_encode($a);
    }
);
$f3->route('POST /api/agregarRegistro',
    function($f3) {
		header('Content-Type: application/json');
		$CUERPO = $f3->get('BODY');
		if (empty($CUERPO)){
			echo '{"R":400,"D":"Cuerpo Vacio"}';
			return;
		}
		$JSON = json_decode($CUERPO,true);
		//////////////////////////////////
		if (!array_key_exists('personas',$JSON)){
			echo '{"R":400,"D":"No hay personas"}';
			return;
		}
		if (!is_numeric($JSON['personas'])){
			echo '{"R":400,"D":"personas no es un número"}';
			return;
		}
		///Si llega aqui es que si hay personas
		$db = new DB\SQL('sqlite:/var/www/html/restlike/basedatos/database.sqlite');
		
        $db->exec("insert into registro values (?, datetime('now'))",$JSON["personas"]);
		$a = [ "R" => 201];
        echo json_encode($a);
    }
);
$f3->run();
