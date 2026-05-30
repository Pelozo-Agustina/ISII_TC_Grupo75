			  <header id="header" id="home">
				<div class="header-top">
		  			<div class="container">
				  		<div class="row justify-content-end">
				  			<div class="col-lg-8 col-sm-4 col-8 header-top-right no-padding">
				  				<ul>
				  					<li>
				  						Lunes - Viernes: 8 am  a  2 pm -  5 pm  a  8:30 pm
				  					</li>
				  					<li>
				  						Sabado - Domingo: 11 am a 8 pm
				  					</li>
				  					<li>
				  						<a href="<?php echo base_url('tel:(0379) 498 5236');?>">tel:(0379) 498 5236 </a>
				  					</li>		  					
				  				</ul>
				  			</div>
				  		</div>			  					
		  			</div>
				</div>			  	
			    <div class="container">
			    	<div class="row align-items-center justify-content-between d-flex">
				      <div id="logo">
				        <a href="<?php echo base_url('#home');?>"><img src="assets/img/logo.png" alt="logo" title="" /></a>
				      </div>
				      <nav id="nav-menu-container">

      <?php $session_data = $this->session->userdata('login_in')?>
				      	<!-- MENU PARA ADMINISTRADOR -->
 <?php if(($this->session->userdata('login_in')) and ($session_data['perfil_id']=='1') ) {?>
    			<ul class="nav-menu">
				          <li class="menu-active"><a href="<?php echo base_url('#home');?>">Inicio</a></li>
				          <li class="menu-has-children"><a href="<?php echo base_url('#');?>">Productos</a>
				          	<ul>
				          		<li><a href="<?php echo base_url('cargar_producto');?>">Agregar Producto</a></li>
				          		<li><a href="<?php echo base_url('productos_todos');?>">Modificar Producto</a></li>
				          		<li><a href="<?php echo base_url('productos_todos');?>">Eliminar Producto</a></li>
				          		<li><a href="<?php echo base_url('productos_eliminados');?>">Productos Eliminados</a></li>
				          		<li><a href="<?php echo base_url('productos_eliminados');?>">Activar Productos</a></li>
				          		<li><a href="<?php echo base_url('productos_todos');?>">Todos los Productos</a></li>
				          	</ul>
				          </li>
				          <li class="menu-has-children"><a href="<?php echo base_url('#');?>">Reportes</a>
				          	<ul>
				              <li><a href="<?php echo base_url('ventas');?>">Ventas</a></li>
				              <li><a href="<?php echo base_url('consultas');?>">Consultas</a></li>
				            </ul>
				          </li>

				          <!--Reservas-->
				          <li class="menu-has-children"><a href="<?php echo base_url('#');?>">Reservas</a>
				          	<ul>
				          		<!--
				              <li><a href="<?php echo base_url('reservasCanceladas');?>">Reservas Canceladas</a></li>
				          		-->
				              <li><a href="<?php echo base_url('reservasConfirmadas');?>">Reservas Confirmadas</a>
				              <li><a href="<?php echo base_url('muestraReservas');?>">Todas las Reservas</a></li>
				            </ul>
				          </li>

				          <li class="menu-has-children"><a href="<?php echo base_url('#');?>">Usuarios</a>
				          	<ul>
				          		<li><a href="<?php echo base_url('registro');?>">Agregar Usuarios</a></li>
				          		<li><a href="<?php echo base_url('usuarios_eliminados');?>">Activar Usuarios</a></li>
				              <li><a href="<?php echo base_url('usuarios_eliminados');?>">Usuarios Eliminados</a></li>
				              <li><a href="<?php echo base_url('usuario_todos');?>">Todos los Usuarios</a></li>
				            </ul>
				          </li>
				         	<li class="menu-has-children"><a href="<?php echo base_url('#');?>">Stock</a>
				            <ul>
				              <li><a href="<?php echo base_url('BebidasC');?>">Bebidas Caliente</a></li>
				              <li><a href="<?php echo base_url('BebidasF');?>">Bebidas Frias</a></li>
				              <li><a href="<?php echo base_url('ParaA');?>">Para Acompañar</a></li>
				            </ul>
				        </li>
				   <li class="menu-has-children" ><a href="<?php echo base_url('#');?>"><b>Administrador <?= $session_data['nombre'] ?></b></a>
				   <ul>
                    <li><a href="<?php echo base_url('cerrarSesion');?>">SALIR</a></li>
				  </ul>
                </li>
				    </ul>

	<?php $session_data = $this->session->userdata('login_in')?>
		<!-- MENU PARA CLIENTES -->
<?php } else if (($this->session->userdata('login_in')) and ($session_data['perfil_id'] =='2') ) {?>
    		<ul class="nav-menu">
				          <li class="menu-active"><a href="<?php echo base_url('#home');?>">Inicio</a></li>
				          <li><a href="<?php echo base_url('#coffee');?>">Coffee</a></li>
				          <li><a href="<?php echo base_url('#review');?>">Contacto</a></li>
				          <li><a href="<?php echo base_url('#blog');?>">Comercializacion</a></li>
				          <li class="menu-has-children"><a href="<?php echo base_url('#');?>">Catalogo</a>
				            <ul>
				            	
				              <li><a href="<?php echo base_url('BebidasCalientes');?>">Bebidas Caliente</a></li>
				              <li><a href="<?php echo base_url('BebidasFrias');?>">Bebidas Frias</a></li>
				              <li><a href="<?php echo base_url('ParaAcom');?>">Para Acompañar</a></li>
				            </ul>
				        </li>
						<li><a href="<?php echo base_url('realizar_reserva');?>">Realizar Reserva</a></li>
						

				        <li class="menu-has-children"><a href="<?php echo base_url('#');?>">Mi Cuenta</a>
				        	<ul>
				              <li class="menu-has-children"><a href="<?php echo base_url('visualizardatos');?>">Visualizar Datos</a></li>
				            </ul>
				        </li>
				   <li class="menu-has-children" ><a href="<?php echo base_url('#');?>"><b>Bienvenido <?= $session_data['nombre'] ?></b>
       				 </a>
				   <ul>
                    <li><a href="<?php echo base_url('cerrarSesion');?>">SALIR</a></li>
				  </ul>
                </li>
				    </ul>
				    <!-- MENU PARA PUBLICO EN GENERAL -->
             <?php } else {?>

				      <!-- mi navbar -->
				        <ul class="nav-menu">
				          <li class="menu-active"><a href="<?php echo base_url('#home');?>">Inicio</a></li>
				          <li><a href="<?php echo base_url('#about');?>">Historia</a></li>
				          <li><a href="<?php echo base_url('#coffee');?>">Coffee</a></li>
				          <li><a href="<?php echo base_url('#review');?>">Contacto</a></li>
				          <li><a href="<?php echo base_url('#blog');?>">Comercializacion</a></li>
				          <li class="menu-has-children"><a href="<?php echo base_url('#');?>">Catalogo</a>
				            <ul>
				              <li><a href="<?php echo base_url('BebidasCaliente');?>">Bebidas Caliente</a></li>
				              <li><a href="<?php echo base_url('BebidasFria');?>">Bebidas Frias</a></li>
				              <li><a href="<?php echo base_url('ParaAcomp');?>">Para Acompañar</a></li>
				            </ul>
				        </li>
				            <li class="menu-has-children"><a href="<?php echo base_url('#');?>">Ingresar</a>
				              <ul>
				            	<li><a href="<?php echo base_url('registro');?>">Registrarse</a></li>
				            	<li><a href="<?php echo base_url('login');?>">Iniciar Sesion</a></li>
				            	</ul>
				            </li>   
				        </ul>
				        <?php }?>
				      </nav><!-- #nav-menu-container -->
			    	</div>
			    </div>	
			  </header>