<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Agregar Nuevo Usuario
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->

<center>
	<section class="menu-area section-gap" id="AltaUsuario">
				<div class="container"> 
<!--<div class="container1">-->
	<div class="well col-lg-12">
		<h2>Cargar Nuevo Usuario</h2>
	</div>
	
	<div class="row">
		<div class="col-lg-12">

			<?php echo validation_errors(); ?>
			<!-- Genero el formulario para cargar un producto -->

			<div class="well bs-component form-horizontal">
				<?php echo form_open_multipart('verificoUsuario/1', 
									['class' => 'form-group', 'role' => 'form', 'id' => 'form_usuario']); ?>
				<fieldset>
					<div class="form-group">
						<label class="col-lg-2 control-label">Nombre</label>
						<div class="col-lg-10"> 
							<?php echo form_input(['name' => 'nombre', 
													'id' => 'nombre', 
													'class' => 'form-control',
													'placeholder' => 'Nombre', 
													'autofocus'=>'autofocus',
													'value'=>set_value('nombre')]); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Apellido</label>
						<div class="col-lg-10">
							<?php echo form_input(['name' => 'apellido', 
													'id' => 'apellido', 
													'class' => 'form-control',
													'placeholder' => 'apellido', 
													'value'=>set_value('apellido')]); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Email</label>
						<div class="col-lg-10">
							<?php echo form_input(['name' => 'email', 
													'id' => 'email', 
													'class' => 'form-control',
													'placeholder' => 'email', 
													'value'=>set_value('email')]); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">usuario</label>
						<div class="col-lg-10">
							<?php echo form_input(['name' => 'usuario', 
													'id' => 'usuario', 
													'class' => 'form-control',
													'placeholder' => 'usuario',
													'value'=>set_value('usuario')]); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Contraseña</label>
						<div class="col-lg-10">
							<?php echo form_input(['name' => 'pass', 
													'id' => 'pass', 
													'class' => 'form-control',
													'placeholder' => 'Contraseña','value' =>set_value('pass')]); ?>
						</div>
					</div>
					<div class="col-lg-3 col-lg-offset-5">
						<?php echo form_submit('submit', 'Cargar',"class='btn btn-lg btn-primary btn-block'"); ?> <br>
						<?php echo form_close(); ?>
					</div>
				</fieldset>
				
			</div>
		</div>
	</div>
</div>
</section>  
</center>

<style>
	.container1{  
		  border: 15px SteelBlue dashed;
		  border-radius: 10px 5px 10px 5px;
	}
</style>  
