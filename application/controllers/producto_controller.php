<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Producto_controller extends CI_Controller{
        
        function __construct() 
        {
            parent::__construct();
        $this->load->model('producto_model');
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
        * Muestra todos los productos en tabla
        */
        function index()
        {
            if($this->_veri_log()){
            $data = array('titulo' => 'productos');
        
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $dat = array('productos' => $this->producto_model->get_productos() );

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view');
            $this->load->view('front/muestraproductos', $dat);
            $this->load->view('partes/footer_view');
            }else{
            redirect('login', 'refresh'); }
        }

        
        /**
        * Muestra todas las Bebidas calientes 
        */
        function muestra_BebidasCalientes()
        {
            if($this->_veri_log()){
            $data = array('titulo' => 'BebidasCaliente');
        
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $dat = array('productos' => $this->producto_model->get_bebidasCalientes() );

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view', $data);
            $this->load->view('front/muestraBebidasCalientes', $dat);
            $this->load->view('partes/footer_view');
            }else{
            redirect('login', 'refresh'); }
        }

        
        /**
        * Muestra todas las Bebbidas Frias
        */
        function muestra_BebidasFrias()
        {
            if($this->_veri_log()){
            $data = array('titulo' => 'BebidasFria');
        
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $dat = array('productos' => $this->producto_model->get_bebidasFrias() );

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view');
            $this->load->view('front/muestraBebidasFrias', $dat);
            $this->load->view('partes/footer_view');
            }else{
            redirect('login', 'refresh'); }
        }
        
        /***/

        function muestra_ParaAcom()
        {
            if($this->_veri_log()){
            $data = array('titulo' => 'ParaAcom');
        
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $dat = array('productos' => $this->producto_model->get_paraAcom() );

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view');
            $this->load->view('front/muestraParaAcom', $dat);
            $this->load->view('partes/footer_view');
            }else{
            redirect('login', 'refresh'); }
        }
        

        /**
        * Muestra formulario para agregar producto
        */
        function form_agrega_producto()     //Si se modifica, modificar (agrega_producto) tambien
        {
            if($this->_veri_log()){
            $data = array('titulo' => 'Agregar Producto');
        
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view');
            $this->load->view('front/agregaproducto');
            $this->load->view('partes/footer_view');
            }else{
            redirect('login', 'refresh'); }
        }

        function agrega_producto()
{
    $this->load->library('form_validation');
    $this->load->helper('form');

    // 1. Reglas de validación (Incluyendo el Callback para la imagen)
    $this->form_validation->set_rules('descripcion', 'Descripción', 'required|is_unique[productos.descripcion]');
    $this->form_validation->set_rules('categoria_id', 'Categoría', 'required|numeric');
    $this->form_validation->set_rules('precio_costo', 'Precio Costo', 'required|numeric');
    $this->form_validation->set_rules('precio_venta', 'Precio Venta', 'required|numeric');
    $this->form_validation->set_rules('stock', 'Stock', 'required|numeric');
    $this->form_validation->set_rules('stock_min', 'Stock Mínimo', 'required|numeric');
    
    // Aquí vinculamos la subida de imagen a la validación
    $this->form_validation->set_rules('filename', 'Imagen', 'callback__image_upload');

    // 2. Mensajes de error estándar
    $this->form_validation->set_message('required', '<div class="alert alert-danger">El campo %s es obligatorio</div>');
    $this->form_validation->set_message('is_unique', '<div class="alert alert-danger">El dato ingresado en el campo %s ya existe</div>');
    $this->form_validation->set_message('numeric', '<div class="alert alert-danger">El campo %s debe contener un valor numérico</div>');

    // 3. Evaluación del formulario
    if ($this->form_validation->run() == FALSE)
    {
        // SI FALLA: Se muestran todos los errores juntos (incluyendo el de la imagen)
        $data = array('titulo' => 'Error de formulario o campos vacíos');
        
        $session_data = $this->session->userdata('login_in');
        $data['perfil_id'] = $session_data['perfil_id'];
        $data['nombre'] = $session_data['nombre'];

        $this->load->view('partes/head_view', $data);
        $this->load->view('partes/navbar_view');
        $this->load->view('front/agregaproducto', $data); 
        $this->load->view('partes/footer_view');
    }
    else
    {
        // SI PASA: Recuperamos el nombre de la imagen que guardamos temporalmente en la sesión
        $imagen_subida = $this->session->userdata('imagen_temporal');
        $this->session->unset_userdata('imagen_temporal'); // Limpiamos la sesión

        $datos_producto = array(
            'descripcion'  => $this->input->post('descripcion'),
            'categoria_id' => $this->input->post('categoria_id'),
            'precio_costo' => $this->input->post('precio_costo'),
            'precio_venta' => $this->input->post('precio_venta'),
            'stock'        => $this->input->post('stock'),
            'stock_min'    => $this->input->post('stock_min'),
            'imagen'       => $imagen_subida,
            'eliminado'    => 'NO' 
        );

        $this->load->model('Producto_model'); 
        $this->Producto_model->add_producto($datos_producto); 
        
        // REDIRECCIÓN CORREGIDA: Apunta al alias de tu archivo routes.php
        redirect('productos_todos');
    }
}

        
        /**
        * Obtiene los datos del archivo imagen.
        * Permite archivos gif, jpg, png
        * Verifica si los datos son correcto en conjunto con la imagen y lo inserta en la tabla correspondiente
        * En la tabla guarda la URL de donde se encuentra la imagen.
        */
        function _image_upload()
{
    if (empty($_FILES['filename']['name'])) {
        $this->form_validation->set_message('_image_upload', '<div class="alert alert-danger">La imagen del producto es obligatoria.</div>');
        return FALSE;
    }

    // Apuntamos a la carpeta correcta utilizando el punto (.) para arrancar desde la raíz de tu proyecto 'Coffee'
    $config['upload_path']   = './assets/img/'; 
    $config['allowed_types'] = 'gif|jpg|jpeg|png';
    $config['max_size']      = '2048'; // 2MB máximo

    // Cargamos e inicializamos la librería de subidas de CodeIgniter
    $this->load->library('upload');
    $this->upload->initialize($config);

    if (!$this->upload->do_upload('filename')) {
        // Si el servidor rechaza el archivo por otra razón, capturamos el motivo real
        $error_msg = $this->upload->display_errors('', '');
        $this->form_validation->set_message('_image_upload', '<div class="alert alert-danger">Error al procesar la imagen: ' . $error_msg . '</div>');
        return FALSE;
    } else {
        // Si la subida es exitosa, guardamos los datos del archivo
        $upload_data = $this->upload->data();
        $this->session->set_userdata('imagen_temporal', $upload_data['file_name']);
        return TRUE;
    }
}

        /**
        * Muestra para modificar un producto
        */
        function muestra_modificar()
        {
            $id = $this->uri->segment(2);
            $datos_producto = $this->producto_model->edit_producto($id);

            if ($datos_producto != FALSE) {
                foreach ($datos_producto->result() as $row) 
                {
                    $descripcion = $row->descripcion;
                    $categoria_id = $row->categoria_id;
                    $imagen = $row->imagen;
                    $precio_costo = $row->precio_costo;
                    $precio_venta = $row->precio_venta;
                    $stock = $row->stock;
                    $stock_min = $row->stock_min;   
                }

                $dat = array('productos' =>$datos_producto,
                    'id'=>$id,
                    'descripcion'=>$descripcion,
                    'categoria_id'=>$categoria_id,
                    'imagen'=>$imagen,
                    'precio_costo'=>$precio_costo,
                    'precio_venta'=>$precio_venta,
                    'stock'=>$stock,
                    'stock_min'=>$stock_min
                );
            } 
            else 
            {
                return FALSE;
            }
            if($this->_veri_log()){
            $data = array('titulo' => 'Modificar Producto');
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $this->load->view('partes/head_view');
            //$this->load->view('partes/navbar_view');
            $this->load->view('front/modificaproducto', $dat);
            $this->load->view('partes/footer_view');
            }else{
            redirect('login', 'refresh');}
        }

        /**
        * Verifica datos para modificar un producto
        */
        function modificar_producto()
        {
            //Validación del formulario
            $this->form_validation->set_rules('descripcion', 'Descripcion', 'required');
            $this->form_validation->set_rules('categoria_id', 'Categoria', 'required');
            $this->form_validation->set_rules('precio_costo', 'Precio Costo', 'required|numeric');
            $this->form_validation->set_rules('precio_venta', 'Precio Venta', 'required|numeric');
            $this->form_validation->set_rules('stock', 'Stock', 'required|numeric');
            $this->form_validation->set_rules('stock_min', 'Stock Minimo', 'required|numeric');
            

            //Mensaje del form_validation
            $this->form_validation->set_message('required','<div class="alert alert-danger">El campo %s es obligatorio, al intentar modificar estaba vacio</div>');

            $this->form_validation->set_message('numeric','<div class="alert alert-danger">El campo %s debe contener un valor numérico, al intentar modificar estaba vacio</div>'); 

            $id = $this->uri->segment(2);
            $datos_producto = $this->producto_model->edit_producto($id);

            foreach ($datos_producto->result() as $row) 
            {
                $imagen = $row->imagen;
            }

            $dat = array(
                'id'=>$id,
                'descripcion'=>$this->input->post('descripcion',true),
                'categoria_id'=>$this->input->post('categoria_id',true),
                'imagen'=>$imagen,
                'precio_costo'=>$this->input->post('precio_costo',true),
                'precio_venta'=>$this->input->post('precio_venta',true),
                'stock'=>$this->input->post('stock',true),
                'stock_min'=>$this->input->post('stock_min',true)
            );

            if ($this->form_validation->run()==FALSE)
            {
                $data = array('titulo' => 'Error de formulario');
                $session_data = $this->session->userdata('login_in');
                $data['perfil_id'] = $session_data['perfil_id'];
                $data['nombre'] = $session_data['nombre'];

                $this->load->view('partes/head_view', $data);
                $this->load->view('partes/navbar_view',$data);
                $this->load->view('front/modificaproducto', $dat);
                $this->load->view('partes/footer_view');
            }
            else
            {
                $this->_image_modif();      
            }
            
        }

        /**
        * Obtiene los datos del archivo imagen.
        * Permite archivos gif, jpg, png
        * Verifica si los datos son correcto en conjunto con la imagen y lo inserta en la tabla correspondiente
        * Si el campo imagen se encuentra vacio asume que la imagen no fue moficado.
        * En la tabla guarda la URL de donde se encuentra la imagen.
        */
        
        function _image_modif()
        {
            //Cargo la libreria para subir archivos
            $this->load->library('upload');

            // Obtengo el id del libro
            $id = $this->uri->segment(2);

            // Array de datos para obtener datos de libros sin la imagen 
            $dat = array(
                'id'=>$id,
                'descripcion'=>$this->input->post('descripcion',true),
                'categoria_id'=>$this->input->post('categoria_id',true),
                'precio_costo'=>$this->input->post('precio_costo',true),
                'precio_venta'=>$this->input->post('precio_venta',true),
                'stock'=>$this->input->post('stock',true),
                'stock_min'=>$this->input->post('stock_min',true)
            );

            // Si la iamgen esta vacia se asume que no se modifica
            if (!empty($_FILES['filename']['name']))
            {            
                // Especifica la configuración para el archivo
                $config['upload_path'] = 'assets/img/productos';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';

                $config['max_size'] = '6048';
                $config['max_width']  = '6024';
                $config['max_height']  = '4768';       

                // Inicializa la configuración para el archivo 
                $this->upload->initialize($config);

                if ($this->upload->do_upload('filename'))
                {
                        // Mueve archivo a la carpeta indicada en la variable $data
                    $data = $this->upload->data();

                        // Path donde guarda el archivo..
                    $url ="assets/img/productos/".$_FILES['filename']['name'];

                        // Agrego la imagen si se modifico.  
                    $dat['imagen']=$url;

                        // Actualiza datos del libro
                    $this->producto_model->update_producto($id, $dat);
                    redirect('productos_todos', 'refresh');
                }
                else
                {
                        //Mensaje de error si no existe imagen correcta
                    $imageerrors = '<div class="alert alert-danger">El campo %s es incorrecta, extención incorrecto o excede el tamaño permitido que es de: 2MB </div>';
                    $this->form_validation->set_message('_image_modif',$imageerrors );
                    return false;
                } 
            }
            else
            {
                $this->producto_model->update_producto($id, $dat);
                redirect('productos_todos', 'refresh');
            }
        }


        /**
        * Obtiene los datos del producto a eliminar
        */
        function eliminar_producto(){
            $id = $this->uri->segment(2); 
            $data = array(
                'eliminado'=>'SI'
            );

            $this->producto_model->estado_producto($id, $data);
            redirect('productos_todos', 'refresh');
        }

        /**
        * Obtiene los datos del producto a activar
        */
        function activar_producto(){
            $id = $this->uri->segment(2);
            $data = array(
                'eliminado'=>'NO'
            );

            $this->producto_model->estado_producto($id, $data);
            redirect('productos_todos', 'refresh');
        }

        /**
        * Productos eliminados logicamente
        */
        function muestra_eliminados()
        {       
            if($this->_veri_log()){
            $data = array('titulo' => 'Productos eliminados');
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];
            
            $dat = array(
                'productos' => $this->producto_model->not_active_productos()
            );

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view');
            $this->load->view('front/muestraeliminados', $dat);
            $this->load->view('partes/footer_view');
            }else{
            redirect('login', 'refresh');}
        }
        
/**************************************************************************                                 Ventas
 **************************************************************************/
        function listar_ventas()
        { 
             if($this->_veri_log()){
            $data = array('titulo' => 'ventas');
        
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $this->db->order_by('id_cabecera', 'ASC');

            $dat = array('ventas_cabecera' => $this->producto_model->get_ventas_cabecera());

            $this->load->view('partes/head_view',$data);
            $this->load->view('partes/navbar_view',$data);
            $this->load->view('usuario/muestraventas',$dat);
            $this->load->view('partes/footer_view');
            }else{
            redirect('login', 'refresh');
            }
         }
        
        
        /**
         * Detalle de una venta — usa SP sp_detalle_venta_completo.
         * Pasa a la vista:
         *   $cabecera       → objeto con datos de la venta y del cliente
         *   $ventas_detalle → array de objetos con lineas de detalle
         */
        function muestra_detalle($id)
        {
            if ($this->_veri_log()) {
                $session_data = $this->session->userdata('login_in');

                $data = array(
                    'titulo'    => 'Detalle',
                    'perfil_id' => $session_data['perfil_id'],
                    'nombre'    => $session_data['nombre'],
                );

                // Llamada al SP: devuelve ['cabecera' => obj, 'detalle' => array]
                $sp = $this->producto_model->sp_detalle_venta($id);

                $dat = array(
                    'cabecera'      => $sp['cabecera'],
                    'ventas_detalle'=> $sp['detalle'],
                );

                $this->load->view('partes/head_view', $data);
                $this->load->view('usuario/muestradetalle', $dat);
                $this->load->view('partes/footer_view');
            } else {
                redirect('login', 'refresh');
            }
        }


        /**
         * Productos con stock bajo — usa SP sp_productos_stock_bajo.
         * Ruta: producto_controller/stock_bajo  (agregar en routes.php si se desea URL amigable)
         */
        public function stock_bajo()
        {
            if ($this->_veri_log()) {
                $session_data = $this->session->userdata('login_in');

                $data = array(
                    'titulo'    => 'Stock Bajo',
                    'perfil_id' => $session_data['perfil_id'],
                    'nombre'    => $session_data['nombre'],
                );

                $dat = array(
                    'productos' => $this->producto_model->sp_stock_bajo(),
                );

                $this->load->view('partes/head_view',  $data);
                $this->load->view('partes/navbar_view', $data);
                $this->load->view('front/stock_bajo',  $dat);
                $this->load->view('partes/footer_view');
            } else {
                redirect('login', 'refresh');
            }
        }


        /**
         * Reporte de ventas por periodo — usa SP sp_reporte_ventas_por_periodo.
         * GET/POST con parametros fecha_inicio y fecha_fin.
         * Ruta: producto_controller/reporte_ventas
         */
        public function reporte_ventas()
        {
            if ($this->_veri_log()) {
                $session_data = $this->session->userdata('login_in');

                $data = array(
                    'titulo'    => 'Reporte de Ventas',
                    'perfil_id' => $session_data['perfil_id'],
                    'nombre'    => $session_data['nombre'],
                );

                $fecha_inicio = $this->input->get_post('fecha_inicio');
                $fecha_fin    = $this->input->get_post('fecha_fin');

                $dat = array('reporte' => []);

                if ($fecha_inicio && $fecha_fin) {
                    $dat['reporte']       = $this->producto_model->sp_reporte_ventas($fecha_inicio, $fecha_fin);
                    $dat['fecha_inicio']  = $fecha_inicio;
                    $dat['fecha_fin']     = $fecha_fin;
                }

                $this->load->view('partes/head_view',    $data);
                $this->load->view('partes/navbar_view',  $data);
                $this->load->view('usuario/reporte_ventas', $dat);
                $this->load->view('partes/footer_view');
            } else {
                redirect('login', 'refresh');
            }
        }


/************************************************************************** 
 *                                  Reservas
 **************************************************************************/
         //Funcion para Visualizar las Reservas
        function muestra_reserva()
        {
            if($this->_veri_log()){
            $data = array('titulo' => 'Muestra Reservas');
        
            $session_data = $this->session->userdata('login_in');
            $data['perfil_id'] = $session_data['perfil_id'];
            $data['nombre'] = $session_data['nombre'];

            $dat = array('reservas' => $this->producto_model->get_muestra_reserva()
            );

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view',$data);
            $this->load->view('front/muestraReservas', $dat);
            $this->load->view('partes/footer_view');
            }else{
            redirect('login', 'refresh'); }
        }


        //Funcion que Muestra las Reservas Confirmadas
    function muestraReservasConfirmadas() {     
    if($this->_veri_log()){
        $session_data = $this->session->userdata('login_in');
        
        $data = array(
            'titulo'    => 'Reservas Confirmadas',
            'perfil_id' => $session_data['perfil_id'],
            'nombre'    => $session_data['nombre'],
            'reservas'  => $this->producto_model->get_reservas_confirmadas()
        );

        $this->load->view('partes/head_view', $data);
        $this->load->view('partes/navbar_view', $data);
        $this->load->view('front/muestraReservasConfirmadas', $data);
        $this->load->view('partes/footer_view');
    } else {
        redirect('login', 'refresh');
        }
    }



    public function get_reservas_confirmadas()
{
    $this->db->select('*');
    $this->db->from('reserva');
    $this->db->where('estado_reserva', 'Confirmada');
    $query = $this->db->get();
    return $query->result();
}



        public function confirmarReserva($id) {
    if($this->_veri_log()){
        // Cargamos la base de datos por seguridad
        $this->load->database();
        
        // Llamamos al modelo pasándole el ID
        $resultado = $this->producto_model->update_estado_confirmar($id);

        if($resultado) {
            // Si funciona, vamos a la nueva vista de confirmadas
            redirect('reservasConfirmadas', 'refresh'); 
        } else {
            // Si falla la consulta SQL, nos mostrará el error real en pantalla
            echo $this->db->error()['message'];
        }
    } else {
        redirect('login', 'refresh');
    }
}


        //muestra reservas
        public function get_muestra_reserva() {
        $this->db->select("r.id_reserva, u.nombre_usuario, r.fecha_reserva, m.descripcion AS mesa, 
                       CONCAT(h.hora_inicio, ' a ', h.hora_fin) AS horario, r.estado_reserva");
        $this->db->from('reservas r');
        $this->db->join('usuarios u', 'r.id_usuario = u.id_usuario');
        $this->db->join('mesa m', 'r.id_mesa = m.id_mesa');
        $this->db->join('horario h', 'r.id_horario = h.id_horario');
        // Traemos las que no estén canceladas para la vista principal
        $this->db->where('r.estado_reserva !=', 'Pendiente'); 
    
         $query = $this->db->get();
         return ($query->num_rows() > 0) ? $query : false;
        }

        
       /**************************** 
        * Funcion Realizar Reserva *
        ****************************/
        function realizar_reserva()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if($this->_veri_log()){
        $data = array('titulo' => 'Hacer Reservas');
    
        $session_data = $this->session->userdata('login_in');
        $data['perfil_id'] = $session_data['perfil_id'];
        $data['nombre'] = $session_data['nombre'];

        $this->load->library('form_validation');
        $this->form_validation->set_rules('fecha-reserva', 'Fecha', 'required');

        if ($this->form_validation->run() == FALSE) {
            $dat = array('reservas' => $this->producto_model->get_muestra_reserva());

            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view',$data);
            $this->load->view('front/pagina_reservas', $dat);
            $this->load->view('partes/footer_view');
        } else {
            // PROCESAR VALIDACIONES DE FECHA Y HORA
            $fecha_seleccionada = $this->input->post('fecha-reserva'); 
            $id_horario = $this->input->post('opciones-cart');       
            $id_mesa = $this->input->post('id_mesa');
            $fecha_actual = date('Y-m-d');                             

            if ($fecha_seleccionada < $fecha_actual) {
                $this->session->set_flashdata('error_reserva', 'No puedes seleccionar una fecha que ya ha pasado.');
                redirect('realizar_reserva', 'refresh');
                return;
            }

            $horas_inicio = [
                "1" => "08:30:00", "2" => "10:30:00", "3" => "12:30:00", "4" => "17:00:00", "5" => "19:00:00"
            ];
            $hora_inicio_turno = isset($horas_inicio[$id_horario]) ? $horas_inicio[$id_horario] : "00:00:00";

            if (strtotime($fecha_seleccionada . ' ' . $hora_inicio_turno) < time()) {
                $this->session->set_flashdata('error_reserva', 'El turno seleccionado para el día de hoy ya ha expirado.');
                redirect('realizar_reserva', 'refresh');
                return;
            }

            if ($this->producto_model->verificar_mesa_ocupada($fecha_seleccionada, $id_horario, $id_mesa)) {
                $this->session->set_flashdata('error_reserva', 'La mesa seleccionada ya se encuentra reservada para ese día y horario.');
                redirect('realizar_reserva', 'refresh');
                return;
            }

            // GUARDAR EN LA BASE DE DATOS
            $insert_data = array(
                'id_usuario'     => $session_data['id'], 
                'fecha_reserva'  => $fecha_seleccionada,
                'id_mesa'        => $id_mesa,
                'id_horario'     => $id_horario,
                'estado_reserva' => 'Pendiente'
            );
            $this->db->insert('reservas', $insert_data); 

            // --- SOLUCIÓN DE VISTA ---
            // Volvemos a armar los datos base de la página de reservas
            $dat = array(
                'reservas' => $this->producto_model->get_muestra_reserva(),
                'reserva_exitosa' => TRUE, // Bandera que avisa que todo salió bien
                'mesa_exitosa' => $id_mesa,
                'fecha_exitosa' => $fecha_seleccionada
            );

            // Cargamos la misma vista que sí existe
            $this->load->view('partes/head_view', $data);
            $this->load->view('partes/navbar_view', $data);
            $this->load->view('front/pagina_reservas', $dat); // Usamos tu vista existente
            $this->load->view('partes/footer_view');
        }
    } else {
        redirect('login', 'refresh'); 
    }
}




//Actualiza el estados de la reserva
public function actualizarEstado($id) {
    if($this->_veri_log()){
        // Ejecutamos el cambio en el modelo
        if($this->producto_model->toggle_estado_reserva($id)){
            $this->session->set_flashdata('success', 'El estado de la reserva se actualizó correctamente.');
        }
        
        // Magia: Redirigimos al usuario de vuelta a la página desde donde vino
        // (Si estaba en pendientes, se queda ahí; si estaba en confirmadas, se queda ahí)
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    } else {
        redirect('login', 'refresh');
    }
}

//


}

/* End of file
*/