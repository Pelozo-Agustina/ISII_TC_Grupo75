<!-- ============================================================
     VISTA: resumen_reservas.php
     SP5 — sp_resumen_reservas_por_estado
     Muestra cantidad de reservas agrupadas por estado (Pendiente/Confirmada)
     en un rango de fechas, con primera y última fecha de cada grupo.
     Campos: estado_reserva, cantidad, primera_fecha, ultima_fecha
     Llamada desde: producto_controller::resumen_reservas()
     ============================================================ -->

<!-- Banner -->
<section class="banner-area" id="home">
    <div class="container">
        <div class="row fullscreen d-flex align-items-center justify-content-start">
            <font size="7" color="white">Resumen de Reservas</font>
        </div>
    </div>
</section>

<div class="container" style="padding-top:30px; padding-bottom:60px;">

    <!-- Formulario de filtro por fechas -->
    <div style="background:#fff; border-radius:12px; border:1px solid #e2e2e2;
                box-shadow:0 4px 12px rgba(0,0,0,0.05); padding:24px; margin-bottom:28px;">
        <h5 style="color:#4a2c11; font-weight:700; margin-bottom:16px;">Filtrar por período</h5>
        <form method="GET" action="<?php echo base_url('resumen_reservas'); ?>"
              style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
            <div>
                <label style="font-size:13px; color:#555; display:block; margin-bottom:4px;">Fecha inicio</label>
                <input type="date" name="fecha_inicio"
                       value="<?php echo htmlspecialchars($fecha_inicio); ?>"
                       style="border:1px solid #ccc; border-radius:8px; padding:8px 12px; font-size:14px;">
            </div>
            <div>
                <label style="font-size:13px; color:#555; display:block; margin-bottom:4px;">Fecha fin</label>
                <input type="date" name="fecha_fin"
                       value="<?php echo htmlspecialchars($fecha_fin); ?>"
                       style="border:1px solid #ccc; border-radius:8px; padding:8px 12px; font-size:14px;">
            </div>
            <button type="submit"
                    style="background:#4a2c11; color:#fff; border:none; border-radius:20px;
                           padding:9px 28px; font-weight:600; font-size:14px; cursor:pointer;">
                Buscar
            </button>
        </form>
    </div>

    <?php if (empty($resumen)): ?>
        <!-- Sin resultados -->
        <div class="card text-center shadow-sm"
             style="border-radius:12px; padding:40px; border:1px solid #e2e2e2; background:#fff;">
            <h3 style="color:#4a2c11; font-weight:700;">No hay reservas en el período seleccionado</h3>
            <p style="color:#777;">Prueba con un rango de fechas diferente.</p>
        </div>

    <?php else: ?>

        <!-- Tarjetas de resumen rápido -->
        <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:28px;">
            <?php
            $total_reservas = 0;
            foreach ($resumen->result() as $row): $total_reservas += $row->cantidad; endforeach;
            // Reset result pointer
            $resumen->result(); // already exhausted, rebuild below
            ?>
        </div>

        <!-- Tabla de resumen por estado -->
        <div style="border-radius:14px; overflow:hidden; border:1px solid #e2e2e2;
                    box-shadow:0 4px 12px rgba(0,0,0,0.05); background:#fff; margin-bottom:28px;">
            <table class="table" style="margin-bottom:0; border-collapse:separate; border-spacing:0; width:100%;">
                <thead>
                    <tr style="background-color:#4a2c11; color:white;">
                        <th style="color:white; padding:15px; border:none; font-weight:600;">Estado</th>
                        <th style="color:white; padding:15px; border:none; font-weight:600; text-align:center;">Cantidad</th>
                        <th style="color:white; padding:15px; border:none; font-weight:600; text-align:center;">Primera Fecha</th>
                        <th style="color:white; padding:15px; border:none; font-weight:600; text-align:center;">Última Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Necesitamos iterar nuevamente — usamos result_array() para no depender del puntero
                    $rows = $resumen;
                    $gran_total = 0;
                    foreach ($rows as $row):
                        $gran_total += $row->cantidad;
                        // Color de badge según estado
                        $badge_color = ($row->estado_reserva === 'Confirmada') ? '#2e7d32' : '#e65100';
                    ?>
                    <tr class="fila-resumen">
                        <td style="padding:14px; border-top:1px solid #eee;">
                            <span style="background:<?php echo $badge_color; ?>; color:#fff; border-radius:20px;
                                          padding:4px 14px; font-size:13px; font-weight:600;">
                                <?php echo htmlspecialchars($row->estado_reserva); ?>
                            </span>
                        </td>
                        <td style="padding:14px; border-top:1px solid #eee; text-align:center;
                                   font-weight:700; font-size:22px; color:#4a2c11;">
                            <?php echo $row->cantidad; ?>
                        </td>
                        <td style="padding:14px; border-top:1px solid #eee; text-align:center; color:#555;">
                            <?php echo date('d/m/Y', strtotime($row->primera_fecha)); ?>
                        </td>
                        <td style="padding:14px; border-top:1px solid #eee; text-align:center; color:#555;">
                            <?php echo date('d/m/Y', strtotime($row->ultima_fecha)); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color:#f3ece6;">
                        <td style="padding:14px; font-weight:700; color:#4a2c11; border-top:2px solid #4a2c11;">
                            TOTAL
                        </td>
                        <td style="padding:14px; text-align:center; font-weight:700; font-size:22px;
                                   color:#4a2c11; border-top:2px solid #4a2c11;">
                            <?php echo $gran_total; ?>
                        </td>
                        <td colspan="2" style="border-top:2px solid #4a2c11;"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Botones de navegación -->
        <div style="text-align:center; margin-top:30px; display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
            <a href="<?php echo base_url('muestraReservas'); ?>"
               style="background:#f3ece6; color:#4a2c11; border:1px solid #4a2c11; border-radius:20px;
                      padding:10px 40px; font-weight:bold; font-size:15px; text-decoration:none; display:inline-block;">
                Ver Todas las Reservas
            </a>
            <a href="<?php echo base_url('reservasConfirmadas'); ?>"
               style="background:#4a2c11; color:#fff; border:1px solid #4a2c11; border-radius:20px;
                      padding:10px 40px; font-weight:bold; font-size:15px; text-decoration:none; display:inline-block;">
                Ver Confirmadas
            </a>
        </div>

    <?php endif; ?>
</div>

<style>
    .fila-resumen:hover { background-color: #fcfaf7 !important; }
</style>
