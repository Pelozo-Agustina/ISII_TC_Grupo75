<section class="menu-area section-gap" id="GuardarReserva">

	<?php

		$servidor = "localhost";
		$usuario = "root";
		$clave = "";
		$baseDatos = 'coffee1';

			//enlace para conectarse con el servidor
				$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDatos);


				if(!$enlace){
					echo"Error en la conexion con el servidor";
				}


				//si tiene la variable tipo POST enviar_consuta vamos a hacer lo siguiente
				if(isset($_POST['enviar-datosForm'])){

					$id_reserva;
					$id_usuario = 2; //Maria
					$fecha = $_POST['fecha-reserva'];
					$mesa = rand(1,10);  //se elige al azar una mesa de entre las 10 que hay
					$hora = $_POST['opciones-cart'];
					$estado = 2; //Reservado


						//insertamos los datos en la tabla reservas de la base de datos
						$insertarDatos = "INSERT INTO reservas VALUES('$id_reserva',
																	'$id_usuario',
																	'$fecha',
																	'$mesa',
																	'$hora',
																	'$estado')";

						$ejecutarInsertar = mysqli_query($enlace, $insertarDatos);

				//comprobacion
				if($ejecutarInsertar){
					echo"<br><br>La reserva de mesa se realizó con éxito!<br>";
					
				}else{
					echo"Error al ingresar los datos";
				}
			}


   ?>

</section>