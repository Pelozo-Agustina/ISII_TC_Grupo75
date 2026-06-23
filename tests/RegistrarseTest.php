<?php
/**
 * =============================================================================
 * CU2 – Registrarse (Tabla 18)
 * Grupo 75 – Ingeniería de Software II – FACENA UNNE 2026
 * =============================================================================
 * Parte de la suite de tests del sistema Coffee. Instancia el modelo real
 * con un stub de $this->db (ver tests/helpers.php) para detectar roturas en
 * el código de producción real, no en lógica reescrita dentro del test.
 * =============================================================================
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/helpers.php';

class RegistrarseTest extends TestCase
{
    /** CP1 – Registro con datos válidos → insert_id correcto. */
    public function testCP1_RegistroExitoso(): void
    {
        $db = new class {
            public function insert($t, $d): bool { return true; }
            public function insert_id(): int     { return 5; }
        };
        $model = makeModel('Usuario_model', $db);
        $data  = ['nombre' => 'María', 'apellido' => 'Herrera',
                  'email' => 'maria10@gmail.com', 'perfil_id' => '2',
                  'usuario' => 'Maria10', 'pass' => 'Maria1234'];

        $id = $model->add_user($data);

        $this->assertEquals(5, $id, 'CP1: add_user debe retornar el insert_id del nuevo usuario.');
    }

    /** CP8 – Insert falla (email/usuario duplicado a nivel BD) → insert_id = 0. */
    public function testCP8_RegistroEmailDuplicadoDBFalla(): void
    {
        $db = new class {
            public function insert($t, $d): bool { return false; }
            public function insert_id(): int     { return 0; }
        };
        $model = makeModel('Usuario_model', $db);
        $data  = ['nombre' => 'Juan', 'apellido' => 'Pérez',
                  'email' => 'juanperez@gmail.com', 'perfil_id' => '2',
                  'usuario' => 'juanPerez', 'pass' => '1234'];

        $id = $model->add_user($data);

        $this->assertEquals(0, $id, 'CP8: insert_id debe ser 0 si la inserción falla.');
    }

    /** Adicional – get_usuarios devuelve resultados cuando hay filas. */
    public function testAdicional_GetUsuariosDevuelveResultado(): void
    {
        $fila = makeRow(['id' => 1, 'nombre' => 'Admin', 'baja' => 'NO']);
        $qr   = makeQueryResult([$fila]);
        $db   = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get($t) { return $this->qr; }
        };
        $model  = makeModel('Usuario_model', $db);
        $result = $model->get_usuarios();
        $this->assertNotFalse($result, 'Adicional: get_usuarios debe retornar resultado cuando hay usuarios.');
    }

    /** Adicional – get_usuarios retorna FALSE cuando la tabla está vacía. */
    public function testAdicional_GetUsuariosRetornaFalseSiVacio(): void
    {
        $qr = makeQueryResult([]);
        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get($t) { return $this->qr; }
        };
        $model  = makeModel('Usuario_model', $db);
        $result = $model->get_usuarios();
        $this->assertFalse($result, 'Adicional: get_usuarios debe retornar FALSE si no hay filas.');
    }

    /** Adicional – Baja lógica de usuario (baja='SI') → TRUE. */
    public function testAdicional_BajaLogicaUsuario(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };
        $model  = makeModel('Usuario_model', $db);
        $result = $model->estado_usuario(3, ['baja' => 'SI']);
        $this->assertTrue($result, 'Adicional: estado_usuario con baja=SI debe retornar TRUE.');
    }

    /** Adicional – Activar usuario (baja='NO') → TRUE. */
    public function testAdicional_ActivarUsuario(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function update($t, $d): bool       { return true; }
        };
        $model  = makeModel('Usuario_model', $db);
        $result = $model->estado_usuario(3, ['baja' => 'NO']);
        $this->assertTrue($result, 'Adicional: Activar usuario debe retornar TRUE.');
    }
}

