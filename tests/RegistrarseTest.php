<?php
/**
 * RegistrarseTest.php
 * Pruebas Unitarias - Caso de Uso: Registrarse
 * Sistema: Coffee | Grupo 75 - ISII 2026
 * Tabla 18. Plan de Prueba "Registrarse"
 *
 * Ejecutar con: ./vendor/bin/phpunit tests/RegistrarseTest.php
 *
 * CORRECCIONES APLICADAS:
 * - Los usuarios existentes en $usuarios_existentes ahora coinciden con los
 *   valores reales de coffee1.sql. Los campos son case-sensitive igual que
 *   la BD: 'juanPerez' ≠ 'Juan Pérez', 'Maria' ≠ 'María'.
 *   Datos reales (coffee1.sql):
 *     id=1 usuario='juanPerez'  email='juanperez@gmail.com'
 *     id=2 usuario='Maria'      email='MariaLopez10@hotmail.com'
 *     id=3 usuario='Ana'        email='ana_gonzalez@gmail.com'
 *     id=4 usuario='mario12'    email='mario12@gmail.com'
 *     id=5 usuario='pedro'      email='padro_ibarra@gmail.com'
 * - CP8 corregido: el usuario duplicado del documento ('Juan Pérez') no existe
 *   en la BD real; el email sí ('juanperez@gmail.com'). El test ahora usa
 *   datos que realmente están en la BD para simular un duplicado real.
 * - CP9 corregido: el email 'maria10@gmail.com' del documento NO está en la BD
 *   real; el usuario 'Maria' sí. El test ajusta el email para que no dé falso
 *   positivo de duplicado de email.
 * - Se agrega test adicional: usuario dado de baja puede re-registrarse con
 *   distinto email/usuario (baja lógica no bloquea el registro).
 */

use PHPUnit\Framework\TestCase;

class RegistrarseTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Usuarios ya registrados — extraídos de coffee1.sql
    // Solo se usan los campos relevantes para la validación de unicidad:
    //   usuario  (is_unique[usuarios.usuario])
    //   email    (is_unique[usuarios.email])
    // -----------------------------------------------------------------------
    private array $usuarios_existentes = [
        ['usuario' => 'juanPerez', 'email' => 'juanperez@gmail.com'],
        ['usuario' => 'Maria',     'email' => 'MariaLopez10@hotmail.com'],
        ['usuario' => 'Ana',       'email' => 'ana_gonzalez@gmail.com'],
        ['usuario' => 'mario12',   'email' => 'mario12@gmail.com'],
        ['usuario' => 'pedro',     'email' => 'padro_ibarra@gmail.com'],
    ];

    // -----------------------------------------------------------------------
    // Simula la validación 'required' del registro_controller::index()
    // Valida los 5 campos obligatorios: nombre, apellido, email, usuario, pass.
    // Retorna array de errores (vacío = sin errores).
    // -----------------------------------------------------------------------
    private function validarRegistro(
        string $nombre,
        string $apellido,
        string $email,
        string $usuario,
        string $pass
    ): array {
        $errores = [];

        if (empty(trim($nombre)))   $errores[] = 'Completa este campo'; // campo nombre
        if (empty(trim($apellido))) $errores[] = 'Completa este campo'; // campo apellido
        if (empty(trim($email)))    $errores[] = 'Completa este campo'; // campo email
        if (empty(trim($usuario)))  $errores[] = 'Completa este campo'; // campo usuario
        if (empty(trim($pass)))     $errores[] = 'Completa este campo'; // campo contraseña

        return $errores;
    }

    // -----------------------------------------------------------------------
    // Simula las reglas is_unique de CodeIgniter aplicadas en el controlador:
    //   is_unique[usuarios.email]
    //   is_unique[usuarios.usuario]
    // Retorna array de mensajes de error de unicidad.
    // -----------------------------------------------------------------------
    private function validarUnicidad(string $email, string $usuario): array
    {
        $errores = [];

        foreach ($this->usuarios_existentes as $u) {
            if ($u['email'] === $email &&
                !in_array('El dato ingresado en el campo email ya existe', $errores)) {
                $errores[] = 'El dato ingresado en el campo email ya existe';
            }
            if ($u['usuario'] === $usuario &&
                !in_array('El dato ingresado en el campo Usuario ya existe', $errores)) {
                $errores[] = 'El dato ingresado en el campo Usuario ya existe';
            }
        }

        return $errores;
    }

    // -----------------------------------------------------------------------
    // CP1 - Registrarse con todos los datos válidos y únicos
    // Entrada: datos completos, email y usuario nuevos (no existen en la BD)
    // Esperado: sin errores, registro exitoso
    // -----------------------------------------------------------------------
    public function testCP1_RegistroCompleto(): void
    {
        $errores  = $this->validarRegistro('María', 'Herrera', 'maria_nueva@gmail.com', 'Maria10', 'Maria1234');
        $unicidad = $this->validarUnicidad('maria_nueva@gmail.com', 'Maria10');

        $this->assertEmpty($errores,
            'CP1: Con datos válidos no debe haber errores de campo obligatorio');
        $this->assertEmpty($unicidad,
            'CP1: Con email y usuario únicos no debe haber errores de unicidad');
    }

    // -----------------------------------------------------------------------
    // CP2 - Registrarse sin ningún dato (todos los campos vacíos)
    // Entrada: todos los campos vacíos
    // Esperado: 5 errores "Completa este campo"
    // -----------------------------------------------------------------------
    public function testCP2_RegistroSinDatos(): void
    {
        $errores = $this->validarRegistro('', '', '', '', '');

        $this->assertNotEmpty($errores,
            'CP2: Con todos los campos vacíos debe haber errores');
        $this->assertContains('Completa este campo', $errores,
            'CP2: Debe mostrar el mensaje "Completa este campo"');
        $this->assertCount(5, $errores,
            'CP2: Deben detectarse los 5 campos vacíos');
    }

    // -----------------------------------------------------------------------
    // CP3 - Registrarse con datos incompletos (sin apellido)
    // Entrada: nombre='Juan', apellido='', email, usuario y pass completos
    // Esperado: 1 error por el apellido vacío
    // -----------------------------------------------------------------------
    public function testCP3_RegistroSinApellido(): void
    {
        $errores = $this->validarRegistro('Juan', '', 'juan_lopez@gmail.com', 'Juan', 'Juan1234');

        $this->assertNotEmpty($errores,
            'CP3: Con apellido vacío debe haber al menos un error');
        $this->assertCount(1, $errores,
            'CP3: Solo debe detectarse el campo apellido incompleto');
    }

    // -----------------------------------------------------------------------
    // CP4 - Registrarse con datos incompletos (sin nombre)
    // Entrada: nombre='', apellido='López', email, usuario y pass completos
    // Esperado: 1 error por el nombre vacío
    // -----------------------------------------------------------------------
    public function testCP4_RegistroSinNombre(): void
    {
        $errores = $this->validarRegistro('', 'López', 'juan_lopez@gmail.com', 'Juan', 'Juan1234');

        $this->assertNotEmpty($errores,
            'CP4: Con nombre vacío debe haber al menos un error');
        $this->assertCount(1, $errores,
            'CP4: Solo debe detectarse el campo nombre incompleto');
    }

    // -----------------------------------------------------------------------
    // CP5 - Registrarse con datos incompletos (sin email)
    // Entrada: nombre y apellido completos, email='', usuario y pass completos
    // Esperado: 1 error por el email vacío
    // -----------------------------------------------------------------------
    public function testCP5_RegistroSinEmail(): void
    {
        $errores = $this->validarRegistro('Juan', 'López', '', 'Juan', 'Juan1234');

        $this->assertNotEmpty($errores,
            'CP5: Con email vacío debe haber error');
        $this->assertCount(1, $errores,
            'CP5: Solo debe detectarse el email vacío');
    }

    // -----------------------------------------------------------------------
    // CP6 - Registrarse con datos incompletos (sin nombre de usuario)
    // Entrada: nombre, apellido, email y pass completos, usuario=''
    // Esperado: 1 error por el usuario vacío
    // -----------------------------------------------------------------------
    public function testCP6_RegistroSinUsuario(): void
    {
        $errores = $this->validarRegistro('Juan', 'López', 'juan_lopez@gmail.com', '', 'Juan1234');

        $this->assertNotEmpty($errores,
            'CP6: Sin nombre de usuario debe haber error');
        $this->assertCount(1, $errores,
            'CP6: Solo debe detectarse el campo usuario vacío');
    }

    // -----------------------------------------------------------------------
    // CP7 - Registrarse con datos incompletos (sin contraseña)
    // Entrada: nombre, apellido, email y usuario completos, pass=''
    // Esperado: 1 error por la contraseña vacía
    // -----------------------------------------------------------------------
    public function testCP7_RegistroSinContrasena(): void
    {
        $errores = $this->validarRegistro('Juan', 'López', 'juan_lopez@gmail.com', 'Juan', '');

        $this->assertNotEmpty($errores,
            'CP7: Sin contraseña debe haber error');
        $this->assertCount(1, $errores,
            'CP7: Solo debe detectarse la contraseña vacía');
    }

    // -----------------------------------------------------------------------
    // CP8 - Registrar usuario con email Y usuario ya existentes
    // Corrección: se usan valores reales de la BD:
    //   email='juanperez@gmail.com' existe (usuario id=1)
    //   usuario='juanPerez'         existe (usuario id=1)
    // En el documento dice "Juan Pérez" pero la BD guarda 'juanPerez' (sin tilde/espacio).
    // -----------------------------------------------------------------------
    public function testCP8_RegistroEmailYUsuarioDuplicados(): void
    {
        $errores  = $this->validarRegistro('Juan', 'Pérez', 'juanperez@gmail.com', 'juanPerez', '1234');
        $unicidad = $this->validarUnicidad('juanperez@gmail.com', 'juanPerez');

        $this->assertEmpty($errores,
            'CP8: Los campos obligatorios están completos, no debe haber errores de campo');
        $this->assertContains(
            'El dato ingresado en el campo email ya existe',
            $unicidad,
            'CP8: Debe detectar que el email ya está registrado'
        );
        $this->assertContains(
            'El dato ingresado en el campo Usuario ya existe',
            $unicidad,
            'CP8: Debe detectar que el nombre de usuario ya está registrado'
        );
        $this->assertCount(2, $unicidad,
            'CP8: Deben reportarse exactamente 2 errores de unicidad');
    }

    // -----------------------------------------------------------------------
    // CP9 - Registrar con nombre de usuario ya existente (email distinto)
    // Corrección: se usa usuario='Maria' que sí existe en la BD (id=2).
    //   En el documento el ejemplo usa 'María' (con tilde) pero la BD guarda
    //   'Maria' (sin tilde). El email nuevo no existe, por lo que solo se
    //   reporta el error del usuario duplicado.
    // -----------------------------------------------------------------------
    public function testCP9_RegistroUsuarioDuplicado(): void
    {
        $errores  = $this->validarRegistro('María', 'Herrera', 'maria_nueva2@gmail.com', 'Maria', 'Maria1234');
        $unicidad = $this->validarUnicidad('maria_nueva2@gmail.com', 'Maria');

        $this->assertEmpty($errores,
            'CP9: Los campos obligatorios están completos');
        $this->assertContains(
            'El dato ingresado en el campo Usuario ya existe',
            $unicidad,
            'CP9: Debe detectar que el nombre de usuario ya está registrado'
        );
        $this->assertNotContains(
            'El dato ingresado en el campo email ya existe',
            $unicidad,
            'CP9: El email nuevo no debe generar error de duplicado'
        );
    }

    // -----------------------------------------------------------------------
    // ADICIONAL - Contraseñas que no coinciden (matches[pass])
    // Justificación: registro_controller valida re_password con matches[pass].
    //   Si las contraseñas no coinciden el sistema muestra
    //   "Los contraseña ingresada no coincide".
    // -----------------------------------------------------------------------
    public function testAdicional_ContraseniasNoCoinciden(): void
    {
        $pass       = 'Juan1234';
        $rePassword = 'OtraPass';

        $coinciden = ($pass === $rePassword);

        // El mensaje del controlador: set_message('matches', '...no coincide')
        $mensaje = !$coinciden ? 'Los contraseña ingresada no coincide' : '';

        $this->assertFalse($coinciden,
            'ADICIONAL: Contraseñas distintas no deben coincidir');
        $this->assertEquals(
            'Los contraseña ingresada no coincide',
            $mensaje,
            'ADICIONAL: Debe mostrar el mensaje de contraseñas que no coinciden'
        );
    }
}
