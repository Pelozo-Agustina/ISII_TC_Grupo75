<section class="menu-area section-gap" id="Guardar">

<?php

	$servidor = "localhost";
	$usuario = "root";
	$clave = "";
	$baseDatos = 'contacto';

	//enlace para conectarse con el servidor
	$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDatos);

		if(!$enlace){
			echo"Error en la conexion con el servidor";
		}




			//si tiene la variable tipo POST enviar_consuta vamos a hacer lo siguiente
			if(isset($_POST['enviar_consulta'])){
				$nombre = $_POST['nombre'];
				$apellido = $_POST['apellido'];
				$email = $_POST['email'];
				$mensaje = $_POST['mensaje'];

				//insertamos los datos en la tabla de la base de datos
				$insertarDatos = "INSERT INTO datos VALUES(NULL,
															'$nombre',
															'$apellido',
															'$email',
															'$mensaje')";

				$ejecutarInsertar = mysqli_query($enlace, $insertarDatos);

				//comprobacion
				if($ejecutarInsertar){
            echo "<h3>Datos insertados correctamente</h3>";
            echo "<p>Serás redirigido al inicio en 5 segundos...</p>";
            
            // REDIRECCIÓN AUTOMÁTICA (JavaScript es más confiable aquí)
            echo "<script>
                    setTimeout(function(){
                        window.location.href = 'index.php'; 
                    }, 5000);
                  </script>";
        } else {
            echo "Error al ingresar los datos: " . mysqli_error($enlace);
        }
			}

		?>

		 <!-- BOTÓN PARA VOLVER MANUALMENTE -->
    <div style="margin-top: 20px;">
        <a href="index.php" class="primary-btn" style="padding: 10px 20px; background: #b68834; color: white; text-decoration: none; border-radius: 5px;">
            Volver al Home
        </a>
    </div>
</section>


</section>