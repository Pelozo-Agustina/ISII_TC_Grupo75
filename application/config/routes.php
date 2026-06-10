<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Welcome';
$route['BebidasCaliente'] = 'Welcome/BebidasCaliente';
$route['BebidasFria'] = 'Welcome/BebidasFria';
$route['ParaAcomp'] = 'Welcome/ParaAcom';
$route['privacidad'] = 'Welcome/privacidad';
$route['Condiciones'] = 'Welcome/Condiciones';

//$route['iniciarSesion'] = 'Welcome/iniciarSesion';
$route['registro'] = 'Welcome/registro';
$route['verifico_nuevoregistro'] = 'registro_controller';
$route['login'] = 'loginController/login';
$route['cerrarSesion'] = 'loginController/cerrar_sesion';
$route['verificoUsuario'] ='loginController';

//$route['usuario'] = 'Welcome/usuario';
//$route['editar'] = 'Welcome/editar';





/************************************************************************** 
							Rutas Productos
**************************************************************************/
$route['cargar_producto'] = 'producto_controller/form_agrega_producto';
$route['verifico_nuevoproducto'] = 'producto_controller/agrega_producto';
$route['productos_todos'] = 'producto_controller';

$route['producto_modifica/(:num)'] = 'producto_controller/muestra_modificar/$1';
$route['verifico_modificaproducto/(:num)'] = 'producto_controller/modificar_producto/$1';

$route['productos_eliminados'] = 'producto_controller/muestra_eliminados';
$route['producto_elimina/(:num)'] = 'producto_controller/eliminar_producto/$1';
$route['productos_activa/(:num)'] = 'producto_controller/activar_producto/$1';





/************************************************************************** 
							Rutas Administrador
**************************************************************************/
$route['cargar_usuario'] = 'registro_controller/form_agrega_usuario';
$route['verificoUsuario/(:num)'] = 'registro_controller/agrega_usuario/$1';
$route['usuario_todos'] = 'registro_controller/muestra_usuario';

//$route['modificar_usuarios/(:num)'] = 'registro_controller/muestra_modifica/$1';
//$route['verifico_modificausuario/(:num)'] = 'registro_controller/modificar_usuario/$1';

$route['usuario_elimina/(:num)'] = 'registro_controller/eliminar_usuario/$1';
$route['usuarios_eliminados'] = 'registro_controller/muestra_eliminados';
$route['usuarios_activa/(:num)'] = 'registro_controller/activar_usuario/$1';

$route['editar'] = 'Welcome/editar_usuario';




/************************************************************************** 
							Rutas Reportes
**************************************************************************/
$route['ventas'] = 'producto_controller/listar_ventas';
$route['muestra_detalle/(:num)'] = 'producto_controller/muestra_detalle/$1';
$route['consultas']='Welcome/consultas';
$route['eliminar'] = 'Welcome/eliminar';
$route['visualizardatos'] = 'registro_controller/visualizardatos';



$route['stock_bajo'] = 'producto_controller/stock_bajo';
$route['reporte_ventas'] = 'producto_controller/reporte_ventas';






/************************************************************************** 
							Rutas Reservas
**************************************************************************/

$route['muestraReservas'] = 'producto_controller/muestra_reserva';
//$route['reservasCanceladas'] = 'producto_controller/muestraReservasCanceladas';
//$route['cancelar_reserva/(:num)'] = 'producto_controller/cancelar_reserva/$1';
//$route['activar_recerva/(:num)'] = 'producto_controller/activar_reserva/$1';
$route['reservasConfirmadas'] = 'producto_controller/muestraReservasConfirmadas';
$route['actualizarEstado/(:num)'] = 'producto_controller/actualizarEstado/$1';
$route['realizar_reserva'] = 'producto_controller/realizar_reserva';



/************************************************************************** 
							Rutas Carrito
**************************************************************************/

//rutas carrito
$route['BebidasC'] = 'carrito_controller/mostrarBebidasC';
$route['BebidasF'] = 'carrito_controller/mostrarBebidasF';
$route['ParaA'] = 'carrito_controller/mostrarParaAcom';

$route['BebidasCalientes'] ='carrito_controller/BebidasCalientes';
$route['BebidasFrias'] ='carrito_controller/BebidasFria';
$route['ParaAcom'] ='carrito_controller/ParaAcom';

$route['carrito_agrega'] = 'carrito_controller/añadirCarrito';
$route['carrito_actualiza'] = 'carrito_controller/actualiza_carrito';
$route['carrito_elimina/(:any)'] = 'carrito_controller/eliminarCarrito/$1';
$route['venta'] = 'carrito_controller/mostrar_venta';
$route['confirma_venta'] = 'carrito_controller/realizar_venta';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;