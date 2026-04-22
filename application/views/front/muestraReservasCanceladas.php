<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Reservas Canceladas
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->

<?php if (!$reservas) { ?>

	<div class="container">
		<div class="well">
			<center><h1>No hay Reservas Canceladas</h1></center>
		</div>	
	</div>

<?php } else { ?>

	<div class="container">
		<div class="well">
			<center><h1>Todos las Reservas Canceladas</h1></center>
		</div>	

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Nro Reserva</th>
					<th>Usuario</th>
					<th>Fecha Reserva</th>
					<th>Mesas</th>
					<th>Horario</th>
					<th>Activar Reserva</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($reservas->result() as $row){ ?>
				<tr>
					<td><?php echo $row->id_reserva;  ?></td>
					<td><?php echo $row->usuario_id;  ?></td>
					<td><?php echo $row->fecha_reserva;  ?></td>
					<td><?php echo $row->mesa_id;	?></td>
					<td><?php echo $row->horario_id;	?></td>
					<td><a href="<?php echo base_url("activar_recerva/$row->id_reserva");?>">Activar</a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>	            
	</div>

<?php } ?>