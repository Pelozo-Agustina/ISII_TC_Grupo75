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
                <tr>
                    <td>
                        Contraseña:
                    </td>
                    <td> 
                        <?php echo($pass) ?> 
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
    #conteiner h1{
        font-size: 35px;
        display: inline-block;
    }
    .btn_new{
        display: inline-block;
        background: #239baa;
        color: #fff;
        padding: 5px 25px;
        border-radius: 4px;
        margin: 20px;
    }
    table{
        border-collapse: collapse;
        font-size: 12px;
        font-family: "arial";
        text-align: center;
        width: 100%;
        margin: 25px;
    }
    table th{
        text-align: left;
        padding: 10px;
        background: #3d7ba8;
        color: #fff;
    }
    table tr:nth-child(odd){
        background: #f0f5f5;
    }
    table td{
        padding: 10px; 
    }
    .link_edit{
        color: #0ca4ce;
    }
    .link_delete{
        color: #f26b6b;
    }
</style>