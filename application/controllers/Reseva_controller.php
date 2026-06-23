<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reserva_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargamos el nuevo modelo de reservas asignado
        $this->load->model('Reserva_model');
    }

    private function _veri_log() {
        return $this->session->userdata('login_in') ? TRUE : FALSE;
    }

    /** Muestra el listado de reservas general */
    public function muestra_reserva() {
        if($this->_veri_log()){
            $data = array('titulo' => 'Muestra Reservas');
        
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $dat = array('reservas' => $this->Reserva_model->get_muestra_reserva());

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view', $data);
            $this->load->view('front/muestraReservas', $dat);
            $this->load->view('partes/footer_view');
        } else {
            redirect('login', 'refresh'); 
        }
    }

    /** Muestra exclusivamente reservas confirmadas */
    public function muestraReservasConfirmadas() {      
        if($this->_veri_log()){
            $session_data = $this->session->userdata('login_in');
            
            $data = array(
                'titulo'    => 'Reservas Confirmadas',
                'perfil_id' => $session_data['perfil_id'],
                'nombre'    => $session_data['nombre'],
                'reservas'  => $this->Reserva_model->get_reservas_confirmadas()
            );

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view', $data);
            $this->load->view('front/muestraReservasConfirmadas', $data);
            $this->load->view('partes/footer_view');
        } else {
            redirect('login', 'refresh');
        }
    }

    /** Transición: Confirmar una reserva bajo el patrón State */
    public function confirmarReserva($id) {
        if($this->_veri_log()){
            $fila = $this->db->get_where('reservas', ['id_reserva' => $id])->row();

            if ($fila) {
                try {
                    $reserva = $this->Reserva_model->mapear($fila->id_reserva, $fila->estado_reserva);
                    $reserva->confirmar();
                    $reserva->actualizar_estado_bd();

                    $this->session->set_flashdata('success', 'Reserva confirmada con éxito.');
                    // CORRECCIÓN: Usar la ruta amigable definida en config/routes.php
                    redirect('reservasConfirmadas', 'refresh'); 
                } catch (LogicException $e) {
                    $this->session->set_flashdata('error', $e->getMessage());
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
            } else {
                show_404();
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    /** Transición: Cancelar una reserva bajo el patrón State */
    public function cancelarReserva($id) {
        if($this->_veri_log()){
            $fila = $this->db->get_where('reservas', ['id_reserva' => $id])->row();

            if ($fila) {
                try {
                    $reserva = $this->Reserva_model->mapear($fila->id_reserva, $fila->estado_reserva);
                    $reserva->cancelar();
                    $reserva->actualizar_estado_bd();

                    $this->session->set_flashdata('success', 'La reserva ha sido cancelada.');
                } catch (LogicException $e) {
                    $this->session->set_flashdata('error', $e->getMessage());
                }
            }
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        } else {
            redirect('login', 'refresh');
        }
    }

    /** Formulario y proceso de guardado de reservas */
    public function realizar_reserva() {
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        if($this->_veri_log()){
            $data = array('titulo' => 'Hacer Reservas');
        
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $this->load->library('form_validation');
            $this->form_validation->set_rules('fecha-reserva', 'Fecha', 'required');

            if ($this->form_validation->run() == FALSE) {
                $dat = array('reservas' => $this->Reserva_model->get_muestra_reserva());

                $this->load->view('partes/head_view', $data);
                $this->load->view('partes/navbar_view', $data);
                $this->load->view('front/pagina_reservas', $dat);
                $this->load->view('partes/footer_view');
            } else {
                $fecha_seleccionada = $this->input->post('fecha-reserva'); 
                $id_horario = $this->input->post('opciones-cart');       
                $id_mesa = $this->input->post('id_mesa');
                $fecha_actual = date('Y-m-d');                             

                if ($fecha_seleccionada < $fecha_actual) {
                    $this->session->set_flashdata('error_reserva', 'No puedes seleccionar una fecha que ya ha pasado.');
                    // CORRECCIÓN: Usar la ruta amigable definida en config/routes.php
                    redirect('realizar_reserva', 'refresh');
                    return;
                }

                $horas_inicio = [
                    "1" => "08:30:00", "2" => "10:30:00", "3" => "12:30:00", "4" => "17:00:00", "5" => "19:00:00"
                ];
                $hora_inicio_turno = isset($horas_inicio[$id_horario]) ? $horas_inicio[$id_horario] : "00:00:00";

                if (strtotime($fecha_seleccionada . ' ' . $hora_inicio_turno) < time()) {
                    $this->session->set_flashdata('error_reserva', 'El turno seleccionado para el día de hoy ya ha expirado.');
                    // CORRECCIÓN: Usar la ruta amigable definida en config/routes.php
                    redirect('realizar_reserva', 'refresh');
                    return;
                }

                if ($this->Reserva_model->verificar_mesa_ocupada($fecha_seleccionada, $id_horario, $id_mesa)) {
                    $this->session->set_flashdata('error_reserva', 'La mesa seleccionada ya se encuentra reservada para ese día y horario.');
                    // CORRECCIÓN: Usar la ruta amigable definida en config/routes.php
                    redirect('realizar_reserva', 'refresh');
                    return;
                }

                $insert_data = array(
                    'id_usuario'     => $session_data['id'], 
                    'fecha_reserva'  => $fecha_seleccionada,
                    'id_mesa'        => $id_mesa,
                    'id_horario'     => $id_horario,
                    'estado_reserva' => 'Pendiente'
                );
                $this->db->insert('reservas', $insert_data); 

                $dat = array(
                    'reservas' => $this->Reserva_model->get_muestra_reserva(),
                    'reserva_exitosa' => TRUE, 
                    'mesa_exitosa' => $id_mesa,
                    'fecha_exitosa' => $fecha_seleccionada
                );

                $this->load->view('partes/head_view', $data);
                $this->load->view('partes/navbar_view', $data);
                $this->load->view('front/pagina_reservas', $dat); 
                $this->load->view('partes/footer_view');
            }
        } else {
            redirect('login', 'refresh'); 
        }
    }
}
