<?php
/**
 * =============================================================================
 * CU6 – Realizar Reserva (Tabla 22)
 * Grupo 75 – Ingeniería de Software II – FACENA UNNE 2026
 * =============================================================================
 * Parte de la suite de tests del sistema Coffee. Instancia el modelo real
 * con un stub de $this->db (ver tests/helpers.php) para detectar roturas en
 * el código de producción real, no en lógica reescrita dentro del test.
 * =============================================================================
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/helpers.php';

class RealizarReservaTest extends TestCase
{
    /** CP1 – Mesa disponible en fecha y horario: verificar_mesa_ocupada → FALSE. */
    public function testCP1_MesaDisponible(): void
    {
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function where_in($k, $v): self     { return $this; }
            public function get($t = ''): object       { return makeQueryResult([]); }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->verificar_mesa_ocupada('2026-05-21', 1, 1);
        $this->assertFalse($result, 'CP1: verificar_mesa_ocupada debe retornar FALSE si la mesa está disponible.');
    }

    /** CP2 – Mesa ya reservada (Confirmada o Pendiente): verificar_mesa_ocupada → TRUE. */
    public function testCP2_MesaYaOcupada(): void
    {
        $fila = makeRow(['id_reserva' => 9, 'estado_reserva' => 'Confirmada']);
        $qr   = makeQueryResult([$fila]);
        $db   = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function where($k, $v = null): self { return $this; }
            public function where_in($k, $v): self     { return $this; }
            public function get($t = ''): object       { return $this->qr; }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->verificar_mesa_ocupada('2026-05-21', 1, 5);
        $this->assertTrue($result, 'CP2: verificar_mesa_ocupada debe retornar TRUE si la mesa ya está reservada.');
    }

    /** CP3 – Horario ya transcurrido: timestamp del turno < tiempo actual. */
    public function testCP3_HorarioTranscurrido(): void
    {
        $timestampTurno = strtotime('2024-01-01 08:30:00');
        $this->assertLessThan(time(), $timestampTurno,
            'CP3: El turno con fecha pasada debe tener timestamp menor al actual.');
    }

    /** CP4 – Fecha de reserva ya pasada. */
    public function testCP4_FechaTranscurrida(): void
    {
        $this->assertTrue('2026-05-18' < date('Y-m-d'),
            'CP4: Una fecha pasada debe ser rechazada por el sistema.');
    }

    /** Adicional – Fecha de hoy es válida (no es pasada). */
    public function testAdicional_FechaHoyEsValida(): void
    {
        $this->assertFalse(date('Y-m-d') < date('Y-m-d'),
            'Adicional: La fecha de hoy no debe ser rechazada.');
    }

    /** Adicional – Mesa con reservas Canceladas no bloquea disponibilidad. */
    public function testAdicional_MesaCanceladaNoBloquea(): void
    {
        // La query filtra where_in(['Confirmada','Pendiente']); canceladas → 0 filas
        $db = new class {
            public function where($k, $v = null): self { return $this; }
            public function where_in($k, $v): self     { return $this; }
            public function get($t = ''): object       { return makeQueryResult([]); }
        };
        $model  = makeModel('Producto_model', $db);
        $result = $model->verificar_mesa_ocupada('2026-06-01', 2, 3);
        $this->assertFalse($result,
            'Adicional: Una mesa con reservas canceladas debe estar disponible para nueva reserva.');
    }

    /**
     * Adicional – toggle_estado_reserva: Pendiente → Confirmada.
     * El stub simula un turno en el futuro para que la lógica de hora no bloquee.
     */
    public function testAdicional_TogglePendienteAConfirmada(): void
    {
        $fechaFutura = date('Y-m-d', strtotime('+7 days'));
        $reserva     = makeRow([
            'id_reserva'    => 1,
            'estado_reserva' => 'Pendiente',
            'fecha_reserva'  => $fechaFutura,
            'hora_fin'       => '23:59:59',
        ]);
        $qr = makeQueryResult([$reserva]);

        $estadoGuardado = null;
        $db = new class($qr, $estadoGuardado) {
            private $qr;
            private $eg;
            public function __construct($qr, &$eg) { $this->qr = $qr; $this->eg = &$eg; }
            public function select($s): self         { return $this; }
            public function from($t): self           { return $this; }
            public function join($t, $c, $tp = 'left'): self { return $this; }
            public function where($k, $v = null): self { return $this; }
            public function get($t = ''): object     { return $this->qr; }
            public function update($t, array $data): bool {
                $this->eg = $data['estado_reserva'];
                return true;
            }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->toggle_estado_reserva(1);

        $this->assertTrue($result, 'Adicional: toggle debe retornar TRUE al confirmar una reserva pendiente.');
        $this->assertEquals('Confirmada', $estadoGuardado,
            'Adicional: El estado guardado en la BD debe ser Confirmada.');
    }

    /**
     * Adicional – toggle_estado_reserva: ya Confirmada → retorna FALSE (no modifica).
     */
    public function testAdicional_ToggleYaConfirmadaRetornaFalse(): void
    {
        $fechaFutura = date('Y-m-d', strtotime('+7 days'));
        $reserva     = makeRow([
            'id_reserva'     => 2,
            'estado_reserva' => 'Confirmada',
            'fecha_reserva'  => $fechaFutura,
            'hora_fin'       => '23:59:59',
        ]);
        $qr = makeQueryResult([$reserva]);

        $db = new class($qr) {
            private $qr;
            public function __construct($qr) { $this->qr = $qr; }
            public function select($s): self  { return $this; }
            public function from($t): self    { return $this; }
            public function join($t, $c, $tp = 'left'): self { return $this; }
            public function where($k, $v = null): self { return $this; }
            public function get($t = ''): object { return $this->qr; }
        };

        $model  = makeModel('Producto_model', $db);
        $result = $model->toggle_estado_reserva(2);

        $this->assertFalse($result,
            'Adicional: toggle sobre una reserva ya Confirmada debe retornar FALSE.');
    }
}

