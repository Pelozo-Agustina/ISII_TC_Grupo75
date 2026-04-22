<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							
						</font>
				    </div>
				</div>
			</section>	
<!-- Fin de banner -->

<?php 

	$host = 'localhost';
	$usuario = 'root';
	$pass = '';
	$db = 'contacto';

	$conection = @mysqli_connect($host,$usuario,$pass,$db);

	if(!$conection){
		echo "Error en la coneccion";
	}

	if(!empty($_POST))
	{
		$id = $_POST['id'];
		$query_delete = mysqli_query($conection,"DELETE FROM datos WHERE id = $id");
		if($query_delete){
			header("location: consultas");
		}else{
			echo "Error al eliminar";
		}
	}

	if(empty($_REQUEST['id']))
	{
		header("location: consultas");
	}else{
	$host = 'localhost';
	$usuario = 'root';
	$pass = '';
	$db = 'contacto';

	$conection = @mysqli_connect($host,$usuario,$pass,$db);

	if(!$conection){
		echo "Error en la coneccion";
	}

	$id = $_REQUEST['id'];

	$query = mysqli_query($conection,"SELECT d.nombre,d.apellido,d.email,d.mensaje FROM datos d WHERE id = $id");
	$result = mysqli_num_rows($query);

	if($result > 0){
		while ($data = mysqli_fetch_array($query)) {
			# code...
			$nombre = $data['nombre'];
			$apellido = $data['apellido'];
		    $email = $data['email'];
		    $mensaje = $data['mensaje'];
		}
	}else{
		header("location: consultas");
	}

	}
 ?>

 <section id="conteiner">
 	<div class="data_delete">
 		<h2>Esta seguro de eliminar la consulta de: </h2><br>
 		<p>Nombre Y Apellido: <span><?php echo $nombre;  ?> <?php echo $apellido;  ?></span></p>
 		<p>Mensaje: <span><?php echo $mensaje;  ?></span></p>

 		<form method="post" action="">
 			<input type="hidden" name="id" value="<?php echo $id; ?>">
 			<a href="<?php echo base_url('consultas');?>" class="btn_cancel">Cancelar</a>
 			<input type="submit" value="Aceptar" class="btn_ok">
 		</form>

 	</div>
 </section>


 <style>
 	.data_delete{
 		text-align: center;
 	}
 	.data_delete{
 		font-size: 12px;
 	}
 	.data_delete{
 		font-weight: bold;
 		color: #987316;
 		font-size: 12px;
 	}
 	.btn_cancel,.btn_ok{
 		width: 124px;
 		background: #478ba2;
 		color: #fff;
 		display: inline-block;
 		padding: 15px;
 		border-radius: 5px;
 		cursor: pointer;
 		margin: 15px;
 	}
 	.btn_cancel{
 		background: #42b343;
 	}
 </style>
