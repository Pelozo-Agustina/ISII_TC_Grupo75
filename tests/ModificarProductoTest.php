<?php
/**
 * ModificarProductoTest.php
 * Pruebas Unitarias - Caso de Uso: Modificar Producto
 * Sistema: Coffee | Grupo 75 - ISII 2026
 *
 * Ejecutar con: ./vendor/bin/phpunit tests/ModificarProductoTest.php
 */

use PHPUnit\Framework\TestCase;

class ModificarProductoTest extends TestCase
{
    private array $productos_db = [];

    /**
     * Configuración inicial del escenario de prueba
     */
    protected function setUp(): void
    {
        $this->productos_db = [
            10 => [
                'id_producto'  => 10,
                'descripcion'  => 'Capuccino Tradicional',
                'id_categoria' => 1,
                'precio_costo' => 1500.00,
                'precio_venta' => 2500.00,
                'stock'        => 30,
                'stock_minimo' => 5,
                'imagen'       => 'capuccino.jpg'
            ]
        ];
    }

    /**
     * Simula la validación y actualización del controlador
     */
    private function actualizarProducto(int $id_producto, array $nuevos_datos): array
    {
        if (!isset($this->productos_db[$id_producto])) {
            return [
                'ok' => false,
                'errores' => ['El producto a modificar no existe.']
            ];
        }

        $errores = [];

        if (empty($nuevos_datos['descripcion'])) {
            $errores[] = "El campo Descripción es obligatorio";
        }
        if (empty($nuevos_datos['id_categoria'])) {
            $errores[] = "El campo categoría es obligatorio";
        }
        if (empty($nuevos_datos['precio_costo'])) {
            $errores[] = "El campo Precio Costo es obligatorio";
        }
        if (empty($nuevos_datos['precio_venta'])) {
            $errores[] = "El campo Precio Venta es obligatorio";
        }
        if (!isset($nuevos_datos['stock']) || $nuevos_datos['stock'] === '') {
            $errores[] = "El campo Stock es obligatorio";
        }

        if (count($errores) > 0) {
            return [
                'ok' => false,
                'errores' => $errores
            ];
        }

        $this->productos_db[$id_producto] = array_merge($this->productos_db[$id_producto], $nuevos_datos);

        return [
            'ok' => true,
            'mensaje' => 'Producto modificado con éxito.'
        ];
    }

    // =========================================================================
    // CP1 - Modificación Exitosa con Todos los Datos Válidos
    // =========================================================================
    public function testCP1_ModificarProductoExitoso(): void
    {
        $cambios = [
            'descripcion'  => 'Capuccino Supremo Italiano',
            'id_categoria' => 1,
            'precio_costo' => 1800.00,
            'precio_venta' => 3000.00,
            'stock'        => 45,
            'stock_minimo' => 5,
            'imagen'       => 'capuccino_supremo.jpg'
        ];

        $resultado = $this->actualizarProducto(10, $cambios);

        $this->assertTrue($resultado['ok']);
        $this->assertEquals('Producto modificado con éxito.', $resultado['mensaje']);
        $this->assertEquals('Capuccino Supremo Italiano', $this->productos_db[10]['descripcion']);
    }

    // =========================================================================
    // CP2 - Intentar Modificar dejando la Descripción Vacía
    // =========================================================================
    public function testCP2_ModificarSinDescripcion(): void
    {
        $cambios = [
            'descripcion'  => '', // VACÍO
            'id_categoria' => 1, 'precio_costo' => 1500.00, 'precio_venta' => 2500.00, 'stock' => 30, 'stock_minimo' => 5, 'imagen' => 'capuccino.jpg'
        ];

        $resultado = $this->actualizarProducto(10, $cambios);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo Descripción es obligatorio', $resultado['errores']);
        $this->assertEquals('Capuccino Tradicional', $this->productos_db[10]['descripcion']);
    }

    // =========================================================================
    // CP3 - Intentar Modificar dejando la Categoría Vacía
    // =========================================================================
    public function testCP3_ModificarSinCategoria(): void
    {
        $cambios = [
            'descripcion'  => 'Capuccino Tradicional',
            'id_categoria' => '', // VACÍO
            'precio_costo' => 1500.00, 'precio_venta' => 2500.00, 'stock' => 30, 'stock_minimo' => 5, 'imagen' => 'capuccino.jpg'
        ];

        $resultado = $this->actualizarProducto(10, $cambios);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo categoría es obligatorio', $resultado['errores']);
    }

    // =========================================================================
    // CP4 - Intentar Modificar dejando el Precio Costo Vacío
    // =========================================================================
    public function testCP4_ModificarSinPrecioCosto(): void
    {
        $cambios = [
            'descripcion'  => 'Capuccino Tradicional',
            'id_categoria' => 1,
            'precio_costo' => '', // VACÍO
            'precio_venta' => 2500.00, 'stock' => 30, 'stock_minimo' => 5, 'imagen' => 'capuccino.jpg'
        ];

        $resultado = $this->actualizarProducto(10, $cambios);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo Precio Costo es obligatorio', $resultado['errores']);
    }

    // =========================================================================
    // CP5 - Intentar Modificar dejando el Precio de Venta Vacío
    // =========================================================================
    public function testCP5_ModificarSinPrecioVenta(): void
    {
        $cambios = [
            'descripcion'  => 'Capuccino Tradicional',
            'id_categoria' => 1,
            'precio_costo' => 1500.00,
            'precio_venta' => '', // VACÍO
            'stock'        => 30, 'stock_minimo' => 5, 'imagen' => 'capuccino.jpg'
        ];

        $resultado = $this->actualizarProducto(10, $cambios);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo Precio Venta es obligatorio', $resultado['errores']);
    }

    // =========================================================================
    // CP6 - Intentar Modificar dejando el Stock Vacío
    // =========================================================================
    public function testCP6_ModificarSinStock(): void
    {
        $cambios = [
            'descripcion'  => 'Capuccino Tradicional',
            'id_categoria' => 1,
            'precio_costo' => 1500.00, 'precio_venta' => 2500.00,
            'stock'        => '', // VACÍO
            'stock_minimo' => 5, 'imagen' => 'capuccino.jpg'
        ];

        $resultado = $this->actualizarProducto(10, $cambios);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo Stock es obligatorio', $resultado['errores']);
    }
}
