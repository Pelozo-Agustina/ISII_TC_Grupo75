<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

// ─── PASO 1: INTERFAZ Y ESTRATEGIAS DE REDIRECCIÓN ────────────────
interface LoginStrategy {
    public function redirigir();
}

class AdminLoginStrategy implements LoginStrategy {
    public function redirigir() {
        redirect('productos_todos', 'refresh');
    }
}

class ClienteLoginStrategy implements LoginStrategy {
    public function redirigir() {
        redirect('Welcome', 'refresh');
    }
}

// ─── CONTEXTO: CONTROLADOR DE LOGIN ──────────────────────────────
class LoginController extends CI_Controller {

    // MAPA ASOCIATIVO DE PERFILES
    private $estrategias = [
        1 => 'AdminLoginStrategy',
        2 => 'ClienteLoginStrategy',
    ];

    public function __construct() 
    {
        parent::__construct();
        // CORRECCIÓN: Carga explícita de librerías y modelos necesarios
        $this->load->library(['form_validation', 'session']);
        $this->load->helper('url');
        $this->load->model('loginModel');    
    }

    public function index()
    {   
        // Reglas de validación
        $this->form_validation->set_rules('usuario', 'Usuario', 'trim|required');
        $this->form_validation->set_rules('pass', 'Contraseña', 'trim|required|callback__valid_login');
        
        // Mensajes de error
        $this->form_validation->set_message('required', 'El campo %s es requerido');
        $this->form_validation->set_message('_valid_login', 'El usuario o contraseña son incorrectos');
        
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        
        if ($this->form_validation->run() == FALSE)
        {   
            // CORRECCIÓN: Orden lógico de carga de vistas (Estructura HTML correcta)
            $data = array('titulo' => 'Error de datos o Login');
            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view');
            $this->load->view('login');
            $this->load->view('partes/footer_view');
        }
        else 
        {
            // PASO 3: EJECUCIÓN DEL PATRÓN STRATEGY
            $session_data = $this->session->userdata('login_in');
            $perfil       = $session_data['perfil_id'] ?? 2; // Default a cliente si no existe
            
            $clase        = $this->estrategias[$perfil] ?? 'ClienteLoginStrategy';
            
            $strategy     = new $clase();
            $strategy->redirigir();
        }
    }

    public function _valid_login($pass)
    { 
        $usuario = $this->input->post('usuario');
        $result = $this->loginModel->validarUsuario($usuario, $pass);

        if($result)
        {   
            $sess_array = array();
            foreach($result as $row)
            {
                // CORRECCIÓN: Eliminamos 'pass' por seguridad. No se guarda en sesión.
                $sess_array = array(
                    'id'        => $row->id,
                    'nombre'    => $row->nombre,
                    'apellido'  => $row->apellido,
                    'email'     => $row->email,
                    'perfil_id' => $row->perfil_id,
                    'usuario'   => $row->usuario
                );
                                    
                $this->session->set_userdata('login_in', $sess_array);
            }
            return TRUE;
        }
        else 	
        {	
            return FALSE;
        }
    }
    
    public function login()
    {
        // Verificar si el usuario está logueado antes de mostrar la vista
        if (!$this->session->userdata('login_in')) {
            redirect('LoginController/index');
        }

        $session_data = $this->session->userdata('login_in');
        $data['titulo'] = 'Login Exitoso';
        $data['perfil_id'] = $session_data['perfil_id'];
        $data['nombre'] = $session_data['nombre'];

        // Estructura ordenada de vistas
        $this->load->view('partes/head_view', $data);
        $this->load->view('partes/navbar_view', $data);
        $this->load->view('login');
        $this->load->view('partes/footer_view');
    }	
    
    public function cerrar_sesion()
    {
        $this->session->sess_destroy();
        redirect(base_url('Welcome'));		
    }	
}