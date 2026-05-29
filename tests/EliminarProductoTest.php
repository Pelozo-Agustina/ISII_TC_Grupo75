<?php
/**
 * EliminarProductoTest.php
 * Pruebas Unitarias - Caso de Uso: Eliminar Producto (Baja Lógica)
 * Sistema: Coffee | Grupo 75 - ISII 2026
 *
 * Ejecutar con: ./vendor/bin/phpunit tests/EliminarProductoTest.php
 */

use PHPUnit\Framework\TestCase;

class EliminarProductoTest extends TestCase
{
    // Simulamos nuestra tabla de productos de la Base de Datos
    private array $productos_db = [];

    /**
     * Configuración inicial antes de cada caso de prueba
     */
    protected function setUp(): void
    {
        // Insertamos el producto "Coulant" inicializado con Eliminado = NO
        $this->productos_db = [
            1 => [
                'id_producto'  => 1,
                'descripcion'  => 'Coulant',
                'id_categoria' => 3,
                'precio_costo' => 2100.00,
                'precio_venta' => 3400.00,
                'stock'        => 14,
                'eliminado'    => 'NO' // Estado inicial activo
            ]
        ];
    }

    /**
     * Simula la lógica de tu Producto_model::eliminar_producto()
     */
    private function ejecutarBajaLogica(int $id_producto): array
    {
        // Verificar si el producto existe en la base de datos
        if (!isset($this->productos_db[$id_producto])) {
            return [
                'ok'      => false,
                'mensaje' => 'El producto seleccionado no existe.'
            ];
        }

        // --- VALIDACIÓN DEL CP2: Si ya está eliminado, rechazamos la operación ---
        if ($this->productos_db[$id_producto]['eliminado'] === 'SI') {
            return [
                'ok'      => false,
                'mensaje' => 'El producto ya se encuentra eliminado lógicamente.'
            ];
        }

        // --- ACCIÓN DEL CP1: Cambiamos el estado a SI (Baja lógica) ---
        $this->productos_db[$id_producto]['eliminado'] = 'SI';

        return [
            'ok'      => true,
            'mensaje' => 'Producto eliminado de forma lógica correctamente.'
        ];
    }

    // =========================================================================
    // CP1 - Eliminar Producto Activo (Datos Válidos)
    // =========================================================================
    public function testCP1_EliminarProductoActivo(): void
    {
        // 1. Ejecutamos la baja del producto con ID 1 (Coulant)
        $resultado = $this->ejecutarBajaLogica(1);

        // 2. Verificaciones de PHPUnit
        $this->assertTrue($resultado['ok'], 
            'CP1: Un producto activo debe poder eliminarse lógicamente');
        
        $this->assertEquals('SI', $this->productos_db[1]['eliminado'], 
            'CP1: El campo "eliminado" debe haber cambiado estrictamente a "SI"');
        
        $this->assertEquals('Producto eliminado de forma lógica correctamente.', $resultado['mensaje']);
    }

    // =========================================================================
    // CP2 - Eliminar Producto Ya Eliminado (Datos Inválidos)
    // =========================================================================
    public function testCP2_EliminarProductoYaEliminado(): void
    {
        // 1. Forzamos el escenario inicial del CP2: el producto ya tiene "eliminado" => "SI"
        $this->productos_db[1]['eliminado'] = 'SI';

        // 2. Intentamos volver a eliminar el mismo producto
        $resultado = $this->ejecutarBajaLogica(1);

        // 3. Verificaciones de PHPUnit
        $this->assertFalse($resultado['ok'], 
            'CP2: El sistema no debe procesar la baja de un ítem que ya fue eliminado');
        
        $this->assertEquals('El producto ya se encuentra eliminado lógicamente.', $resultado['mensaje'],
            'CP2: Debe alertar al usuario que la acción no es necesaria o permitida');
        
        // Nos aseguramos de que el estado siga permaneciendo en SI sin romperse
        $this->assertEquals('SI', $this->productos_db[1]['eliminado']);
    }
}
