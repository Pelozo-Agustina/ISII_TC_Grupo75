<?php
/**
 * =============================================================================
 * CU1 – Iniciar Sesión (Tabla 17) – Modelo: LoginModel::validarUsuario()
 * Grupo 75 – Ingeniería de Software II – FACENA UNNE 2026
 * =============================================================================
 * Parte de la suite de tests del sistema Coffee. Instancia el modelo real
 * con un stub de $this->db (ver tests/helpers.php) para detectar roturas en
 * el código de producción real, no en lógica reescrita dentro del test.
 * =============================================================================
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/helpers.php';

class IniciarSesionTest extends TestCase
{
    /**
     * CP1 – Usuario y contraseña vacíos → FALSE.
     * validarUsuario llama a get_where con '' → BD devuelve 0 filas.
     */
    public function testCP1_LoginSinDatosRetornaFalse(): void
    {
        $db    = makeDbStub(makeQueryResult([]));
        $model = makeModel('LoginModel', $db);

        $result = $model->validarUsuario('', '');

        $this->assertFalse($result,
            'CP1: Con usuario y contraseña vacíos validarUsuario debe retornar FALSE.');
    }

    /**
     * CP2 – Datos correctos (usuario 'Ana', pass '1234') → array con la fila.
     */
    public function testCP2_LoginConDatosCorrectos(): void
    {
        $fila = makeRow(['id' => 3, 'nombre' => 'Ana', 'usuario' => 'Ana',
                         'pass' => '1234', 'perfil_id' => 2, 'baja' => 'NO']);
        $qr   = makeQueryResult([$fila]);

        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function get_where($t, $w, $l = null) { return $this->qr; }
        };

        $model  = makeModel('LoginModel', $db);
        $result = $model->validarUsuario('Ana', '1234');

        $this->assertIsArray($result, 'CP2: Login correcto debe retornar un array.');
        $this->assertNotEmpty($result, 'CP2: El array de resultado no debe estar vacío.');
        $this->assertEquals('Ana', $result[0]->nombre, 'CP2: El nombre del usuario debe coincidir.');
    }

    /** CP3 – Sin contraseña → FALSE. */
    public function testCP3_LoginSinContrasenia(): void
    {
        $db    = makeDbStub(makeQueryResult([]));
        $model = makeModel('LoginModel', $db);
        $this->assertFalse($model->validarUsuario('Ana', ''),
            'CP3: Sin contraseña validarUsuario debe retornar FALSE.');
    }

    /** CP4 – Sin usuario → FALSE. */
    public function testCP4_LoginSinUsuario(): void
    {
        $db    = makeDbStub(makeQueryResult([]));
        $model = makeModel('LoginModel', $db);
        $this->assertFalse($model->validarUsuario('', '1234'),
            'CP4: Sin usuario validarUsuario debe retornar FALSE.');
    }

    /** CP5 – Usuario no registrado → FALSE. */
    public function testCP5_LoginUsuarioNoRegistrado(): void
    {
        $db    = makeDbStub(makeQueryResult([]));
        $model = makeModel('LoginModel', $db);
        $this->assertFalse($model->validarUsuario('Jose', 'xyz'),
            'CP5: Usuario inexistente debe retornar FALSE.');
    }

    /**
     * Adicional – Usuario con baja='SI' no puede ingresar.
     * LoginModel filtra baja='NO' en la query → BD devuelve 0 filas.
     */
    public function testAdicional_UsuarioDadoDeBajaNoIngresa(): void
    {
        $db    = makeDbStub(makeQueryResult([]));
        $model = makeModel('LoginModel', $db);
        $this->assertFalse($model->validarUsuario('mario12', '1234'),
            'Adicional: Usuario con baja=SI no debe poder iniciar sesión.');
    }
}

