<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class Usuario_model extends CI_Model{

	/**
    * Constructor de la clase
    */
    public function __construct() {
        parent::__construct();
    }

		
	function get_usuarios()
	{
		//$this->db->select('id, nombre, apellido, username');
		$query = $this->db->get('usuarios');

		if($query->num_rows()>0) {
            return $query;
        } else {
            return FALSE;
        }
	}
	

	function add_user($data)
	{
		$this->db->insert('usuarios', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	

	function edit_usuario($id)
	{
		$query = $this->db->get_where('usuarios', array('id' => $id),1);
                
        if($query->num_rows() == 1) {
            return $query;
        } else {
            return FALSE;
        }
	}
	
	function update_usuario($id, $data)
	{
		$this->db->where('id', $id);
        $query = $this->db->update('usuarios', $data);
        if($query) {
            return TRUE;
        } else {
            return FALSE;
        }
	}

	function delete_usuario($id)
	{			
		$this->db->where('id', $id);
		$query = $this->db->delete('usuarios'); 
		return true;	
	}

	//
	/**
    * Eliminación y activación logica de un usuario
    */
	function estado_usuario($id, $data){
        $this->db->where('id', $id);
        $query = $this->db->update('usuarios', $data);
        if($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function estado_consulta($id, $data){
        $this->db->where('id', $id);
        $query = $this->db->update('contacto.datos', $data);
        if($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    * Retorna todos los productos inactivos
    */
    function not_active_usuario()
    {
        $query = $this->db->get_where('usuarios', array('baja' => 'SI'));
        if($query->num_rows()>0) {
            return $query;
        } else {
            return FALSE;
        }        
    }

}