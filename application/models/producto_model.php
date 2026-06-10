<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
class Producto_model extends CI_Model{
        
    /**
    * Constructor de la clase
    */
     public function __construct() {
        parent::__construct();
        $this->load->database(); // Carga la base de datos de CodeIgniter
    }


    /**
    * Retorna todos los productos
    */
    function get_productos()
    {
        $query = $this->db->get_where('productos');
        
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

/************************************************************************** 
 *                                  Ventas_Cabecera
 **************************************************************************/
     function get_ventas_cabecera() {
        $this->db->select('*');
        $this->db->from('ventas_cabecera');
        $this->db->join('usuarios', 'ventas_cabecera.usuario_id = usuarios.id');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query : FALSE;
    }


    // =========================================================================
    //                    VENTAS - DETALLE  (SP: sp_detalle_venta_completo)
    // =========================================================================

    /**
     * Llama al SP sp_detalle_venta_completo(p_id_cabecera).
     *
     * El SP devuelve DOS result sets:
     *   [0] cabecera + datos del cliente
     *   [1] lineas de detalle con descripcion del producto
     *
     * @param  int $id   id_cabecera
     * @return array  ['cabecera' => object|null, 'detalle' => array de objetos]
     */
    public function sp_detalle_venta($id) {
        $id   = (int) $id;
        $conn = $this->db->conn_id;   // conexion mysqli nativa
        $result = ['cabecera' => null, 'detalle' => []];

        if (mysqli_multi_query($conn, "CALL sp_detalle_venta_completo($id)")) {
            // Primer result set: cabecera con datos del cliente
            if ($rs = mysqli_store_result($conn)) {
                $result['cabecera'] = mysqli_fetch_object($rs) ?: null;
                mysqli_free_result($rs);
            }
            // Segundo result set: lineas de detalle
            if (mysqli_next_result($conn) && ($rs = mysqli_store_result($conn))) {
                while ($row = mysqli_fetch_object($rs)) {
                    $result['detalle'][] = $row;
                }
                mysqli_free_result($rs);
            }
            // Consumir resultados adicionales para no bloquear la conexion
            while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
                if ($rs = mysqli_store_result($conn)) mysqli_free_result($rs);
            }
        }
        return $result;
    }

    /**
     * Mantiene compatibilidad con las vistas que ya usan get_ventas_detalle().
     * Ahora delega al SP internamente.
     *
     * @param  int $id
     * @return array de objetos | FALSE
     */
    function get_ventas_detalle($id) {
        $data = $this->sp_detalle_venta($id);
        return !empty($data['detalle']) ? $data['detalle'] : FALSE;
    }

    // =========================================================================
    //                    REPORTE DE VENTAS  (SP: sp_reporte_ventas_por_periodo)
    // =========================================================================

    /**
     * Llama al SP sp_reporte_ventas_por_periodo(p_fecha_inicio, p_fecha_fin).
     * Devuelve resumen agrupado por dia: cantidad_ventas, total_dia, promedio_venta.
     *
     * @param  string $fecha_inicio  'YYYY-MM-DD'
     * @param  string $fecha_fin     'YYYY-MM-DD'
     * @return array de objetos (vacio si no hay datos)
     */
    public function sp_reporte_ventas($fecha_inicio, $fecha_fin) {
        $fi    = $this->db->escape($fecha_inicio);
        $ff    = $this->db->escape($fecha_fin);
        $query = $this->db->query("CALL sp_reporte_ventas_por_periodo($fi, $ff)");
        return ($query && $query->num_rows() > 0) ? $query->result() : [];
    }


    // =========================================================================
    //                    STOCK BAJO  (SP: sp_productos_stock_bajo)
    // =========================================================================

    /**
     * Llama al SP sp_productos_stock_bajo().
     * Retorna productos donde stock <= stock_min y eliminado = 'NO'.
     * Campos: id, descripcion, categoria, stock_actual, stock_minimo, unidades_faltantes.
     *
     * @return array de objetos (vacio si todo esta bien)
     */
    public function sp_stock_bajo() {
        $query = $this->db->query("CALL sp_productos_stock_bajo()");
        return ($query && $query->num_rows() > 0) ? $query->result() : [];
    }


/************************************************************************** 
 *                                  Reservas
 **************************************************************************/
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

    // Solo registros con estado 'Confirmada'
    $this->db->where('r.estado_reserva', 'Confirmada');

    $query = $this->db->get();
    return ($query->num_rows() > 0) ? $query : FALSE;
}


