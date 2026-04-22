<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Modificar Usuario
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->


<div class="container1">
<div class="col-sm-10 col-md-10">           
	<?php echo form_open_multipart("verifico_modificausuario/$id", ['class' => 'form-signin', 'role' => 'form']); ?>
		<div class="row">
	   		<div class="col-md-6">
				<div class="form-group">
					<?php echo form_label('nombre:', 'nombre'); ?>
					<?php echo form_input(['name' => 'nombre', 
													'id' => 'nombre', 
													'class' => 'form-control',
													'placeholder' => 'Nombre', 
													'autofocus'=>'autofocus',
													'value'=>"$nombre"]); ?>
					<?php echo form_error('nombre'); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?php echo form_label('apellido:', 'apellido'); ?>	
					<?php echo form_input(['name' => 'apellido', 
													'id' => 'apellido', 
													'class' => 'form-control',
													'placeholder' => 'apellido', 
													'value'=>"$apellido"]); ?>
					<?php echo form_error('apellido'); ?>
				</div>
			</div>
		</div>
		<div class="row">
	   		<div class="col-md-6">
				<div class="form-group">
					<?php echo form_label('email:', 'email'); ?>
					<?php echo form_input(['name' => 'email', 
													'id' => 'email', 
													'class' => 'form-control',
													'placeholder' => 'email', 
													'value'=>"$email"]); ?>
					<?php echo form_error('email'); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?php echo form_label('usuario:', 'usuario'); ?>
					<?php echo form_input(['name' => 'usuario', 
													'id' => 'usuario', 
													'class' => 'form-control',
													'placeholder' => 'usuario',
													'value'=>"$usuario"]); ?>
					<?php echo form_error('usuario'); ?>
				</div>
			</div>
		</div>
		<div class="row">
	   		<div class="col-md-6">
				<div class="form-group">
					<?php echo form_label('pass:', 'pass'); ?>
					<?php echo form_input(['name' => 'pass', 
													'id' => 'pass', 
													'class' => 'form-control',
													'placeholder' => 'pass',
													'value'=>"$pass"]); ?>
					<?php echo form_error('pass'); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<?php echo form_submit('modificar', 'Modificar',"class='btn btn-lg btn-warning btn-block'"); ?>
				</div>		
			</div>
		</div>
	<?php echo form_close(); ?>
</div>
</div>

<style>
	.container1{  
		  border: 15px SteelBlue dashed;
		  border-radius: 10px 5px 10px 5px;
	}
</style>