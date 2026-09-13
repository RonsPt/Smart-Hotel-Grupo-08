<?php
require_once __DIR__ . '/M_Person.php';

class M_Reception extends Model
{
    private function query($sql, $bind = [], $one = false)
    {
        try {
            $rows = $one ? $this->pdo->fetchOne($sql, $bind) : $this->pdo->fetchAll($sql, $bind);
            return ['status' => 'OK', 'result' => $rows ?: []];
        } catch (Throwable $e) {
            return ['status' => 'EXCEPTION', 'result' => $e];
        }
    }

    private function roomsSql()
    {
        return 'SELECT room.*, room_type.bed_type, room_type.type_name, room_type.person_limit,
            room_type.price_temporary, room_type.price_half, room_type.price_day
            FROM room JOIN room_type ON room.id_type = room_type.id_type';
    }

    public function get_rooms() { return $this->query($this->roomsSql() . ' ORDER BY CAST(room_number AS UNSIGNED)'); }
    public function get_room_by_status($bind) { return $this->query($this->roomsSql() . ' WHERE room_status = :room_status ORDER BY CAST(room_number AS UNSIGNED)', $bind); }
    public function get_room_by_id($bind) { return $this->query($this->roomsSql() . ' WHERE id_room = :id_room', $bind, true); }
    public function get_rooms_price($bind) { return $this->query('SELECT * FROM room_type WHERE type_name = :type_name', $bind); }

    public function get_guest($bind)
    {
        try {
            return ['status' => 'OK', 'result' => (new M_Person($this->pdo))->find($bind['document_type'], $bind['document_number'])];
        } catch (Throwable $e) {
            return ['status' => 'EXCEPTION', 'result' => $e];
        }
    }

    public function create_guest_reservation($input)
    {
        return (new M_Person($this->pdo))->save($input);
    }

    private function dateTime($date, $time)
    {
        $value = $date . ' ' . $time;
        $format = strlen($time) === 5 ? 'Y-m-d H:i' : 'Y-m-d H:i:s';
        $result = DateTimeImmutable::createFromFormat('!' . $format, $value);
        if (!$result || $result->format($format) !== $value) {
            throw new InvalidArgumentException('Fecha u hora inválida.');
        }
        return $result;
    }

    private function price($start, $end, $room)
    {
        $minutes = (int) ceil(($end->getTimestamp() - $start->getTimestamp()) / 60);
        $days = intdiv($minutes, 1440);
        $rest = $minutes % 1440;
        $amount = $days * (float) $room['price_day'];
        if ($rest > 0) {
            $amount += (float) $room[$rest <= 240 ? 'price_temporary' : ($rest <= 720 ? 'price_half' : 'price_day')];
        }
        return round($amount, 2);
    }

