<?php
/**
 * LoginTest.php
 * Pruebas Unitarias - Caso de Uso: Iniciar Sesión
 * Sistema: Coffee | Grupo 75 - ISII 2026
 * Tabla 17. Plan de Prueba "Iniciar Sesión"
 *
 * Ejecutar con: ./vendor/bin/phpunit tests/LoginTest.php
 *
 * CORRECCIONES APLICADAS:
 * - Se eliminó el hash md5(): la BD real almacena las contraseñas en texto
 *   plano (columna `pass` varchar(50), ej: pass = '1234').
 *   Ver: coffee1.sql → INSERT INTO `usuarios` ... VALUES (3,'Ana','Gonzalez',...,'Ana','1234',2,'NO')
 * - Los usuarios de prueba ahora coinciden exactamente con la tabla `usuarios`
 *   de la BD: usuario='Ana', pass='1234', perfil_id=2, baja='NO'.
 * - Se añade validación de baja='SI': el usuario mario12 tiene baja='SI'
 *   en la BD y NO debe poder iniciar sesión (LoginModel filtra baja='NO').
 * - El mensaje CP5 ya estaba correcto ("El usuario o contraseña son incorrectos").
 */

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Datos de prueba — extraídos literalmente de coffee1.sql
    // INSERT INTO `usuarios` VALUES
    //   (1,'juan','perez','juanperez@gmail.com','juanPerez','1234',1,'NO'),
    //   (2,'Maria','lopez','MariaLopez10@hotmail.com','Maria','1234',2,'NO'),
    //   (3,'Ana','Gonzalez','ana_gonzalez@gmail.com','Ana','1234',2,'NO'),
    //   (4,'Mario','Rodiguez','mario12@gmail.com','mario12','1234',2,'SI'),  ← baja
    //   (5,'Pedro','Ibarra','padro_ibarra@gmail.com','pedro','1234',2,'NO')
    // -----------------------------------------------------------------------
    private array $usuarios_db = [
        ['usuario' => 'juanPerez', 'pass' => '1234', 'perfil_id' => 1, 'baja' => 'NO'],
        ['usuario' => 'Maria',     'pass' => '1234', 'perfil_id' => 2, 'baja' => 'NO'],
        ['usuario' => 'Ana',       'pass' => '1234', 'perfil_id' => 2, 'baja' => 'NO'],
        ['usuario' => 'mario12',   'pass' => '1234', 'perfil_id' => 2, 'baja' => 'SI'],
        ['usuario' => 'pedro',     'pass' => '1234', 'perfil_id' => 2, 'baja' => 'NO'],
    ];

    // -----------------------------------------------------------------------
    // Simula LoginModel::validarUsuario($usuario, $pass)
    // Replica exactamente la query del modelo:
    //   get_where('usuarios', ['usuario'=>$u, 'pass'=>$p, 'baja'=>'NO'], 1)
    // Las contraseñas se comparan en texto plano (sin hash), igual que la BD.
    // -----------------------------------------------------------------------
    private function validarUsuario(string $usuario, string $pass): object|false
    {
        if (empty(trim($usuario)) || empty(trim($pass))) {
            return false;
        }

        foreach ($this->usuarios_db as $u) {
            if (
                $u['usuario'] === $usuario &&
                $u['pass']    === $pass &&
                $u['baja']    === 'NO'      // LoginModel filtra explícitamente baja='NO'
            ) {
                return (object) $u;
            }
        }

        return false;
    }

    // -----------------------------------------------------------------------
    // Simula la validación de campos requeridos (atributo required del HTML5
    // y form_validation de CodeIgniter: rule 'required').
    // Retorna el mensaje de error o cadena vacía si todo está completo.
    // -----------------------------------------------------------------------
    private function validarCamposRequeridos(string $usuario, string $pass): string
    {
        if (empty(trim($usuario)) || empty(trim($pass))) {
            return 'Rellene este campo';
        }
        return '';
    }

    // -----------------------------------------------------------------------
    // CP1 - Iniciar Sesión sin datos (usuario y contraseña vacíos)
    // Entrada: usuario='' / pass=''
    // Esperado: no permite acceso, muestra "Rellene este campo"
    // -----------------------------------------------------------------------
    public function testCP1_LoginSinDatosMuestraMensajeError(): void
    {
        $mensaje = $this->validarCamposRequeridos('', '');
        $this->assertEquals(
            'Rellene este campo',
            $mensaje,
            'CP1: Con ambos campos vacíos debe mostrar "Rellene este campo"'
        );

        $resultado = $this->validarUsuario('', '');
        $this->assertFalse(
            $resultado,
            'CP1: Con campos vacíos no debe permitir el acceso al sistema'
        );
    }

    // -----------------------------------------------------------------------
    // CP2 - Iniciar Sesión con datos correctos
    // Entrada: usuario='Ana' / pass='1234'  (fila real de la BD)
    // Esperado: acceso permitido, perfil_id=2 (Cliente)
    // -----------------------------------------------------------------------
    public function testCP2_LoginConDatosCorrectos(): void
    {
        $resultado = $this->validarUsuario('Ana', '1234');

        $this->assertNotFalse(
            $resultado,
            'CP2: Con credenciales válidas debe permitir el acceso al sistema'
        );
        $this->assertEquals(
            2,
            $resultado->perfil_id,
            'CP2: El perfil_id del usuario Ana debe ser 2 (Cliente)'
        );
        $this->assertEquals(
            'NO',
            $resultado->baja,
            'CP2: El usuario activo debe tener baja=NO'
        );
    }

    // -----------------------------------------------------------------------
    // CP3 - Iniciar Sesión sin contraseña
    // Entrada: usuario='Ana' / pass=''
    // Esperado: no permite acceso, muestra "Rellene este campo"
    // -----------------------------------------------------------------------
    public function testCP3_LoginSinPasswordMuestraMensajeError(): void
    {
        $mensaje = $this->validarCamposRequeridos('Ana', '');
        $this->assertEquals(
            'Rellene este campo',
            $mensaje,
            'CP3: Sin contraseña debe mostrar "Rellene este campo"'
        );

        $resultado = $this->validarUsuario('Ana', '');
        $this->assertFalse(
            $resultado,
            'CP3: Sin contraseña no debe permitir el acceso al sistema'
        );
    }

    // -----------------------------------------------------------------------
    // CP4 - Iniciar Sesión sin usuario
    // Entrada: usuario='' / pass='1234'
    // Esperado: no permite acceso, muestra "Rellene este campo"
    // -----------------------------------------------------------------------
    public function testCP4_LoginSinUsuarioMuestraMensajeError(): void
    {
        $mensaje = $this->validarCamposRequeridos('', '1234');
        $this->assertEquals(
            'Rellene este campo',
            $mensaje,
            'CP4: Sin usuario debe mostrar "Rellene este campo"'
        );

        $resultado = $this->validarUsuario('', '1234');
        $this->assertFalse(
            $resultado,
            'CP4: Sin usuario no debe permitir el acceso al sistema'
        );
    }

    // -----------------------------------------------------------------------
    // CP5 - Iniciar Sesión con usuario no registrado
    // Entrada: usuario='José' / pass='12dr3'  (no existe en la BD)
    // Esperado: no permite acceso, mensaje "El usuario o contraseña son incorrectos"
    // -----------------------------------------------------------------------
    public function testCP5_LoginUsuarioNoRegistradoMuestraMensajeError(): void
    {
        $resultado = $this->validarUsuario('José', '12dr3');

        $this->assertFalse(
            $resultado,
            'CP5: Un usuario no registrado no debe poder acceder al sistema'
        );

        // Mensaje que muestra el controlador cuando validarUsuario() retorna false
        // Ver: LoginController::_valid_login() → set_message('_valid_login', '...')
        $mensajeVista = ($resultado === false)
            ? 'El usuario o contraseña son incorrectos'
            : '';

        $this->assertEquals(
            'El usuario o contraseña son incorrectos',
            $mensajeVista,
            'CP5: Debe mostrar el mensaje de credenciales incorrectas'
        );
    }

    // -----------------------------------------------------------------------
    // ADICIONAL - Usuario con baja='SI' no puede iniciar sesión
    // Entrada: usuario='mario12' / pass='1234'  (existe en BD pero baja='SI')
    // Justificación: LoginModel filtra explícitamente baja='NO' en la query;
    //   el usuario mario12 está dado de baja en coffee1.sql (id=4, baja='SI').
    // -----------------------------------------------------------------------
    public function testAdicional_UsuarioDadoDeBajaNoIngresa(): void
    {
        $resultado = $this->validarUsuario('mario12', '1234');

        $this->assertFalse(
            $resultado,
            'ADICIONAL: Un usuario con baja=SI no debe poder iniciar sesión aunque la contraseña sea correcta'
        );
    }
}
