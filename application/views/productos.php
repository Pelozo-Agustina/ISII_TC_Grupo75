<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Registro Productos
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->

<?php 

	if(!empty($_POST))
	{

		$alert = '';
		if(empty($_POST['id']) || empty($_POST['descripcion']) || empty($_POST['categoria_id']) || empty($_POST['precio_costo']) || empty($_POST['precio_venta']) || empty($_POST['stock']) || empty($_POST['stock_min'])){
			$alert='<p class"msg_error">Todos los campos son Obligatorios.</p>';
		}
		else{
			$descripcion =$_POST['descripcion'];
			$categoria_id =$_POST['categoria_id'];
			$precio_costo =$_POST['precio_costo'];
			$precio_venta =$_POST['precio_venta'];
			$stock =$_POST['stock'];
			$stock_min =$_POST['stock_min'];

			$query_insert = mysqli_query($conection, "INSERT INTO datos VALUES('$descripcion','categoria_id,'$precio_costo','$precio_venta','stock','stock_min')");
			if($query_insert){
				$alert='<p class="msg_save">Provedor guardado correctamente.</p>';
			}else{
				$alert='<p class="msg_error"Error al guardar el provedor.</p>';
			}
		}
		mysql_close($conection);

	}
 ?>

 	<section id="container">
 		<div class="form-register">
 		<h1><i class="fas fa-cubes"></i> Registro de Productos</h1>
 		<hr>
 		<div class="alert"><?php echo isset($alert) ? $alert : '';?></div>
	<form action="" method="POST" enctype="multipart/form-data">  
			<label for="categoria_id">Categoria</label>  
			  <select name="categoria_id" id="categoria_id">
			  		<option value="1">Bebidas Calientes</option>
			  </select>
            <label for="descripcion">descripcion</label> 
                <input type="text1" size=36 REQUIRED name="descripcion" placeholder="descripcion"></input><br>
            <label for="precio_costo">Precio Costo</label>
                <input type="text" size=36 REQUIRED name="precio_costo" placeholder="precio de costo"></input><br>
            <label for="precio_venta"> Precio Venta &nbsp;</label>
                <input type="precio_venta" size=36 REQUIRED name="precio_venta" placeholder="precio de venta"></input><br>
            <label for="stock">Stock</label> 
                <input type="number" size=36 REQUIRED name="stock" placeholder="stock"></input><br>
            <label for="stock_min">Stock minimo </label>
                 <input type="number" size=36 REQUIRED name="stock_min" placeholder="stock minimo"></input><br>
            <button type="submit" class="bnt_save"><i class="far fa-save fa-lg"></i>Guardar Producto</button>
                              </input>
         </form>

     </div>
     </section>

