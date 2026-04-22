<!-- inicio banner -->
                <section class="banner-area" id="home"> 
                <div class="container">
                    <div class="row fullscreen d-flex align-items-center justify-content-start">
                        <font size="7" color="white">
                            Modificar mis Datos
                        </font>
                    </div>
                </div>
            </section>      
            <!-- Fin banner -->

<?php 
	include "conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nombre']) || empty($_POST['apellido']) ||empty($_POST['email']) || empty($_POST['usuario']) || empty($_POST['pass'])){
			$alert = '<p class="msg_error">Todos los compos son obligatorio.</p>';
		}else{
			$id = $_POST['id'];
			$nombre = $_POST['nombre'];
			$apellido = $_POST['apellido'];
			$email = $_POST['email'];
			$usuario = $_POST['usuario'];
			$pass = $_POST['pass'];

			$query = mysqli_query($conection,"SELECT * FROM usuarios WHERE ((usuario = '$usuario' AND id != $id) OR (email = '$email' AND id != $id)) ");
			$result = mysqli_fetch_array($query);

			if($result > 0){
				$alert = '<p class="msg_error>"El correo o el usuario ya existe.</p>';
			}else{
				if(empty($_POST['pass'])){
					$sql_update = mysqli_query($conection,"UPDATE usuarios SET nombre = '$nombre',apellido = '$apellido', email = '$email',usuario = '$usuario' WHERE id = id ");
				}else{
					$sql_update = mysqli_query($conection,"UPDATE usuarios SET nombre = '$nombre',apellido = '$apellido', email = '$email',usuario = '$usuario',pass = $pass WHERE id = id ");
				  }
				if($sql_update){
					$alert = '<p class"msg_save"Usuario actializado Correctamente.</p>';
				}else{
					$alert = '<p class"msg_error"Error al Actualizar el usuario</p>';
				}
			}
		}
				}


	/*Mostrar Datos
	$idUser = $_GET['id'];
	$sql = mysql_query($conection,"SELECT u.nombre,u.apellido,u.email,u.usuario,u.pass FROM usuarios u WHERE id = $idUser");
	$result_sql = mysql_num_rows($sql);
	if($result_sql == 0){
		header('location: misdatos');
	}else{

		while ($data = mysql_fetch_array($sql)) {
			# code...
			$nombre = $data['nombre'];
			$apellido = $data['apellido'];
			$email = $data['email'];
			$usuario = $data['usuario'];
			$pass = $data['pass'];
		}
	}*/
		
 ?>

 	<section>
 		<div class="form_register">
 			<h1>Actualizar Datos</h1>
 			<hr>
 			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

 			<form action="" method="post">
 				<input type="hidden" name="id" value="id">
 				<label for="nombre">Nombre &nbsp; &nbsp; &nbsp;</label>
 				<input type="text" name="nombre" id="nombre" placeholder="nombre"><br>
 				<label for="apellido">Apellido &nbsp; &nbsp; &nbsp;</label>
 				<input type="text" name="apellido" id="apellido" placeholder="apellido"><br>
 				<label for="email">E-mail &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label>
 				<input type="text" name="email" id="email" placeholder="email"><br>
 				<label for="usuario">Usuario &nbsp; &nbsp; &nbsp; &nbsp;</label>
 				<input type="text" name="usuario" id="usuario" placeholder="usuario"><br>
 				<label for="pass">Contraseña</label>
 				<input type="password" name="pass" id="pass" placeholder="Contraseña"><br>
 				<center><input type="submit" value="Actualizar Usuario" class="btn_save"></center>
 			</form>
 		</div>
 	</section>


<style>
.form_register{
	width:  450px;
	margin: auto;
}
hr{
	border: 0;
	background: #ccc;
	height: 1px;
	margin: 10px 0;
	display: block;;
}
form{
	background: #fff;
	margin: auto;
	padding: 20px 50px;
	border: 1px solid #d1d1d1;
}	
label{
	display: block;
	font-size: 12pt;
	font-family: 'GothamBook';
	margin: 15px auto 5px auto;
}
.btn_save{
	font-size: 12px;
	background: #12a4c6;
	padding: 10px;
	color: #fff;
	letter-spacing: 1px;
	border: 0;
	cursor: pointer;
	margin: 15px auto;
}
.alert{
	width: 100%;
	background: #66e07d66;
	border-radius: 600px;
}
button, input {
    overflow: visible;
    width: 70%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
}
</style>