    public function create_reservation($input)
    {
        try {
            $personId = $input['id_person'] ?? $input['id_guest'] ?? '';
            if (!ctype_digit((string) $personId) || (int) $personId < 1) {
                throw new InvalidArgumentException('Busque o registre una persona y selecciónela antes de reservar.');
            }
            $person = (new M_Person($this->pdo))->byId($personId);
            if (!$person || (int) $person['status'] !== 1) {
                throw new InvalidArgumentException('La persona seleccionada no está activa.');
            }
            $doc = (new M_Person($this->pdo))->document($input['document_type'] ?? '', $input['document_number'] ?? '');
            if ($doc['document_number'] !== strtoupper(trim($person['document_number'])) || $doc['id_document_type'] !== (int) $person['id_document_type']) {
                throw new InvalidArgumentException('El documento cambió. Vuelva a buscar y seleccionar la persona.');
            }
            $start = $this->dateTime($input['checkin_date'] ?? '', $input['checkin_time'] ?? '');
            $end = $this->dateTime($input['checkout_date'] ?? '', $input['checkout_time'] ?? '');
            if ($end <= $start) {
                throw new InvalidArgumentException('La salida debe ser posterior al ingreso.');
            }
            $state = $input['room_status'] ?? '';
            if (!in_array($state, ['Ocupado', 'Reservado'], true)) {
                throw new InvalidArgumentException('Seleccione Ocupado o Reservado.');
            }
            $advance = $input['pre_payment'] ?? '0';
            if (!preg_match('/^\d+(\.\d{1,2})?$/D', (string) $advance)) {
                throw new InvalidArgumentException('El adelanto debe ser un monto positivo o cero, con hasta dos decimales.');
            }
            $this->pdo->beginTransaction();
            // Serializa reservas concurrentes para la misma habitación.
            $room = $this->pdo->fetchOne($this->roomsSql() . ' WHERE id_room = :id_room FOR UPDATE', ['id_room' => $input['id_room'] ?? 0]);
            if (!$room || in_array($room['room_status'], ['Limpieza', 'Ocupado'], true)) {
                throw new InvalidArgumentException('La habitación no está disponible para registrar esta reserva.');
            }
            $conflict = $this->pdo->fetchOne('SELECT id_reservation FROM reservation WHERE id_room = :room
                AND status IN ("Reservado", "Ocupado", "Pendiente", "Libre")
                AND TIMESTAMP(checkin_date, checkin_time) < :end
                AND (checkout_date IS NULL OR TIMESTAMP(checkout_date, checkout_time) > :start) LIMIT 1',
                ['room' => $room['id_room'], 'start' => $start->format('Y-m-d H:i:s'), 'end' => $end->format('Y-m-d H:i:s')]);
            if ($conflict) {
                throw new InvalidArgumentException('La habitación ya tiene una reserva en ese intervalo.');
            }
            $amount = $this->price($start, $end, $room);
            if (!is_finite($amount) || $amount <= 0 || (float) $advance > $amount) {
                throw new InvalidArgumentException('Verifique la tarifa y el adelanto: no puede superar el total.');
            }
            $status = $state === 'Ocupado' ? 'Ocupado' : ((float) $advance > 0 ? 'Reservado' : 'Pendiente');
            // id_guest se conserva como columna legacy obligatoria; id_person es la relación explícita nueva.
            $this->pdo->perform('INSERT INTO reservation (checkin_date, checkin_time, checkout_date, checkout_time, id_room, id_guest, id_person, status)
                VALUES (:checkin_date, :checkin_time, :checkout_date, :checkout_time, :id_room, :legacy_id, :id_person, :status)', [
                'checkin_date' => $start->format('Y-m-d'), 'checkin_time' => $start->format('H:i:s'),
                'checkout_date' => $end->format('Y-m-d'), 'checkout_time' => $end->format('H:i:s'),
                'id_room' => $room['id_room'], 'legacy_id' => $personId, 'id_person' => $personId, 'status' => $status
            ]);
            $id = (int) $this->pdo->lastInsertId();
            $this->pdo->perform('INSERT INTO payment (id_reservation, payment_room, pre_payment, payment_sales, payment_extra, payment_discount, payment_total)
                VALUES (:id, :amount, :advance, 0, 0, 0, :total)', ['id' => $id, 'amount' => $amount, 'advance' => $advance, 'total' => $amount]);
            $this->pdo->perform('UPDATE room SET room_status = :status WHERE id_room = :id', ['status' => $state, 'id' => $room['id_room']]);
            $this->pdo->commit();
            return ['status' => 'OK', 'result' => ['id_reservation' => $id, 'id_person' => (int) $personId, 'payment_room' => $amount]];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['status' => 'EXCEPTION', 'result' => $e];
        }
    }

    public function create_reservation_free($bind) { return $this->create_reservation($bind); }

    public function get_reservation_room($bind)
    {
        return $this->query('SELECT r.id_reservation, r.id_room, r.id_person AS id_guest, r.status,
            g.first_names, g.last_names, g.company_name FROM reservation r
            JOIN reservation_guest g ON g.id_reservation = r.id_reservation
            WHERE r.id_room = :id_room AND r.status IN ("Pendiente", "Reservado", "Ocupado")', $bind);
    }

    public function date_reservation($bind)
    {
        $response = $this->query('SELECT id_reservation FROM reservation WHERE id_room = :id_room
            AND status IN ("Reservado", "Ocupado", "Pendiente", "Libre")
            AND TIMESTAMP(checkin_date, checkin_time) < :checkout_date
            AND (checkout_date IS NULL OR TIMESTAMP(checkout_date, checkout_time) > :checkin_date)', $bind);
        if ($response['status'] === 'OK' && $response['result']) {
            $response['status'] = 'ERROR';
        }
        return $response;
    }

    public function update_state($bind)
    {
        try {
            if (!in_array($bind['room_status'], ['Disponible', 'Limpieza'], true)) {
                throw new InvalidArgumentException('Estado de habitación inválido.');
            }
            $this->pdo->beginTransaction();
            $room = $this->pdo->fetchOne('SELECT id_room FROM room WHERE id_room = :id_room FOR UPDATE', ['id_room' => $bind['id_room']]);
            if (!$room) {
                throw new InvalidArgumentException('Habitación no encontrada.');
            }
            $active = $this->pdo->fetchOne('SELECT id_reservation FROM reservation WHERE id_room = :id_room AND status IN ("Ocupado", "Libre") LIMIT 1', ['id_room' => $bind['id_room']]);
            if ($active) {
                throw new InvalidArgumentException('Finalice la estancia desde Reservas antes de liberar la habitación.');
            }
            $this->pdo->perform('UPDATE room SET room_status = :room_status WHERE id_room = :id_room', $bind);
            $this->pdo->commit();
            return ['status' => 'OK', 'result' => []];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            return ['status' => 'EXCEPTION', 'result' => $e];
        }
    }

    public function update_state_reservation($bind) { return $this->update_state($bind); }
    public function clean_rooms($bind) { return $this->update_state(['id_room' => $bind['id_room'], 'room_status' => 'Disponible']); }
}
