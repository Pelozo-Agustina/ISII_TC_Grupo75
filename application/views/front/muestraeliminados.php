<!-- inicio banner -->
<section class="banner-area" id="home"> 
    <div class="container">
        <div class="row fullscreen d-flex align-items-center justify-content-start">
            <font size="7" color="white">
                Productos Eliminados
            </font>
        </div>
    </div>
</section>      
<!-- Fin banner -->

<section class="seccion-contenedor">
    <?php if (!$productos) { ?>

        <div class="container tarjeta-cafe">
            <div class="tabla-contenedor-redondeado">
                <table class="tabla-estilo-cafe">
                    <thead>
                        <tr>
                            <th style="border-radius: 12px 12px 0 0; text-align: center;">Aviso del Sistema</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-atenuada" style="text-align: center; padding: 40px;">
                                <h2 class="titulo-seccion" style="font-size: 22px; margin-bottom: 15px;">No hay Productos Eliminados</h2>
                                <p>No existen registros de productos dados de baja en el sistema actualmente.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="text-center-container">
                <a href="<?php echo base_url('productos_todos'); ?>" class="btn-cafe-solido">
                    Todos los Productos
                </a>
            </div>
        </div>

    <?php } else { ?>

        <div class="container tarjeta-cafe">
            <!-- Título Principal Superior -->
            <h1 class="titulo-seccion">Todos los Productos Eliminados</h1>
            
            <div class="tabla-contenedor-redondeado">
                <table class="tabla-estilo-cafe">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripcion</th>
                            <th>Categoria</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($productos->result() as $row){ ?>
                        <tr>
                            <td class="col-negrita"><?php echo $row->id;  ?></td>
                            <td class="col-atenuada"><?php echo htmlspecialchars($row->descripcion);  ?></td>
                            <td><span class="badge-categoria"><?php echo $row->categoria_id;  ?></span></td>
                            <td class="col-atenuada" style="font-weight: 600; color: #4a2e1b;">$<?php echo number_format($row->precio_venta, 2);  ?></td>
                            <td>
                                <span class="badge-stock <?php echo ($row->stock > 0) ? 'stock-disponible' : 'stock-agotado'; ?>">
                                    <?php echo $row->stock;  ?> u.
                                </span>
                            </td>
                            <?php
                                // PATRÓN STATE: todos los productos en esta vista son Inactivos
                                // pero usamos el objeto igual para que la vista sea consistente
                                $estadoProducto = new ProductoInactivo();
                            ?>
                            <td class="col-atenuada">
                                <span class="badge-eliminado <?php echo $estadoProducto->getLabelCss(); ?>">
                                    <?php echo $estadoProducto->getNombre(); ?>
                                </span>
                            </td>
                            <td>
                                <div class="grupo-acciones">
                                    <a class="btn-pildora btn-accion-modificar" href="<?php echo base_url("producto_modifica/$row->id");?>">Modificar</a>
                                    <?php if (!$estadoProducto->estaActivo()): ?>
                                        <!-- estaActivo() == false → ProductoInactivo: mostrar Activar -->
                                        <a class="btn-pildora btn-accion-activar" href="<?php echo base_url("productos_activa/$row->id");?>">
                                            Activar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>     
            </div>

            <!-- Botón Inferior Centrado estilo Óvalo Claro -->
            <div class="text-center-container">
                <a href="<?php echo base_url('productos_todos'); ?>" class="btn-cafe-solido">
                    Todos los Productos
                </a>
            </div>           
        </div>

    <?php } ?>
</section>

<!-- ========================================== -->
<!--  ESTILOS UNIFICADOS DE LA COMPAÑÍA        -->
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
        margin-bottom: 25px;
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

    /* Fijamos el tamaño compacto para que los botones de acción queden alineados */
    .tabla-estilo-cafe th:last-child, 
    .tabla-estilo-cafe td:last-child {
        width: 180px; 
        text-align: center; 
    }

    .tabla-estilo-cafe td {
        padding: 16px 15px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 14px;
    }

    .tabla-estilo-cafe tr:hover td {
        background-color: #fafafa; 
    }

    .col-negrita {
        font-weight: 700;
        color: #222222;
    }

    .col-atenuada {
        color: #7a7a7a;
        font-weight: 400;
    }

    .badge-categoria {
        background-color: #ebf5fb;
        color: #2e86c1;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .badge-stock {
        font-weight: 600;
        font-size: 13px;
        padding: 2px 8px;
        border-radius: 4px;
    }
    .stock-disponible { color: #2e7d32; background: #e8f5e9; }
    .stock-agotado { color: #c62828; background: #ffebee; }

    .badge-eliminado {
        font-weight: 600;
    }
    .elim-si { color: #e57373; font-weight: 700; }

    .grupo-acciones {
        display: inline-flex; 
        gap: 8px; 
        justify-content: center;
        align-items: center;
        width: auto;
    }

    .btn-pildora {
        display: inline-block;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 20px; 
        text-decoration: none;
        text-align: center;
        white-space: nowrap; 
        transition: all 0.2s ease-in-out;
        background: #ffffff;
    }

    .btn-accion-modificar {
        border: 1px solid #4a2e1b;
        color: #4a2e1b !important;
    }
    .btn-accion-modificar:hover {
        background-color: #4a2e1b;
        color: #ffffff !important;
    }

    .btn-accion-activar {
        border: 1px solid #6d4c41;
        color: #6d4c41 !important;
    }
    .btn-accion-activar:hover {
        background-color: #6d4c41;
        color: #ffffff !important;
    }

    /* Contenedor del botón de retorno inferior */
    .text-center-container {
        text-align: center;
        margin: 25px 0 10px 0;
    }

    /* Botón estilo óvalo claro */
    .btn-cafe-solido {
        background-color: #6d4c41; 
        color: #ffffff !important;
        padding: 10px 32px;
        font-size: 14px;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        border-radius: 25px; 
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease-in-out;
        border: none;
        box-shadow: 0 3px 8px rgba(0,0,0,0.06);
    }
    .btn-cafe-solido:hover {
        background-color: #5d4037;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>