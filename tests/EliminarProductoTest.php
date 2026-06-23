<?php
/**
 * =============================================================================
 * CU5 – Eliminar Producto (Tabla 21)
 * Grupo 75 – Ingeniería de Software II – FACENA UNNE 2026
 * =============================================================================
 * Parte de la suite de tests del sistema Coffee. Instancia el modelo real
 * con un stub de $this->db (ver tests/helpers.php) para detectar roturas en
 * el código de producción real, no en lógica reescrita dentro del test.
 * =============================================================================
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/helpers.php';

class EliminarProductoTest extends TestCase
{
    /** CP1 – Baja lógica exitosa: estado_producto con eliminado='SI' → TRUE. */
    public function testCP1_EliminarProductoExistente(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->estado_producto(5, ['eliminado' => 'SI']);
        $this->assertTrue($result, 'CP1: estado_producto con eliminado=SI debe retornar TRUE.');
    }

    /** CP2 – Intentar dar de baja un producto ya eliminado → sigue retornando TRUE (idempotente). */
    public function testCP2_ProductoYaEliminadoActualizaNuevamente(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->estado_producto(5, ['eliminado' => 'SI']);
        $this->assertTrue($result, 'CP2: Segunda baja lógica debe retornar TRUE (idempotente).');
    }

    /** Adicional – Activar producto (eliminado='NO') → TRUE. */
    public function testAdicional_ActivarProducto(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->estado_producto(5, ['eliminado' => 'NO']);
        $this->assertTrue($result, 'Adicional: Activar un producto eliminado debe retornar TRUE.');
    }

    /** Adicional – not_active_productos devuelve solo los eliminados. */
    public function testAdicional_NotActiveProductosDevuelveEliminados(): void
    {
        $fila = makeRow(['id' => 3, 'descripcion' => 'Muffin', 'eliminado' => 'SI']);
        $qr   = makeQueryResult([$fila]);
        $db   = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get_where($t, $w, $l = null) {
                assert($w['eliminado'] === 'SI');
                return $this->qr;
            }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->not_active_productos();
        $this->assertNotFalse($result, 'Adicional: not_active_productos debe retornar resultado cuando hay eliminados.');
        $this->assertEquals('SI', $result->result()[0]->eliminado,
            'Adicional: Los productos retornados deben tener eliminado=SI.');
    }

    /** Adicional – Fallo en BD al dar de baja → FALSE. */
    public function testAdicional_EstadoProductoFallaEnBD(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return false; }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->estado_producto(99, ['eliminado' => 'SI']);
        $this->assertFalse($result, 'Adicional: Si la BD falla al actualizar, debe retornar FALSE.');
    }
}

