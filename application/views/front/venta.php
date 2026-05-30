<!-- inicio banner -->
                <section class="banner-area" id="home"> 
                <div class="container">
                    <div class="row fullscreen d-flex align-items-center justify-content-start">
                        <font size="7" color="white">
                            Informacion de venta
                        </font>
                    </div>
                </div>
            </section>      
            <!-- Fin banner -->

<?php
    $gran_total = 0;

    // Calcula gran total de forma exacta respetando negativos/stock
    if ($cart = $this->cart->contents()):
        foreach ($cart as $item):
            $cantidad_real = (isset($item['cantidad_negativa']) && $item['cantidad_negativa'] !== null) ? $item['cantidad_negativa'] : $item['qty'];
            $subtotal_real = $item['price'] * $cantidad_real;
            $gran_total = $gran_total + $subtotal_real;
        endforeach;
    endif;
?>
        
<div id="bill_info">
    <?php // Regresamos al formulario original
        echo form_open("confirma_venta", ['class' => 'form-signin', 'role' => 'form']); 
    ?>
    <div align="center">
        
        <!-- Mantenemos la tarjeta con tu color café institucional -->
        <div class="card shadow" style="border-radius: 12px; border: 1px solid #e2e2e2; background: #ffffff; overflow: hidden; max-width: 100%; display: inline-block; text-align: left;">
            
            <div class="card-header" style="background-color: #4a2c11; color: white; padding: 15px 25px; text-align: center;">
                <h3 style="margin: 0; font-weight: 600; color: #ffffff; font-size: 22px;">Info de venta</h3>
            </div>

            <div class="card-body" style="padding: 25px;">
                
                <!-- Volvemos al formato de tabla limpia que tenías al inicio -->
                <table class="table" border="0" cellpadding="5px" style="margin-bottom: 0;">
                    <tr>
                        <td style="padding: 10px; font-size: 16px; color: #777; font-weight: bold; white-space: nowrap;">Total venta:</td>
                        <td style="padding: 10px; font-size: 22px; color: #4a2c11; font-weight: 800;">$<?php echo number_format($gran_total, 2); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; font-size: 16px; font-weight: bold; white-space: nowrap;">Nombre:</td>
                        <td style="padding: 10px; font-size: 16px;"><?php echo($nombre); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; font-size: 16px; font-weight: bold; white-space: nowrap;">Apellido:</td>
                        <td style="padding: 10px; font-size: 16px;"><?php echo($apellido); ?></td>
                    </tr>  
                    <tr>
                        <td style="padding: 10px; font-size: 16px; font-weight: bold; white-space: nowrap;">Email:</td>
                        <!-- EL TRUCO: white-space: nowrap obliga al navegador a estirar la tarjeta horizontalmente para que entre el email completo -->
                        <td style="padding: 10px; font-size: 16px; white-space: nowrap;"><?php echo($email); ?></td>
                    </tr>
                    <?php echo form_hidden('total_venta', $gran_total); ?>
                </table>
                
                <br> 
                <div style="text-align: center; display: flex; flex-direction: column; gap: 12px; max-width: 280px; margin: 0 auto;">
                 <!-- Botón original para procesar la venta -->
                     <?php 
                         $btn_style = array(
                            'class' => 'btn btn-lg',
                            'style' => 'background-color: #4a2c11; color: white; border: none; padding: 12px 30px; font-weight: bold; border-radius: 6px; width: 100%; font-size: 18px;'
                             );
                        echo form_submit('confirmar', 'Confirmar', $btn_style); 
                     ?> 
                </div>
            </div>
        </div>

    </div>
    <?php echo form_close(); ?>
</div>
<br>

