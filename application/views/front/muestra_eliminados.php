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

	<div class="container tarjeta-cafe">
            <!-- Título Principal Superior -->
            <h1 class="titulo-seccion">Todos los Usuarios Eliminados</h1>
            
            <div class="tabla-contenedor-redondeado">

		<table class="tabla-estilo-cafe">
			<thead>
				<tr>
					<th>ID</th>
					<th>nombre</th>
					<th>apellido</th>
					<th>email</th>
					<th>usuario</th>
					<th>pass</th>
					<th>perfil</th>
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
					<td>
						<!-- Botón "Activar" tipo píldora contorneada interactivo -->
						<!--<a href="<?php echo base_url("usuario_modifica/$row->id");?>">Modificar</a>|-->
						<a class="btn-pildora btn-accion-activar" href="<?php echo base_url("usuarios_activa/$row->id");?>">Activar</a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>	            
	</div>
	</div>

<?php } ?>

<!-- ========================================== -->
<!-- 🎨 ESTILOS DE LA COMPAÑÍA                  -->
<!-- ========================================== -->
<style>
    .seccion-contenedor {
        background-color: #ffffff;
        padding: 40px 0;
        font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .titulo-seccion {
        text-align: center;
        font-family: "Poppins", Arial, sans-serif;
        font-weight: 700;
        color: #b58d4a; 
        margin-bottom: 25px;
        font-size: 32px;
    }

    .tarjeta-cafe {
        background: #ffffff;
        margin-bottom: 30px;
    }

    .tabla-contenedor-redondeado {
        border-radius: 12px;
        overflow: hidden; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid #e0e0e0;
    }

    .tabla-estilo-cafe {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
    }

    .tabla-estilo-cafe th {
        background-color: #4a2e1b; 
        color: #ffffff;
        font-weight: 600;
        font-size: 14px;
        padding: 16px 15px;
        text-align: left;
        border: none;
    }

    .tabla-estilo-cafe td {
        padding: 16px 15px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 14px;
    }

    .col-negrita {
        font-weight: 700;
        color: #222222;
    }

    .col-atenuada {
        color: #7a7a7a;
        font-weight: 400;
    }

    .password-celda {
        -webkit-text-security: disc;
        text-security: disc;
    }

    .badge-baja {
        font-weight: 700;
        color: #c0392b; 
    }

    .btn-pildora {
        display: inline-block;
        padding: 6px 20px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 20px; 
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease-in-out;
        background: #ffffff;
    }

    .btn-accion-activar {
        border: 1px solid #4a2e1b;
        color: #4a2e1b !important;
        cursor: pointer;
    }
    .btn-accion-activar:hover {
        background-color: #4a2e1b;
        color: #ffffff !important;
    }
</style>