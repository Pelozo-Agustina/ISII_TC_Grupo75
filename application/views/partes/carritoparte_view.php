<!-- inicio banner -->
<section class="banner-area" id="home"> 
    <div class="container">
        <div class="row fullscreen d-flex align-items-center justify-content-start">
            <font size="7" color="white">Productos</font>
        </div>
    </div>
</section>      
<!-- Fin banner -->

<section class="menu-area section-gap" id="Carrito">
<div class="container-fluid" id="carrito">
    <div class="cart">
        <div class="heading">
            <h2 id="h2" align="center">Productos en tu Carrito</h2>
        </div>
        
        <!-- MENSAJE DE ALERTA: Si el controlador detecta un número inválido o falta de stock -->
        <?php if($this->session->flashdata('error_carrito')): ?>
            <div class="alert alert-danger" role="alert" style="text-align: center; max-width: 500px; margin: 20px auto;">
                <strong>¡Atención!</strong> <?php echo $this->session->flashdata('error_carrito'); ?>
            </div>
        <?php endif; ?>

        <div class="text" align="center"> 
            <?php  
            $cart_check = $this->cart->contents();
            if (empty($cart_check)) {
                echo 'Para agregar productos al carrito, click en "Agregar al Carrito"';
            }  
            ?>    
        </div>
        
        <table class="table" border="0" cellpadding="5px" cellspacing="1px">
            <?php if ($cart = $this->cart->contents()): ?>
                <tr id="main_heading">
                    <td>ID</td>
                    <td>Descripcion</td>
                    <td>Precio</td>
                    <td>Cantidad</td>
                    <td>Total</td>
                    <td>Cancelar Producto</td>
                </tr>

                <?php 
                echo form_open('carrito_actualiza');
                $gran_total = 0;
                $i = 1;

                foreach ($cart as $item):
                    echo form_hidden('cart[' . $item['id'] . '][id]', $item['id']);
                    echo form_hidden('cart[' . $item['id'] . '][rowid]', $item['rowid']);
                    echo form_hidden('cart[' . $item['id'] . '][name]', $item['name']);
                    echo form_hidden('cart[' . $item['id'] . '][price]', $item['price']);
                    
                    $gran_total = $gran_total + $item['subtotal'];
                ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td>$ <?php echo number_format($item['price'], 2); ?></td>
                        <td>
    <!-- Permitimos el teclado pero vigilamos la entrada en tiempo real con oninput -->
    <input type="number" 
           name="cart[<?php echo $item['id']; ?>][qty]" 
           value="<?php echo $item['qty']; ?>" 
           min="1" 
           max="<?php echo $item['stock']; ?>" 
           style="text-align: right; width: 80px;" 
           class="form-control" 
           oninput="validarStockEnTiempoReal(this)">
</td>
                        <td>$ <?php echo number_format($item['subtotal'], 2) ?></td>
                        <td> 
                            <?php 
                                $path = '<img src= '. base_url('assets/img/carrito.jpg') . ' width="50px" height="50px">';
                                echo anchor('carrito_elimina/' . $item['rowid'], $path); 
                            ?>
                        </td>
                    </tr>
                <?php 
                endforeach; 
                ?>
                    
                <tr>
                    <td>
                        <b>Total: $
                            <?php echo number_format($gran_total, 2); ?>
                        </b>
                    </td> 
                    <td colspan="5" align="right">
                        <!-- Botón Borrar Carrito -->
                        <input type="button" class='btn btn-primary btn-lg' value="Borrar Carrito" onclick="borra_carrito()">
                        
                        <!-- Botón Actualizar (Ahora procesa la validación) -->
                        <input type="submit" class='btn btn-primary btn-lg' value="Actualizar">
                        
                        <!-- Botón Confirmar Orden (Ahora llama a una función de validación segura) -->
                        <!-- Botón Confirmar Orden corregido -->
                        <input type="button" class='btn btn-primary btn-lg' value="Confirmar Orden" onclick="window.location = 'venta'">

                    </td>
                </tr>
                <?php echo form_close();
            endif; ?>
        </table>
    </div>
</div>
</section>

<!-- SCRIPT DE VALIDACIÓN DE SEGURIDAD -->
<script>
function validarStockEnTiempoReal(input) {
    var valor = parseInt(input.value);
    var max = parseInt(input.getAttribute('max'));
    var min = parseInt(input.getAttribute('min')) || 1;

    if (input.value === "") {
        return;
    }

    // Si escribe un número menor o igual a 0
    if (valor < min) {
        input.value = min;
        alert("Ingrese una cantidad mayor al 0 por favor.");
    } 
    // Si excede el stock máximo permitido
    else if (valor > max) {
        input.value = max;
        alert("Lo sentimos, la cantidad ingresada supera el stock disponible. Máximo permitido: " + max + " unidades.");
    }
}
</script>

<br>
