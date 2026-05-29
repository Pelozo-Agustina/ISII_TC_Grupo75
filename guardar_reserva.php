<?php
// 1. Iniciar la sesión nativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

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
        
        // ─── COMPROBACIÓN EXHAUSTIVA DE LA SESIÓN DE CODEIGNITER ───
        $id_usuario = null;

        // Intento 1: Buscar en el array estándar de CodeIgniter en $_SESSION
        if (isset($_SESSION['login_in']['id'])) {
            $id_usuario = $_SESSION['login_in']['id'];
        } 
        // Intento 2: Por si CodeIgniter serializó el array como un objeto
        elseif (isset($_SESSION['login_in'])) {
            $login_data = $_SESSION['login_in'];
            $id_usuario = is_array($login_data) ? ($login_data['id'] ?? null) : ($login_data->id ?? null);
        }

        // CONTROL DE SEGURIDAD CRÍTICO: Si no encuentra el ID real, frena el sistema
        if (empty($id_usuario)) {
            die("<h3 style='color:red; text-align:center; padding:20px;'>Error: No se detectó un ID de usuario activo de CodeIgniter. Por favor, vuelve a iniciar sesión.</h3>");
        }
        // ───────────────────────────────────────────────────────────

        // Continuación segura de tu código
        $fecha = mysqli_real_escape_string($enlace, $_POST['fecha-reserva']);
        $id_horario = mysqli_real_escape_string($enlace, $_POST['opciones-cart']);
        $id_mesa_elegida = mysqli_real_escape_string($enlace, $_POST['id_mesa']); 
        $estado = "Pendiente";

        // Verificar disponibilidad de la mesa
        $consultaOcupada = "SELECT * FROM reservas 
                            WHERE fecha_reserva = '$fecha' 
                            AND id_horario = '$id_horario' 
                            AND id_mesa = '$id_mesa_elegida'";

        $resultadoValidacion = mysqli_query($enlace, $consultaOcupada);

        if(mysqli_num_rows($resultadoValidacion) == 0){
            // Insertar la reserva utilizando la variable dinámica que AHORA SÍ es el ID 5
            $insertar = "INSERT INTO reservas (id_usuario, fecha_reserva, id_mesa, id_horario, estado_reserva) 
                         VALUES ('$id_usuario', '$fecha', '$id_mesa_elegida', '$id_horario', '$estado')";
            
            if(mysqli_query($enlace, $insertar)){
                echo "<div style='background-color: #fcf9f5; padding: 50px; text-align: center; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 500px; margin: 50px auto;'>
                        <h2 style='color: #4a3428;'>☕ ¡Reserva Realizada!</h2>
                        <p style='color: #8d6e63; font-size: 1.2em;'>Te esperamos en la <strong>Mesa $id_mesa_elegida</strong></p>
                        <p>Fecha: $fecha</p>
                        <p style='color: #2e7d32; font-weight: bold;'>Registrado con éxito para el Usuario ID: $id_usuario</p>
                        <br>
                        <a href='index.php' style='background-color: #6d4c41; color: white; padding: 12px 25px; text-decoration: none; border-radius: 10px; font-weight: bold;'>Volver al Inicio</a>
                      </div>";
            }
        } else {
            echo "<h3 style='color:red; text-align:center;'>Lo sentimos, la mesa elegida ya está reservada para ese horario. Por favor, elige otra mesa u otra hora.</h3>";
        }
    }
    ?>
</section>
