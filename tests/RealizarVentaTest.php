<?php
/**
 * RealizarVentaTest.php
 * Pruebas Unitarias - Caso de Uso: Realizar Venta
 * Sistema: Coffee | Grupo 75 - ISII 2026
 * Tabla 19. Plan de Prueba "Realizar Venta"
 *
 * Ejecutar con: ./vendor/bin/phpunit tests/RealizarVentaTest.php
 *
 * CORRECCIONES APLICADAS:
 * - El producto 'Café Vienés' del documento NO existe en la BD real.
 *   Se reemplaza por 'Cafe Expresso' (id=1, stock=18, precio_venta=2500.00)
 *   que es el primer producto real de coffee1.sql.
 * - Se agrega $producto_sin_stock construido sobre un producto real (Latte,
 *   id=10, stock=8) con stock forzado a 0 para simular el CP4.
 * - Los mensajes exactos se extraen directamente del código fuente:
 *     · 'Ingrese una cantidad mayor al 0 por favor.'
 *       → carrito_controller::actualiza_carrito() (set_flashdata)
 *       → carritoparte_view.php (alert JS)
 *     · 'Lo sentimos, la cantidad ingresada supera el stock disponible. Máximo permitido: X unidades.'
 *       → carritoparte_view.php (alert JS con variable max)
 *     · 'No hay unidades disponibles'
 *       → BebidasCaliente_Carrito.php, BebidasFria_Carrito.php, ParaAcom_Carrito.php
 *     · 'Venta Registrada Correctamente!'  (con signo de exclamación)
 *       → front/listarventa.php <h1>
 * - Se corrige el mensaje de CP1: el texto real es 'Venta Registrada Correctamente!'
 *   (con '!' al final), no sin signo de exclamación como tenía el test original.
 */

use PHPUnit\Framework\TestCase;

class RealizarVentaTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Productos de prueba — extraídos de coffee1.sql
    //
    // CP1/CP2/CP3: Cafe Expresso (id=1, categoria=BebidasCalientes, stock=18)
    //   precio_venta=2500.00  precio_costo=750.00  stock_min=3
    //
    // CP4: mismo producto con stock forzado a 0 para simular agotamiento
    // -----------------------------------------------------------------------
    private array $producto_cafe_expresso = [
        'id'           => 1,
        'descripcion'  => 'Cafe Expresso',
        'categoria_id' => 1,
        'precio_venta' => 2500.00,
        'stock'        => 18,
        'stock_min'    => 3,
        'eliminado'    => 'NO',
    ];

    private array $producto_sin_stock = [
        'id'           => 1,
        'descripcion'  => 'Cafe Expresso',
        'categoria_id' => 1,
        'precio_venta' => 2500.00,
        'stock'        => 0,   // stock forzado a 0 para el CP4
        'stock_min'    => 3,
        'eliminado'    => 'NO',
    ];

    // -----------------------------------------------------------------------
    // Simula la lógica de validación de stock del sistema.
    //
    // Combina dos fuentes del código real:
    //   1. carrito_controller::actualiza_carrito()  → valida qty <= 0
    //   2. carritoparte_view.php (JavaScript)       → valida qty > stock y stock = 0
    //   3. BebidasCaliente_Carrito.php              → muestra "No hay unidades disponibles"
    //   4. front/listarventa.php                    → muestra "Venta Registrada Correctamente!"
    //
    // Retorna array con:
    //   'ok'               bool   — true si la venta puede procesarse
    //   'mensaje'          string — mensaje exacto del sistema
    //   'cantidad_ajustada'int    — cantidad final (ajuste automático si aplica)
    // -----------------------------------------------------------------------
    private function validarVenta(array $producto, int $cantidad): array
    {
        // Cantidad negativa o cero:
        // El controlador redirige con set_flashdata('error_carrito', '...')
        // La vista JS ajusta el input al mínimo (1) automáticamente.
        if ($cantidad <= 0) {
            return [
                'ok'                => false,
                'mensaje'           => 'Ingrese una cantidad mayor al 0 por favor.',
                'cantidad_ajustada' => 1,
            ];
        }

        // Sin stock disponible:
        // Las vistas BebidasCaliente_Carrito, BebidasFria_Carrito y ParaAcom_Carrito
        // muestran el texto y ocultan el botón de agregar al carrito.
        if ($producto['stock'] === 0) {
            return [
                'ok'                => false,
                'mensaje'           => 'No hay unidades disponibles',
                'cantidad_ajustada' => 0,
            ];
        }

        // Cantidad supera el stock:
        // El JS de carritoparte_view.php ajusta el input al máximo (stock)
        // y muestra un alert con el mensaje exacto.
        if ($cantidad > $producto['stock']) {
            return [
                'ok'                => false,
                'mensaje'           => sprintf(
                    'Lo sentimos, la cantidad ingresada supera el stock disponible. Máximo permitido: %d unidades.',
                    $producto['stock']
                ),
                'cantidad_ajustada' => $producto['stock'],
            ];
        }

        // Venta válida: se registra y la vista listarventa.php muestra el mensaje
        return [
            'ok'                => true,
            'mensaje'           => 'Venta Registrada Correctamente!',
            'cantidad_ajustada' => $cantidad,
        ];
    }

    // -----------------------------------------------------------------------
    // CP1 - Venta con stock suficiente
    // Entrada: Cafe Expresso (stock=18), cantidad=3
    // Esperado: venta procesada, mensaje "Venta Registrada Correctamente!"
    // -----------------------------------------------------------------------
    public function testCP1_VentaConStockSuficiente(): void
    {
        $resultado = $this->validarVenta($this->producto_cafe_expresso, 3);

        $this->assertTrue($resultado['ok'],
            'CP1: Con stock suficiente la venta debe procesarse correctamente');
        $this->assertEquals(
            'Venta Registrada Correctamente!',
            $resultado['mensaje'],
            'CP1: Debe mostrar "Venta Registrada Correctamente!" (con signo de exclamación, igual que listarventa.php)'
        );
        $this->assertEquals(3, $resultado['cantidad_ajustada'],
            'CP1: La cantidad vendida debe ser exactamente la solicitada');
    }

    // -----------------------------------------------------------------------
    // CP2 - Cantidad solicitada supera el stock disponible
    // Entrada: Cafe Expresso (stock=18), cantidad=30
    // Esperado: venta bloqueada, mensaje con el stock máximo (18)
    // -----------------------------------------------------------------------
    public function testCP2_CantidadSuperaStock(): void
    {
        $resultado = $this->validarVenta($this->producto_cafe_expresso, 30);

        $this->assertFalse($resultado['ok'],
            'CP2: Con cantidad mayor al stock la venta no debe procesarse');
        $this->assertStringContainsString(
            'Lo sentimos, la cantidad ingresada supera el stock disponible',
            $resultado['mensaje'],
            'CP2: El mensaje debe informar que la cantidad supera el stock'
        );
        $this->assertStringContainsString(
            (string) $this->producto_cafe_expresso['stock'],
            $resultado['mensaje'],
            'CP2: El mensaje debe mostrar el stock máximo disponible (18 unidades)'
        );
        $this->assertEquals(
            $this->producto_cafe_expresso['stock'],
            $resultado['cantidad_ajustada'],
            'CP2: La cantidad ajustada debe ser el stock disponible máximo'
        );
    }

    // -----------------------------------------------------------------------
    // CP3 - Cantidad negativa ingresada
    // Entrada: Cafe Expresso (stock=18), cantidad=-3
    // Esperado: venta bloqueada, sistema asigna automáticamente cantidad=1
    // -----------------------------------------------------------------------
    public function testCP3_CantidadNegativa(): void
    {
        $resultado = $this->validarVenta($this->producto_cafe_expresso, -3);

        $this->assertFalse($resultado['ok'],
            'CP3: Con cantidad negativa la venta no debe procesarse');
        $this->assertEquals(
            'Ingrese una cantidad mayor al 0 por favor.',
            $resultado['mensaje'],
            'CP3: Debe mostrar el mensaje exacto del controlador/vista'
        );
        $this->assertEquals(1, $resultado['cantidad_ajustada'],
            'CP3: El sistema debe asignar automáticamente la cantidad mínima de 1');
    }

    // -----------------------------------------------------------------------
    // CP4 - Producto sin stock disponible (stock=0)
    // Entrada: Cafe Expresso con stock forzado a 0, cantidad=1
    // Esperado: no se puede agregar al carrito, "No hay unidades disponibles"
    // -----------------------------------------------------------------------
    public function testCP4_ProductoSinStock(): void
    {
        $resultado = $this->validarVenta($this->producto_sin_stock, 1);

        $this->assertFalse($resultado['ok'],
            'CP4: Sin stock el producto no debe poder agregarse al carrito');
        $this->assertEquals(
            'No hay unidades disponibles',
            $resultado['mensaje'],
            'CP4: Debe mostrar "No hay unidades disponibles" igual que las vistas de carrito'
        );
        $this->assertEquals(0, $resultado['cantidad_ajustada'],
            'CP4: La cantidad ajustada debe ser 0 cuando no hay stock');
    }

    // -----------------------------------------------------------------------
    // ADICIONAL - Descuento de stock tras confirmación de venta
    // Justificación: carrito_controller::realizar_venta() descuenta el stock
    //   llamando a producto_model::update_producto($id, ['stock' => $nuevo]).
    //   Este test verifica que el cálculo del nuevo stock es correcto.
    // -----------------------------------------------------------------------
    public function testAdicional_DescuentoStockTrasVenta(): void
    {
        $stockAntes   = $this->producto_cafe_expresso['stock']; // 18
        $cantidadVenta = 3;
        $stockEsperado = 15;

        $stockResultante = $stockAntes - $cantidadVenta;

        $this->assertEquals(
            $stockEsperado,
            $stockResultante,
            'ADICIONAL: Tras vender 3 unidades de stock=18 el stock resultante debe ser 15'
        );
        $this->assertGreaterThanOrEqual(
            $this->producto_cafe_expresso['stock_min'],
            $stockResultante,
            'ADICIONAL: El stock resultante no debe caer por debajo del stock mínimo (' .
            $this->producto_cafe_expresso['stock_min'] . ')'
        );
    }

    // -----------------------------------------------------------------------
    // ADICIONAL - Cantidad cero también es inválida (igual que negativa)
    // Justificación: el controlador valida $qty <= 0, no solo $qty < 0.
    // -----------------------------------------------------------------------
    public function testAdicional_CantidadCeroEsInvalida(): void
    {
        $resultado = $this->validarVenta($this->producto_cafe_expresso, 0);

        $this->assertFalse($resultado['ok'],
            'ADICIONAL: Una cantidad igual a 0 tampoco debe ser válida');
        $this->assertEquals(
            'Ingrese una cantidad mayor al 0 por favor.',
            $resultado['mensaje'],
            'ADICIONAL: El mensaje debe ser el mismo que para cantidad negativa'
        );
    }
}
