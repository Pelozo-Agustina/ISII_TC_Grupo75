<!-- inicio banner -->
                <section class="banner-area" id="home"> 
                <div class="container">
                    <div class="row fullscreen d-flex align-items-center justify-content-start">
                        <font size="7" color="white">
                            Realizar Reserva
                        </font>
                    </div>
                </div>
            </section>      
            <!-- Fin banner -->

    <!-- Pagina para realizar reserva de mesa -->   
    <!-- La vista solicitará los datos del día y hora de la reserva --> 

    <div class="contenedor-principal-reserva">
    
         <h4 id="titulo-1reserva">Si desea realizar una reserva de mesa, por favor rellene los siguientes campos.</h4>
        <h5 id="titulo-2reserva">Seleccione día y hora preferida para la reserva de la mesa.</h5>


        <div class="conteiner-form-reserva">

        <!-- CASO 1: SI LA RESERVA FUE EXITOSA -> Muestra el cartel con los datos -->
        <?php if (isset($reserva_exitosa) && $reserva_exitosa == TRUE): ?>
            
            <div style="text-align: center; padding: 10px;">
                <h2 style="color: #4a3428; font-weight: bold; margin-bottom: 20px;">☕ <br> ¡Reserva Realizada!</h2>
                <p style="font-size: 18px; color: #6d4c41; margin-bottom: 15px; line-height: 1.6;">
                     Te esperamos el día <strong><?php echo date('d/m/Y', strtotime($fecha_exitosa)); ?></strong> <br>
                    En la <strong>Mesa <?php echo $mesa_exitosa; ?></strong>.
                </p>
                
                <a href="<?php echo base_url('realizar_reserva'); ?>" id="btn_confirmar_reserva" style="text-decoration: none; display: block; text-align: center;">
                    HACER OTRA RESERVA
                </a>
            </div>

        <!-- CASO 2: SI NO SE ENVIÓ NADA -> Muestra el formulario estándar de reserva -->
        <?php else: ?>

            <form action="<?php echo base_url('realizar_reserva'); ?>" method="POST" id="formulario-re">
                       
                <!-- Mensaje de error si falla la validación de tiempo o mesa ocupada -->
                <?php if($this->session->flashdata('error_reserva')): ?>
                    <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; text-align: center; font-size: 14px;">
                        <?php echo $this->session->flashdata('error_reserva'); ?>
                    </div>
                <?php endif; ?>

                <label id="labels">Fecha de reserva:</label>
                <input type="date" class="campo-datos" name="fecha-reserva" id="fechaReserva" required> <br>

                <!-- Selecciona hora -->
                <label id="labels">Hora de la reserva:</label>  
                <select name="opciones-cart" id="option_carts">
                    <option value="1">8:30 a 10:00</option>
                    <option value="2">10:30 a 12:00</option>    
                    <option value="3">12:30 a 14:00</option>   
                    <option value="4">17:00 a 18:30</option>
                    <option value="5">19:00 a 20:30</option>     
                </select> 

                <!-- Selecciona Mesa -->
                <label id="labels">Seleccione Mesa:</label>
                <select name="id_mesa" id="option_mesa" class="campo-datos">
                    <option value="1">Mesa 1 - Ventana (2 pers.)</option>
                    <option value="2">Mesa 2 - Ventana (2 pers.)</option>
                    <option value="3">Mesa 3 - Centro (4 pers.)</option>
                    <option value="4">Mesa 4 - Centro (4 pers.)</option>
                    <option value="5">Mesa 5 - Rincón (2 pers.)</option>
                    <option value="6">Mesa 6 - Terraza (6 pers.)</option>
                </select>

                <!-- BOTÓN PARA REALIZAR LA RESERVA -->
                <input type="submit" id="btn_confirmar_reserva" name="enviar-datosForm" value="REALIZAR RESERVA">
             
            </form>  

        <?php endif; ?>
            
    </div>
</div>


<style>
/* Estilo para pagina de reservar mesa y Menú */

/* Importación corregida de Google Fonts */
@import url('https://googleapis.com');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;

}

/* --- SECCIÓN DE RESERVA MEJORADA --- */

.contenedor-principal-reserva {
    width: 90%;           /* No ocupa todo el ancho, dejando aire a los lados */
    max-width: 800px;    /* Ancho máximo para que no se estire de más en PC */
    margin: 50px auto;    /* CENTRA la tarjeta y le da espacio arriba/abajo */
    min-height: 500px;
    
    /* Configuración de fondo para que cubra TODO */
    background-image: url('assets/img/menu-bg.jpg');
     background-size: cover;
    background-position: center;
    
    /* BORDES REDONDEADOS */
    border-radius: 30px;  
    box-shadow: 0 10px 30px rgba(0,0,0,0.05); /* Sombra suave para que flote */
    
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 60px 20px;
}

/* Títulos con margen corregido para que no creen espacios blancos */
#titulo-1reserva {
    text-align: center;
    color: #4a3428;
    font-weight: 700;
    margin-bottom: 5px;
    max-width: 800px;
}

#titulo-2reserva {
    text-align: center;
    margin-bottom: 30px;
    color: #8d6e63;
    font-weight: 300;
    max-width: 800px;
}

/* Tarjeta del formulario */
.conteiner-form-reserva {
    border-radius: 20px;
    background-color: rgba(255, 255, 255, 0.95); /* Un toque de transparencia */
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    width: 100%;
    max-width: 450px;
    padding: 40px;
}

/* Inputs y Selects */
.campo-datos, #option_carts {
    width: 100%;
    height: 48px;
    border: 1px solid #d7ccc8;
    border-radius: 10px;
    margin-bottom: 25px;
    padding: 0 15px;
    font-size: 16px;
    color: #4a3428;
    outline: none;
}

.campo-datos:focus, #option_carts:focus {
    border-color: #6d4c41;
    box-shadow: 0 0 0 2px rgba(109, 76, 65, 0.1);
}

/* Botón Profesional */
#btn_confirmar_reserva {
    background-color: #6d4c41;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    width: 100%;
}

#btn_confirmar_reserva:hover {
    background-color: #4e342e;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(78, 52, 46, 0.3);
}

/* Reutilización de tus clases de Menú */
.section-gap { padding: 80px 0; }
.pb-60 { padding-bottom: 60px; }

</style>

<script>
    // Obtener la fecha actual en formato local
    const hoy = new Date();
    const anio = hoy.getFullYear();
    let mes = hoy.getMonth() + 1; // Los meses empiezan en 0
    let dia = hoy.getDate();

    // Formatear mes y día para que siempre tengan 2 dígitos (ej: 05 en vez de 5)
    if (mes < 10) mes = '0' + mes;
    if (dia < 10) dia = '0' + dia;

    const fechaMinima = `${anio}-${mes}-${dia}`;

    // Aplicar al input por su ID
    const inputFecha = document.getElementById("fechaReserva");
    inputFecha.min = fechaMinima;
    
    // Opcional: Establecer por defecto la fecha de hoy
    inputFecha.value = fechaMinima;
</script>