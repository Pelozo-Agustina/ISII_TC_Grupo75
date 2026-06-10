<?php
/**
 * ReservaService.php
 * Sistema: Coffee | Grupo 75 - ISII 2026
 *
 * Clase de lógica de negocio para Realizar Reserva.
 * Implementa los métodos que el test RealizarReservaTest.php necesita.
 *
 * Esta clase es pura lógica de negocio, sin base de datos ni sesión.
 * Testeable directamente con PHPUnit.
 *
 * Relación con el sistema real:
 *   - La validación de fecha/horario corresponde a producto_controller::realizar_reserva()
 *   - La verificación de disponibilidad corresponde a producto_model::verificar_mesa_ocupada()
 */

class ReservaService
{
    // Mapa de horarios — extraído de coffee1.sql (tabla `horario`)
    // Se usa para verificar si un turno ya expiró cuando la fecha es hoy.
    private array $horas_inicio = [
        1 => '08:30:00',
        2 => '10:30:00',
        3 => '12:30:00',
        4 => '17:00:00',
        5 => '19:00:00',
    ];

    // =========================================================================
    // verificarMesaOcupada()
    // =========================================================================
    // Simula producto_model::verificar_mesa_ocupada().
    // Busca si ya existe una reserva activa (Confirmada o Pendiente)
    // para la misma mesa, fecha y horario.
    //
    // @param array  $reservas_existentes  Lista de reservas activas de la BD
    // @param int    $id_mesa
    // @param string $fecha_reserva        Formato 'YYYY-MM-DD'
    // @param int    $id_horario
    //
    // @return bool  true si la mesa ya está ocupada en ese slot
    // =========================================================================
    public function verificarMesaOcupada(
        array $reservas_existentes,
        int $id_mesa,
        string $fecha_reserva,
        int $id_horario
    ): bool {
        foreach ($reservas_existentes as $r) {
            if (
                $r['id_mesa']       === $id_mesa &&
                $r['fecha_reserva'] === $fecha_reserva &&
                $r['id_horario']    === $id_horario &&
                // Solo bloquean las reservas activas (igual que where_in del modelo)
                in_array($r['estado_reserva'], ['Confirmada', 'Pendiente'])
            ) {
                return true;
            }
        }
        return false;
    }

    // =========================================================================
    // validarReserva()
    // =========================================================================
    // Aplica todas las validaciones del controlador antes de insertar la reserva.
    //
    // @param array  $reservas_existentes  Reservas activas (simulan la BD)
    // @param int    $id_mesa
    // @param string $fecha_reserva        Formato 'YYYY-MM-DD'
    // @param int    $id_horario
    // @param string|null $fecha_hoy       Inyectada para tests; si es null usa date('Y-m-d')
    // @param string|null $hora_actual     Inyectada para tests; si es null usa date('H:i:s')
    //
    // @return array [
    //     'ok'      => bool,
    //     'mensaje' => string
    // ]
    //
    // Orden de validaciones (igual que producto_controller::realizar_reserva()):
    //   1. fecha < hoy            → "No puedes seleccionar una fecha que ya ha pasado."
    //   2. fecha = hoy y turno expirado → "El turno seleccionado para el día de hoy ya ha expirado."
    //   3. mesa ocupada           → "La mesa seleccionada ya se encuentra reservada para ese día y horario."
    //   4. Todo válido            → "Reserva Realizada! ..."
    // =========================================================================
    public function validarReserva(
        array $reservas_existentes,
        int $id_mesa,
        string $fecha_reserva,
        int $id_horario,
        ?string $fecha_hoy = null,
        ?string $hora_actual = null
    ): array {
        $fecha_hoy   = $fecha_hoy   ?? date('Y-m-d');
        $hora_actual = $hora_actual ?? date('H:i:s');

        // --- CP4: Fecha anterior a hoy ---
        if ($fecha_reserva < $fecha_hoy) {
            return [
                'ok'      => false,
                'mensaje' => 'No puedes seleccionar una fecha que ya ha pasado.',
            ];
        }

        // --- CP3: Fecha es hoy pero el turno ya expiró ---
        if ($fecha_reserva === $fecha_hoy) {
            $hora_inicio_turno = $this->horas_inicio[$id_horario] ?? '00:00:00';
            if ($hora_actual >= $hora_inicio_turno) {
                return [
                    'ok'      => false,
                    'mensaje' => 'El turno seleccionado para el día de hoy ya ha expirado.',
                ];
            }
        }

        // --- CP2: Mesa ya reservada en ese slot ---
        if ($this->verificarMesaOcupada($reservas_existentes, $id_mesa, $fecha_reserva, $id_horario)) {
            return [
                'ok'      => false,
                'mensaje' => 'La mesa seleccionada ya se encuentra reservada para ese día y horario.',
            ];
        }

        // --- CP1: Todo válido → reserva registrada con estado "Pendiente" ---
        // Fuente real: guardar_reserva.php → INSERT con estado_reserva = 'Pendiente'
        //              pagina_reservas.php → mensaje de confirmación
        return [
            'ok'      => true,
            'mensaje' => sprintf(
                '¡Reserva Realizada! Te esperamos en la mesa %d el %s.',
                $id_mesa,
                date('d/m/Y', strtotime($fecha_reserva))
            ),
        ];
    }
}
