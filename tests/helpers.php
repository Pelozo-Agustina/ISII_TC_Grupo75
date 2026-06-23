<?php
/**
 * =============================================================================
 * HELPERS COMPARTIDOS – SUITE DE PRUEBAS PHPUNIT – SISTEMA COFFEE
 * Grupo 75 – Ingeniería de Software II – FACENA UNNE 2026
 * =============================================================================
 * Funciones de stub usadas por todas las clases de test (IniciarSesionTest,
 * RegistrarseTest, AgregarProductoTest, ModificarProductoTest,
 * RealizarVentaTest, EliminarProductoTest, RealizarReservaTest).
 *
 * Cada archivo *Test.php hace require_once de este archivo antes de declarar
 * su clase, para no redeclarar las funciones (use declare guard incluido).
 * =============================================================================
 */

// -----------------------------------------------------------------------------
// Los modelos reales de CodeIgniter empiezan con:
//   if (!defined('BASEPATH')) exit('No direct script access allowed');
// Como acá los cargamos de forma aislada (sin levantar el framework completo),
// BASEPATH nunca queda definida y ese exit() mata la ejecución de PHPUnit antes
// de correr ningún test. Definirla con cualquier valor evita el corte; no se
// usa para nada más dentro de los modelos en este contexto de test.
// -----------------------------------------------------------------------------
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__);
}

// Los modelos reales (LoginModel, Usuario_model, Producto_model, Carrito_model)
// hacen "extends CI_Model". Esa clase vive en system/core/Model.php; sin
// cargarla antes, declarar el modelo tira "Class CI_Model not found".
if (!class_exists('CI_Model')) {
    require_once __DIR__ . '/../system/core/Model.php';
}

if (!function_exists('makeDbStub')) {

    /**
     * Crea un stub del objeto db de CodeIgniter con comportamiento configurable.
     *
     * @param  object|null $queryResult  Objeto con num_rows() y result(), o null
     * @param  bool        $updateResult Valor que devuelven insert/update/delete
     */
    function makeDbStub($queryResult = null, bool $updateResult = true): object
    {
        return new class($queryResult, $updateResult) {
            private $qr;
            private bool $ur;
            private int $lastId = 1;

            public function __construct($qr, bool $ur)
            {
                $this->qr = $qr;
                $this->ur = $ur;
            }

            public function get_where(string $table, array $where = [], int $limit = null) { return $this->qr; }
            public function get(string $table = ''): object { return $this->qr ?: $this; }
            public function insert(string $table, array $data): bool { return $this->ur; }
            public function update(string $table, array $data = []): bool { return $this->ur; }
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
            public function escape($val): string { return "'$val'"; }
        };
    }
}

if (!function_exists('makeRow')) {
    /** Construye un stdClass simulando una fila de BD. */
    function makeRow(array $fields): object
    {
        return (object) $fields;
    }
}

if (!function_exists('makeQueryResult')) {
    /** Construye un query-result simulado con num_rows() y result(). */
    function makeQueryResult(array $rows): object
    {
        return new class($rows) {
            private array $rows;
            public function __construct(array $r) { $this->rows = $r; }
            public function num_rows(): int  { return count($this->rows); }
            public function result(): array  { return $this->rows; }
            public function row()            { return $this->rows[0] ?? null; }
        };
    }
}

if (!function_exists('makeModel')) {
    /**
     * Instancia un modelo sin el framework CI completo, inyectando un stub de $db.
     * En un entorno con ci-phpunit-test el autoload ya carga los modelos; el map
     * actúa como fallback para ejecución aislada.
     */
    function makeModel(string $class, object $db): object
    {
        $map = [
            'LoginModel'     => __DIR__ . '/../application/models/loginModel.php',
            'Usuario_model'  => __DIR__ . '/../application/models/usuario_model.php',
            'Producto_model' => __DIR__ . '/../application/models/producto_model.php',
            'Carrito_model'  => __DIR__ . '/../application/models/carrito_model.php',
        ];

        if (isset($map[$class]) && file_exists($map[$class]) && !class_exists($class)) {
            require_once $map[$class];
        }

        // PHP 8.2+ marca como "deprecada" la asignación de una propiedad que
        // la clase no declaró ($db no está declarada en los modelos reales
        // de CodeIgniter; en la app real la inyecta el framework por otra
        // vía, vía __get() de CI_Model). No cambia ningún comportamiento,
        // es solo un aviso a futuro. Se evita sin tocar los modelos reales
        // en application/models: si la clase no declara $db, se usa una
        // subclase auxiliar que sí la declara explícitamente.
        //
        // Es seguro hacerlo porque, igual que con la clase real, esta
        // subclase se instancia con newInstanceWithoutConstructor(): nunca
        // se ejecuta ningún constructor (ni el propio ni el heredado), que
        // es justamente lo que hace falta — el constructor real de
        // Producto_model llama a $this->load->database(), que depende del
        // framework completo y rompería si se ejecutara en este contexto
        // de test aislado.
        $dbHolderClass = $class . '__WithDbProperty';

        if (!class_exists($dbHolderClass)) {
            eval(
                'class ' . $dbHolderClass . ' extends \\' . $class . ' { public $db; }'
            );
        }

        $ref   = new ReflectionClass($dbHolderClass);
        $model = $ref->newInstanceWithoutConstructor();
        $model->db = $db;

        return $model;
    }
}
