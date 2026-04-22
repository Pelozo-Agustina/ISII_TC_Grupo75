<?php 
	$host = 'localhost';
	$usuario = 'root';
	$pass = '';
	$db = 'Coffee';

	$conection = @mysqli_connect($host,$usuario,$pass,$db);

	if(!$conection){
		echo "Error en la coneccion";
	}
?>