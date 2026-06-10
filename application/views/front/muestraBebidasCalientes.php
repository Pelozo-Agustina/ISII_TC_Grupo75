<!-- inicio banner -->
<section class="banner-area" id="home"> 
    <div class="container">
        <div class="row fullscreen d-flex align-items-center justify-content-start">
            <font size="7" color="white">
              Bebidas Calientes
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
                                <h2 class="titulo-seccion" style="font-size: 22px; margin-bottom: 15px;">No hay productos disponibles</h2>
                                <p>No hay productos disponibles para esta categoría actualmente.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php $session_data = $this->session->userdata('login_in')?>
            <?php if( ($this->session->userdata('login_in')) and ($session_data['perfil_id'] =='1') ) { ?>
                <div class="contenedor-acciones-top" style="margin-top: 20px; justify-content: center;">
                    <a class="btn-cafe-solido" href="<?php echo base_url('cargar_producto'); ?>">Agregar Producto</a>
                </div>
            <?php } ?>
         </div>

    <?php } else { ?>

         

         <div class="container tarjeta-cafe">
            <!-- Título Principal Superior con la tipografía oficial -->
            <h1 class="titulo-seccion">Todos las Bebidas Calientes</h1>
            
<!-- Contenedor superior para el botón Agregar (Estilo Óvalo Claro) -->
         <div class="container contenedor-acciones-top">
            <?php $session_data = $this->session->userdata('login_in')?>
            <?php if( ($this->session->userdata('login_in')) and ($session_data['perfil_id'] =='1') ) { ?>
                <a class="btn-cafe-solido" href="<?php echo base_url('cargar_producto'); ?>">Agregar Producto</a>
            <?php } ?>
         </div>
            
            <div class="tabla-contenedor-redondeado">
                <table class="tabla-estilo-cafe">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripcion</th>
                            <th>Precio Costo</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                            <th>Eliminado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($productos->result() as $row){ ?>
                        <tr>
                            <td class="col-negrita"><?php echo $row->id;  ?></td>
                            <td class="col-atenuada"><?php echo htmlspecialchars($row->descripcion);  ?></td>
                            <td class="col-atenuada">$<?php echo number_format($row->precio_costo, 2);  ?></td>
                            <td class="col-atenuada" style="font-weight: 600; color: #4a2e1b;">$<?php echo number_format($row->precio_venta, 2);  ?></td>
                            <td>
                                <span class="badge-stock <?php echo ($row->stock > 0) ? 'stock-disponible' : 'stock-agotado'; ?>">
                                    <?php echo $row->stock;  ?> u.
                                </span>
                            </td>
                            <td class="col-atenuada">
                                <span class="badge-eliminado <?php echo (trim($row->eliminado) === 'SI') ? 'elim-si' : 'elim-no'; ?>">
                                    <?php echo $row->eliminado;  ?>
                                </span>
                            </td>
                            <td>
                                <div class="grupo-acciones">
                                    <!-- Enlace Modificar: Píldora contorneada café claro -->
                                    <a class="btn-pildora btn-accion-modificar" href="<?php echo base_url("producto_modifica/$row->id");?>">Modificar</a>
                                    
                                    <!-- Enlace Eliminar: Píldora contorneada roja suave -->
                                    <a class="btn-pildora btn-accion-eliminar" href="<?php echo base_url("producto_elimina/$row->id");?>">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>	            
            </div>	            
        </div>

    <?php } ?>
</section>

<!-- ========================================== -->
<!-- 🎨 ESTILOS DE LA COMPAÑÍA (BEBIDAS CALIENTES) -->
<!-- ========================================== -->
<style>
    /* Contenedor general de la sección */
    .seccion-contenedor {
        background-color: #ffffff;
        padding: 40px 0;
        font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    /* Título ocre dorado superior */
    .titulo-seccion {
        text-align: center;
        font-family: "Poppins", Arial, sans-serif;
        font-weight: 700;
        color: #b58d4a; 
        margin-bottom: 25px;
        font-size: 32px;
    }

    /* Contenedor del botón superior */
    .contenedor-acciones-top {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        justify-content: flex-start;
    }

    /* Estructura base de la tarjeta */
    .tarjeta-cafe {
        background: #ffffff;
        margin-bottom: 30px;
    }

    /* Forzar bordes redondeados en la cabecera oscura */
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

    /* Cabecera marrón oscuro oficial */
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

    /* Badges visuales para controlar el stock de insumos */
    .badge-stock {
        font-weight: 600;
        font-size: 13px;
        padding: 2px 8px;
        border-radius: 4px;
    }
    .stock-disponible { color: #2e7d32; background: #e8f5e9; }
    .stock-agotado { color: #c62828; background: #ffebee; }

    /* Indicador sutil de eliminación lógica */
    .badge-eliminado {
        font-weight: 600;
    }
    .elim-no { color: #7a7a7a; }
    .elim-si { color: #e57373; font-weight: 700; }

    /* Fuerza a que la última columna (Acciones) tenga un ancho compacto y controlado */
.tabla-estilo-cafe th:last-child, 
.tabla-estilo-cafe td:last-child {
    width: 180px; /* Ancho justo para que entren los dos botones juntos */
    text-align: center; /* Centra los botones en su celda */
}

/* Agrupa los botones de manera compacta sin dejar espacios vacíos */
.grupo-acciones {
    display: inline-flex; /* Cambiado a inline-flex para que ocupe solo el espacio necesario */
    gap: 8px; /* Espaciado fijo y pequeño entre Modificar y Eliminar */
    justify-content: center;
    align-items: center;
    width: auto;
}

/* Ajuste fino al botón píldora para que mantenga proporciones elegantes */
.btn-pildora {
    display: inline-block;
    padding: 6px 14px; /* Ajuste sutil de relleno */
    font-size: 12px;
    font-weight: 600;
    border-radius: 20px; 
    text-decoration: none;
    text-align: center;
    white-space: nowrap; /* Evita que el texto del botón se rompa en dos líneas */
    transition: all 0.2s ease-in-out;
    background: #ffffff;
}


    /* Botón Modificar Contorneado Café */
    .btn-accion-modificar {
        border: 1px solid #4a2e1b;
        color: #4a2e1b !important;
    }
    .btn-accion-modificar:hover {
        background-color: #4a2e1b;
        color: #ffffff !important;
    }

    /* Botón Eliminar Contorneado Rojo Suave */
    .btn-accion-eliminar {
        border: 1px solid #e57373;
        color: #e57373 !important;
    }
    .btn-accion-eliminar:hover {
        background-color: #e57373;
        color: #ffffff !important;
    }

    /* Botones exteriores sólidos óvalos claros */
    .btn-cafe-solido {
        background-color: #6d4c41; 
        color: #ffffff !important;
        padding: 8px 26px;
        font-size: 13px;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        border-radius: 20px; 
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease-in-out;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .btn-cafe-solido:hover {
        background-color: #5d4037;
        transform: translateY(-1px);
    }
</style>
