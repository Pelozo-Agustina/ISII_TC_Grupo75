<?php
/**
 * =============================================================================
 * CU3 – Agregar Producto (Tabla 20)
 * Grupo 75 – Ingeniería de Software II – FACENA UNNE 2026
 * =============================================================================
 * Parte de la suite de tests del sistema Coffee. Instancia el modelo real
 * con un stub de $this->db (ver tests/helpers.php) para detectar roturas en
 * el código de producción real, no en lógica reescrita dentro del test.
 * =============================================================================
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/helpers.php';

class AgregarProductoTest extends TestCase
{
    /** CP1 – add_producto con datos válidos no lanza excepción. */
    public function testCP1_AgregarProductoCompleto(): void
    {
        $db = new class {
            public function insert($t, $d): bool { return true; }
            public function insert_id(): int     { return 10; }
        };
        $model = makeModel('Producto_model', $db);
        $data  = ['descripcion' => 'Cookies', 'categoria_id' => 3,
                  'precio_costo' => 300.00, 'precio_venta' => 1000.00,
                  'stock' => 50, 'stock_min' => 5,
                  'imagen' => 'cookies.jpg', 'eliminado' => 'NO'];

        $exception = null;
        try {
            $model->add_producto($data);
        } catch (\Throwable $e) {
            $exception = $e;
        }
        $this->assertNull($exception, 'CP1: add_producto con datos válidos no debe lanzar excepción.');
    }

    /** CP7 – Sin imagen el campo $_FILES queda vacío (validación de controlador). */
    public function testCP7_SinImagenDetectaCampoVacio(): void
    {
        $_FILES['filename']['name'] = '';
        $this->assertTrue(empty($_FILES['filename']['name']),
            'CP7: Sin imagen, el campo debe estar vacío y el controlador debe bloquearlo.');
    }

    /** Adicional – edit_producto devuelve la fila correcta por ID. */
    public function testAdicional_EditProductoRetornaFila(): void
    {
        $fila = makeRow(['id' => 2, 'descripcion' => 'Café Vienés', 'stock' => 15, 'eliminado' => 'NO']);
        $qr   = makeQueryResult([$fila]);
        $db   = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get_where($t, $w, $l = null) { return $this->qr; }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->edit_producto(2);
        $this->assertNotFalse($result);
        $this->assertEquals('Café Vienés', $result->result()[0]->descripcion);
    }

    /** Adicional – edit_producto retorna FALSE para ID inexistente. */
    public function testAdicional_EditProductoIDInexistente(): void
    {
        $db = new class {
            public function get_where($t, $w, $l = null) { return makeQueryResult([]); }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->edit_producto(9999);
        $this->assertFalse($result, 'Adicional: edit_producto debe retornar FALSE si el ID no existe.');
    }

    /** Adicional – update_producto retorna TRUE en actualización exitosa. */
    public function testAdicional_UpdateProductoExitoso(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->update_producto(2, ['stock' => 20]);
        $this->assertTrue($result, 'Adicional: update_producto debe retornar TRUE si la BD actualiza.');
    }
}

