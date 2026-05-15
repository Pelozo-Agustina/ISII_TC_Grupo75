<!-- inicio banner -->
                <section class="banner-area" id="home"> 
                <div class="container">
                    <div class="row fullscreen d-flex align-items-center justify-content-start">
                        <font size="7" color="white">
                            Informacion de compra
                        </font>
                    </div>
                </div>
            </section>      
            <!-- Fin banner -->

<?php
    $gran_total = 0;

    // Calcula gran total si el carrito tiene elementos
    if ($cart = $this->cart->contents()):
        foreach ($cart as $item):
            $gran_total = $gran_total + $item['subtotal'];
        endforeach;
    endif;
?>
        
    <div id="bill_info">
        
        <?php // Crea formulario para guarda los datos de la venta
            echo form_open("confirma_compra", ['class' => 'form-signin', 'role' => 'form']); 
        ?>
        <div align="center">
            <h2 id="h2" align="center">Info de Compra</h2>

            <table class="table" border="0" cellpadding="2px" >
                <tr>
                    <td>
                        Total Compra:
                    </td>
                    <td>
                        <strong>$<?php echo number_format($gran_total, 2); ?></strong>
                    </td>
                </tr>
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
                <?php echo form_hidden('total_venta', $gran_total); ?>
            </table>
            <br> 
            <?php echo form_submit('confirmar', 'Confirmar',"class='btn btn-lg btn-primary'"); ?> 
            <br> <br>

        </div>
        <?php echo form_close(); ?>
       
    </div>
