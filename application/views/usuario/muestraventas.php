 <!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Muestra de Ventas
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->

<div class="container">
    <div class="well" style="background: transparent; border: none; box-shadow: none;">
        <br>       
        <?php if (!$ventas_cabecera) { ?>

            <div class="container">
                <div class="well">
                    <h1>No se realizaron Ventas</h1>
                    <hr>
                </div>
            </div>

        <?php } else { ?>           
            
                <div class="well" style="background: transparent; border: none; box-shadow: none;">
                    <center><h1><b>Ventas Realizadas</b></h1></center>
                </div>	
                <td style="padding: 14px; border-top: 1px solid #eeeeee; border-bottom: none; border-left: none; border-right: none; text-align: center;">
                                    <a href="<?php echo base_url("reporte_ventas/$row->id_cabecera"); ?>" 
                                       class="btn btn-sm btn-detalle" 
                                       style="background-color: #f3ece6; color: #4a2c11; border: 1px solid #4a2c11; border-radius: 20px; padding: 6px 22px; font-weight: bold; font-size: 13px; text-decoration: none; display: inline-block; transition: all 0.2s ease;">
                                        Ventas por Periodo
                                    </a>
                                </td><br>
                <br>
                


                <!-- EL CAMBIO CLAVE: Envolvemos la tabla en este div contenedor con bordes redondeados y una sombra sutil -->
                <div style="border-radius: 14px; overflow: hidden; border: 1px solid #e2e2e2; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: #ffffff;">
                    
                    <table class="table table-bordered" style="margin-bottom: 0; border-collapse: separate; border-spacing: 0; border: none;">
                        <thead>
                            <tr style="background-color: #4a2c11; color: white;">
                                <th style="color: white; padding: 15px; border: none; font-weight: 600;">ID</th>
                                <th style="color: white; padding: 15px; border: none; font-weight: 600;">Nombre</th>
                                <th style="color: white; padding: 15px; border: none; font-weight: 600;">Apellido</th>
                                <th style="color: white; padding: 15px; border: none; font-weight: 600;">Fecha</th>
                                <th style="color: white; padding: 15px; border: none; font-weight: 600;">Total</th>
                                <th style="color: white; padding: 15px; border: none; font-weight: 600; text-align: center;">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($ventas_cabecera->result() as $row){ ?>
                            <tr class="fila-venta" style="transition: background-color 0.2s ease;">
                                <td style="padding: 14px; border-top: 1px solid #eeeeee; border-bottom: none; border-left: none; border-right: none; font-weight: bold; color: #666;"><?php echo $row->id_cabecera; ?></td>
                                <td style="padding: 14px; border-top: 1px solid #eeeeee; border-bottom: none; border-left: none; border-right: none;"><?php echo $row->nombre; ?></td>
                                <td style="padding: 14px; border-top: 1px solid #eeeeee; border-bottom: none; border-left: none; border-right: none;"><?php echo $row->apellido; ?></td>
                                <td style="padding: 14px; border-top: 1px solid #eeeeee; border-bottom: none; border-left: none; border-right: none; color: #555;"><?php echo $row->fecha; ?></td>
                                <td style="padding: 14px; border-top: 1px solid #eeeeee; border-bottom: none; border-left: none; border-right: none; font-weight: bold; color: #4a2c11;">$<?php echo number_format($row->total_venta, 2); ?></td>
                                <td style="padding: 14px; border-top: 1px solid #eeeeee; border-bottom: none; border-left: none; border-right: none; text-align: center;">
                                    <a href="<?php echo base_url("muestra_detalle/$row->id_cabecera"); ?>" 
                                       class="btn btn-sm btn-detalle" 
                                       style="background-color: #f3ece6; color: #4a2c11; border: 1px solid #4a2c11; border-radius: 20px; padding: 6px 22px; font-weight: bold; font-size: 13px; text-decoration: none; display: inline-block; transition: all 0.2s ease;">
                                        Detalle
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                </div> <!-- Fin del contenedor redondeado -->
                
        <?php } ?>
            </div>
    </div>
</div>

<!-- Estilos para mejorar la experiencia visual e interactiva -->
<style>
    /* Efecto hover suave al pasar el mouse por encima de las filas de la tabla */
    .fila-venta:hover {
        background-color: #fcfaf7 !important;
    }
    /* Estilo interactivo para el botón de detalle */
    .btn-detalle:hover {
        background-color: #4a2c11 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 6px rgba(74, 44, 17, 0.25);
    }
</style>