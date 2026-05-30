<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Carrito_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('carrito_model');
		$this->load->model('producto_model');
        $this->load->library('cart');
	}

	private function _veri_log()
    	{
	    	if ($this->session->userdata('login_in')) 
	    	{
	    		return TRUE;
	    	} else {
	    		return FALSE;
	    	}
    	}

	public function index()
	{
		if($this->_veri_log()){
			$data = array('titulo' => 'carrito');
		
			$session_data = $this->session->userdata('login_in');
			$data['perfil_id'] = $session_data['perfil_id'];
			$data['nombre'] = $session_data['nombre'];

			$dat = array('ventas_cabecera' => $this->carrito_model->insert_venta() );

			$this->load->view('partes/head_view', $data);
			$this->load->view('partes/navbar_view',$data);
			$this->load->view('front/muestraproductos', $dat);
			$this->load->view('partes/footer_view');
			}else{
			redirect('login', 'refresh');
			}
	}

	//Este método llama a la página Bebidas Calientes, con el carrito si está logueado
	public function BebidasCalientes()
	{
		$dat = array('productos' => $this->producto_model->get_bebidasCalientes());

		$data = array('titulo' => 'BebidasCaliente');
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];
		
		$this->load->view('partes/head_view', $data);
		$this->load->view('partes/navbar_view', $data);
		if ($session_data) 
		{
			$this->load->view('partes/carritoparte_view' );
		}
		
		$this->load->view('front/BebidasCaliente_Carrito', $dat);
		$this->load->view('partes/footer_view');
	}

	public function BebidasFria()
	{
		$dat = array('productos' => $this->producto_model->get_bebidasFrias());

		$data = array('titulo' => 'BebidasFria');
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];
		
		$this->load->view('partes/head_view', $data);
		$this->load->view('partes/navbar_view', $data);
		if ($session_data) 
		{
			$this->load->view('partes/carritoparte_view' );
		}
		
		$this->load->view('front/BebidasFria_Carrito', $dat);
		$this->load->view('partes/footer_view');
	}

	public function ParaAcom()
	{
		$dat = array('productos' => $this->producto_model->get_paraAcom());

		$data = array('titulo' => 'ParaAcom');
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];
		
		$this->load->view('partes/head_view', $data);
		$this->load->view('partes/navbar_view', $data);
		if ($session_data) 
		{
			$this->load->view('partes/carritoparte_view' );
		}
		
		$this->load->view('front/ParaAcom_Carrito', $dat);
		$this->load->view('partes/footer_view');
	}


		
	//Agrega elemento al carrito
	function añadirCarrito()
{
    // Genera array para insertar en el carrito incluyendo el stock dinámico
    $insert_data = array(
        'id'    => $this->input->post('id'),
        'name'  => $this->input->post('descripcion'),
        'price' => $this->input->post('precio_venta'),
        'qty'   => 1,
        'stock' => $this->input->post('stock') // <--- AGREGAMOS ESTA LÍNEA
    );	

    // Inserta elemento al carrito
    $this->cart->insert($insert_data);
      
    // Redirige a la misma página que se encuentra
    header('Location: '.$_SERVER['HTTP_REFERER']);
}

	
	//Elimina elemento del carrito o el carrito entero
	function eliminarCarrito($rowid) {
        //Si $rowid es "all" destruye el carrito
		if ($rowid==="all")
		{
			$this->cart->destroy();
		}
		else //Sino destruye sola fila seleccionada
		{ 
			$data = array(
				'rowid'   => $rowid,
				'qty'     => 0
				);
            // Actualiza los datos
			$this->cart->update($data);
		}
		
        // Redirige a la misma página que se encuentra
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}
	

	//Actualiza el carrito que se muestra
