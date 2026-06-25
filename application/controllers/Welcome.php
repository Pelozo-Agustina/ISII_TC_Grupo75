<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data = array('tuitulo' => 'coffee' );

		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

		$this-> load->view('partes/head_view',$data); //cargamos las vistas
		$this-> load->view('coffee',$data);
		$this-> load->view('partes/navbar_view');
		$this-> load->view('partes/footer_view');
	}
	public function BebidasCaliente()
	{
    	$data = array('tuitulo' => 'BebidasCaliente' );

    	$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

    	$this-> load->view('BebidasCaliente',$data);
		$this-> load->view('partes/head_view',$data); //cargamos las vistas
		$this-> load->view('partes/navbar_view');
		$this-> load->view('partes/footer_view');
	}
	public function BebidasFria(){
    	$data = array('tuitulo' => 'BebidasFria' );

    	$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

    	$this-> load->view('BebidasFria',$data);
		$this-> load->view('partes/head_view',$data); //cargamos las vistas	
		$this-> load->view('partes/navbar_view');
		$this-> load->view('partes/footer_view');
	}
	public function ParaAcom()
	{
    	$data = array('tuitulo' => 'ParaAcom' );

    	$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

		$this-> load->view('ParaAcom',$data);
		$this-> load->view('partes/head_view',$data); //cargamos las vistas
		$this-> load->view('partes/navbar_view');
		$this-> load->view('partes/footer_view');
	}
	public function privacidad()
	{
    	$data = array('tuitulo' => 'privacidad' );

    	$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

    	$this-> load->view('privacidad',$data);
		$this-> load->view('partes/head_view',$data); //cargamos las vistas
		$this-> load->view('partes/navbar_view');	
		$this-> load->view('partes/footer_view');
	}
	public function Condiciones()
	{
    	$data = array('tuitulo' => 'Condiciones' );

    	$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

    	$this-> load->view('Condiciones',$data);
		$this-> load->view('partes/head_view',$data);
		$this-> load->view('partes/navbar_view');
		$this-> load->view('partes/footer_view');
	}


	public function registro()
	{
	    $data['titulo']='registro';

		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];	 

		$data = array('tuitulo' => 'registro' );
		$this-> load->view('registro',$data);
		$this-> load->view('partes/head_view',$data);
		$this-> load->view('partes/navbar_view');
		$this-> load->view('partes/footer_view');
	}

	//Este método llama a la página del login
	public function login(){

		$data['titulo']='login';
		
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

		$data = array('tuitulo' => 'login' );
		$this->load->view('login',$data);
		$this->load->view('partes/head_view',$data);
		$this->load->view('partes/navbar_view');
	    $this->load->view('partes/footer_view');
	}

	public function consultas(){

		$data['titulo']='consultas';
		
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

		$data = array('tuitulo' => 'consultas' );
		$this->load->view('usuario/consultas',$data);
		$this->load->view('partes/head_view',$data);
		$this->load->view('partes/navbar_view');
	    $this->load->view('partes/footer_view');
	}


	public function editar_usuario(){

		$data['titulo']='editar';
		
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

		$data = array('tuitulo' => 'editar' );
		$this->load->view('usuario/editar_usuario',$data);
		$this->load->view('partes/head_view',$data);
		$this->load->view('partes/navbar_view');
	    $this->load->view('partes/footer_view');
	}

	public function eliminar(){

		$data['titulo']='eliminar';
		
		$session_data = $this->session->userdata('login_in');
		$data['perfil_id'] = $session_data['perfil_id'];
		$data['nombre'] = $session_data['nombre'];

		$data = array('tuitulo' => 'eliminar' );
		$this->load->view('usuario/eliminar.php',$data);
		$this->load->view('partes/head_view',$data);
		$this->load->view('partes/navbar_view');
	    $this->load->view('partes/footer_view');
	}

}

