<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Usuarios
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->

<?php if (!$usuarios){ ?>

	<div class="container">
		<div class="well">
			<h1>No hay usuarios</h1>
		</div>
		<?php $session_data = $this->session->userdata('login_in')?>
		<?php if( ($this->session->userdata('login_in')) and ($session_data['perfil_id']=='1') ) { ?>
			<a type="button" class="btn btn-success" href="<?php echo base_url('cargar_usuario'); ?>">Agregar</a>
			<a type="button" class="btn btn-danger" href="<?php echo base_url('usuarios_eliminados'); ?>">ELIMINADOS</a>
			<br> <br>
		<?php } ?>	
	</div>

<?php } else { ?>

	<div class="container">
		<div class="well">
			<h1>Todos los Usuarios</h1>
		</div>	
		<a type="button" class="btn btn-success" href="<?php echo base_url('cargar_usuario'); ?>">Agregar</a>
		<a type="button" class="btn btn-danger" href="<?php echo base_url('usuarios_eliminados'); ?>">ELIMINADOS</a>
		<br> <br>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Apellido</th>
					<th>Email</th>
					<th>Perfil_id</th>
					<th>baja</th>
					<th>Accion</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($usuarios->result() as $row){ ?>
				<tr>
					<td><?php echo $row->id;  ?></td>
					<td><?php echo $row->nombre;  ?></td>
					<td><?php echo $row->apellido;  ?></td>
					<td><?php echo $row->email;  ?></td>
					<td><?php echo $row->perfil_id;  ?></td>
					<td><?php echo $row->baja;  ?></td>
					<td><!--<a href="<?php echo base_url("modificar_usuarios/$row->id");?>">Modificar</a>|--><a href="<?php echo base_url("usuario_elimina/$row->id");?>">Eliminar</a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>	            
	</div>

<?php } ?>
