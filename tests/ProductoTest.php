<?php
/**
 * RegistrarProductoTest.php
 * Pruebas Unitarias - Caso de Uso: Registrar / Agregar Producto
 * Sistema: Coffee | Grupo 75 - ISII 2026
 *
 * Ejecutar con: ./vendor/bin/phpunit tests/RegistrarProductoTest.php
 */

use PHPUnit\Framework\TestCase;

class RegistrarProductoTest extends TestCase
{
    /**
     * Simula la lógica de validación de formulario del controlador (producto_controller)
     * utilizando las reglas nativas de CodeIgniter para campos obligatorios.
     */
    private function validarFormularioProducto(array $post_data): array
    {
        $errores = [];

        if (empty($post_data['descripcion'])) {
            $errores[] = "El campo Descripción es obligatorio";
        }
        if (empty($post_data['id_categoria'])) {
            $errores[] = "El campo categoría es obligatorio"; // Minúscula como tus CP
        }
        if (empty($post_data['precio_costo'])) {
            $errores[] = "El campo Precio Costo es obligatorio";
        }
        if (empty($post_data['precio_venta'])) {
            $errores[] = "El campo Precio Venta es obligatorio";
        }
        if (!isset($post_data['stock']) || $post_data['stock'] === '') {
            $errores[] = "El campo Stock es obligatorio";
        }
        if (!isset($post_data['stock_minimo']) || $post_data['stock_minimo'] === '') {
            $errores[] = "El campo Stock mínimo es obligatorio";
        }
        if (empty($post_data['imagen'])) {
            $errores[] = "El campo imagen es obligatorio";
        }

        if (count($errores) > 0) {
            return [
                'ok' => false,
                'errores' => $errores
            ];
        }

        return [
            'ok' => true,
            'mensaje' => 'Producto registrado con éxito.'
        ];
    }

    // =========================================================================
    // CP1 - Registro Exitoso con Campos Completos
    // =========================================================================
    public function testCP1_AgregarProductoCamposCompletos(): void
    {
        $datos = [
            'descripcion'  => 'Frappé de Caramelo Toffee',
            'id_categoria' => '3',
            'precio_costo' => '3000.00',
            'precio_venta' => '4500.00',
            'stock'        => '50',
            'stock_minimo' => '2',
            'imagen'       => 'Frappé de Caramelo Toffee.jpg'
        ];

        $resultado = $this->validarFormularioProducto($datos);

        $this->assertTrue($resultado['ok'], 'CP1: Con todos los datos el producto debe registrarse');
        $this->assertEquals('Producto registrado con éxito.', $resultado['mensaje']);
    }

    // =========================================================================
    // CP2 al CP8 - Campos Incompletos Individuales
    // =========================================================================
    
    public function testCP2_FaltaDescripcion(): void
    {
        $datos = [
            'descripcion'  => '', // Vacío
            'id_categoria' => '3', 'precio_costo' => '3000.00', 'precio_venta' => '4500.00',
            'stock'        => '50', 'stock_minimo' => '2', 'imagen' => 'Frappé de Caramelo Toffee.jpg'
        ];

        $resultado = $this->validarFormularioProducto($datos);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo Descripción es obligatorio', $resultado['errores']);
    }

    public function testCP3_FaltaCategoria(): void
    {
        $datos = [
            'descripcion'  => 'Frappé de Caramelo Toffee',
            'id_categoria' => '', // Vacío
            'precio_costo' => '3000.00', 'precio_venta' => '4500.00',
            'stock'        => '50', 'stock_minimo' => '2', 'imagen' => 'Frappé de Caramelo Toffee.jpg'
        ];

        $resultado = $this->validarFormularioProducto($datos);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo categoría es obligatorio', $resultado['errores']);
    }

    public function testCP4_FaltaPrecioCosto(): void
    {
        $datos = [
            'descripcion'  => 'Frappé de Caramelo Toffee', 'id_categoria' => '3',
            'precio_costo' => '', // Vacío
            'precio_venta' => '4500.00', 'stock' => '50', 'stock_minimo' => '2',
            'imagen'       => 'Frappé de Caramelo Toffee.jpg'
        ];

        $resultado = $this->validarFormularioProducto($datos);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo Precio Costo es obligatorio', $resultado['errores']);
    }

    public function testCP5_FaltaPrecioVenta(): void
    {
        $datos = [
            'descripcion'  => 'Frappé de Caramelo Toffee', 'id_categoria' => '3',
            'precio_costo' => '3000.00',
            'precio_venta' => '', // Vacío
            'stock'        => '50', 'stock_minimo' => '2', 'imagen' => 'Frappé de Caramelo Toffee.jpg'
        ];

        $resultado = $this->validarFormularioProducto($datos);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo Precio Venta es obligatorio', $resultado['errores']);
    }

    public function testCP6_FaltaStock(): void
    {
        $datos = [
            'descripcion'  => 'Frappé de Caramelo Toffee', 'id_categoria' => '3',
            'precio_costo' => '3000.00', 'precio_venta' => '4500.00',
            'stock'        => '', // Vacío
            'stock_minimo' => '2', 'imagen' => 'Frappé de Caramelo Toffee.jpg'
        ];

        $resultado = $this->validarFormularioProducto($datos);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo Stock es obligatorio', $resultado['errores']);
    }

    public function testCP7_FaltaStockMinimo(): void
    {
        $datos = [
            'descripcion'  => 'Frappé de Caramelo Toffee', 'id_categoria' => '3',
            'precio_costo' => '3000.00', 'precio_venta' => '4500.00',
            'stock'        => '50',
            'stock_minimo' => '', // Vacío
            'imagen'       => 'Frappé de Caramelo Toffee.jpg'
        ];

        $resultado = $this->validarFormularioProducto($datos);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo Stock mínimo es obligatorio', $resultado['errores']);
    }

    public function testCP8_FaltaImagen(): void
    {
        $datos = [
            'descripcion'  => 'Frappé de Caramelo Toffee', 'id_categoria' => '3',
            'precio_costo' => '3000.00', 'precio_venta' => '4500.00',
            'stock'        => '50', 'stock_minimo' => '2',
            'imagen'       => '' // Vacío
        ];

        $resultado = $this->validarFormularioProducto($datos);

        $this->assertFalse($resultado['ok']);
        $this->assertContains('El campo imagen es obligatorio', $resultado['errores']);
    }

    // =========================================================================
    // CP9 - Todos los Campos Vacíos en Simultáneo
    // =========================================================================
    public function testCP9_TodosLosCamposVacios(): void
    {
        $datos = [
            'descripcion'  => '',
            'id_categoria' => '',
            'precio_costo' => '',
            'precio_venta' => '',
            'stock'        => '',
            'stock_minimo' => '',
            'imagen'       => ''
        ];

        $resultado = $this->validarFormularioProducto($datos);

        $this->assertFalse($resultado['ok'], 'CP9: Debe fallar debido a la ausencia total de datos');
        
        // Verificamos que se listen absolutamente todos los mensajes de error requeridos por tu documento
        $this->assertContains('El campo Descripción es obligatorio', $resultado['errores']);
        $this->assertContains('El campo categoría es obligatorio', $resultado['errores']);
        $this->assertContains('El campo Precio Costo es obligatorio', $resultado['errores']);
        $this->assertContains('El campo Precio Venta es obligatorio', $resultado['errores']);
        $this->assertContains('El campo Stock es obligatorio', $resultado['errores']);
        $this->assertContains('El campo Stock mínimo es obligatorio', $resultado['errores']);
        $this->assertContains('El campo imagen es obligatorio', $resultado['errores']);
    }
}
