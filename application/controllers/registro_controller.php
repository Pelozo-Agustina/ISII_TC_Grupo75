<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');	
	class registro_controller extends CI_Controller{
		
		function __construct() 
		{
			parent::__construct();
			$this -> load->model('usuario_model');
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
		
		/**
	    * 
	    */
		function index()
		{
			//Genero las reglas de validacion
			$this->form_validation->set_rules('nombre', 'Nombre', 'required');
			$this->form_validation->set_rules('apellido', 'Apellido', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[usuarios.email]');
			/*$this->form_validation->set_rules('username', 'Usuario', 
											'trim|required|xss_clean|is_unique[usuarios.username]');*/
			$this->form_validation->set_rules('usuario', 'Usuario', 
											'trim|required|is_unique[usuarios.usuario]');
			//$this->form_validation->set_rules('password', 'Contraseña','required|xss_clean');
			$this->form_validation->set_rules('pass', 'Contraseña','required');

			$this->form_validation->set_rules('re_password', 'Repetir contraseña', 'required|matches[pass]');

			//Mensaje de error si no pasan las reglas
			$this->form_validation->set_message('required',
										'<div class="alert alert-danger">El campo %s es obligatorio</div>');

			$this->form_validation->set_message('matches',
										'<div class="alert alert-danger">Los contraseña ingresada no coincide</div>');

			$this->form_validation->set_message('is_unique',
										'<div class="alert alert-danger">El campo %s ya existe</div>');

			$pass = $this->input->post('re_password',true);

			//Preparo los datos para guardar en la base, en caso de que pase la validacion
			$data = array(
				'nombre'=>$this->input->post('nombre',true),
				'apellido'=>$this->input->post('apellido',true),
				'email'=>$this->input->post('email',true),
				'perfil_id'=>'2',
				'usuario'=>$this->input->post('usuario',true),
				'pass'=>($pass)
			);

			//Si no pasa la validacion de datos
			if ($this->form_validation->run() == FALSE)
			{
				//Muestra la página de registro con el título de error
				$data = array('titulo' => 'Error de formulario');

				$this->load->view('partes/head_view', $data);
				$this->load->view('partes/navbar_view');
				$this->load->view('registro');
				$this->load->view('partes/footer_view');		
			}
			
			else 	//Pasa la validacion
			{
				//Envio array al metodo insert para registro de datos
				$usuario = $this->usuario_model->add_user($data);

				//Redirecciono a la pagina de perfil
				redirect('login');
			}	
		}

		//usuario

		/**
	    * Muestra formulario para agregar usuario
	    */
		function form_agrega_usuario()  	//Si se modifica, modificar (agrega_producto) tambien
		{
			if($this->_veri_log()){
			$data = array('titulo' => 'Agregar Usuario');
		
			$session_data = $this->session->userdata('login_in');
			$data['perfil_id'] = $session_data['perfil_id'];
			$data['nombre'] = $session_data['nombre'];

			$this->load->view('partes/head_view', $data);
			$this->load->view('partes/navbar_view');
			$this->load->view('front/agregarusuario');
			$this->load->view('partes/footer_view');
			}else{
			redirect('login', 'refresh'); }
		}

		/**
	    * Verifica datos ingresados en el formulario para agregar producto
	    */
		function agrega_usuario()
		{
			//Genero las reglas de validacion
			$this->form_validation->set_rules('nombre', 'nombre', 'required|is_unique[usuarios.nombre]');
			$this->form_validation->set_rules('apellido', 'apellido', 'required');
			$this->form_validation->set_rules('email', 'email', 'required');
			$this->form_validation->set_rules('usuario', 'usuario', 'required');
			$this->form_validation->set_rules('pass', 'pass', 'required');//

			//Mensaje de error si no pasan las reglas
			$this->form_validation->set_message('required',
										'<div class="alert alert-danger">El campo %s es obligatorio</div>');

			$this->form_validation->set_message('is_unique',
										'<div class="alert alert-danger">El campo %s ya existe</div>');

			$this->form_validation->set_message('numeric',
							'<div class="alert alert-danger">El campo %s debe contener un valor numérico</div>');


			if (!$this->form_validation->run())
			{
				$data = array('titulo' => 'Error de formulario');
		
				$session_data = $this->session->userdata('login_in');
				$data['perfil_id'] = $session_data['perfil_id'];
				$data['nombre'] = $session_data['nombre'];


				$this->load->view('partes/head_view', $data);
				$this->load->view('partes/navbar_view',$data);
				$this->load->view('front/agregausuario');
				$this->load->view('partes/footer_view');
			}
		}

		/*function usuario_modifica()
		{
			$id = $this->uri->segment(2);
			$datos_usuarios = $this->usuario_model->edit_usuario($id);

			if ($datos_usuarios != FALSE) {
				foreach ($datos_usuarios->result() as $row) 
				{
					$nombre = $row->nombre;
					$apellido= $row->apellido;
					$email= $row->email;
					$usuario = $row->usuario;
					$pass = $row->pass;
				}

				$dat = array('usuarios' =>$datos_usuarios,
					'id'=>$id,
					'nombre'=>$nombre,
					'apellido'=>$apellido,
					'email'=>$email,
					'usuario'=>$usuario,
					'pass'=>$pass,
				);
			} 
			else 
			{
				return FALSE;
			}
			if($this->_veri_log()){
			$data = array('titulo' => 'Modificar Usuario');
			$session_data = $this->session->userdata('login_in');
			$data['perfil_id'] = $session_data['perfil_id'];
			$data['nombre'] = $session_data['nombre'];

			$this->load->view('partes/head_view', $data);
			$this->load->view('partes/navbar_view',$data);
			$this->load->view('front/modificausuario', $dat);
			$this->load->view('partes/footer_view');
			}else{
			redirect('login', 'refresh');}
		}*/

		/**
	    * Muestra para modificar un usuario
	    */
		function muestra_modifica()
		{
			$id = $this->uri->segment(2);
			$datos_usuarios = $this->usuario_model->edit_usuario($id);

			if ($datos_usuarios != FALSE) {
				foreach ($datos_usuarios->result() as $row) 
				{
					$nombre = $row->nombre;
					$apellido = $row->apellido;
					$email = $row->email;
					$usuario = $row->usuario;
					$pass = $row->pass;
				}

				$dat = array('usuarios' =>$datos_usuarios,
					'id'=>$id,
					'nombre'=>$nombre,
					'apellido'=>$apellido,
					'email'=>$email,
					'usuario'=>$usuario,
					'pass'=>$pass,
				);
			} 
			else 
			{
				return FALSE;
			}
			if($this->_veri_log()){
			$data = array('titulo' => 'Modificar Usuarios');
			$session_data = $this->session->userdata('login_in');
			$data['perfil_id'] = $session_data['perfil_id'];
			$data['nombre'] = $session_data['nombre'];
			$data['id'] = $session_data['id'];

			$this->load->view('partes/head_view', $data);
			$this->load->view('partes/navbar_view',$data);
			$this->load->view('front/modificausuario', $dat);
			$this->load->view('partes/footer_view');
			}else{
			redirect('login', 'refresh');}
		}

		/**
	    * Verifica datos para modificar un usuario
	    */
		function modificar_usuario()
		{
			//Validación del formulario
			$this->form_validation->set_rules('nombre', 'nombre', 'required');
			$this->form_validation->set_rules('apellido', 'apellido', 'required');
			$this->form_validation->set_rules('email', 'email', 'required');
			$this->form_validation->set_rules('usuario', 'usuario', 'required');
			$this->form_validation->set_rules('pass', 'pass', 'required');
			

			//Mensaje del form_validation
			$this->form_validation->set_message('required','<div class="alert alert-danger">El campo %s es obligatorio, al intentar modificar estaba vacio</div>');

			$this->form_validation->set_message('numeric','<div class="alert alert-danger">El campo %s debe contener un valor numérico, al intentar modificar estaba vacio</div>'); 

			$id = $this->uri->segment(2);
			$datos_usuarios = $this->usuario_model->edit_usuario($id);

			$dat = array(
				'id'=>$id,
				'nombre'=>$this->input->post('nombre',true),
				'apellido'=>$this->input->post('apellido',true),
				'email'=>$this->input->post('email',true),
				'usuario'=>$this->input->post('usuario',true),
				'pass'=>$this->input->post('pass',true)
			);

			if ($this->form_validation->run()==FALSE)
			{
				$data = array('titulo' => 'Error de formulario');
				$session_data = $this->session->userdata('login_in');
				$data['perfil_id'] = $session_data['perfil_id'];
				$data['nombre'] = $session_data['nombre'];

				$this->load->view('partes/head_view', $data);
				$this->load->view('partes/navbar_view',$data);
				$this->load->view('front/modificausuario', $dat);
				$this->load->view('partes/footer_view');
			}
			
		}

		function muestra_usuario()
		{
			if($this->_veri_log()){
			$data = array('titulo' => 'Muestra Usuario');
		
			$session_data = $this->session->userdata('login_in');
			$data['perfil_id'] = $session_data['perfil_id'];
			$data['nombre'] = $session_data['nombre'];

			$dat = array('usuarios' => $this->usuario_model->get_usuarios() );

			$this->load->view('partes/head_view', $data);
			$this->load->view('partes/navbar_view',$data);
			$this->load->view('front/muestrausuario', $dat);
			$this->load->view('partes/footer_view');
			}else{
			redirect('login', 'refresh'); }
		}


		/**
		* Obtiene los datos del usuario a eliminar
		*/
	    function eliminar_usuario(){
	    	$id = $this->uri->segment(2); 
	    	$data = array(
	    		'baja'=>'SI'
	    	);

	    	$this->usuario_model->estado_usuario($id, $data);
	    	redirect('usuario_todos', 'refresh');
	    	
	    }

	    /**
		* Obtiene los datos del usuario a activar
		*/
	    function activar_usuario(){
	    	$id = $this->uri->segment(2);
	    	$data = array(
	    		'baja'=>'NO'
	    	);

	    	$this->usuario_model->estado_usuario($id, $data);
	    	redirect('usuario_todos', 'refresh');
	    }

	    /**
		* Usuarios eliminados logicamente
		*/
	    function muestra_eliminados()
	    {    	
	    	if($this->_veri_log()){
	    	$data = array('titulo' => 'Usuarios eliminados');

			$session_data = $this->session->userdata('login_in');
			$data['perfil_id'] = $session_data['perfil_id'];
			$data['nombre'] = $session_data['nombre'];
			
			$dat = array(
		        'usuarios' => $this->usuario_model->not_active_usuario()
			);

			$this->load->view('partes/head_view', $data);
			$this->load->view('partes/navbar_view');
			$this->load->view('front/muestra_eliminados', $dat);
			$this->load->view('partes/footer_view');
			}
			else{
			redirect('login', 'refresh');
		    }
		}

		function visualizardatos()
	    {
			$data = array('titulo' => 'datos');
		
			$session_data = $this->session->userdata('login_in');
			$data['perfil_id'] = $session_data['perfil_id'];
			$data['nombre'] = $session_data['nombre'];
			$data['apellido'] = $session_data['apellido'];
			$data['email'] = $session_data['email'];
			$data['usuario'] = $session_data['usuario'];
			$data['pass'] = $session_data['pass'];

			$this->load->view('partes/head_view',$data);
			$this->load->view('partes/navbar_view',$data);
			$this->load->view('usuario/visualizardatos');
			$this->load->view('partes/footer_view');
         }

	}
/* End of file 
*/