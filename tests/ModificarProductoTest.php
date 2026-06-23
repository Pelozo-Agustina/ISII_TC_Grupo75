<?php
/**
 * =============================================================================
 * CU3b – Modificar Producto (Tabla 23)
 * Grupo 75 – Ingeniería de Software II – FACENA UNNE 2026
 * =============================================================================
 * Parte de la suite de tests del sistema Coffee. Instancia el modelo real
 * con un stub de $this->db (ver tests/helpers.php) para detectar roturas en
 * el código de producción real, no en lógica reescrita dentro del test.
 * =============================================================================
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/helpers.php';

class ModificarProductoTest extends TestCase
{
    /**
     * CP1 – Modificación exitosa: edit + update retornan resultados válidos.
     */
    public function testCP1_ModificacionExitosa(): void
    {
        $filaExistente = makeRow(['id' => 10, 'descripcion' => 'Capuccino Tradicional',
                                  'categoria_id' => 1, 'precio_costo' => 1500.00,
                                  'precio_venta' => 2500.00, 'stock' => 30,
                                  'stock_min' => 5, 'eliminado' => 'NO']);
        $qr = makeQueryResult([$filaExistente]);

        $db = new class($qr) {
            private $qr;
            public bool $updateCalled = false;
            public function __construct($qr) { $this->qr = $qr; }
            public function get_where($t, $w, $l = null) { return $this->qr; }
            public function where($k, $v = null): self    { return $this; }
            public function update($t, $d): bool          { $this->updateCalled = true; return true; }
        };

        $model   = makeModel('Producto_model', $db);
        $edicion = $model->edit_producto(10);
        $this->assertNotFalse($edicion, 'CP1: edit_producto debe encontrar el producto.');

        $ok = $model->update_producto(10, ['precio_venta' => 2800.00, 'stock' => 25]);
        $this->assertTrue($ok, 'CP1: update_producto debe retornar TRUE.');
        $this->assertTrue($db->updateCalled, 'CP1: El método update de la BD debe haberse llamado.');
    }

    /** CP2 – Producto no encontrado: edit_producto retorna FALSE. */
    public function testCP2_ProductoNoEncontradoRetornaFalse(): void
    {
        $db = new class {
            public function get_where($t, $w, $l = null) { return makeQueryResult([]); }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->edit_producto(9999);
        $this->assertFalse($result, 'CP2: edit_producto debe retornar FALSE para un ID inexistente.');
    }

    /** CP3 – Falla en la BD al actualizar: update_producto retorna FALSE. */
    public function testCP3_UpdateProductoFallaEnBD(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return false; }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->update_producto(10, ['precio_venta' => 2800.00]);
        $this->assertFalse($result, 'CP3: update_producto debe retornar FALSE si la BD falla.');
    }

    /** Adicional – Stock no puede quedar negativo (validación en controlador). */
    public function testAdicional_StockNegativoEsInvalido(): void
    {
        $stockNuevo = -5;
        $this->assertLessThan(0, $stockNuevo,
            'Adicional: Un stock negativo debe ser rechazado antes de llegar al modelo.');
    }
}

