<?php
/**
 * RealizarReservaTest.php  — versión refactorizada
 * Pruebas Unitarias - Caso de Uso: Realizar Reserva
 * Sistema: Coffee | Grupo 75 - ISII 2026
 * Tabla 22. Plan de Prueba "Realizar Reserva"
 *
 * Ejecutar con: ./vendor/bin/phpunit tests/RealizarReservaTest.php
 *
 * DIFERENCIA CON LA VERSIÓN ANTERIOR:
 * - Antes: la lógica de validación vivía en el método PRIVADO validarReserva()
 *   dentro del propio test. PHPUnit la ejecutaba, pero no probaba ningún
 *   archivo real del sistema — solo código escrito en el test.
 *
 * - Ahora: la lógica vive en ReservaService (application/Services/ReservaService.php).
 *   El test importa esa clase y la usa. PHPUnit ahora prueba código real.
 *   Si alguien rompe ReservaService, los tests fallan → eso es lo correcto.
 *
 * RELACIÓN CON EL CONTRATO DE OPERACIONES (informe):
 *   Nombre: Realizar Reserva (cliente_id, fecha_reserva, horario, mesa_id)
 *   Pre-cond : sesión activa, mesas/fecha/horarios disponibles
 *   Post-cond: registro en tabla "Reserva" con estado_reserva = "Pendiente"
 *   Excepciones: mesa ocupada en ese horario/fecha → informa al cliente
 *
 * MÉTODOS DE ReservaService que se prueban aquí:
 *   - validarReserva()        → regla principal del caso de uso
 *   - verificarMesaOcupada()  → sub-regla usada internamente (también se prueba sola)
 */

require_once __DIR__ . '/../application/Services/ReservaService.php';

use PHPUnit\Framework\TestCase;

class RealizarReservaTest extends TestCase
{
    // Instancia del servicio que se prueba
    private ReservaService $service;

    // -----------------------------------------------------------------------
    // Reservas existentes — extraídas de coffee1.sql (estado Confirmada/Pendiente)
    //
    // Solo se incluyen las activas (Confirmada o Pendiente) porque
    // ReservaService::verificarMesaOcupada() filtra solo esos estados,
    // igual que Producto_model::verificar_mesa_ocupada() con where_in().
    // -----------------------------------------------------------------------
    private array $reservas_existentes = [
        // id_reserva=4  → Mesa 6 - Terraza, horario 4, Pendiente
        ['id_mesa' => 6, 'fecha_reserva' => '2026-05-04', 'id_horario' => 4, 'estado_reserva' => 'Pendiente'],
        // id_reserva=5  → Mesa 5 - Rincón, horario 5, Pendiente
        ['id_mesa' => 5, 'fecha_reserva' => '2026-05-07', 'id_horario' => 5, 'estado_reserva' => 'Pendiente'],
        // id_reserva=7  → Mesa 1 - Ventana, horario 1, Pendiente (fecha pasada)
        ['id_mesa' => 1, 'fecha_reserva' => '2026-05-20', 'id_horario' => 1, 'estado_reserva' => 'Pendiente'],
        // id_reserva=8  → Mesa 2 - Ventana, horario 1, Pendiente
        ['id_mesa' => 2, 'fecha_reserva' => '2026-05-20', 'id_horario' => 1, 'estado_reserva' => 'Pendiente'],
        // id_reserva=9  → Mesa 5 - Rincón, horario 1, Confirmada  ← usada en CP2
        ['id_mesa' => 5, 'fecha_reserva' => '2026-05-21', 'id_horario' => 1, 'estado_reserva' => 'Confirmada'],
        // id_reserva=10 → Mesa 6 - Terraza, horario 5, Pendiente
        ['id_mesa' => 6, 'fecha_reserva' => '2026-05-21', 'id_horario' => 5, 'estado_reserva' => 'Pendiente'],
        // id_reserva=11 → Mesa 5 - Rincón, horario 3, Pendiente
        ['id_mesa' => 5, 'fecha_reserva' => '2026-05-21', 'id_horario' => 3, 'estado_reserva' => 'Pendiente'],
        // id_reserva=12 → Mesa 3 - Centro, horario 4, Confirmada
        ['id_mesa' => 3, 'fecha_reserva' => '2026-05-21', 'id_horario' => 4, 'estado_reserva' => 'Confirmada'],
        // id_reserva=13 → Mesa 4, horario 1, Pendiente  ← usada en CP2
        ['id_mesa' => 4, 'fecha_reserva' => '2026-06-25', 'id_horario' => 1, 'estado_reserva' => 'Pendiente'],
    ];

