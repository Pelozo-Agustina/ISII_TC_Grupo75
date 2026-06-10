<!-- inicio banner -->
				<section class="banner-area" id="home">	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-start">
						<font size="7" color="white">
							Bebidas Calientes
						</font>
				    </div>
				</div>
			</section>		
			<!-- Fin banner -->
      
		<!-- About Generic Start -->
    <div class="main-wrapper">
      
			<!-- Catalogo -->
<div class="cards-container">

    <!-- Producto 1: Cappuccino -->
    <div class="custom-card">
        <div class="card-image">
            <img src="assets/img/cappuccino.jpg" alt="Cappuccino">
        </div>
        <div class="card-body">
            <h2 class="card-title">Cappuccino</h2>
            <p class="card-description">Expreso con leche vaporizada de textura cremosa.</p>
            <div class="card-actions">
                <span class="price">$19,50 <del>$24</del></span>
                <a href="<?php echo base_url('login');?>" class="genric-btn primary-border circle">Comprar</a>
            </div>
        </div>
    </div>

    <!-- Producto 2: Coffee Latte -->
    <div class="custom-card">
        <div class="card-image">
            <img src="assets/img/latte.jpg" alt="Coffee Latte">
        </div>
        <div class="card-body">
            <h2 class="card-title">Coffee Latte</h2>
            <p class="card-description">Mezcla de espresso cubierta de leche vaporizada.</p>
            <div class="card-actions">
                <span class="price">$41 <del>$43</del></span>
                <a href="<?php echo base_url('login');?>" class="genric-btn primary-border circle">Comprar</a>
            </div>
        </div>
    </div>

    <!-- Producto 3: Coffee Americano -->
    <div class="custom-card">
        <div class="card-image">
            <img src="assets/img/americano.jpg" alt="Coffee Americano">
        </div>
        <div class="card-body">
            <h2 class="card-title">Coffee Americano</h2>
            <p class="card-description">Mezcla de agua caliente y café procesado en una máquina de Expreso.</p>
            <div class="card-actions">
                <span class="price">$30 <del>$35,10</del></span>
                <a href="<?php echo base_url('login');?>" class="genric-btn primary-border circle">Comprar</a>
            </div>
        </div>
    </div>

    <!-- Producto 4: Espresso -->
    <div class="custom-card">
        <div class="card-image">
            <img src="assets/img/espresso.jpg" alt="Espresso">
        </div>
        <div class="card-body">
            <h2 class="card-title">Espresso</h2>
            <p class="card-description">Bebida corta con carácter, intensa y concentrada.</p>
            <div class="card-actions">
                <span class="price">$35 <del>$37,50</del></span>
                <a href="<?php echo base_url('login');?>" class="genric-btn primary-border circle">Comprar</a>
            </div>
        </div>
    </div>

    <!-- Producto 5: Mocca -->
    <div class="custom-card">
        <div class="card-image">
            <img src="assets/img/mocca.jpg" alt="Mocca">
        </div>
        <div class="card-body">
            <h2 class="card-title">Mocca</h2>
            <p class="card-description">Intensa combinación de café y chocolate.</p>
            <div class="card-actions">
                <span class="price">$32,10 <del>$36</del></span>
                <a href="<?php echo base_url('login');?>" class="genric-btn primary-border circle">Comprar</a>
            </div>
        </div>
    </div>

    <!-- Producto 6: Macchiato -->
    <div class="custom-card">
        <div class="card-image">
            <img src="assets/img/macchiato.jpg" alt="Macchiato">
        </div>
        <div class="card-body">
            <h2 class="card-title">Macchiato</h2>
            <p class="card-description">Un expreso con una pequeña cantidad de leche caliente y espumada.</p>
            <div class="card-actions">
                <span class="price">$30,10 <del>$34,90</del></span>
                <a href="<?php echo base_url('login');?>" class="genric-btn primary-border circle">Comprar</a>
            </div>
        </div>
    </div>

    <!-- Producto 7: Ristretto -->
    <div class="custom-card">
        <div class="card-image">
            <img src="assets/img/ristretto.png" alt="Ristretto">
        </div>
        <div class="card-body">
            <h2 class="card-title">Ristretto</h2>
            <p class="card-description">Café expreso hecho con la cantidad normal de café molido.</p>
            <div class="card-actions">
                <span class="price">$40 <del>$45</del></span>
                <a href="<?php echo base_url('login');?>" class="genric-btn primary-border circle">Comprar</a>
            </div>
        </div>
    </div>

    <!-- Producto 8: Affogato -->
    <div class="custom-card">
        <div class="card-image">
            <img src="assets/img/affogato.jpg" alt="Affogato">
        </div>
        <div class="card-body">
            <h2 class="card-title">Affogato</h2>
            <p class="card-description">Helado cubierto o "ahogado" con un trago de espresso caliente.</p>
            <div class="card-actions">
                <span class="price">$41,30 <del>$44</del></span>
                <a href="<?php echo base_url('login');?>" class="genric-btn primary-border circle">Comprar</a>
            </div>
        </div>
    </div>

    <!-- Producto 9: Piccolo Latte -->
    <div class="custom-card">
        <div class="card-image">
            <img src="assets/img/piccolo-latte.jpg" alt="Piccolo Latte">
        </div>
        <div class="card-body">
            <h2 class="card-title">Piccolo Latte</h2>
            <p class="card-description">Inyección de Ristretto cubierta con leche.</p>
            <div class="card-actions">
                <span class="price">$35,40 <del>$39,20</del></span>
                <a href="<?php echo base_url('login');?>" class="genric-btn primary-border circle">Comprar</a>
            </div>
        </div>
    </div>

</div>

</div>

<style>
  /* Contenedor principal de las tarjetas */
.cards-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  padding: 40px 20px;
  max-width: 1200px;
  margin: 0 auto;
}

/* Tarjeta individual */
.custom-card {
  background-color: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
}

/* Efecto hover al pasar el cursor */
.custom-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Contenedor de la imagen */
.card-image {
  width: 100%;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fcfcfc;
  padding: 20px;
}

.card-image img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

/* Cuerpo de la tarjeta */
.card-body {
  padding: 20px;
  text-align: center;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

/* Título del producto */
.card-title {
  font-size: 1.4rem;
  font-weight: 700;
  color: #333333;
  margin-bottom: 10px;
}

/* Descripción */
.card-description {
  font-size: 0.95rem;
  color: #666666;
  line-height: 1.5;
  margin-bottom: 20px;
  flex-grow: 1; /* Hace que todas las descripciones ocupen el mismo espacio vertical */
}

/* Acciones y precios */
.card-actions {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  margin-top: auto;
}

/* Estilo de precios */
.price {
  font-size: 1.3rem;
  font-weight: bold;
  color: #27ae60; /* Color verde para el precio activo */
}

.price del {
  font-size: 1rem;
  color: #999999;
  margin-left: 8px;
}
</style>