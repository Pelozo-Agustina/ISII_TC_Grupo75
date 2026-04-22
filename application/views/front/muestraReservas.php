<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Reservas
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->

<?php if (!$reservas){ ?>

	<div class="container">
		<div class="well">
			<center><h1>No hay Reservas</h1></center>
		</div>
		<?php $session_data = $this->session->userdata('login_in')?>
		<?php if( ($this->session->userdata('login_in')) and ($session_data['perfil_id']=='1') ) { ?>
		<!--	<a type="button" class="btn btn-success" href="<?php echo base_url('#'); ?>">Reservar</a>
			<a type="button" class="btn btn-danger" href="<?php echo base_url('reservasCanceladas'); ?>">Cancelado</a> -->
			<br> <br>
		<?php } ?>	
	</div>

<?php } else { ?>

	<div class="container">
		<div class="well">
			<center><h1>Todos las Resevas</h1></center>
		</div>	
	<!--	<a type="button" class="btn btn-success" href="<?php echo base_url('#'); ?>">RESERVAR</a>
		<a type="button" class="btn btn-danger" href="<?php echo base_url('#'); ?>">Cancelados</a>-->
		<br> <br>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Nro Reserva</th>
					<th>Usuario</th>
					<th>Fecha Reserva</th>
					<th>Mesas</th>
					<th>Horario</th>
					<th>Estado</th>			
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
					<td><?php echo $row->estado_id;?></td>
					
				</tr>
				<?php } ?>
			</tbody>
		</table>	            
	</div>

<?php } ?>