<!-- ============================================================
     VISTA: stock_bajo.php
     SP3 — sp_productos_stock_bajo
     Lista productos donde stock_actual <= stock_min.
     Campos: id, descripcion, categoria, stock_actual, stock_minimo, unidades_faltantes
     Llamada desde: producto_controller::stock_bajo()
     ============================================================ -->

<!-- Banner -->
<section class="banner-area" id="home">
    <div class="container">
        <div class="row fullscreen d-flex align-items-center justify-content-start">
            <font size="7" color="white">Stock Bajo</font>
        </div>
    </div>
</section>

<div class="container" style="padding-top:30px; padding-bottom:60px;">

    <?php if (empty($productos)): ?>
        <!-- Sin productos críticos -->
        <div class="card text-center shadow-sm"
             style="border-radius:12px; padding:40px; border:1px solid #e2e2e2; background:#fff;">
            <h3 style="color:#2e7d32; font-weight:700;">✔ Todo en orden</h3>
            <p style="color:#777;">No hay productos por debajo del stock mínimo configurado.</p>
        </div>

    <?php else: ?>

        <!-- Alerta informativa -->
        <div style="background:#fff3cd; border:1px solid #ffc107; border-radius:10px;
                    padding:14px 20px; margin-bottom:24px; color:#856404; font-weight:600;">
            ⚠ Se encontraron productos que requieren reposición inmediata.
        </div>

        <!-- Tabla de productos con stock bajo -->
        <div style="border-radius:14px; overflow:hidden; border:1px solid #e2e2e2;
                    box-shadow:0 4px 12px rgba(0,0,0,0.05); background:#fff;">
            <table class="table" style="margin-bottom:0; border-collapse:separate; border-spacing:0; width:100%;">
                <thead>
                    <tr style="background-color:#4a2c11; color:white;">
                        <th style="color:white; padding:15px; border:none; font-weight:600; text-align:center;">ID</th>
                        <th style="color:white; padding:15px; border:none; font-weight:600;">Producto</th>
                        <th style="color:white; padding:15px; border:none; font-weight:600;">Categoría</th>
                        <th style="color:white; padding:15px; border:none; font-weight:600; text-align:center;">Stock Actual</th>
                        <th style="color:white; padding:15px; border:none; font-weight:600; text-align:center;">Stock Mínimo</th>
                        <th style="color:white; padding:15px; border:none; font-weight:600; text-align:center;">Unidades Faltantes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $row): ?>
                    <tr class="fila-stock">
                        <td style="padding:14px; border-top:1px solid #eee; text-align:center; color:#777; font-weight:bold;">
                            <?php echo $row->id; ?>
                        </td>
                        <td style="padding:14px; border-top:1px solid #eee; font-weight:500; color:#333;">
                            <?php echo htmlspecialchars($row->descripcion); ?>
                        </td>
                        <td style="padding:14px; border-top:1px solid #eee; color:#555;">
                            <?php echo htmlspecialchars($row->categoria); ?>
                        </td>
                        <td style="padding:14px; border-top:1px solid #eee; text-align:center;
                                   font-weight:700; color:<?php echo ($row->stock_actual == 0) ? '#c62828' : '#e65100'; ?>;">
                            <?php echo $row->stock_actual; ?>
                        </td>
                        <td style="padding:14px; border-top:1px solid #eee; text-align:center; color:#555;">
                            <?php echo $row->stock_minimo; ?>
                        </td>
                        <td style="padding:14px; border-top:1px solid #eee; text-align:center;
                                   font-weight:700; color:#c62828;">
                            <?php echo $row->unidades_faltantes; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Botón volver -->
        <div style="text-align:center; margin-top:30px;">
            <a href="<?php echo base_url('productos_todos'); ?>"
               style="background:#f3ece6; color:#4a2c11; border:1px solid #4a2c11; border-radius:20px;
                      padding:10px 45px; font-weight:bold; font-size:16px; text-decoration:none; display:inline-block;">
                Volver a Productos
            </a>
        </div>

    <?php endif; ?>
</div>

<style>
    .fila-stock:hover { background-color: #fcfaf7 !important; }
</style>
