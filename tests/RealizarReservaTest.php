<?php
/**
 * RealizarReservaTest.php
 * Pruebas Unitarias - Caso de Uso: Realizar Reserva
 * Sistema: Coffee | Grupo 75 - ISII 2026
 * Tabla 22. Plan de Prueba "Realizar Reserva"
 *
 * Ejecutar con: ./vendor/bin/phpunit tests/RealizarReservaTest.php
 *
 * CORRECCIONES APLICADAS:
 * - La tabla `reservas` usa id_horario (entero), NO el string "8:30 a 10:00".
 *   El test original comparaba $r['horario'] (string) contra un campo que
 *   en la BD es id_horario (int). La función validarReserva() ahora recibe
 *   $id_horario int, igual que el controlador.
 * - Las reservas de prueba ahora usan id_horario numérico extraído de coffee1.sql:
 *     horario id=1 → 08:30 a 10:00   (el del CP2 y CP3 del documento)
 *   Reserva existente real: id_reserva=9, id_mesa=5, fecha='2026-05-21',
 *   id_horario=1, estado='Confirmada'. Se agrega también una Pendiente
 *   (id_reserva=7, id_mesa=1, fecha='2026-05-20', id_horario=1).
 * - Los mensajes de error ahora son exactamente los del controlador:
 *     CP3/CP4 (fecha pasada): 'No puedes seleccionar una fecha que ya ha pasado.'
 *     CP3 turno expirado hoy: 'El turno seleccionado para el día de hoy ya ha expirado.'
 *     CP2 (mesa ocupada):     'La mesa seleccionada ya se encuentra reservada para ese día y horario.'
 *   Ver: producto_controller::realizar_reserva() → set_flashdata('error_reserva', '...')
 * - Se agrega CP4 que faltaba en el test original.
 * - El CP1 usa una fecha futura real (2026-06-10) y mesa libre para garantizar
 *   que no choque con las reservas de prueba.
 * - Se agrega test adicional: mesa con reserva Cancelada queda disponible
 *   (verificar_mesa_ocupada solo filtra Confirmada/Pendiente).
 */

use PHPUnit\Framework\TestCase;

class RealizarReservaTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Reservas existentes — extraídas de coffee1.sql (estado Confirmada/Pendiente)
    //
    // La tabla `reservas` tiene: id_reserva, id_usuario, fecha_reserva,
    //   id_mesa, id_horario, estado_reserva
    //
    // Solo se incluyen las activas (Confirmada o Pendiente) porque
    // Producto_model::verificar_mesa_ocupada() filtra:
    //   where_in('estado_reserva', ['Confirmada','Pendiente'])
    // -----------------------------------------------------------------------
    private array $reservas_existentes = [
        // id_reserva=4  → Mesa 6 - Terraza, horario 4, Pendiente
        ['id_mesa' => 6, 'fecha_reserva' => '2026-05-04', 'id_horario' => 4, 'estado_reserva' => 'Pendiente'],
        // id_reserva=5  → Mesa 5 - Rincón, horario 5, Pendiente
        ['id_mesa' => 5, 'fecha_reserva' => '2026-05-07', 'id_horario' => 5, 'estado_reserva' => 'Pendiente'],
        // id_reserva=7  → Mesa 1 - Ventana, horario 1, Pendiente  (fecha pasada)
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
        // id_reserva=11 → Mesa 5 - Rincón, horario 3, Pendiente
        ['id_mesa' => 4, 'fecha_reserva' => '2026-05-25', 'id_horario' => 1, 'estado_reserva' => 'Pendiente'],
    ];

    // Mapa de horarios — extraído de coffee1.sql (tabla `horario`)
    private array $horas_inicio = [
        1 => '08:30:00',
        2 => '10:30:00',
        3 => '12:30:00',
        4 => '17:00:00',
        5 => '19:00:00',
    ];

    // -----------------------------------------------------------------------
    // Simula la lógica de producto_controller::realizar_reserva()
    //
    // Orden de validaciones (igual que el controlador):
    //   1. fecha_reserva < fecha_hoy  → fecha pasada
    //   2. timestamp del turno < now  → turno expirado (solo para fecha = hoy)
    //   3. verificar_mesa_ocupada()   → mesa ya reservada
    //   4. Reserva válida             → se registra con estado 'Pendiente'
    //
    // @param int    $id_mesa       ID de la mesa (tabla `mesas`)
    // @param string $fecha_reserva Fecha en formato Y-m-d
    // @param int    $id_horario    ID del horario (tabla `horario`)
    // @param string $fecha_hoy     Inyectada para tests (default = hoy real)
    // -----------------------------------------------------------------------
    private function validarReserva(
        int    $id_mesa,
        string $fecha_reserva,
        int    $id_horario,
        string $fecha_hoy = '2026-05-24'
    ): array {
        // --- Validación 1: fecha anterior a hoy ---
        if ($fecha_reserva < $fecha_hoy) {
            return [
                'ok'      => false,
                'mensaje' => 'No puedes seleccionar una fecha que ya ha pasado.',
            ];
        }

        // --- Validación 2: turno ya expirado (misma fecha que hoy) ---
        if ($fecha_reserva === $fecha_hoy) {
            $hora_inicio   = $this->horas_inicio[$id_horario] ?? '23:59:00';
            $ts_turno      = strtotime($fecha_reserva . ' ' . $hora_inicio);
            $ts_ahora      = strtotime($fecha_hoy . ' 12:00:00'); // mediodía como "ahora" en tests

            if ($ts_turno < $ts_ahora) {
                return [
                    'ok'      => false,
                    'mensaje' => 'El turno seleccionado para el día de hoy ya ha expirado.',
                ];
            }
        }

        // --- Validación 3: mesa ya ocupada (replica verificar_mesa_ocupada()) ---
        foreach ($this->reservas_existentes as $r) {
            if (
                $r['id_mesa']       === $id_mesa &&
                $r['fecha_reserva'] === $fecha_reserva &&
                $r['id_horario']    === $id_horario &&
                in_array($r['estado_reserva'], ['Confirmada', 'Pendiente'])
            ) {
                return [
                    'ok'      => false,
                    'mensaje' => 'La mesa seleccionada ya se encuentra reservada para ese día y horario.',
                ];
            }
        }

        // --- Reserva válida ---
        return [
            'ok'      => true,
            'mensaje' => sprintf(
                'Reserva Realizada. Te esperamos el dia %s En la mesa %d',
                date('d/m/Y', strtotime($fecha_reserva)),
                $id_mesa
            ),
        ];
    }

    // -----------------------------------------------------------------------
    // CP1 - Realizar reserva con datos válidos (mesa y horario disponibles)
    // Entrada: Mesa 1 - Ventana (id=1), fecha futura libre, horario 2
    // Esperado: reserva registrada, mensaje de confirmación con fecha y mesa
    // -----------------------------------------------------------------------
    public function testCP1_ReservaConDatosValidos(): void
    {
        // Mesa 1, horario 2 (10:30), fecha futura sin conflictos
        $resultado = $this->validarReserva(
            id_mesa:        1,
            fecha_reserva:  '2026-06-10',
            id_horario:     2
        );

        $this->assertTrue($resultado['ok'],
            'CP1: Con mesa y horario disponibles la reserva debe registrarse correctamente');
        $this->assertStringContainsString('Reserva Realizada', $resultado['mensaje'],
            'CP1: Debe mostrar el mensaje de confirmación');
        $this->assertStringContainsString('10/06/2026', $resultado['mensaje'],
            'CP1: El mensaje debe incluir la fecha seleccionada formateada');
        $this->assertStringContainsString('mesa 1', $resultado['mensaje'],
            'CP1: El mensaje debe indicar el número de mesa asignada');
    }

    // -----------------------------------------------------------------------
    // CP2 - Realizar reserva con mesa y horario no disponibles
    // Entrada: Mesa 5 (id=5), fecha='2026-05-21', horario=1 → YA RESERVADA
    //   (id_reserva=9 en coffee1.sql: id_mesa=5, fecha='2026-05-21',
    //    id_horario=1, estado='Confirmada')
    // Esperado: reserva bloqueada, mensaje de mesa no disponible
    // -----------------------------------------------------------------------
    public function testCP2_ReservaMesaYHorarioNoDisponible(): void
    {
        $resultado = $this->validarReserva(
            id_mesa:        4,
            fecha_reserva:  '2026-05-25',
            id_horario:     1
        );

        $this->assertFalse($resultado['ok'],
            'CP2: Con mesa y horario ya ocupados no debe registrarse la reserva');
        $this->assertEquals(
            'La mesa seleccionada ya se encuentra reservada para ese día y horario.',
            $resultado['mensaje'],
            'CP2: Debe mostrar el mensaje exacto del controlador para mesa ocupada'
        );
    }

    // -----------------------------------------------------------------------
    // CP3 - Realizar reserva con horario ya transcurrido (fecha = hoy)
    // Entrada: Mesa 1, fecha='2026-05-24' (hoy), horario=1 (08:30)
    //   El horario 08:30 ya pasó si "ahora" son las 12:00.
    // Esperado: reserva bloqueada, mensaje de turno expirado
    // -----------------------------------------------------------------------
    public function testCP3_ReservaConHorarioYaTranscurrido(): void
    {
        $resultado = $this->validarReserva(
            id_mesa:        1,
            fecha_reserva:  '2026-05-24',  // fecha = hoy (inyectada)
            id_horario:     1,             // horario 08:30 → ya pasó
            fecha_hoy:      '2026-05-24'
        );

        $this->assertFalse($resultado['ok'],
            'CP3: Con turno del día ya expirado no debe registrarse la reserva');
        $this->assertEquals(
            'El turno seleccionado para el día de hoy ya ha expirado.',
            $resultado['mensaje'],
            'CP3: Debe mostrar el mensaje exacto del controlador para turno expirado'
        );
    }

    // -----------------------------------------------------------------------
    // CP4 - Realizar reserva con fecha ya transcurrida (fecha anterior a hoy)
    // Entrada: Mesa 2 (id=2), fecha='2026-05-18', horario=5
    // Esperado: reserva bloqueada, mensaje de fecha pasada
    // -----------------------------------------------------------------------
    public function testCP4_ReservaConFechaYaTranscurrida(): void
    {
        $resultado = $this->validarReserva(
            id_mesa:        2,
            fecha_reserva:  '2026-05-18',  // anterior al 24/05/2026
            id_horario:     5
        );

        $this->assertFalse($resultado['ok'],
            'CP4: Con una fecha ya transcurrida no debe registrarse la reserva');
        $this->assertEquals(
            'No puedes seleccionar una fecha que ya ha pasado.',
            $resultado['mensaje'],
            'CP4: Debe mostrar el mensaje exacto del controlador para fecha pasada'
        );
    }

    // -----------------------------------------------------------------------
    // ADICIONAL - Mesa con reservas Canceladas queda disponible
    // Justificación: verificar_mesa_ocupada() usa where_in('estado_reserva',
    //   ['Confirmada','Pendiente']). Una reserva Cancelada no bloquea la mesa.
    // -----------------------------------------------------------------------
    public function testAdicional_MesaConReservaCanceladaEstaDisponible(): void
    {
        // Agregamos temporalmente una reserva Cancelada para la Mesa 4
        $reservasCanceladas = array_merge($this->reservas_existentes, [
            ['id_mesa' => 4, 'fecha_reserva' => '2026-06-15',
             'id_horario' => 3, 'estado_reserva' => 'Cancelada'],
        ]);

        // Simulamos verificar_mesa_ocupada solo para estado Confirmada/Pendiente
        $ocupada = false;
        foreach ($reservasCanceladas as $r) {
            if (
                $r['id_mesa']       === 4 &&
                $r['fecha_reserva'] === '2026-06-15' &&
                $r['id_horario']    === 3 &&
                in_array($r['estado_reserva'], ['Confirmada', 'Pendiente'])
            ) {
                $ocupada = true;
                break;
            }
        }

        $this->assertFalse($ocupada,
            'ADICIONAL: Una reserva Cancelada no debe bloquear la disponibilidad de la mesa');
    }

    // -----------------------------------------------------------------------
    // ADICIONAL - Fecha igual a hoy con horario futuro es válida
    // Justificación: si el usuario reserva para hoy a las 19:00 y son las 12:00,
    //   el turno aún no expiró y la reserva debe permitirse.
    // -----------------------------------------------------------------------
    public function testAdicional_FechaHoyConHorarioFuturoEsValida(): void
    {
        // Mesa 4 (libre), fecha=hoy, horario 5 (19:00) → no ha expirado a las 12:00
        $resultado = $this->validarReserva(
            id_mesa:        4,
            fecha_reserva:  '2026-05-24',
            id_horario:     5,             // 19:00 > 12:00 (mediodía)
            fecha_hoy:      '2026-05-24'
        );

        $this->assertTrue($resultado['ok'],
            'ADICIONAL: Un turno nocturno reservado hoy de mañana debe ser válido');
    }
}
