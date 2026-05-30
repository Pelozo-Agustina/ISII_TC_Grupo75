<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Usuario Eliminados
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->

<?php if (!$usuarios) { ?>

	<div class="container">
		<div class="well">
			<h1>No hay Usuarios Eliminados</h1>
		</div>	
	</div>

<?php } else { ?>

	<div class="container">
		<div class="well">
			<h1>Todos los Usuarios Eliminados</h1>
		</div>	

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>nombre</th>
					<th>apellido</th>
					<th>email</th>
					<th>usuario</th>
					<th>pass</th>
					<th>perfil_id</th>
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
					<td><?php echo $row->usuario;  ?></td>
					<td><?php echo $row->pass;  ?></td>
					<td><?php echo $row->perfil_id;  ?></td>
					<td><?php echo $row->baja;  ?></td>
					<td><!--<a href="<?php echo base_url("usuario_modifica/$row->id");?>">Modificar</a>|--><a href="<?php echo base_url("usuarios_activa/$row->id");?>">Activar</a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>	            
	</div>

<?php } ?>