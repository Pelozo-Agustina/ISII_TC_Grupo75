<?php if (!$productos) { ?>

	<div class="container">
		<div class="well">
			<h1>No hay Para Acompañar</h1>
		</div>	
	</div>

<?php } else { ?>

<div class="container-fluid">
	
	<h2 class="text-center">Para Acompañar</h2>

	<!--<hr>-->

	<div class="row text-center">
		<?php foreach($productos->result() as $row){ ?>
			<div class="col-md-6 col-sm-6 col-lg-4 col-xl-3 hero-feature">
				<div class="thumbnail">

				<div class="cards">
                   <div class="card">
					<img src="<?php echo base_url($row->imagen); ?>" alt="" class="imgRedonda">

					<div class="card-title">
					<!--<div class="caption">-->
						<center><h4><?php echo trim($row->descripcion); ?></h4>

						<p>
							<?php 
								if ($row->stock < $row->stock_min && $row->stock > 0) {
									echo 'Por debajo del valor minimo: '.$row->stock_min;
								} elseif ($row->stock == 0) {
									echo 'No hay unidades disponibles';
								}else {
									echo 'Disponible:'.$row->stock.' unidades';
								}
							?>
						</p>

						<p>Precio: $ <?php echo $row->precio_venta; ?> </p>

						<p>
						<?php 
							if (($row->stock > 0) && ($session_data = $this->session->userdata('login_in'))) {

								// Envia los datos en forma de formulario para agregar al carrito
		                        echo form_open('carrito_agrega');
		                        echo form_hidden('id', $row->id);
		                        echo form_hidden('descripcion', $row->descripcion);
		                        echo form_hidden('precio_venta', $row->precio_venta);
		                        echo form_hidden('stock', $row->stock);
		            	?>
		                    	<div>
		                <?php
		                        $btn = array(
		                            'class' => 'btn btn-primary',
		                            'value' => 'Comprar',
		                            'name' => 'action'
		                        	);
		                        
		                        echo form_submit($btn);
		                        echo form_close();
		               	?>
		                    	</div>
		               	<?php 

							}
						?>	
						</p></center>
						
					</div>

						</div><!-- cards -->
 				 </div><!-- card -->

				</div>
			</div>
		<?php } ?>	
	</div>
	<hr>
</div>
<?php } ?>

<style>
	.card{
		border-radius: 5px 30px 45px 60px;
		 border-color: #8A4B08;
	}
	.imgRedonda {
    width:300px;
    height:300px;
    border-radius:150px;
    border:5px solid #666;
    border-color: #61380B;
}
</style>