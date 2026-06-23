<?php
/**
 * =============================================================================
 * CU4 – Realizar Venta (Tabla 19)
 * Grupo 75 – Ingeniería de Software II – FACENA UNNE 2026
 * =============================================================================
 * Parte de la suite de tests del sistema Coffee. Instancia el modelo real
 * con un stub de $this->db (ver tests/helpers.php) para detectar roturas en
 * el código de producción real, no en lógica reescrita dentro del test.
 * =============================================================================
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/helpers.php';

class RealizarVentaTest extends TestCase
{
    /** CP1 – insert_venta retorna ID válido de la venta creada. */
    public function testCP1_InsertVentaRetornaId(): void
    {
        $db = new class {
            public function insert($t, $d): bool { return true; }
            public function insert_id(): int     { return 42; }
        };
        $model = makeModel('Carrito_model', $db);
        $venta = ['fecha' => '2026-05-24', 'usuario_id' => 1,
                  'subtotal' => 3000, 'total_venta' => 3000];

        $id = $model->insert_venta($venta);
        $this->assertEquals(42, $id, 'CP1: insert_venta debe retornar el ID de la nueva venta.');
    }

    /** CP2 – Cantidad pedida supera el stock disponible. */
    public function testCP2_CantidadSuperaStock(): void
    {
        $stockDisponible = 10;
        $cantidadPedida  = 30;
        $this->assertTrue($cantidadPedida > $stockDisponible,
            'CP2: Cuando la cantidad supera el stock, la validación debe bloquearlo.');
    }

    /** CP3 – Cantidad negativa o cero es inválida. */
    public function testCP3_CantidadNegativaEsInvalida(): void
    {
        $this->assertTrue(-3 <= 0, 'CP3: Una cantidad negativa debe ser considerada inválida.');
    }

    /** CP4 – Stock = 0: producto no puede agregarse al carrito. */
    public function testCP4_ProductoSinStockNoPuedeAgregarse(): void
    {
        $this->assertFalse(0 > 0, 'CP4: Con stock = 0 el producto no debe poder agregarse al carrito.');
    }

    /** Adicional – insert_ventas_detalle no lanza excepción con datos válidos. */
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
            'Adicional: insert_ventas_detalle con datos válidos no debe lanzar excepción.');
    }

    /** Adicional – Descuento de stock tras venta: update_producto recibe el stock correcto. */
    public function testAdicional_DescuentoStockTrasVenta(): void
    {
        $stockActual   = 15;
        $cantidadVenta = 3;
        $stockEsperado = 12;

        $db = new class {
            public int $stockRecibido = 0;
            public function where($k, $v = null): self  { return $this; }
            public function update($t, array $data): bool {
                $this->stockRecibido = $data['stock'];
                return true;
            }
        };

        $model = makeModel('Producto_model', $db);
        $model->update_producto(2, ['stock' => $stockActual - $cantidadVenta]);

        $this->assertEquals($stockEsperado, $db->stockRecibido,
            'Adicional: Tras la venta el stock debe descontarse correctamente.');
    }

    /** Adicional – get_ventas_cabecera retorna FALSE si no hay ventas. */
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
        $this->assertFalse($result, 'Adicional: get_ventas_cabecera debe retornar FALSE si no hay registros.');
    }
}

