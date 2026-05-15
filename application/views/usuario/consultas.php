<!-- inicio banner -->
<section class="banner-area" id="home">	
    <div class="container">
        <div class="row fullscreen d-flex align-items-center justify-content-start">
            <font size="7" color="white">Consultas Recibidas</font>
        </div>
    </div>
</section>		

<section class="menu-area section-gap" id="Consultas">
    <div class="container"> 
        <?php 
            include "conexion.php";
            $query = mysqli_query($conection, "SELECT d.id, d.nombre, d.apellido, d.email, d.mensaje FROM `datos` d");
            $result = mysqli_num_rows($query);

            if($result > 0){
        ?>
        <!-- La tabla inicia ANTES del bucle -->
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Mensaje</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        // El bucle SOLO repite las filas (tr)
                        while ($data = mysqli_fetch_array($query)) { 
                    ?>
                    <tr>
                        <td><?php echo $data["id"];?></td>
                        <td><strong><?php echo $data["nombre"];?></strong></td>
                        <td><?php echo $data["apellido"];?></td>
                        <td><?php echo $data["email"];?></td>
                        <td><?php echo $data["mensaje"];?></td>
                        <td class="text-center">
                            <button class="btn-delete" title="Eliminar">🗑️</button>
                        </td>
                    </tr>
                    <?php } // Fin del while ?>
                </tbody>
            </table>
        </div>
        <?php 
            } else {
                echo "<center><h1 style='color:#d4a373;'>No se realizaron Consultas</h1></center>";
            }
        ?>
    </div>
</section>

<style>
    /* Estilos unificados para combinar con el Café */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background-color: white;
        border-radius: 12px; 
        overflow: hidden;
        font-family: 'Segoe UI', sans-serif;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin: 20px 0;
    }

    .custom-table thead {
        background-color: #2c1e1a; 
        color: #d4a373; 
    }

    .custom-table th {
        padding: 18px 15px;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1.5px;
        border: none;
    }

    .custom-table td {
        padding: 15px;
        border-bottom: 1px solid #f2ede4;
        color: #5d4037; 
        vertical-align: middle;
    }

    /* Filas pares con un toque crema */
    .custom-table tbody tr:nth-child(even) {
        background-color: #fdfaf5;
    }

    /* Efecto Hover elegante */
    .custom-table tbody tr:hover {
        background-color: #f9f3eb;
        transition: 0.3s;
    }

    .btn-delete {
        background-color: #fee2e2;
        border: 1px solid #fca5a5;
        color: #b91c1c;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-delete:hover {
        background-color: #dc2626;
        color: white;
        transform: scale(1.1); 
    }

    /* Para que se vea bien en celulares */
    .table-responsive {
        overflow-x: auto;
    }
</style>
