<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Lista de Usuarios
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->
<?php 
 
 	include"partes/conexion.php";
 ?>
<section id="conteiner">
	<a href="<?php echo base_url('registro');?>" class="btn_new"> Crear Usuario </a>
	<table>
		<tr>
			<th>ID</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Correo</th>
			<th>Rol</th>
			<th>Acciones</th>
		</tr>

		<?php 
		$query= mysqli_query($conection, "SELECT u.perfil_id,u.nombre,u.apellido,u.email,p.id FROM `usuarios` u INNER JOIN perfiles p ON u.perfil_id = p.id");
		
		$result = mysqli_num_rows($query);

		if($result > 0){

			while ($data = mysqli_fetch_array($query)) {
				# code...
				 ?>
				<tr>
			<td><?php echo $data["perfil_id"];?></td>
			<td><?php echo $data["nombre"];?></td>
			<td><?php echo $data["apellido"];?></td>
			<td><?php echo $data["email"];?></td>
			<td><?php echo $data["id"];?></td>
			<td>
				<a class="link_edit" href="<?php echo base_url('usuario_modifica');?>">Editar</a>
				|
				<a class="link_delete" href="<?php echo base_url('usuario_elimina');?>">Eliminar</a>
			</td>
		</tr>
		<?php 
			}
		}
		 ?>

	</table>
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