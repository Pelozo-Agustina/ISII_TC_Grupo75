<?php 
	$host = 'localhost';
	$usuario = 'root';
	$pass = '';
	$db = 'contacto';

	$conection = @mysqli_connect($host,$usuario,$pass,$db);

	if(!$conection){
		echo "Error en la coneccion";
	}
?>