// Funcion que maneja el estado de las reservas
public function toggle_estado_reserva($id) {
    // 1. CORRECCIÓN: Traemos tanto hora_inicio como hora_fin de la tabla horario
    $this->db->select('r.*, h.hora_inicio, h.hora_fin');
    $this->db->from('reservas r');
    $this->db->join('horario h', 'h.id_horario = r.id_horario', 'left');
    $this->db->where('r.id_reserva', $id);
    $query = $this->db->get();

    if ($query->num_rows() == 0) {
        return FALSE;
    }

    $fila           = $query->row();
    $estado_actual  = $fila->estado_reserva;
    $fecha_reserva  = $fila->fecha_reserva;   // 'YYYY-MM-DD'
    $hora_fin       = $fila->hora_fin;         // 'HH:MM:SS'

    // 2. Si ya está Confirmada, no se hace nada
    if ($estado_actual === 'Confirmada') {
        return FALSE;
    }

    // 3. CORRECCIÓN: Forzar la hora de Argentina antes de usar time()
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    // 4. Si el turno ya terminó por completo (tolerancia por si llega tarde), no se puede confirmar
    $hora_limite = isset($hora_fin) ? $hora_fin : "23:59:59";
    $datetime_fin  = strtotime($fecha_reserva . ' ' . $hora_limite);
    
    if (time() >= $datetime_fin) {
        return FALSE;
    }

    // 5. Si está pendiente y el turno no ha terminado -> se confirma
    $this->db->where('id_reserva', $id);
    return $this->db->update('reservas', array('estado_reserva' => 'Confirmada'));
}



/**
 * No lo utilizamos ya que aplicamos patron State

public function toggle_estado_reserva($id) {
    // 1. Consultamos el registro en la base de datos
    $this->db->where('id_reserva', $id);
    $query = $this->db->get('reservas');
    
    // CONTROL CRÍTICO DE SEGURIDAD: Verificamos si realmente existe el registro
    if ($query->num_rows() == 0) {
        return FALSE; // Evita el Error 500 si la reserva no existe
    }

    $reserva = $query->row();

    // 2. Definimos el nuevo estado de forma segura
    $nuevo_estado = ($reserva->estado_reserva == 'Pendiente') ? 'Confirmada' : 'Pendiente';
    
    // 3. Actualizamos el registro
    $this->db->where('id_reserva', $id);
    return $this->db->update('reservas', array('estado_reserva' => $nuevo_estado));
}*/


//Verificamos las mesas ocupadas
public function verificar_mesa_ocupada($fecha, $id_horario, $id_mesa){
    $this->db->where('fecha_reserva', $fecha);
    $this->db->where('id_horario', $id_horario);
    $this->db->where('id_mesa', $id_mesa);
    
    // Solo contamos las reservas que están activas (ignora canceladas o expiradas)
    $this->db->where_in('estado_reserva', array('Confirmada', 'Pendiente'));

    $query = $this->db->get('reservas');

    if ($query->num_rows() > 0) {
        return TRUE; // La mesa ya está ocupada en ese día y turno
    }
    return FALSE; // La mesa está libre
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

    // =========================================================================
    //              RESERVAS - CONSULTAS POR SP  (SP4 / SP5)
    // =========================================================================

    /**
     * Llama al SP sp_reservas_por_fecha(p_fecha).
     * Devuelve todas las reservas de un dia con datos de usuario, mesa y turno.
     *
     * @param  string $fecha  'YYYY-MM-DD'
     * @return array de objetos
     */
    public function sp_reservas_por_fecha($fecha) {
        $f     = $this->db->escape($fecha);
        $query = $this->db->query("CALL sp_reservas_por_fecha($f)");
        return ($query && $query->num_rows() > 0) ? $query->result() : [];
    }

    /**
     * Llama al SP sp_resumen_reservas_por_estado(p_fecha_inicio, p_fecha_fin).
     * Devuelve cantidad de reservas agrupadas por estado en el periodo dado.
     *
     * @param  string $fecha_inicio  'YYYY-MM-DD'
     * @param  string $fecha_fin     'YYYY-MM-DD'
     * @return array de objetos
     */
    public function sp_resumen_reservas($fecha_inicio, $fecha_fin) {
        $fi    = $this->db->escape($fecha_inicio);
        $ff    = $this->db->escape($fecha_fin);
        $query = $this->db->query("CALL sp_resumen_reservas_por_estado($fi, $ff)");
        return ($query && $query->num_rows() > 0) ? $query->result() : [];
    }

}/***/
// =========================================================================
// PATRÓN STATE — Estado de Producto (Activo / Inactivo)
// =========================================================================

interface EstadoProducto {
    public function estaActivo(): bool;
    public function puedeVenderse(): bool;
    public function getNombre(): string;
    public function getLabelCss(): string;
}

class ProductoActivo implements EstadoProducto {

    public function estaActivo(): bool {
        return true;  // aparece en el catálogo y puede venderse
    }

    public function puedeVenderse(): bool {
        return true;  // se puede agregar al carrito
    }

    public function getNombre(): string {
        return 'Activo';
    }

    public function getLabelCss(): string {
        return 'elim-no';  // clase CSS verde/gris para el badge
    }
}

class ProductoInactivo implements EstadoProducto {

    public function estaActivo(): bool {
        return false;  // no aparece en el catálogo público
    }

    public function puedeVenderse(): bool {
        return false;  // no se puede agregar al carrito
    }

    public function getNombre(): string {
        return 'Inactivo';
    }

    public function getLabelCss(): string {
        return 'elim-si';  // clase CSS rojo para el badge
    }
}


//