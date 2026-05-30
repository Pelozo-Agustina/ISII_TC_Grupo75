<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Carrito_model extends CI_Model {

	/*
    * Constructor de la clase
    */
    public function __construct() {
        parent::__construct();
    }
       
	public function insert_venta($data)
	{
		//$this->db es SIEMPRE la misma instancia (Singleton de CI)
		$this->db->insert('ventas_cabecera', $data);
		$id = $this->db->insert_id(); //ID de la cabecera recien creada
		return (isset($id)) ? $id : FALSE;
	}
	
	public function insert_ventas_detalle($data)
	{
		//MISMA instancia: garantiza coherencia con insert_venta()
		$this->db->insert('ventas_detalle', $data);
	}
       
}

