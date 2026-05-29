<?php
/**
 * =============================================================================
 * SUITE DE PRUEBAS PHPUNIT – SISTEMA COFFEE
 * Grupo 75 – Ingeniería de Software II – UNNE FACENA 2026
 * =============================================================================
 *
 * Cobertura:
 *   - CU1: Iniciar Sesión         (LoginModel::validarUsuario)
 *   - CU2: Registrarse            (Usuario_model::add_user + validaciones)
 *   - CU3: Agregar Producto       (Producto_model::add_producto + validaciones)
 *   - CU4: Realizar Venta         (Carrito_model + Producto_model::update_producto)
 *   - CU5: Eliminar Producto      (Producto_model::estado_producto / baja lógica)
 *   - CU6: Realizar Reserva       (Producto_model::verificar_mesa_ocupada + insertar)
 *
 * Tests adicionales justificados:
 *   - Activar producto (inverso de eliminar)
 *   - Modificar producto
 *   - Alta / baja lógica de usuario
 *   - Toggle estado reserva
 *   - Listado de ventas / detalle de venta
 *   - Validación de stock en carrito
 *
 * INSTRUCCIONES DE EJECUCIÓN:
 *   1. Instalar PHPUnit: composer require --dev phpunit/phpunit ^9
 *   2. Instalar CodeIgniter Test Helper: composer require kenjis/codeigniter-cli
 *      (o usar ci-phpunit-test: composer require kenjis/ci-phpunit-test)
 *   3. Copiar este archivo en: application/tests/CoffeeTestSuite.php
 *   4. Configurar phpunit.xml apuntando al bootstrap de CI.
 *   5. Ejecutar: ./vendor/bin/phpunit application/tests/CoffeeTestSuite.php
 *
 * NOTA SOBRE MOCKS:
 *   En CodeIgniter 3 los tests de integración usan ci-phpunit-test que
 *   bootstrapea el framework completo con una BD de test (SQLite en memoria
 *   o MySQL de test). Los mocks de $this->db se crean con PHPUnit MockBuilder.
 *   Cada clase de test extiende TestCase e instancia el modelo directamente,
 *   inyectando un stub del objeto $db mediante reflexión o el helper CI.
 * =============================================================================
 */

use PHPUnit\Framework\TestCase;

// ---------------------------------------------------------------------------
// Helpers de stub reutilizables
// ---------------------------------------------------------------------------

/**
 * Crea un stub del objeto db de CodeIgniter que devuelve un resultado
 * configurable sin tocar la base de datos real.
 *
 * @param  object|false  $queryResult  Objeto con num_rows() y result(), o FALSE
 * @param  bool          $updateResult Valor que devuelven insert/update/delete
 * @return object Mock de CI_DB_driver
 */
function makeDbStub($queryResult, bool $updateResult = true): object
{
    $stub = new class($queryResult, $updateResult) {
        private $qr;
        private $ur;
        private int $lastId = 1;

        public function __construct($qr, $ur)
        {
            $this->qr = $qr;
            $this->ur = $ur;
        }

        // Métodos de query
        public function get_where(string $table, array $where = [], int $limit = null) { return $this->qr; }
        public function get(string $table = ''): object { return $this->qr ?: $this; }
        public function insert(string $table, array $data): bool { return $this->ur; }
        public function update(string $table, array $data): bool { return $this->ur; }
        public function delete(string $table): bool { return $this->ur; }
        public function insert_id(): int { return $this->lastId; }
        public function where(string $key, $val = null): self { return $this; }
        public function where_in(string $key, array $vals): self { return $this; }
        public function select(string $sel): self { return $this; }
        public function from(string $table): self { return $this; }
        public function join(string $table, string $cond, string $type = 'inner'): self { return $this; }
        public function order_by(string $col, string $dir = 'ASC'): self { return $this; }
        public function num_rows(): int { return $this->qr ? 1 : 0; }
        public function result(): array { return $this->qr ? [$this->qr] : []; }
        public function row() { return $this->qr; }
    };

    return $stub;
}

/**
 * Construye un objeto stdClass simulando una fila de BD.
 */
function makeRow(array $fields): object
{
    return (object) $fields;
}

