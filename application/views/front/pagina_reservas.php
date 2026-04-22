<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Pagina para realizar reserva de mesa -->   
    <!-- La vista solicitará los datos del día y hora de la reserva --> 
    
    <h4 id="titulo-1reserva">Si desea realizar una reserva de mesa, por favor rellene los siguientes campos.</h3>
    <h5 id="titulo-2reserva">Seleccione día y hora preferida para la reserva de la mesa.</h4>


    <div class="contenedor-principal-reserva">
    
        <div class="conteiner-form-reserva">
         <!-- Formulario Reservas -->
         <form action="guardar_reserva.php" method="POST" id="formulario-re">
                   
               <label id="labels">Fecha de reserva:  </Label>
               <input type="date" class="campo-datos" name="fecha-reserva" id="fechaReserva">  <br>

              
               <label id="labels">Hora de la reserva: </label>  
                    <select name="opciones-cart" id="option_carts">
                         <option value="1">8:30 a 10:00</option>
                         <option value="2">10:30 a 12:00</option>    
                         <option value="3">12:30 a 14:00</option>   
                         <option value="4">17:00 a 18:30</option>
                         <option value="5">19:00 a 20:30</option>     
                    </select> 


                <!-- BOTÓN PARA CONFIRMAR LA RESERVA -->
                <input type="submit" id="btn_confirmar_reserva" name="enviar-datosForm" value="CONFIRMAR RESERVA"> </input>

         
        </form>  

            
        </div>
    </div>




</body>
</html>