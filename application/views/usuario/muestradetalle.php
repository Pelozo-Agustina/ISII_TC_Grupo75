 <section class="mbr-section article content12 cid-qRlf4ndxBK" id="content12-m">
       <div class="container">
	<div class="well">
	<br>       
     <?php if (!$ventas_detalle) { ?>

	<div class="container">
		<div class="well">
			<br><br>
			<center><h1>No se realizaron Ventas</h1></center>
            <hr>
		</div>
		<center><?php  { ?>
            <a type="button" class="btn btn-success" href="<?php echo base_url('ventas'); ?>">Volver</a>
            <?php } ?></center>
	</div>

<?php } else { ?>                  
<div class="container mt-5 pt-5">
	<div class="well">
        <br>
		<center><h1><b>Detalle de Ventas</b></h1></center>
        <hr>
	</div>	
	<br>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>id producto </th>
              	<th>Descripción</th>
				<th>Cantidad</th>
				<th>Precio Unitario</th>
				<th>Precio Costo</th>
				<th>Precio Venta</th>
				<th>Ganancias</th>
				<th>Sub Total</th>
				
			</tr>
		</thead>
		<tbody>
			<?php foreach($ventas_detalle->result() as $row){ ?>

			<tr>
                <td><?php echo $row->id_detalle;  ?></td>
				<td><?php echo $row->descripcion;  ?></td>
				<td><?php echo $row->cantidad;  ?></td>
				<td><?php echo $row->precio_venta;  ?></td>
				<td><?php echo $row->precio_costo;	?></td>
				<td><?php echo $row->precio_venta;	?></td>
				<td><?php echo ($row->precio_venta - $row->precio_costo) * $row->cantidad;	?></td>
                <td><?php echo $row->precio * $row->cantidad; ?></td>

			</tr>
           
            
			<?php } ?>
		</tbody>
	</table>
	<center><?php  { ?>
            <a type="button" class="btn btn-success" href="<?php echo base_url('ventas'); ?>">Volver</a>
            <?php } ?></center>
		<?php } ?>
</div>	            
	 <br>
</div>
</div>
</section>




