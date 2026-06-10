<?php
/**
 * PRUEBA UNITARIA - modificar_producto
 * 
 * Ejecutar desde consola: php test_modificar_producto.php
 * O colocar en la raíz del proyecto y acceder desde el navegador.
 * 
 * Simula las mismas validaciones que hace el controlador antes de guardar.
 */

// ============================================================
//   FUNCIÓN QUE REPLICA LAS VALIDACIONES DEL CONTROLADOR
// ============================================================
function validarModificarProducto($p_id_producto, $p_descripcion, $p_stock, $p_precio_unitario, $p_estado) {

    // Simula buscarProducto: si id <= 0 se considera "no encontrado"
    if ($p_id_producto <= 0) {
        throw new RuntimeException("Producto no encontrado");
    }

    if ($p_stock < 0) {
        throw new RuntimeException("No se puede ingresar un numero negativo");
    }

    if ($p_precio_unitario < 0) {
        throw new RuntimeException("El valor del producto no puede ser menor a 0");
    }

    // Descripcion no puede ser solo números ni estar vacía
    if (empty($p_descripcion) || ctype_digit($p_descripcion)) {
        throw new RuntimeException("Ingrese un nombre valido");
    }

    if ($p_estado !== "Activo" && $p_estado !== "Inactivo") {
        throw new RuntimeException("Ingrese un estado valido");
    }

    // Si pasa todas las validaciones
    return "Producto modificado correctamente";
}


// ============================================================
//   FUNCIÓN AUXILIAR PARA EJECUTAR CADA CASO DE PRUEBA
// ============================================================
function ejecutarPrueba($nombre, $id, $descripcion, $stock, $precio, $estado, $esperaError = null) {
    $ok    = "\033[32m[PASS]\033[0m";
    $fail  = "\033[31m[FAIL]\033[0m";

    try {
        $resultado = validarModificarProducto($id, $descripcion, $stock, $precio, $estado);

        if ($esperaError === null) {
            echo "$ok  $nombre → $resultado\n";
        } else {
            echo "$fail $nombre → Se esperaba excepción: \"$esperaError\" pero no se lanzó ninguna\n";
        }

    } catch (RuntimeException $e) {
        if ($esperaError !== null && $e->getMessage() === $esperaError) {
            echo "$ok  $nombre → Excepción correcta: \"{$e->getMessage()}\"\n";
        } else {
            echo "$fail $nombre → Excepción inesperada: \"{$e->getMessage()}\"";
            if ($esperaError !== null) {
                echo " (se esperaba: \"$esperaError\")";
            }
            echo "\n";
        }
    }
}


// ============================================================
//   CASOS DE PRUEBA
// ============================================================
echo "\n========================================\n";
echo "  PRUEBAS UNITARIAS - modificarProducto\n";
echo "========================================\n\n";

// CASO 1: Producto no encontrado (id inválido)
ejecutarPrueba(
    "Producto no encontrado (id=0)",
    0, "Americano", 10, 5.50, "Activo",
    "Producto no encontrado"
);

// CASO 2: Stock negativo
ejecutarPrueba(
    "Stock negativo",
    1, "Americano", -5, 5.50, "Activo",
    "No se puede ingresar un numero negativo"
);

// CASO 3: Precio unitario negativo
ejecutarPrueba(
    "Precio unitario negativo",
    1, "Americano", 10, -1.0, "Activo",
    "El valor del producto no puede ser menor a 0"
);

// CASO 4: Descripcion vacía
ejecutarPrueba(
    "Descripcion vacia",
    1, "", 10, 5.50, "Activo",
    "Ingrese un nombre valido"
);

// CASO 5: Descripcion solo números
ejecutarPrueba(
    "Descripcion solo numeros (ej: '123')",
    1, "123", 10, 5.50, "Activo",
    "Ingrese un nombre valido"
);

// CASO 6: Estado inválido
ejecutarPrueba(
    "Estado invalido (ej: 'Pendiente')",
    1, "Americano", 10, 5.50, "Pendiente",
    "Ingrese un estado valido"
);

// CASO 7: Caso exitoso - datos correctos con estado "Activo"
ejecutarPrueba(
    "Datos validos - estado Activo",
    1, "Americano", 10, 5.50, "Activo"
    // Sin $esperaError → se espera éxito
);

// CASO 8: Caso exitoso - estado "Inactivo"
ejecutarPrueba(
    "Datos validos - estado Inactivo",
    2, "Cappuccino", 5, 3.75, "Inactivo"
);

// CASO 9: Stock = 0 (borde, válido)
ejecutarPrueba(
    "Stock en cero (borde valido)",
    1, "Espresso", 0, 2.50, "Activo"
);

// CASO 10: Precio = 0 (borde, válido)
ejecutarPrueba(
    "Precio en cero (borde valido)",
    1, "Agua", 20, 0.0, "Activo"
);

echo "\n========================================\n\n";
