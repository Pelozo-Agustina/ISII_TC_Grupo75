<section class="menu-area section-gap" id="GuardarReserva">
    <?php
    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $baseDatos = 'coffee1';

    $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDatos);

    if(!$enlace){
        die("Error en la conexion: " . mysqli_connect_error());
    }

    if(isset($_POST['enviar-datosForm'])){
    $id_usuario = 2; 
    $fecha = $_POST['fecha-reserva'];
    $id_horario = $_POST['opciones-cart'];
    $id_mesa_elegida = $_POST['id_mesa']; // Recibimos la mesa del formulario
    $estado = "Pendiente";

    // 1. VERIFICAR SI ESA MESA YA ESTÁ OCUPADA EN ESE MOMENTO
    $consultaOcupada = "SELECT * FROM reservas 
                        WHERE fecha_reserva = '$fecha' 
                        AND id_horario = '$id_horario' 
                        AND id_mesa = '$id_mesa_elegida'";

    $resultadoValidacion = mysqli_query($enlace, $consultaOcupada);

    if(mysqli_num_rows($resultadoValidacion) == 0){
        // 2. SI ESTÁ LIBRE, PROCEDEMOS A INSERTAR
        $insertar = "INSERT INTO reservas (id_usuario, fecha_reserva, id_mesa, id_horario, estado_reserva) 
                     VALUES ('$id_usuario', '$fecha', '$id_mesa_elegida', '$id_horario', '$estado')";
        
        if(mysqli_query($enlace, $insertar)){
            echo "<div style='background-color: #fcf9f5; padding: 50px; text-align: center; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 500px; margin: 50px auto;'>
        <h2 style='color: #4a3428;'>☕ ¡Reserva Realizada!</h2>
        <p style='color: #8d6e63; font-size: 1.2em;'>Te esperamos en la <strong>Mesa $id_mesa_elegida</strong></p>
        <p>Fecha: $fecha</p>
        <br>
        <a href='index.php' style='background-color: #6d4c41; color: white; padding: 12px 25px; text-decoration: none; border-radius: 10px; font-weight: bold;'>Volver al Inicio</a>
      </div>";
        }
    } else {
        // 3. SI ESTÁ OCUPADA, AVISAMOS
        echo "<h3 style='color:red;'>Lo sentimos, la mesa elegida ya está reservada para ese horario. Por favor, elige otra mesa u otra hora.</h3>";
    }
}
//Agregar un boton o un redireccionamiento para volver a la pagina principal
    ?>
</section>