    // Se ejecuta antes de cada test — crea una instancia nueva de ReservaService
    protected function setUp(): void
    {
        $this->service = new ReservaService();
    }

    // =======================================================================
    // CP1 — Realizar reserva con datos válidos (mesa y horario disponibles)
    // -----------------------------------------------------------------------
    // Contrato: Pre-cond cumplida → Post-cond: registro con estado "Pendiente"
    // Entrada : Mesa 1 (id=1), fecha futura sin conflictos, horario 2 (10:30)
    // Esperado: ok=true, mensaje con "Reserva Realizada", fecha y número de mesa
    // =======================================================================
    public function testCP1_ReservaConDatosValidos(): void
    {
        $resultado = $this->service->validarReserva(
            reservas_existentes: $this->reservas_existentes,
            id_mesa:             1,
            fecha_reserva:       '2026-06-10',
            id_horario:          2
        );

        $this->assertTrue(
            $resultado['ok'],
            'CP1: Con mesa y horario disponibles la reserva debe registrarse correctamente'
        );
        $this->assertStringContainsString(
            'Reserva Realizada',
            $resultado['mensaje'],
            'CP1: Debe mostrar el mensaje de confirmación'
        );
        $this->assertStringContainsString(
            '10/06/2026',
            $resultado['mensaje'],
            'CP1: El mensaje debe incluir la fecha seleccionada formateada'
        );
        $this->assertStringContainsString(
            'mesa 1',
            $resultado['mensaje'],
            'CP1: El mensaje debe indicar el número de mesa asignada'
        );
    }

    // =======================================================================
    // CP2 — Realizar reserva con mesa y horario NO disponibles
    // -----------------------------------------------------------------------
    // Contrato: Excepción → mesa ocupada en ese horario/fecha
    // Entrada : Mesa 4 (id=4), fecha='2026-05-25', horario=1 → YA RESERVADA
    //           (id_reserva=13 en coffee1.sql: Pendiente)
    // Esperado: ok=false, mensaje exacto del controlador
    // =======================================================================
    public function testCP2_ReservaMesaYHorarioNoDisponible(): void
    {
        $resultado = $this->service->validarReserva(
            reservas_existentes: $this->reservas_existentes,
            id_mesa:             4,
            fecha_reserva:       '2026-06-25',
            id_horario:          1
        );

        $this->assertFalse(
            $resultado['ok'],
            'CP2: Con mesa y horario ya ocupados no debe registrarse la reserva'
        );
        $this->assertEquals(
            'La mesa seleccionada ya se encuentra reservada para ese día y horario.',
            $resultado['mensaje'],
            'CP2: Debe mostrar el mensaje exacto del controlador para mesa ocupada'
        );
    }

