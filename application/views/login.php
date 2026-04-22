<!-- inicio banner -->
                <section class="banner-area" id="home"> 
                <div class="container">
                    <div class="row fullscreen d-flex align-items-center justify-content-start">
                        <font size="7" color="white">
                            Iniciar Sesion 
                        </font>
                    </div>
                </div>
            </section>      
            <!-- Fin banner -->

<section class="cid-qWojGWOW0Y mbr-parallax-background mbr-fullscreen "  id="header15-14">

    
<!---->    

    <div class="container align-right">
<div class="row">
    <div class="mbr-white col-lg-8 col-md-7 content-container">
        <h1 class="mbr-section-title mbr-bold pb-3 mbr-fonts-style display-1">Iniciar sesion</h1>
        
    </div>
    <div class="col-lg-4 col-md-5">
    <div class="form-container">
        <div class="media-container-column" data-form-type="formoid">
            <div data-form-alert="" hidden="" class="align-center">Thanks for filling out the form!</div>
            
           <div class="media-container-column" data-form-type="formoid">
            <div data-form-alert="" hidden="" class="align-center">Thanks for filling out the form!</div>
             <?php echo validation_errors(); ?>
			
            <?php echo form_open('verificoUsuario', ['class' => 'form-signin', 'role' => 'form']); ?> <br>
				
				<?php echo form_input(['name' => 'usuario', 
										'id' => 'usuario', 
										'class' => 'form-control',
										'placeholder' => 'Usuario', 
										'required'=>'required', 
										'autofocus'=>'autofocus']); ?>
            <br>
				
				<?php echo form_input(['type' => 'password',
										'name' => 'pass', 
										'id' => 'contrasena', 
										'class' => 'form-control',
										'placeholder' => 'Contraseña', 
										'required'=>'required']); ?> <br>
				
				<?php echo form_submit('submit', 'Iniciar sesión',"class='btn btn-form btn-primary display-4'"); ?> <br>
				
				<?php echo form_close(); ?>
				<br>
        </div>
        </div>
    </div>
    </div>
</div>
    </div>
    <div class="mbr-arrow hidden-sm-down" aria-hidden="true">
        <a href="<?php echo base_url('#next');?>">
            <i class="mbri-down mbr-iconfont"></i>
        </a>
    </div>
</section>
