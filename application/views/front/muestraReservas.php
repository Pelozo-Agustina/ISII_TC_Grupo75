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

<?php if($this->session->flashdata('success')): ?>
    <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c8e6c9; text-align: center;">
        <strong>¡Hecho!</strong> <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
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
					<th class="text-center">Acciones</th>		
				</tr>
			</thead>

			<tbody>
    <?php foreach($reservas->result() as $row){ ?>
    <tr>
        <td><strong>#<?php echo $row->id_reserva; ?></strong></td>
        
        <!-- Si el JOIN funcionó, muestra el nombre. Si no, muestra el ID por seguridad -->
        <td><?php echo isset($row->nombre) ? ($row->nombre." ".$row->apellido) : "ID: ".$row->id_usuario; ?></td>
        
        <td><?php echo date("d/m/Y", strtotime($row->fecha_reserva)); ?></td>
        
       <!-- Celda de Mesas -->
		<td>
    <strong><?php echo $row->nombre_mesa; ?></strong><br>
    <small style="color: #8d6e63;">Capacidad: <?php echo $row->capacidad; ?> pers.</small>
		</td>
		<!-- Celda de Horario -->
		<td>
    		<?php 
    			if(isset($row->hora_inicio)){
        			echo substr($row->hora_inicio, 0, 5) . " a " . substr($row->hora_fin, 0, 5);
    			} else {
    			    echo "Turno ".$row->id_horario;
    			}
    		?>
    			
    	</td>

       <td>
    <!-- Si es Confirmada usa badge-confirmado (verde), si no badge-pendiente (naranja) -->
    <span class="<?php echo ($row->estado_reserva == 'Confirmada') ? 'badge-confirmado' : 'badge-pendiente'; ?>">
        <?php echo $row->estado_reserva; ?>
    </span>
</td>

<td class="text-center">
    <?php 
        // 1. Calculamos si la reserva ya expiró
        $fecha_y_hora_fin = $row->fecha_reserva . ' ' . $row->hora_fin;
        $expirada = (time() > strtotime($fecha_y_hora_fin));
    ?>

    <?php if ($expirada): ?>
        <!-- Si ya pasó el tiempo, no importa el estado, mostramos que expiró -->
        <span class="badge bg-light text-muted" style="font-style: italic;">Expirada</span>

    <?php elseif ($row->estado_reserva == 'Confirmada'): ?>
        <!-- Botón bloqueado (Gris) -->
        <button class="btn btn-secondary" style="cursor: not-allowed; opacity: 0.7;" disabled>
            ✔ Confirmado
        </button>

    <?php else: ?>
        <!-- Botón activo (Verde) solo si NO ha expirado y NO está confirmada -->
        <a href="<?php echo base_url('actualizarEstado/'.$row->id_reserva); ?>" 
           class="btn btn-success"
           onclick="return confirm('¿Desea confirmar esta reserva?')">
           Confirmar
        </a>
    <?php endif; ?>
</td>
    </tr>
    <?php } ?>

</tbody>

		</table>	            
	</div>

<?php } ?>

<style>
/* 1. Estilo General del Título */
h1 {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #2c1e1a; 
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin: 30px 0;
    text-align: center;
}

/* 2. Estilo de la Tabla Profesional */
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1); /* Sombra más profunda */
    background-color: white;
    margin-bottom: 50px;
}

.table thead {
    background-color: #2c1e1a; 
    color: #d4a373; 
}

.table th {
    padding: 18px 15px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: none;
    text-align: left;
}

.table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #f2ede4;
    color: #5d4037; 
}

/* 3. Interactividad: Efecto Hover en las filas */
.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #fdfaf5 !important; 
    box-shadow: inset 4px 0 0 #d4a373; 
}

/* 4. Estilo de los Estados (Badges) */
.badge-pendiente {
    background-color: #fff8e1;
    color: #f57c00;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.5px;
    border: 1px solid #ffe082;
    display: inline-block;
}

/* 5. Botón Cancelar Moderno */
.btn-cancelar {
    background-color: #e74c3c;
    color: white !important;
    padding: 8px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    display: inline-block;
    box-shadow: 0 4px 6px rgba(231, 76, 60, 0.2);
}

.btn-cancelar:hover {
    background-color: #c0392b;
    box-shadow: 0 6px 12px rgba(231, 76, 60, 0.3);
    transform: translateY(-2px);
    text-decoration: none;
}

/* 6. Utilidades */
.text-center {
    text-align: center;
}

.table td:last-child {
    width: 140px; /* Espacio fijo para el botón */
}

/* Estilo para los textos secundarios (como la capacidad) */
small {
    font-size: 11px;
    color: #8d6e63;
    display: block;
    margin-top: 3px;
}
</style>