    // =======================================================================
    // CP3 — Realizar reserva con horario ya transcurrido (fecha = hoy)
    // -----------------------------------------------------------------------
    // Contrato: Excepción → turno expirado para el día actual
    // Entrada : Mesa 1, fecha='2026-05-24' (hoy inyectado), horario=1 (08:30)
    //           El turno 08:30 ya expiró a las 12:00 del mismo día
    // Esperado: ok=false, mensaje de turno expirado
    // =======================================================================
    public function testCP3_ReservaConHorarioYaTranscurrido(): void
    {
        $resultado = $this->service->validarReserva(
            reservas_existentes: $this->reservas_existentes,
            id_mesa:             1,
            fecha_reserva:       '2026-05-24',
            id_horario:          1,          // 08:30 → ya expiró
            fecha_hoy:           '2026-05-24',
            hora_actual:         '12:00:00'  // mediodía inyectado para el test
        );

        $this->assertFalse(
            $resultado['ok'],
            'CP3: Con turno del día ya expirado no debe registrarse la reserva'
        );
        $this->assertEquals(
            'El turno seleccionado para el día de hoy ya ha expirado.',
            $resultado['mensaje'],
            'CP3: Debe mostrar el mensaje exacto del controlador para turno expirado'
        );
    }

    // =======================================================================
    // CP4 — Realizar reserva con fecha ya transcurrida (anterior a hoy)
    // -----------------------------------------------------------------------
    // Contrato: Excepción → fecha pasada no permitida
    // Entrada : Mesa 2 (id=2), fecha='2026-05-18', horario=5
    // Esperado: ok=false, mensaje de fecha pasada
    // =======================================================================
    public function testCP4_ReservaConFechaYaTranscurrida(): void
    {
        $resultado = $this->service->validarReserva(
            reservas_existentes: $this->reservas_existentes,
            id_mesa:             2,
            fecha_reserva:       '2026-05-18',  // anterior a 2026-05-24
            id_horario:          5
        );

        $this->assertFalse(
            $resultado['ok'],
            'CP4: Con una fecha ya transcurrida no debe registrarse la reserva'
        );
        $this->assertEquals(
            'No puedes seleccionar una fecha que ya ha pasado.',
            $resultado['mensaje'],
            'CP4: Debe mostrar el mensaje exacto del controlador para fecha pasada'
        );
    }

    // =======================================================================
    // ADICIONAL — verificarMesaOcupada() ignora reservas Canceladas
    // -----------------------------------------------------------------------
    // Justificación: ReservaService::verificarMesaOcupada() filtra solo
    //   ['Confirmada','Pendiente'], igual que el modelo con where_in().
    //   Una Cancelada no debe bloquear el slot.
    // Se prueba verificarMesaOcupada() directamente como método público.
    // =======================================================================
    public function testAdicional_MesaConReservaCanceladaEstaDisponible(): void
    {
        // Añadimos una reserva Cancelada para Mesa 4, fecha futura, horario 3
        $reservasConCancelada = array_merge($this->reservas_existentes, [
            ['id_mesa' => 4, 'fecha_reserva' => '2026-06-15',
             'id_horario' => 3, 'estado_reserva' => 'Cancelada'],
        ]);

        // Llamamos al método de servicio directamente (no inline en el test)
        $ocupada = $this->service->verificarMesaOcupada(
            reservas_existentes: $reservasConCancelada,
            id_mesa:             4,
            fecha_reserva:       '2026-06-15',
            id_horario:          3
        );

        $this->assertFalse(
            $ocupada,
            'ADICIONAL: Una reserva Cancelada no debe bloquear la disponibilidad de la mesa'
        );
    }

    // =======================================================================
    // ADICIONAL — Fecha = hoy con horario FUTURO es válida
    // -----------------------------------------------------------------------
    // Justificación: reservar hoy a las 19:00 siendo las 12:00 debe permitirse.
    //   El turno aún no expiró → la reserva es válida.
    // =======================================================================
    public function testAdicional_FechaHoyConHorarioFuturoEsValida(): void
    {
        $resultado = $this->service->validarReserva(
            reservas_existentes: $this->reservas_existentes,
            id_mesa:             4,
            fecha_reserva:       '2026-05-24',
            id_horario:          5,             // 19:00 → no expiró a las 12:00
            fecha_hoy:           '2026-05-24',
            hora_actual:         '12:00:00'
        );

        $this->assertTrue(
            $resultado['ok'],
            'ADICIONAL: Un turno nocturno reservado hoy de mañana debe ser válido'
        );
    }
}
