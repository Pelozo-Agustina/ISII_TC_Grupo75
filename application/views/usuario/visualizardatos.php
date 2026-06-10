<!-- inicio banner -->
                <section class="banner-area" id="home"> 
                <div class="container">
                    <div class="row fullscreen d-flex align-items-center justify-content-start">
                        <font size="7" color="white">
                            Mis Datos
                        </font>
                    </div>
                </div>
            </section>      
            <!-- Fin banner -->
        
 
 <section class="menu-area section-gap" id="Mis Datos">
    <div id="bill_info">
        <?php // Crea formulario para guarda los datos de la venta
            echo form_open("confirma_compra", ['class' => 'form-signin', 'role' => 'form']); 
        ?>
        <div align="center">
            <h2 id="h2" align="center">Informacion Personal</h2>            
            <table class="table" border="0" cellpadding="2px" >
                <tr>
                    <td>
                        Nombre:
                    </td>
                    <td> 
                        <?php echo($nombre) ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                        Apellido:
                    </td>
                    <td> 
                        <?php echo($apellido) ?> 
                    </td>
                </tr>  
                <tr>
                    <td>
                        Email:
                    </td>
                    <td> 
                        <?php echo($email) ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                        Usuario:
                    </td>
                    <td> 
                        <?php echo($usuario) ?> 
                    </td>
                </tr>
            </table>
            <br>  
        </div>
        <?php echo form_close(); ?>  
    </div>
</section>


<!-- Estilo -->
<style>
    /* Estilo para el contenedor de la sección */
    .menu-area {
        background-color: #f9f9f9; /* Un fondo gris muy claro para que resalte la tarjeta */
        padding: 60px 0;
    }

    /* Tarjeta blanca centrada */
    #bill_info {
        background: #ffffff;
        max-width: 600px; /* Ancho máximo para que no se estire de más */
        margin: 0 auto;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05); /* Sombra suave profesional */
    }

    /* Título "Información Personal" */
    #h2 {
        font-family: "Poppins", sans-serif;
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
        position: relative;
    }

    /* Ajustes a la Tabla para que no parezca tabla */
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        font-size: 15px;
        margin: 0; /* Quitamos el margen de 25px que tenías */
    }

    /* Filas de la tabla */
    table tr {
        border-bottom: 1px solid #eee; /* Línea divisoria sutil */
        transition: background 0.3s;
    }

    table tr:last-child {
        border-bottom: none;
    }

    table tr:hover {
        background-color: #fcfcfc;
    }

    /* Celdas de etiquetas (Nombre, Apellido, etc.) */
    table td:first-child {
        text-align: left;
        color: #888;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        padding: 18px 10px;
        width: 40%;
    }

    /* Celdas de datos */
    table td:last-child {
        text-align: right;
        color: #222;
        font-weight: 500;
        padding: 18px 10px;
    }
</style>
