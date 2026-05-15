<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class Producto_model extends CI_Model{
		
	/**
    * Constructor de la clase
    */
    public function __construct() {
        parent::__construct();
    }

    /**
    * Retorna todos los productos
    */
    function get_productos()
    {
        $query = $this->db->get_where('productos', array('eliminado' => 'NO'));
        
        if($query->num_rows()>0) {
            return $query;
        } else {
            return FALSE;
        }        
    }

    /**
    * Retorna todos las bebidas calientes
    */
    function get_BebidasCalientes()
    {
        $query = $this->db->get_where('productos', array('eliminado' => 'NO', 'categoria_id' => '1'));
        
        if($query->num_rows()>0) {
            return $query;
        } else {
            return FALSE;
        }        
    }

    /**
    * Retorna todos las bebidas frias
    */
    function get_bebidasFrias()
    {
        $query = $this->db->get_where('productos', array('eliminado' => 'NO', 'categoria_id' => '2'));
        
        if($query->num_rows()>0) {
            return $query;
        } else {
            return FALSE;
        }        
    }

    function get_paraAcom()
    {
        $query = $this->db->get_where('productos', array('eliminado' => 'NO', 'categoria_id' => '3'));
        
        if($query->num_rows()>0) {
            return $query;
        } else {
            return FALSE;
        }        
    }

    /**
    * Inserta un producto
    */
    public function add_producto($data){
        $this->db->insert('productos', $data);
    }

    /**
    * Retorna todos los datos de un producto
    */
    function edit_producto($id){

        $query = $this->db->get_where('productos', array('id' => $id),1);
                
        if($query->num_rows() == 1) {
            return $query;
        } else {
            return FALSE;
        }
    }

    /**
    * Actualiza los datos de un producto
    */
    function update_producto($id, $data){
        $this->db->where('id', $id);
        $query = $this->db->update('productos', $data);
        if($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    * Eliminación y activación logica de un producto
    */
    function estado_producto($id, $data){
        $this->db->where('id', $id);
        $query = $this->db->update('productos', $data);
        if($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    * Retorna todos los productos inactivos
    */
    function not_active_productos(){
        $query = $this->db->get_where('productos', array('eliminado' => 'SI'));
        if($query->num_rows()>0) {
            return $query;
        } else {
            return FALSE;
        }        
    }

    //
    function get_ventas_cabecera(){
        $this->db->select('*');
        $this->db->from('ventas_cabecera');
        $this->db->join('usuarios', 'ventas_cabecera.usuario_id = usuarios.id') ;   

        $query = $this->db->get();
        //select * from ventas_cabecera;
       // $query = $this->db->get('ventas_cabecera', 'usuarios.nombre','usuarios.apellido');
       
        if($query->num_rows()>0) {
            return $query;
        } else {
            return FALSE;
        }
    }
    
        function get_ventas_detalle($id){
        $this->db->join('productos','productos.id = ventas_detalle.producto_id');   

        //select * from ventas_detalle;
        $query = $this->db->get_where('ventas_detalle', array('venta_id' => $id));
       
          
        if($query->num_rows()>0) {
            return $query;
        } else {
            return FALSE;
        }
    }


//reservas
function get_muestra_reserva() {
    // Seleccionamos datos de reserva, nombre de usuario, descripción de mesa y horas
    $this->db->select('r.*, u.nombre, u.apellido, m.descripcion as nombre_mesa, m.capacidad, h.hora_inicio, h.hora_fin');
    $this->db->from('reservas r');
    
    // Join con Usuarios
    $this->db->join('usuarios u', 'u.id = r.id_usuario', 'left');
    
    // Join con Mesas usando 'id_mesa'
    $this->db->join('mesas m', 'm.id_mesa = r.id_mesa', 'left');
    
    // Join con Horario usando 'id_horario'
    $this->db->join('horario h', 'h.id_horario = r.id_horario', 'left');

    $query = $this->db->get();
    return ($query->num_rows() > 0) ? $query : FALSE;
}


 /** Estado confirmación reserva */
public function get_reservas_confirmadas() {
    // Seleccionamos los campos necesarios
    $this->db->select('r.*, u.nombre, u.apellido, m.descripcion as nombre_mesa, m.capacidad, h.hora_inicio, h.hora_fin');
    $this->db->from('reservas r');
    
    // Joins para obtener información de otras tablas
    $this->db->join('usuarios u', 'u.id = r.id_usuario', 'left');
    $this->db->join('mesas m', 'm.id_mesa = r.id_mesa', 'left');
    $this->db->join('horario h', 'h.id_horario = r.id_horario', 'left');

    // FILTRO CRUCIAL: Solo registros con estado 'Confirmada'
    // Asegúrate de que 'Confirmada' coincida exactamente con el valor en tu base de datos
    $this->db->where('r.estado_reserva', 'Confirmada');

    $query = $this->db->get();
    return ($query->num_rows() > 0) ? $query : FALSE;
}





/** Alterna el estado entre Confirmada y Pendiente */
public function toggle_estado_reserva($id) {
    // 1. Primero consultamos el estado actual de esa reserva
    $this->db->where('id_reserva', $id);
    $query = $this->db->get('reservas');
    $reserva = $query->row();

    // 2. Definimos el nuevo estado (el opuesto al que tiene)
    $nuevo_estado = ($reserva->estado_reserva == 'Pendiente') ? 'Confirmada' : 'Pendiente';
    
    // 3. Actualizamos
    $this->db->where('id_reserva', $id);
    return $this->db->update('reservas', array('estado_reserva' => $nuevo_estado));
}




/** Actualiza el estado_reserva 
public function update_estado_confirmar($id) {
    $data = array(
        'estado_reserva' => 'Confirmada' 
    );

    $this->db->where('id_reserva', $id); 
    // Asegúrate de que aquí también diga 'reservas'
    return $this->db->update('reservas', $data);
}*/




        /** Cancelacion y activación logica de una reserva
    
    function estado_reserva($id, $data){
        $this->db->where('id_reserva', $id);
        $query = $this->db->update('reservas', $data);
        if($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    //cancelacion de recervas
  public function not_active_reservas() {
    $this->db->select('*');
    $this->db->from('reserva'); 
    
    // IMPORTANTE: Asegúrate que 'estado_reserva' es el nombre real en tu BD
    // y que el valor sea el que usas para cancelar (ej: 'Cancelada' o 'Liberada')
    $this->db->where('estado_reserva', 'Cancelada'); 
    
    $query = $this->db->get();

    // Verificación de seguridad para evitar Error 500
    if ($query && is_object($query) && $query->num_rows() > 0) {
        return $query->result();
    }
    
    return array(); // Devuelve un array vacío si no hay datos o falla la consulta



    public function cancelar_reserva($id) {
    // Definimos los datos a actualizar (ajusta 'estado_reserva' si se llama distinto)
    $data = array(
        'estado_reserva' => 'Cancelado' 
    );

    // Llamamos a la función que ya tienes en tu modelo
    if ($this->tu_modelo_name->estado_reserva($id, $data)) {
        // Mensaje de éxito que aparecerá en la pantalla
        $this->session->set_flashdata('success', 'La reserva se ha cancelado correctamente.');
    } else {
        $this->session->set_flashdata('error', 'No se pudo cancelar la reserva.');
    }

    // Redirigimos de vuelta a la tabla
    redirect('Coffee/muestraReservas');*/



}
    


