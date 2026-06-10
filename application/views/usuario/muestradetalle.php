<section class="mbr-section article content12 cid-qRlf4ndxBK" id="content12-m">
    <div class="container" style="padding-top: 40px; padding-bottom: 60px;">
        
        <?php if (empty($ventas_detalle)) { ?>

            <div class="card shadow-sm text-center" style="border-radius: 12px; padding: 40px; border: 1px solid #e2e2e2; background: #ffffff; max-width: 600px; margin: 0 auto;">
                <h1 style="color: #4a2c11; font-weight: 700; margin-bottom: 25px;">No se realizaron Ventas</h1>
                <div style="text-align: center;">
                    <a href="<?php echo base_url('ventas'); ?>" class="btn btn-lg btn-volver" style="background-color: #f3ece6; color: #4a2c11; border: 1px solid #4a2c11; border-radius: 20px; padding: 10px 40px; font-weight: bold; font-size: 16px; text-decoration: none; display: inline-block; transition: all 0.2s ease;">Volver</a>
                </div>
            </div>

        <?php } else { ?>

            <!-- Cabecera de la venta (datos del cliente, provenientes del SP) -->
            <?php if (!empty($cabecera)): ?>
            <div style="background:#fff; border:1px solid #e2e2e2; border-radius:12px; padding:20px 28px;
                        margin-bottom:24px; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                <h2 style="color:#4a2c11; font-weight:700; margin-bottom:12px;">
                    Venta #<?php echo $cabecera->id_cabecera; ?>
                </h2>
                <p style="margin:4px 0; color:#555;">
                    <strong>Cliente:</strong>
                    <?php echo htmlspecialchars($cabecera->nombre . ' ' . $cabecera->apellido); ?>
                    &mdash; <?php echo htmlspecialchars($cabecera->email); ?>
                </p>
                <p style="margin:4px 0; color:#555;">
                    <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($cabecera->fecha)); ?>
                    &nbsp;|&nbsp;
                    <strong>Total:</strong>
                    <span style="color:#2e7d32; font-weight:700;">
                        $<?php echo number_format($cabecera->total_venta, 2, ',', '.'); ?>
                    </span>
                </p>
            </div>
            <?php endif; ?>

            <!-- Título principal estilizado -->
            <h2 class="text-center" style="color: #4a2c11; font-weight: 700; font-size: 28px; letter-spacing: 0.5px; margin-bottom: 30px;">
                Detalle de Ventas
            </h2>

            <!-- Tabla de detalle — $ventas_detalle es ahora un array de objetos (resultado del SP) -->
            <div style="border-radius: 14px; overflow: hidden; border: 1px solid #e2e2e2; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: #ffffff; margin-bottom: 30px;">
                
                <table class="table" style="margin-bottom: 0; border-collapse: separate; border-spacing: 0; border: none; width: 100%;">
                    <thead>
                        <tr style="background-color: #4a2c11; color: white;">
                            <th style="color: white; padding: 15px; border: none; font-weight: 600; text-align: center; width: 120px;">ID Detalle</th>
                            <th style="color: white; padding: 15px; border: none; font-weight: 600;">Descripción</th>
                            <th style="color: white; padding: 15px; border: none; font-weight: 600; text-align: center; width: 100px;">Cantidad</th>
                            <th style="color: white; padding: 15px; border: none; font-weight: 600; text-align: right;">Precio Unitario</th>
                            <th style="color: white; padding: 15px; border: none; font-weight: 600; text-align: right;">Precio Costo</th>
                            <th style="color: white; padding: 15px; border: none; font-weight: 600; text-align: right;">Precio Venta</th>
                            <th style="color: white; padding: 15px; border: none; font-weight: 600; text-align: right;">Ganancias</th>
                            <th style="color: white; padding: 15px; border: none; font-weight: 600; text-align: right; padding-right: 25px;">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas_detalle as $row) {
                            $ganancia_calculada = ($row->precio_venta - $row->precio_costo) * $row->cantidad;
                            $subtotal_calculado = $row->precio * $row->cantidad;
                        ?>
                        <tr class="fila-detalle" style="transition: background-color 0.2s ease;">
                            <td style="padding: 14px; border-top: 1px solid #eeeeee; text-align: center; font-weight: bold; color: #777;"><?php echo $row->id_detalle; ?></td>
                            <td style="padding: 14px; border-top: 1px solid #eeeeee; color: #333; font-weight: 500;"><?php echo trim($row->descripcion); ?></td>
                            <td style="padding: 14px; border-top: 1px solid #eeeeee; text-align: center; font-weight: 500;"><?php echo $row->cantidad; ?></td>
                            <td style="padding: 14px; border-top: 1px solid #eeeeee; text-align: right;">$<?php echo number_format($row->precio_venta, 2); ?></td>
                            <td style="padding: 14px; border-top: 1px solid #eeeeee; text-align: right; color: #777;">$<?php echo number_format($row->precio_costo, 2); ?></td>
                            <td style="padding: 14px; border-top: 1px solid #eeeeee; text-align: right;">$<?php echo number_format($row->precio_venta, 2); ?></td>
                            <td style="padding: 14px; border-top: 1px solid #eeeeee; text-align: right; color: #2e7d32; font-weight: 600;">$<?php echo number_format($ganancia_calculada, 2); ?></td>
                            <td style="padding: 14px; border-top: 1px solid #eeeeee; text-align: right; font-weight: bold; color: #4a2c11; padding-right: 25px;">$<?php echo number_format($subtotal_calculado, 2); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
            </div>

            <!-- Botón Volver integrado a la paleta de colores café corporativa -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="<?php echo base_url('ventas'); ?>" class="btn btn-lg btn-volver" style="background-color: #f3ece6; color: #4a2c11; border: 1px solid #4a2c11; border-radius: 20px; padding: 10px 45px; font-weight: bold; font-size: 16px; text-decoration: none; display: inline-block; transition: all 0.2s ease;">
                    Volver
                </a>
            </div>

        <?php } ?>
    </div>
</section>

<!-- Estilos interactivos adicionales -->
<style>
    .fila-detalle:hover {
        background-color: #fcfaf7 !important;
    }
    .btn-volver:hover {
        background-color: #4a2c11 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 6px rgba(74, 44, 17, 0.2);
    }
</style>