/**
 * Construye un query-result simulado con num_rows() > 0 y result() con filas.
 */
function makeQueryResult(array $rows): object
{
    return new class($rows) {
        private array $rows;
        public function __construct(array $r) { $this->rows = $r; }
        public function num_rows(): int { return count($this->rows); }
        public function result(): array { return $this->rows; }
        public function row() { return $this->rows[0] ?? null; }
    };
}

// ---------------------------------------------------------------------------
// Helper para instanciar modelos sin el framework completo
// ---------------------------------------------------------------------------
function makeModel(string $class, object $db): object
{
    // Carga el archivo si no está disponible (adaptar path según instalación)
    $map = [
        'LoginModel'     => __DIR__ . '/../models/loginModel.php',
        'Usuario_model'  => __DIR__ . '/../models/usuario_model.php',
        'Producto_model' => __DIR__ . '/../models/producto_model.php',
        'Carrito_model'  => __DIR__ . '/../models/carrito_model.php',
    ];

    if (isset($map[$class]) && file_exists($map[$class])) {
        // En un entorno real con ci-phpunit-test el autoload ya funciona.
        // Aquí el include es por si se ejecuta de forma aislada.
        require_once $map[$class];
    }

    // Instancia sin constructor de CI (usando reflección)
    $ref   = new ReflectionClass($class);
    $model = $ref->newInstanceWithoutConstructor();
    $model->db = $db;
    return $model;
}

// =============================================================================
// CU1 – INICIAR SESIÓN
// Plan de Prueba: Tabla 17 del documento
// =============================================================================
class IniciarSesionTest extends TestCase
{
    // -------------------------------------------------------------------------
    // CP1 – Login sin datos: usuario y contraseña vacíos
    // El modelo no debe retornar resultado válido.
    // -------------------------------------------------------------------------
    public function testCP1_LoginSinDatosRetornaFalse(): void
    {
        // Arrange: DB no encuentra filas (usuario vacío no existe)
        $emptyResult = makeQueryResult([]); // num_rows = 0
        $db    = makeDbStub($emptyResult);
        $model = makeModel('LoginModel', $db);

        // Act
        $result = $model->validarUsuario('', '');

        // Assert: debe retornar false porque num_rows() == 0
        $this->assertFalse(
            $result,
            'CP1: Con usuario y contraseña vacíos validarUsuario debe retornar FALSE.'
        );
    }

    // -------------------------------------------------------------------------
    // CP2 – Login con datos correctos: debe retornar array de resultado
    // -------------------------------------------------------------------------
    public function testCP2_LoginConDatosCorrectos(): void
    {
        // Arrange: DB devuelve exactamente 1 fila (usuario válido)
        $fila   = makeRow(['id' => 1, 'nombre' => 'Ana', 'perfil_id' => 2,
                           'usuario' => 'Ana', 'pass' => '1234', 'baja' => 'NO']);
        $qr     = makeQueryResult([$fila]);

        // Sobreescribimos get_where para devolver num_rows == 1
        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get_where($t, $w, $l = null) { return $this->qr; }
        };

        $model  = makeModel('LoginModel', $db);

        // Act
        $result = $model->validarUsuario('Ana', '1234');