function actualiza_carrito()
{        
    $cart_info = $_POST['cart'];

    if (!empty($cart_info)) {
        // PRIMERA PASADA: Validamos que todas las cantidades sean correctas antes de guardar nada
        foreach ($cart_info as $id => $cart) {
            $qty = (int)$cart['qty'];

            if ($qty <= 0) {
                // Si encuentra una cantidad inválida, guarda el mensaje de error y detiene todo
                $this->session->set_flashdata('error_carrito', 'Ingrese una cantidad mayor al 0 por favor.');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                return; // Corta la ejecución de la función inmediatamente
            }
            
            /* 
            NOTA SOBRE EL STOCK: 
            Para verificar el stock real de tu Base de Datos, aquí deberías consultar tu modelo, por ejemplo:
            
            $stock_disponible = $this->Producto_model ->obtener_stock($cart['id']);
            if ($qty > $stock_disponible) {
                $this->session->set_flashdata('error_carrito', 'No hay suficiente stock para el producto seleccionado.');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                return;
            }
            */
        }

        // SEGUNDA PASADA: Si todo está perfecto, procedemos a actualizar el carrito de forma normal
        foreach ($cart_info as $id => $cart) {	
            $rowid = $cart['rowid'];
            $price = $cart['price'];
            $qty = (int)$cart['qty'];
        
            $data = array(
                'rowid' => $rowid,
                'price' => $price,
                'qty'   => $qty
            );
             
            $this->cart->update($data);
        }
    }

    // Redirige a la misma página limpia
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}




	//Muestra los detalles de la venta y confirma(función realizar_venta())
	function mostrar_venta()
	{
		$data = array('titulo' => 'Confirmar Venta');
		
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];
		$data['apellido'] = $session_data['apellido'];
		$data['email'] = $session_data['email'];
		
		$this->load->view('partes/head_view', $data);
		$this->load->view('partes/navbar_view', $data);
		$this->load->view('front/Venta', $data);
		$this->load->view('partes/footer_view');
    }
    

    //Guarda los datos de la venta en la base de datos    
    public function realizar_venta()
{
    $session_data = $this->session->userdata('login_in');
    $data['id'] = $session_data['id'];

    $total = $this->input->post('total_venta');

    $venta = array(
        'fecha' 		=> date('Y-m-d'),
        'usuario_id' 	=> $data['id'],
         'subtotal'      => $total,
        'total_venta'	=> $total
    );	
    //Paso 1: insertar venta - usa $this->db (Patron Singleton)
    $venta_id = $this->carrito_model->insert_venta($venta);
    
    if ($cart = $this->cart->contents()):
    	//Paso 2: Insertar cada item del carrito - MISMA coexion
        foreach ($cart as $item):
            $ventas_detalle = array(
                'cabecera_id' 	=> $venta_id,  //ID generado en paso 1
                'producto_id' 	=> $item['id'],
                'cantidad' 		=> $item['qty'],
                'precio' 		=> $item['price'],
                'total' 		=> $item['subtotal']
            );	
        
            $cust_id = $this->carrito_model->insert_ventas_detalle($ventas_detalle);

            // Descuenta del stock y lo guarda en la base de datos
            $producto = $this->producto_model->edit_producto($item['id']);
            foreach ($producto->result() as $row) 
            {
                $stock = $row->stock;
            }

            $stock_edit = $stock - $item['qty'];

            $stock_nuevo = array(
                'stock'	=> $stock_edit
            );

            $modifica = $this->producto_model->update_producto($item['id'], $stock_nuevo);

        endforeach;
    endif;
    
    $data = array('titulo' => 'Venta Finalizada');

    $data['perfil_id'] = $session_data['perfil_id'];
    $data['nombre'] = $session_data['nombre'];

    $this->load->view('partes/head_view', $data);
    $this->load->view('partes/navbar_view', $data);
    $this->load->view('front/listarventa');
    $this->load->view('partes/footer_view');

    $final = $this->cart->destroy();
}

	public function mostrarBebidasC()
	{
		$dat = array('productos' => $this->producto_model->get_bebidasCalientes());

		$data = array('titulo' => 'BebidasC');
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];
		
		$this->load->view('partes/head_view', $data);
		$this->load->view('partes/navbar_view', $data);
		$this->load->view('front/muestraBebidasCalientes', $dat);
		$this->load->view('partes/footer_view');
	}

	public function mostrarBebidasF()
	{
		$dat = array('productos' => $this->producto_model->get_bebidasFrias());

		$data = array('titulo' => 'BebidasF');
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];
		
		$this->load->view('partes/head_view', $data);
		$this->load->view('partes/navbar_view', $data);
		$this->load->view('front/muestraBebidasFrias', $dat);
		$this->load->view('partes/footer_view');
	}

	public function mostrarParaAcom()
	{
		$dat = array('productos' => $this->producto_model->get_paraAcom());

		$data = array('titulo' => 'BebidasF');
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];
		
		$this->load->view('partes/head_view', $data);
		$this->load->view('partes/navbar_view', $data);
		$this->load->view('front/muestraParaAcom', $dat);
		$this->load->view('partes/footer_view');
	}

}