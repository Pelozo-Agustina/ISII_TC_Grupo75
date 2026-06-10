<!-- inicio banner -->
<section class="banner-area" id="home">	
    <div class="container">
        <div class="row fullscreen d-flex align-items-center justify-content-start">
            <font size="7" color="white">
                Usuarios
            </font>
        </div>
    </div>
</section>		
<!-- Fin banner -->

<section class="seccion-contenedor">
    <?php if (!$usuarios){ ?>

        <div class="container tarjeta-cafe">
            <div class="cabecera-oscura">
                <h2>No hay usuarios</h2>
            </div>
            <div class="cuerpo-tarjeta">
                <?php $session_data = $this->session->userdata('login_in')?>
                <?php if( ($this->session->userdata('login_in')) and ($session_data['perfil_id']=='1') ) { ?>
                    <div class="botones-superiores">
                        <a class="btn-cafe-solido" href="<?php echo base_url('cargar_usuario'); ?>">Agregar</a>
                        <a class="btn-cafe-solido" href="<?php echo base_url('usuarios_eliminados'); ?>">ELIMINADOS</a>
                    </div>
                <?php } ?>	
            </div>
        </div>

    <?php } else { ?>

        <div class="container tarjeta-cafe">
            <!-- Título Principal Superior -->
            <h1 class="titulo-seccion">Todos los Usuarios</h1>
            

            <!-- Botones Agregar / Eliminados fuera de la tabla, limpios en la parte superior -->
        <div class="container contenedor-acciones-top">
            <a class="btn-cafe-solido" href="<?php echo base_url('cargar_usuario'); ?>">Agregar Usuario</a>
            <a class="btn-cafe-solido btn-rojo-suave" href="<?php echo base_url('usuarios_eliminados'); ?>">Usuarios Eliminados</a>
        </div>


            <div class="tabla-contenedor-redondeado">
                <table class="tabla-estilo-cafe">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>baja</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios->result() as $row){ ?>
                        <tr>
                            <td class="col-negrita"><?php echo $row->id;  ?></td>
                            <td class="col-atenuada"><?php echo htmlspecialchars($row->nombre);  ?></td>
                            <td class="col-atenuada"><?php echo htmlspecialchars($row->apellido);  ?></td>
                            <td class="col-atenuada"><?php echo htmlspecialchars($row->email);  ?></td>
                            <td class="col-atenuada"><?php echo $row->perfil_id;  ?></td>
                            <td class="col-atenuada">
                                <span class="badge-baja <?php echo (trim($row->baja) === 'SI') ? 'baja-si' : 'baja-no'; ?>">
                                    <?php echo $row->baja; ?>
                                </span>
                            </td>
                            <td>
                                <?php if (trim($row->baja) === 'SI') { ?>
                                    <!-- Botón deshabilitado estilo píldora contorneada pero gris sin click -->
                                    <button class="btn-pildora btn-deshabilitado" disabled title="Este usuario ya fue dado de baja">
                                        Eliminar
                                    </button>
                                <?php } else { ?>
                                    <!-- Botón "Eliminar" tipo píldora contorneada interactivo -->
                                    <a class="btn-pildora btn-accion-eliminar" href="<?php echo base_url("usuario_elimina/$row->id");?>">
                                        Eliminar
                                    </a>
                                <?php } ?>
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
<!-- 🎨 ESTILOS DE LA COMPAÑÍA (VENTAS REALIZADAS) -->
<!-- ========================================== -->
<style>
    /* Contenedor general espaciado */
    .seccion-contenedor {
        background-color: #ffffff;
        padding: 40px 0;
        font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    /* Título superior estilizado en color marrón/dorado */
    .titulo-seccion {
        text-align: center;
        font-family: "Poppins", Arial, sans-serif;
        font-weight: 700;
        color: #b58d4a; /* Tono ocre/dorado del título original */
        margin-bottom: 25px;
        font-size: 32px;
    }

    /* Contenedor superior para los botones de control */
    .contenedor-acciones-top {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        justify-content: flex-start;
    }

    /* Estructura de la tarjeta que emula el contenedor redondeado */
    .tarjeta-cafe {
        background: #ffffff;
        margin-bottom: 30px;
    }

    /* Caja contenedora para forzar bordes redondeados en la cabecera oscura y tabla */
    .tabla-contenedor-redondeado {
        border-radius: 12px;
        overflow: hidden; /* Clave para que la cabecera respete la esquina redondeada */
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid #e0e0e0;
    }

    /* Tabla principal */
    .tabla-estilo-cafe {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
    }

    /* Cabecera oscura idéntica a tu referencia */
    .tabla-estilo-cafe th {
        background-color: #4a2e1b; /* Marrón oscuro / Café */
        color: #ffffff;
        font-weight: 600;
        font-size: 14px;
        padding: 16px 20px;
        text-align: left;
        border: none;
    }

    /* Filas y celdas */
    .tabla-estilo-cafe td {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 14px;
    }

    /* El ID en negrita al inicio de la fila */
    .col-negrita {
        font-weight: 700;
        color: #222222;
    }

    /* Textos de datos ligeramente atenuados como tu imagen */
    .col-atenuada {
        color: #7a7a7a;
        font-weight: 400;
    }

    /* Pequeño indicador de estado estético */
    .badge-baja {
        font-weight: 600;
        font-size: 13px;
    }
    .baja-no { color: #7a7a7a; }
    .baja-si { color: #c0392b; font-weight: 700; }

    /* Botón estilo píldora contorneada (Igual al botón Detalle) */
    .btn-pildora {
        display: inline-block;
        padding: 6px 24px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 20px; /* Hace el efecto ovalado*/
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease-in-out;
        background: #ffffff;
    }

    /* Botón Activo: Borde marrón oscuro, letras oscuras */
    .btn-accion-eliminar {
        border: 1px solid #4a2e1b;
        color: #4a2e1b !important;
        cursor: pointer;
    }
    .btn-accion-eliminar:hover {
        background-color: #4a2e1b;
        color: #ffffff !important;
    }

    /* Botón Inactivo/Bloqueado: Borde gris claro, letras grises */
    .btn-deshabilitado {
        border: 1px solid #dcdcdc;
        color: #b5b5b5;
        cursor: not-allowed;
        opacity: 0.7;
    }

 /* Botones exteriores sólidos */
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

/* Variante para el botón ELIMINADOS */
.btn-rojo-suave {
    background-color: #c0392b; 
}

.btn-rojo-suave:hover {
    background-color: #ef5350; 
}
</style>
