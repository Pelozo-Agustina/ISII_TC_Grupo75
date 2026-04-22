<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Consultas Recibidas
						</font>
				    </div>
				</div>
			</section>		

<section class="menu-area section-gap" id="Consultas">
<?php 
 
 	include"conexion.php";
 ?>

		<?php 
		$query = mysqli_query($conection, "SELECT d.id,d.nombre,d.apellido,d.email,d.mensaje FROM `datos` d");
		
		$result = mysqli_num_rows($query);

		if($result > 0){

			while ($data = mysqli_fetch_array($query)) {
				# code...
				 ?>

<!-- Crea Tabla de Consultas -->
<section id="conteiner">
	<table>
		<tr>
			<th>ID</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Correo</th>
			<th>Mensaje</th>
			<th>Accion</th>
		</tr>

				<tr>
					<td><?php echo $data["id"];?></td>
					<td><?php echo $data["nombre"];?></td>
					<td><?php echo $data["apellido"];?></td>
					<td><?php echo $data["email"];?></td>
					<td><?php echo $data["mensaje"];?></td>
					<td><a href="eliminar?id=<?php echo $data["id"]; ?>">Eliminar</a></td>
		</tr>
		<?php 
			}
		}
		else{
			 echo "<center><h1>No se realizaron Consultas</h1></center>";
		}
		 ?>
		<br>
	</table>
	<br>
</section>
</section>


<!-- Estilo -->
<style>
	#conteiner h1{
		font-size: 35px;
		display: inline-block;
	}
	.btn_new{
		display: inline-block;
		background: #239baa;
		color: #fff;
		padding: 5px 25px;
		border-radius: 4px;
		margin: 20px;
	}
	table{
		border-collapse: collapse;
		font-size: 12px;
		font-family: "arial";
		width: 100%
	}
	table th{
		text-align: left;
		padding: 10px;
		background: #3d7ba8;
		color: #fff;
	}
    table tr:nth-child(odd){
    	background: #f0f5f5;
    }
    table td{
    	padding: 10px; 
    }
    .link_edit{
    	color: #0ca4ce;
    }
    .link_delete{
    	color: #f26b6b;
    }
</style>