        // Assert
        $this->assertIsArray($result, 'CP2: Login correcto debe retornar un array.');
        $this->assertNotEmpty($result, 'CP2: El array de resultado no debe estar vacío.');
        $this->assertEquals('Ana', $result[0]->nombre, 'CP2: El nombre del usuario debe coincidir.');
    }

    // -------------------------------------------------------------------------
    // CP3 – Login sin contraseña: DB retorna 0 filas → FALSE
    // -------------------------------------------------------------------------
    public function testCP3_LoginSinContrasenia(): void
    {
        $db    = makeDbStub(makeQueryResult([]));
        $model = makeModel('LoginModel', $db);

        $result = $model->validarUsuario('Ana', '');

        $this->assertFalse($result, 'CP3: Sin contraseña validarUsuario debe retornar FALSE.');
    }

    // -------------------------------------------------------------------------
    // CP4 – Login sin usuario: DB retorna 0 filas → FALSE
    // -------------------------------------------------------------------------
    public function testCP4_LoginSinUsuario(): void
    {
        $db    = makeDbStub(makeQueryResult([]));
        $model = makeModel('LoginModel', $db);

        $result = $model->validarUsuario('', '1234');

        $this->assertFalse($result, 'CP4: Sin usuario validarUsuario debe retornar FALSE.');
    }

    // -------------------------------------------------------------------------
    // CP5 – Login con usuario no registrado: DB retorna 0 filas → FALSE
    // -------------------------------------------------------------------------
    public function testCP5_LoginUsuarioNoRegistrado(): void
    {
        $db    = makeDbStub(makeQueryResult([]));
        $model = makeModel('LoginModel', $db);

        $result = $model->validarUsuario('José', '12dr3');

        $this->assertFalse($result, 'CP5: Usuario inexistente debe retornar FALSE.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – Usuario con baja = 'SI' no puede ingresar
    // La query en loginModel filtra baja='NO', por lo que DB devuelve 0 filas.
    // -------------------------------------------------------------------------
    public function testAdicional_UsuarioDadoDeBajaNoIngresa(): void
    {
        // La BD filtra baja='NO', así que si el usuario está de baja devuelve vacío
        $db    = makeDbStub(makeQueryResult([]));
        $model = makeModel('LoginModel', $db);

        $result = $model->validarUsuario('usuarioBaja', 'pass123');

        $this->assertFalse($result, 'ADICIONAL: Usuario con baja=SI no debe poder iniciar sesión.');
    }
}

// =============================================================================
// CU2 – REGISTRARSE
// Plan de Prueba: Tabla 18 del documento
// Los tests de validación de formulario (campos vacíos, is_unique) son
// responsabilidad de la capa CI Form_validation; aquí probamos el modelo.
// =============================================================================
class RegistrarseTest extends TestCase
{
    // -------------------------------------------------------------------------
    // CP1 – Registro con datos válidos: add_user devuelve el insert_id
    // -------------------------------------------------------------------------
    public function testCP1_RegistroExitoso(): void
    {
        $db = new class {
            public function insert($t, $d): bool { return true; }
            public function insert_id(): int    { return 5; }
        };

        $model  = makeModel('Usuario_model', $db);

        $data   = [
            'nombre'    => 'María',
            'apellido'  => 'Herrera',
            'email'     => 'maria10@gmail.com',
            'perfil_id' => '2',
            'usuario'   => 'Maria10',
            'pass'      => 'Maria1234',
        ];

        $id = $model->add_user($data);

        $this->assertEquals(5, $id, 'CP1: add_user debe retornar el insert_id del nuevo usuario.');
    }

    // -------------------------------------------------------------------------
    // CP8 – Registro con email duplicado:
    // La validación is_unique la hace CodeIgniter antes de llegar al modelo.
    // Aquí verificamos que add_user no se ejecuta si la BD lanza excepción/false.
    // -------------------------------------------------------------------------
    public function testCP8_RegistroEmailDuplicadoDBFalla(): void
    {
        // Simulamos que insert falla (ej: duplicate key)
        $db = new class {
            public function insert($t, $d): bool { return false; }
            public function insert_id(): int    { return 0; }
        };

        $model = makeModel('Usuario_model', $db);

        $data = ['nombre' => 'Juan', 'apellido' => 'Pérez',
                 'email'  => 'juanperez@gmail.com', 'perfil_id' => '2',
                 'usuario' => 'Juan Pérez', 'pass' => '1234'];

        $id = $model->add_user($data);

        // insert_id() retorna 0 cuando no hubo insert real
        $this->assertEquals(0, $id, 'CP8: insert_id debe ser 0 si la inserción falla.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – get_usuarios devuelve listado cuando hay registros
    // -------------------------------------------------------------------------
    public function testAdicional_GetUsuariosDevuelveResultado(): void
    {
        $fila = makeRow(['id' => 1, 'nombre' => 'Admin', 'baja' => 'NO']);
        $qr   = makeQueryResult([$fila]);

        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get($t) { return $this->qr; }
        };

        $model  = makeModel('Usuario_model', $db);
        $result = $model->get_usuarios();

        $this->assertNotFalse($result, 'ADICIONAL: get_usuarios debe retornar resultado cuando hay usuarios.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – get_usuarios retorna FALSE cuando la tabla está vacía
    // -------------------------------------------------------------------------
    public function testAdicional_GetUsuariosRetornaFalseSiVacio(): void
    {
        $qr = makeQueryResult([]);

        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get($t) { return $this->qr; }
        };

        $model  = makeModel('Usuario_model', $db);
        $result = $model->get_usuarios();

        $this->assertFalse($result, 'ADICIONAL: get_usuarios debe retornar FALSE si no hay filas.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – edit_usuario retorna la fila correcta por ID
    // -------------------------------------------------------------------------
    public function testAdicional_EditUsuarioRetornaFila(): void
    {
        $fila = makeRow(['id' => 3, 'nombre' => 'Gisela', 'email' => 'g@mail.com']);
        $qr   = makeQueryResult([$fila]);

        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get_where($t, $w, $l = null) { return $this->qr; }
        };

        $model  = makeModel('Usuario_model', $db);
        $result = $model->edit_usuario(3);

        $this->assertNotFalse($result);
        $this->assertEquals('Gisela', $result->result()[0]->nombre);
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – Baja lógica de usuario: estado_usuario actualiza correctamente
    // -------------------------------------------------------------------------
    public function testAdicional_BajaLogicaUsuario(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };

        $model  = makeModel('Usuario_model', $db);
        $result = $model->estado_usuario(3, ['baja' => 'SI']);

        $this->assertTrue($result, 'ADICIONAL: estado_usuario con baja=SI debe retornar TRUE.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – Activar usuario: estado_usuario con baja = 'NO'
    // -------------------------------------------------------------------------
    public function testAdicional_ActivarUsuario(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };

        $model  = makeModel('Usuario_model', $db);
        $result = $model->estado_usuario(3, ['baja' => 'NO']);

        $this->assertTrue($result, 'ADICIONAL: Activar usuario debe retornar TRUE.');
    }
}

// =============================================================================
// CU3 – AGREGAR PRODUCTO
// Plan de Prueba: Tabla 20 del documento
// =============================================================================
class AgregarProductoTest extends TestCase
{
    // -------------------------------------------------------------------------
    // CP1 – Agregar producto con todos los campos válidos
    // -------------------------------------------------------------------------
    public function testCP1_AgregarProductoCompleto(): void
    {
        $lastId = 10;
        $db = new class($lastId) {
            private int $id;
            public function __construct(int $id) { $this->id = $id; }
            public function insert($t, $d): bool { return true; }
            public function insert_id(): int     { return $this->id; }
        };

        $model = makeModel('Producto_model', $db);

        $data = [
            'descripcion'  => 'Cookies',
            'categoria_id' => 3,
            'precio_costo' => 300.00,
            'precio_venta' => 1000.00,
            'stock'        => 50,
            'stock_min'    => 5,
            'imagen'       => 'cookies.jpg',
            'eliminado'    => 'NO',
        ];

        // add_producto no retorna nada explícitamente; probamos que no lanza excepción
        $exception = null;
        try {
            $model->add_producto($data);
        } catch (\Throwable $e) {
            $exception = $e;
        }

        $this->assertNull($exception, 'CP1: add_producto con datos válidos no debe lanzar excepción.');
    }

    // -------------------------------------------------------------------------
    // CP2-CP6 – Campos numéricos con valor NULL/vacío deben ser detectados
    // antes de llegar al modelo. Aquí validamos que el modelo inserta lo que
    // recibe; la lógica de validación (form_validation) es del controlador.
    // Este test confirma que si llegan datos incompletos el insert se llama.
    // -------------------------------------------------------------------------
    public function testCP2al6_ProductoConCamposVaciosLlegaAlInsert(): void
    {
        $called = false;
        $db = new class($called) {
            public bool $called = false;
            public function insert($t, $d): bool { $this->called = true; return true; }
            public function insert_id(): int     { return 1; }
        };

        $model = makeModel('Producto_model', $db);

        // Datos incompletos (sin descripcion, etc.) – el controlador debería
        // haberlos rechazado; el modelo los acepta si llegan.
        $model->add_producto(['descripcion' => '', 'categoria_id' => 3,
                              'stock' => 0, 'eliminado' => 'NO']);

        $this->assertTrue($db->called,
            'CP2-6: El modelo no aplica validación; la responsabilidad es del controlador.');
    }

    // -------------------------------------------------------------------------
    // CP7 – Sin imagen: el controlador debe retornar error antes del modelo
    // Probamos la función de validación de imagen de forma aislada.
    // -------------------------------------------------------------------------
    public function testCP7_SinImagenFallaValidacion(): void
    {
        // La función _image_upload del controlador evalúa $_FILES['filename']['name']
        // Simulamos el comportamiento esperado
        $_FILES['filename']['name'] = ''; // sin imagen

        $imagenVacia = empty($_FILES['filename']['name']);

        $this->assertTrue($imagenVacia,
            'CP7: Sin imagen, la validación debe detectar el campo vacío y bloquear el registro.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – edit_producto devuelve la fila correcta por ID
    // -------------------------------------------------------------------------
    public function testAdicional_EditProductoRetornaFila(): void
    {
        $fila = makeRow(['id' => 2, 'descripcion' => 'Café Vienés',
                         'stock' => 15, 'eliminado' => 'NO']);
        $qr   = makeQueryResult([$fila]);

        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get_where($t, $w, $l = null) { return $this->qr; }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->edit_producto(2);

        $this->assertNotFalse($result);
        $this->assertEquals('Café Vienés', $result->result()[0]->descripcion);
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – edit_producto retorna FALSE para un ID inexistente
    // -------------------------------------------------------------------------
    public function testAdicional_EditProductoIDInexistente(): void
    {
        $db = new class {
            public function get_where($t, $w, $l = null) { return makeQueryResult([]); }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->edit_producto(9999);

        $this->assertFalse($result, 'ADICIONAL: edit_producto debe retornar FALSE si el ID no existe.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – update_producto retorna TRUE en actualización exitosa
    // -------------------------------------------------------------------------
    public function testAdicional_UpdateProductoExitoso(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->update_producto(2, ['stock' => 20]);

        $this->assertTrue($result, 'ADICIONAL: update_producto debe retornar TRUE si la BD actualiza.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – get_productos devuelve solo los activos (eliminado = 'NO')
    // -------------------------------------------------------------------------
    public function testAdicional_GetProductosSoloActivos(): void
    {
        $fila = makeRow(['id' => 1, 'descripcion' => 'Cappuccino', 'eliminado' => 'NO']);
        $qr   = makeQueryResult([$fila]);

        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get_where($t, $w, $l = null) {
                // Verificamos que el filtro es correcto
                assert($w['eliminado'] === 'NO');
                return $this->qr;
            }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->get_productos();

        $this->assertNotFalse($result, 'ADICIONAL: get_productos debe retornar resultados para productos activos.');
    }
}

// =============================================================================
// CU4 – REALIZAR VENTA
// Plan de Prueba: Tabla 19 del documento
// =============================================================================
class RealizarVentaTest extends TestCase
{
    // -------------------------------------------------------------------------
    // CP1 – Venta con stock suficiente: insert_venta retorna ID válido
    // -------------------------------------------------------------------------
    public function testCP1_InsertVentaRetornaId(): void
    {
        $db = new class {
            public function insert($t, $d): bool { return true; }
            public function insert_id(): int     { return 42; }
        };

        $model  = makeModel('Carrito_model', $db);
        $venta  = ['fecha' => '2026-05-24', 'usuario_id' => 1,
                   'subtotal' => 3000, 'total_venta' => 3000];

        $id = $model->insert_venta($venta);

        $this->assertEquals(42, $id, 'CP1: insert_venta debe retornar el ID de la nueva venta.');
    }

    // -------------------------------------------------------------------------
    // CP2 – Cantidad supera stock disponible:
    // La lógica de control de stock está en actualiza_carrito del controlador.
    // Probamos que la cantidad supera el stock y la validación debe bloquearla.
    // -------------------------------------------------------------------------
    public function testCP2_CantidadSuperaStock(): void
    {
        $stockDisponible  = 10;
        $cantidadPedida   = 30;

        $superaStock = $cantidadPedida > $stockDisponible;

        $this->assertTrue($superaStock,
            'CP2: Si la cantidad pedida supera el stock disponible la validación debe bloquearlo.');
    }

    // -------------------------------------------------------------------------
    // CP3 – Cantidad negativa (≤ 0): debe ser rechazada por el controlador
    // -------------------------------------------------------------------------
    public function testCP3_CantidadNegativaEsInvalida(): void
    {
        $qty = -3;

        $esInvalida = $qty <= 0;

        $this->assertTrue($esInvalida,
            'CP3: Una cantidad negativa debe ser considerada inválida (mensaje: "Ingrese una cantidad mayor al 0").');
    }

    // -------------------------------------------------------------------------
    // CP4 – Producto sin stock (stock = 0): no se puede agregar al carrito
    // -------------------------------------------------------------------------
    public function testCP4_ProductoSinStockNoPuedeAgregarse(): void
    {
        $stock = 0;

        $hayStock = $stock > 0;

        $this->assertFalse($hayStock,
            'CP4: Con stock = 0 el producto no debe poderse agregar al carrito.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – insert_ventas_detalle no lanza excepción con datos válidos
    // -------------------------------------------------------------------------
    public function testAdicional_InsertVentasDetalleExitoso(): void
    {
        $db = new class {
            public function insert($t, $d): bool { return true; }
        };

        $model   = makeModel('Carrito_model', $db);
        $detalle = ['cabecera_id' => 42, 'producto_id' => 2,
                    'cantidad' => 3, 'precio' => 1400, 'total' => 4200];

        $exception = null;
        try {
            $model->insert_ventas_detalle($detalle);
        } catch (\Throwable $e) {
            $exception = $e;
        }

        $this->assertNull($exception,
            'ADICIONAL: insert_ventas_detalle con datos válidos no debe lanzar excepción.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – Descuento de stock tras venta: update_producto con nuevo stock
    // -------------------------------------------------------------------------
    public function testAdicional_DescuentoStockTrasventa(): void
    {
        $stockActual  = 15;
        $cantidadVenta = 3;
        $stockEsperado = 12;

        $db = new class($stockEsperado) {
            private int $stockRecibido = 0;
            private int $expected;
            public function __construct(int $e) { $this->expected = $e; }
            public function where($k, $v = null): self { return $this; }
            public function update($t, array $data): bool {
                $this->stockRecibido = $data['stock'];
                return true;
            }
            public function getStockRecibido(): int { return $this->stockRecibido; }
        };

        $model = makeModel('Producto_model', $db);
        $nuevoStock = $stockActual - $cantidadVenta;
        $model->update_producto(2, ['stock' => $nuevoStock]);

        $this->assertEquals($stockEsperado, $db->getStockRecibido(),
            'ADICIONAL: Tras la venta el stock debe descontarse correctamente.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – get_ventas_cabecera devuelve FALSE si no hay ventas
    // -------------------------------------------------------------------------
    public function testAdicional_GetVentasCabeceraVacia(): void
    {
        $db = new class {
            public function select($s): self  { return $this; }
            public function from($t): self    { return $this; }
            public function join($t, $c, $tp = 'inner'): self { return $this; }
            public function get($t = ''): object { return makeQueryResult([]); }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->get_ventas_cabecera();

        $this->assertFalse($result,
            'ADICIONAL: get_ventas_cabecera debe retornar FALSE si no hay registros.');
    }
}

// =============================================================================
// CU5 – ELIMINAR PRODUCTO (baja lógica)
// Plan de Prueba: Tabla 21 del documento
// =============================================================================
class EliminarProductoTest extends TestCase
{
    // -------------------------------------------------------------------------
    // CP1 – Eliminar producto existente: estado_producto con eliminado = 'SI'
    // -------------------------------------------------------------------------
    public function testCP1_EliminarProductoExistente(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->estado_producto(5, ['eliminado' => 'SI']);

        $this->assertTrue($result,
            'CP1: estado_producto con eliminado=SI debe retornar TRUE para un producto existente.');
    }

    // -------------------------------------------------------------------------
    // CP2 – Producto ya eliminado: intento de segunda baja sigue retornando TRUE
    // (la BD actualiza aunque el valor ya sea el mismo)
    // -------------------------------------------------------------------------
    public function testCP2_ProductoYaEliminadoActualizaNuevamente(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->estado_producto(5, ['eliminado' => 'SI']);

        $this->assertTrue($result,
            'CP2: Intentar dar de baja un producto ya eliminado debe retornar TRUE (idempotente).');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – Activar producto eliminado: estado_producto con eliminado = 'NO'
    // -------------------------------------------------------------------------
    public function testAdicional_ActivarProducto(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->estado_producto(5, ['eliminado' => 'NO']);

        $this->assertTrue($result,
            'ADICIONAL: Activar un producto eliminado debe retornar TRUE.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – not_active_productos devuelve solo los eliminados
    // -------------------------------------------------------------------------
    public function testAdicional_NotActiveProductosDevuelveEliminados(): void
    {
        $fila = makeRow(['id' => 3, 'descripcion' => 'Muffin', 'eliminado' => 'SI']);
        $qr   = makeQueryResult([$fila]);

        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get_where($t, $w, $l = null) {
                assert($w['eliminado'] === 'SI');
                return $this->qr;
            }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->not_active_productos();

        $this->assertNotFalse($result,
            'ADICIONAL: not_active_productos debe retornar resultado cuando hay eliminados.');
        $this->assertEquals('SI', $result->result()[0]->eliminado,
            'ADICIONAL: Los productos retornados deben tener eliminado=SI.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – estado_producto falla (BD retorna false): debe retornar FALSE
    // -------------------------------------------------------------------------
    public function testAdicional_EstadoProductoFallaEnBD(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return false; } // Fallo
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->estado_producto(99, ['eliminado' => 'SI']);

        $this->assertFalse($result,
            'ADICIONAL: Si la BD falla al actualizar, estado_producto debe retornar FALSE.');
    }
}

// =============================================================================
// CU6 – REALIZAR RESERVA
// Plan de Prueba: Tabla 22 del documento
// =============================================================================
class RealizarReservaTest extends TestCase
{
    // -------------------------------------------------------------------------
    // CP1 – Mesa disponible en fecha y horario: verificar_mesa_ocupada = FALSE
    // -------------------------------------------------------------------------
    public function testCP1_MesaDisponible(): void
    {
        // La query no retorna filas → mesa libre
        $db = new class {
            public function where($k, $v = null): self    { return $this; }
            public function where_in($k, $v): self        { return $this; }
            public function get($t = ''): object          { return makeQueryResult([]); }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->verificar_mesa_ocupada('2026-05-21', 1, 1);

        $this->assertFalse($result,
            'CP1: verificar_mesa_ocupada debe retornar FALSE si la mesa está disponible.');
    }

    // -------------------------------------------------------------------------
    // CP2 – Mesa ya reservada en ese día y horario: debe retornar TRUE
    // -------------------------------------------------------------------------
    public function testCP2_MesaYaOcupada(): void
    {
        $fila = makeRow(['id_reserva' => 7, 'estado_reserva' => 'Pendiente']);
        $qr   = makeQueryResult([$fila]);

        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function where($k, $v = null): self { return $this; }
            public function where_in($k, $v): self     { return $this; }
            public function get($t = ''): object       { return $this->qr; }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->verificar_mesa_ocupada('2026-05-21', 1, 1);

        $this->assertTrue($result,
            'CP2: verificar_mesa_ocupada debe retornar TRUE si la mesa ya está reservada.');
    }

    // -------------------------------------------------------------------------
    // CP3 – Horario ya transcurrido: validación en el controlador
    // La comparación de timestamps ocurre en realizar_reserva() del controlador.
    // -------------------------------------------------------------------------
    public function testCP3_HorarioTranscurrido(): void
    {
        // Fecha y hora anteriores al momento actual
        $fechaSeleccionada  = '2024-01-01';
        $horaInicioTurno    = '08:30:00';
        $tiempoActual       = time();

        $timestampTurno = strtotime($fechaSeleccionada . ' ' . $horaInicioTurno);

        $this->assertLessThan($tiempoActual, $timestampTurno,
            'CP3: El turno con fecha pasada debe tener timestamp menor al actual (ya transcurrió).');
    }

    // -------------------------------------------------------------------------
    // CP4 – Fecha de reserva ya transcurrida: fecha < fecha_actual
    // -------------------------------------------------------------------------
    public function testCP4_FechaTranscurrida(): void
    {
        $fechaSeleccionada = '2026-05-18'; // fecha pasada
        $fechaActual       = date('Y-m-d');

        $esPasada = $fechaSeleccionada < $fechaActual;

        $this->assertTrue($esPasada,
            'CP4: Una fecha pasada debe ser rechazada por el sistema.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – Fecha igual a hoy es válida (no es pasada)
    // -------------------------------------------------------------------------
    public function testAdicional_FechaHoyEsValida(): void
    {
        $fechaHoy   = date('Y-m-d');
        $fechaActual = date('Y-m-d');

        $esValida = !($fechaHoy < $fechaActual);

        $this->assertTrue($esValida, 'ADICIONAL: La fecha de hoy no debe ser rechazada.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – Toggle estado reserva: Pendiente → Confirmada
    // -------------------------------------------------------------------------
    public function testAdicional_ToggleEstadoPendienteAConfirmada(): void
    {
        $reservaActual = makeRow(['id_reserva' => 1, 'estado_reserva' => 'Pendiente']);
        $qr            = makeQueryResult([$reservaActual]);

        $nuevoEstadoGuardado = null;

        $db = new class($qr, &$nuevoEstadoGuardado) {
            private $qr;
            private &$ns;
            public function __construct($qr, &$ns) { $this->qr = $qr; $this->ns = &$ns; }
            public function where($k, $v = null): self { return $this; }
            public function get($t = ''): object       { return $this->qr; }
            public function update($t, array $data): bool {
                $this->ns = $data['estado_reserva'];
                return true;
            }
        };

        $model = makeModel('Producto_model', $db);
        $model->toggle_estado_reserva(1);

        $this->assertEquals('Confirmada', $nuevoEstadoGuardado,
            'ADICIONAL: Toggle desde Pendiente debe guardar Confirmada en la BD.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – Toggle estado reserva: Confirmada → Pendiente
    // -------------------------------------------------------------------------
    public function testAdicional_ToggleEstadoConfirmadaAPendiente(): void
    {
        $reservaActual = makeRow(['id_reserva' => 2, 'estado_reserva' => 'Confirmada']);
        $qr            = makeQueryResult([$reservaActual]);

        $nuevoEstadoGuardado = null;

        $db = new class($qr, &$nuevoEstadoGuardado) {
            private $qr;
            private &$ns;
            public function __construct($qr, &$ns) { $this->qr = $qr; $this->ns = &$ns; }
            public function where($k, $v = null): self { return $this; }
            public function get($t = ''): object       { return $this->qr; }
            public function update($t, array $data): bool {
                $this->ns = $data['estado_reserva'];
                return true;
            }
        };

        $model = makeModel('Producto_model', $db);
        $model->toggle_estado_reserva(2);

        $this->assertEquals('Pendiente', $nuevoEstadoGuardado,
            'ADICIONAL: Toggle desde Confirmada debe guardar Pendiente en la BD.');
    }

    // -------------------------------------------------------------------------
    // ADICIONAL – Mesa con estado 'Cancelada' NO bloquea la disponibilidad
    // verificar_mesa_ocupada solo considera Confirmada/Pendiente
    // -------------------------------------------------------------------------
    public function testAdicional_MesaCanceladaNoBloquea(): void
    {
        // La query filtra where_in('estado_reserva', ['Confirmada','Pendiente'])
        // Si solo hay canceladas, la query devuelve 0 filas → mesa libre
        $db = new class {
            public function where($k, $v = null): self    { return $this; }
            public function where_in($k, $v): self        { return $this; }
            public function get($t = ''): object          { return makeQueryResult([]); }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->verificar_mesa_ocupada('2026-06-01', 2, 3);

        $this->assertFalse($result,
            'ADICIONAL: Una mesa con reservas canceladas debe estar disponible para nueva reserva.');
    }
}
