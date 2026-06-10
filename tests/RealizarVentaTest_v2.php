<?php
/**
 * RealizarVentaTest_v2.php
 * Sistema: Coffee | Grupo 75 - ISII 2026
 *
 * Versión del test que usa VentaService como clase de lógica de negocio
 * en vez de tener la función validarVenta() inline en el test.
 *
 * Ejecutar con: ./vendor/bin/phpunit tests/RealizarVentaTest_v2.php
 */

require_once __DIR__ . '/../application/Services/VentaService.php';

use PHPUnit\Framework\TestCase;

class RealizarVentaTest_v2 extends TestCase
{
    private VentaService $service;

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
        'stock'        => 0,
        'stock_min'    => 3,
        'eliminado'    => 'NO',
    ];

    protected function setUp(): void
    {
        $this->service = new VentaService();
    }

    // CP1 - Venta con stock suficiente
    public function testCP1_VentaConStockSuficiente(): void
    {
        $resultado = $this->service->validarVenta($this->producto_cafe_expresso, 3);

        $this->assertTrue($resultado['ok']);
        $this->assertEquals('Venta Registrada Correctamente!', $resultado['mensaje']);
        $this->assertEquals(3, $resultado['cantidad_ajustada']);
    }

    // CP2 - Cantidad supera el stock
    public function testCP2_CantidadSuperaStock(): void
    {
        $resultado = $this->service->validarVenta($this->producto_cafe_expresso, 30);

        $this->assertFalse($resultado['ok']);
        $this->assertStringContainsString('Lo sentimos, la cantidad ingresada supera el stock disponible', $resultado['mensaje']);
        $this->assertStringContainsString('18', $resultado['mensaje']);
        $this->assertEquals(18, $resultado['cantidad_ajustada']);
    }

    // CP3 - Cantidad negativa
    public function testCP3_CantidadNegativa(): void
    {
        $resultado = $this->service->validarVenta($this->producto_cafe_expresso, -3);

        $this->assertFalse($resultado['ok']);
        $this->assertEquals('Ingrese una cantidad mayor al 0 por favor.', $resultado['mensaje']);
        $this->assertEquals(1, $resultado['cantidad_ajustada']);
    }

    // CP4 - Producto sin stock
    public function testCP4_ProductoSinStock(): void
    {
        $resultado = $this->service->validarVenta($this->producto_sin_stock, 1);

        $this->assertFalse($resultado['ok']);
        $this->assertEquals('No hay unidades disponibles', $resultado['mensaje']);
        $this->assertEquals(0, $resultado['cantidad_ajustada']);
    }

    // ADICIONAL - Descuento de stock tras venta
    public function testAdicional_DescuentoStockTrasVenta(): void
    {
        $stockResultante = $this->service->calcularStockTrasVenta(18, 3);

        $this->assertEquals(15, $stockResultante);
        $this->assertGreaterThanOrEqual($this->producto_cafe_expresso['stock_min'], $stockResultante);
    }

    // ADICIONAL - Cantidad cero también es inválida
    public function testAdicional_CantidadCeroEsInvalida(): void
    {
        $resultado = $this->service->validarVenta($this->producto_cafe_expresso, 0);

        $this->assertFalse($resultado['ok']);
        $this->assertEquals('Ingrese una cantidad mayor al 0 por favor.', $resultado['mensaje']);
    }
}
