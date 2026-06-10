<?php
/**
 * VentaService.php
 * Sistema: Coffee | Grupo 75 - ISII 2026
 *
 * Clase de lógica de negocio para Realizar Venta.
 * Implementa los métodos que el test RealizarVentaTest.php necesita.
 *
 * Esta clase NO extiende CI_Controller ni usa $this->db.
 * Es pura lógica de negocio, aislada, testeable con PHPUnit sin base de datos.
 *
 * Relación con el sistema real:
 *   - La validación de stock corresponde a carrito_controller::actualiza_carrito()
 *   - El mensaje de éxito corresponde a front/listarventa.php
 *   - El descuento de stock corresponde a carrito_controller::realizar_venta()
 */

class VentaService
{
    // =========================================================================
    // validarVenta()
    // =========================================================================
    // Valida si una venta puede procesarse según las reglas del negocio.
    //
    // @param array $producto  Array con datos del producto (id, descripcion, stock, precio_venta, etc.)
    // @param int   $cantidad  Cantidad que el cliente quiere comprar
    //
    // @return array [
    //     'ok'                => bool,   // true si la venta es válida
    //     'mensaje'           => string, // mensaje exacto del sistema
    //     'cantidad_ajustada' => int     // cantidad final (puede ajustarse)
    // ]
    //
    // Casos cubiertos (Tabla 19 del informe):
    //   CP1 - Venta normal con stock suficiente            → ok: true
    //   CP2 - Cantidad supera el stock disponible          → ok: false
    //   CP3 - Cantidad negativa (el sistema ajusta a 1)    → ok: false
    //   CP4 - Producto sin stock (stock = 0)               → ok: false
    // =========================================================================
    public function validarVenta(array $producto, int $cantidad): array
    {
        // --- CP3: Cantidad negativa o cero ---
        // Fuente real: carrito_controller::actualiza_carrito() → set_flashdata
        // La vista JS también ajusta el input al mínimo (1) automáticamente.
        if ($cantidad <= 0) {
            return [
                'ok'                => false,
                'mensaje'           => 'Ingrese una cantidad mayor al 0 por favor.',
                'cantidad_ajustada' => 1,  // ajuste automático del sistema
            ];
        }

        // --- CP4: Sin stock disponible ---
        // Fuente real: BebidasCaliente_Carrito.php / BebidasFria_Carrito.php / ParaAcom_Carrito.php
        // El botón "Agregar al carrito" se oculta y se muestra el texto.
        if ($producto['stock'] === 0) {
            return [
                'ok'                => false,
                'mensaje'           => 'No hay unidades disponibles',
                'cantidad_ajustada' => 0,
            ];
        }

        // --- CP2: Cantidad supera el stock ---
        // Fuente real: carritoparte_view.php (JavaScript) → alert con variable 'max'
        if ($cantidad > $producto['stock']) {
            return [
                'ok'                => false,
                'mensaje'           => sprintf(
                    'Lo sentimos, la cantidad ingresada supera el stock disponible. Máximo permitido: %d unidades.',
                    $producto['stock']
                ),
                'cantidad_ajustada' => $producto['stock'], // el JS ajusta al máximo
            ];
        }

        // --- CP1: Venta válida ---
        // Fuente real: front/listarventa.php → <h1>Venta Registrada Correctamente!</h1>
        return [
            'ok'                => true,
            'mensaje'           => 'Venta Registrada Correctamente!',
            'cantidad_ajustada' => $cantidad,
        ];
    }

    // =========================================================================
    // calcularStockTrasVenta()
    // =========================================================================
    // Calcula el nuevo stock luego de descontar la cantidad vendida.
    //
    // @param int $stockActual   Stock antes de la venta
    // @param int $cantidadVenta Cantidad que se vendió
    //
    // @return int  Nuevo stock resultante
    //
    // Fuente real: carrito_controller::realizar_venta()
    //   $stock_edit = $stock - $item['qty'];
    //   $this->producto_model->update_producto($item['id'], ['stock' => $stock_edit]);
    // =========================================================================
    public function calcularStockTrasVenta(int $stockActual, int $cantidadVenta): int
    {
        return $stockActual - $cantidadVenta;
    }
}
