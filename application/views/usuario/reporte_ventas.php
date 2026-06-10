<!-- ============================================================
     VISTA: reporte_ventas.php
     SP: sp_reporte_ventas_por_periodo
     Campos: fecha, cantidad_ventas, total_dia, promedio_venta, subtotal_acumulado
     Llamada desde: producto_controller::reporte_ventas()
     ============================================================ -->

 <!-- inicio banner -->
                <section class="banner-area" id="home"> 
                <div class="container">
                    <div class="row fullscreen d-flex align-items-center justify-content-start">
                        <font size="7" color="white">
                            Ventas por Periodo
                        </font><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                    </div>
                </div>
            </section>      
            <!-- Fin banner -->


<div class="container" style="padding-top:30px; padding-bottom:60px;">

    <!-- Formulario de fechas -->
    <div style="background:#fff; border:1px solid #e2e2e2; border-radius:12px;
                padding:24px 30px; margin-bottom:30px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <form method="GET" action="<?php echo base_url('reporte_ventas'); ?>">
            <div style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
                <div>
                    <label style="display:block; font-weight:600; color:#4a2c11; margin-bottom:6px;">Fecha inicio</label>
                    <input type="date" name="fecha_inicio"
                           value="<?php echo isset($fecha_inicio) ? htmlspecialchars($fecha_inicio) : ''; ?>"
                           style="border:1px solid #ccc; border-radius:8px; padding:8px 12px;">
                </div>
                <div>
                    <label style="display:block; font-weight:600; color:#4a2c11; margin-bottom:6px;">Fecha fin</label>
                    <input type="date" name="fecha_fin"
                           value="<?php echo isset($fecha_fin) ? htmlspecialchars($fecha_fin) : ''; ?>"
                           style="border:1px solid #ccc; border-radius:8px; padding:8px 12px;">
                </div>
                <button type="submit"
                        style="background:#4a2c11; color:white; border:none; border-radius:8px;
                               padding:9px 28px; font-weight:600; cursor:pointer;">
                    Consultar
                </button>
            </div>
        </form>
    </div>

    <?php if (empty($reporte)): ?>
        <div class="card text-center shadow-sm"
             style="border-radius:12px; padding:40px; border:1px solid #e2e2e2; background:#fff;">
            <h3 style="color:#777; font-weight:600;">
                <?php echo (isset($fecha_inicio)) ? 'Sin ventas en el período seleccionado.' : 'Seleccione un rango de fechas para consultar.'; ?>
            </h3>
        </div>

    <?php else:
        // Totales acumulados para el resumen
        $total_ventas  = array_sum(array_column((array)$reporte, 'cantidad_ventas'));
        $total_importe = array_sum(array_column((array)$reporte, 'total_dia'));
    ?>

        <!-- Resumen -->
        <div style="display:flex; gap:16px; margin-bottom:24px; flex-wrap:wrap;">
            <div style="flex:1; min-width:160px; background:#4a2c11; color:white; border-radius:12px; padding:20px; text-align:center;">
                <div style="font-size:28px; font-weight:700;"><?php echo count($reporte); ?></div>
                <div style="font-size:13px; opacity:.85;">Días con ventas</div>
            </div>
            <div style="flex:1; min-width:160px; background:#6d4226; color:white; border-radius:12px; padding:20px; text-align:center;">
                <div style="font-size:28px; font-weight:700;"><?php echo $total_ventas; ?></div>
                <div style="font-size:13px; opacity:.85;">Ventas totales</div>
            </div>
            <div style="flex:1; min-width:160px; background:#8b5e3c; color:white; border-radius:12px; padding:20px; text-align:center;">
                <div style="font-size:28px; font-weight:700;">$<?php echo number_format($total_importe, 2, ',', '.'); ?></div>
                <div style="font-size:13px; opacity:.85;">Importe total</div>
            </div>
        </div>

        <!-- Tabla -->
        <div style="border-radius:14px; overflow:hidden; border:1px solid #e2e2e2;
                    box-shadow:0 4px 12px rgba(0,0,0,0.05); background:#fff;">
            <table class="table" style="margin-bottom:0; width:100%;">
                <thead>
                    <tr style="background-color:#4a2c11; color:white;">
                        <th style="padding:15px; border:none; font-weight:600;">Fecha</th>
                        <th style="padding:15px; border:none; font-weight:600; text-align:center;">Cant. Ventas</th>
                        <th style="padding:15px; border:none; font-weight:600; text-align:right;">Total del Día</th>
                        <th style="padding:15px; border:none; font-weight:600; text-align:right;">Promedio Venta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reporte as $row): ?>
                    <tr class="fila-reporte">
                        <td style="padding:13px 15px; border-top:1px solid #eee; color:#333;">
                            <?php echo date('d/m/Y', strtotime($row->fecha)); ?>
                        </td>
                        <td style="padding:13px 15px; border-top:1px solid #eee; text-align:center; font-weight:600; color:#4a2c11;">
                            <?php echo $row->cantidad_ventas; ?>
                        </td>
                        <td style="padding:13px 15px; border-top:1px solid #eee; text-align:right; font-weight:700; color:#2e7d32;">
                            $<?php echo number_format($row->total_dia, 2, ',', '.'); ?>
                        </td>
                        <td style="padding:13px 15px; border-top:1px solid #eee; text-align:right; color:#555;">
                            $<?php echo number_format($row->promedio_venta, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="text-align:center; margin-top:30px;">
            <a href="<?php echo base_url('ventas'); ?>"
               style="background:#f3ece6; color:#4a2c11; border:1px solid #4a2c11; border-radius:20px;
                      padding:10px 45px; font-weight:bold; font-size:16px; text-decoration:none; display:inline-block;">
                Volver a Ventas
            </a>
        </div>

    <?php endif; ?>
</div>

<style>
    .fila-reporte:hover { background-color: #fcfaf7 !important; }
</style>
