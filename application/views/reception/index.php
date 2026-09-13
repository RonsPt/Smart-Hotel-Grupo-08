<div class="app-content content">
    <div class="content-overlay"></div><div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <h2 class="mb-2">Recepción — Huéspedes y reservas</h2>
        <div class="card">
            <div class="card-header d-flex flex-wrap gap-2">
                <select id="btn_room_status" class="form-select w-auto" aria-label="Filtrar habitaciones">
                    <option value="">Todas las habitaciones</option><option>Disponible</option><option>Ocupado</option>
                    <option>Reservado</option><option>Limpieza</option><option>Libre</option>
                </select>
                <button id="btn_add_client" type="button" class="btn btn-primary">Registrar huésped / empresa</button>
            </div>
            <div class="card-body"><div id="data-container" class="d-flex flex-wrap gap-2 justify-content-center" aria-live="polite"></div></div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_habitacion_modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="reservation_title">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h3 id="reservation_title">Registrar reserva</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body">
            <form id="create_reservation_form" class="row g-2">
                <input type="hidden" name="id_room">
                <div class="col-12"><p id="room_summary" class="fw-bold"></p></div>
                <div class="col-md-4"><label for="room_status_reservation" class="form-label">Estado de habitación</label>
                    <select id="room_status_reservation" name="room_status" class="form-select" required><option value="">Seleccione</option><option>Ocupado</option><option>Reservado</option></select></div>
                <div class="col-md-3"><label for="client_document_type" class="form-label">1. Tipo de documento</label>
                    <select id="client_document_type" name="document_type" class="form-select document-types" required></select></div>
                <div class="col-md-5"><label for="document_number_reservation" class="form-label">Número de documento</label>
                    <div class="input-group"><input id="document_number_reservation" name="document_number" type="text" maxlength="12" class="form-control" required autocomplete="off">
                    <button id="btn_buscar_bd" type="button" class="btn btn-primary">2. Buscar</button></div></div>
                <div class="col-12"><label for="id_guest" class="form-label">Coincidencias</label>
                    <select id="id_guest" name="id_person" class="form-select" required><option value="">Busque una persona</option></select>
                    <p id="person_summary" class="mt-1 mb-1" aria-live="polite"></p>
                    <button id="btn_new_guest" type="button" class="btn btn-outline-primary d-none">3. Registrar nueva persona</button>
                </div>
                <div class="col-md-6"><label for="fechaInicio" class="form-label">Fecha de ingreso</label><input id="fechaInicio" name="checkin_date" type="date" class="form-control" required></div>
                <div class="col-md-6"><label for="horaInicio" class="form-label">Hora de ingreso</label><input id="horaInicio" name="checkin_time" type="time" class="form-control" required></div>
                <div class="col-md-6"><label for="fechaFin" class="form-label">Fecha de salida</label><input id="fechaFin" name="checkout_date" type="date" class="form-control" required></div>
                <div class="col-md-6"><label for="horaFin" class="form-label">Hora de salida</label><input id="horaFin" name="checkout_time" type="time" class="form-control" required></div>
                <div class="col-md-6"><label for="payment_room" class="form-label">Total estimado</label><input id="payment_room" name="payment_room" class="form-control" readonly></div>
                <div class="col-md-6"><label for="pre_payment" class="form-label">Adelanto</label><input id="pre_payment" name="pre_payment" type="number" min="0" step="0.01" value="0" class="form-control" required></div>
                <div class="col-12"><p id="reservation_feedback" class="text-danger" role="status"></p>
                    <button id="btn_create_reservation" class="btn btn-primary" type="submit">Guardar reserva</button>
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancelar</button></div>
            </form>
        </div>
    </div></div>
</div>

<div class="modal fade" id="create_guest_reservation_modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="guest_title">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h3 id="guest_title">Registro de huésped / empresa</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body"><form id="create_guest_reservation_form" class="row g-2">
            <div class="col-md-4"><label for="client_document" class="form-label">1. Tipo de documento</label><select id="client_document" name="document_type" class="form-select document-types" required></select></div>
            <div class="col-md-8"><label for="document_number" class="form-label">Número de documento</label><div class="input-group">
                <input id="document_number" name="document_number" type="text" class="form-control" maxlength="12" required autocomplete="off">
                <button id="buscar_huesped" type="button" class="btn btn-primary">2. Buscar</button></div></div>
            <div class="col-12"><p id="guest_feedback" role="status"></p><div id="guest_matches" class="d-flex flex-column gap-1"></div>
                <button id="btn_buscar_api" class="btn btn-outline-info" type="button">Autocompletar DNI / RUC</button>
                <small class="d-block mt-1">Si no existe, complete los datos manualmente. La consulta externa es opcional.</small></div>
            <div class="col-12"><h5>4. Datos de identificación y contacto</h5></div>
            <div class="col-md-6 natural-person"><label for="nombre" class="form-label">Nombres *</label><input id="nombre" name="first_names" class="form-control" maxlength="50" required></div>
            <div class="col-md-6 natural-person"><label for="apellido" class="form-label">Apellidos *</label><input id="apellido" name="last_names" class="form-control" maxlength="50" required></div>
            <div class="col-md-6 natural-person"><label for="nacionalidad" class="form-label">Nacionalidad *</label><input id="nacionalidad" name="nationality" class="form-control" maxlength="50" required></div>
            <div class="col-12 business-person d-none"><label for="razon_social" class="form-label">Razón social *</label><input id="razon_social" name="company_name" class="form-control" maxlength="50"></div>
            <div class="col-md-6"><label for="guest_phone" class="form-label">Teléfono</label><input id="guest_phone" name="phone" type="tel" class="form-control" maxlength="45"></div>
            <div class="col-md-6"><label for="guest_email" class="form-label">Correo electrónico</label><input id="guest_email" name="email" type="email" class="form-control" maxlength="50"></div>
            <div class="col-12"><small>Ingrese al menos un teléfono o correo de contacto.</small></div>
            <div class="col-12"><label for="direccion" class="form-label">Dirección</label><input id="direccion" name="address" class="form-control" maxlength="100"></div>
            <div class="col-12 mt-2"><button id="btn_create_guest_reservation" class="btn btn-primary" type="submit">3. Guardar y seleccionar</button>
                <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancelar</button></div>
        </form></div>
    </div></div>
</div>

<div class="modal fade" id="timer_modal" tabindex="-1" aria-labelledby="room_state_title">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h3 id="room_state_title">Estado de habitación</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body"><form id="create_timer_form">
            <input name="id_room" type="hidden">
            <p>Para finalizar una estancia o modificar una reserva, abra <a href="<?php echo BASE_URL; ?>Reservation">Reservas</a>.</p>
            <label for="manual_room_status" class="form-label">Nuevo estado</label><select id="manual_room_status" name="room_status" class="form-select"><option>Disponible</option><option>Limpieza</option></select>
            <button class="btn btn-primary mt-2" type="submit">Actualizar estado</button>
        </form></div>
    </div></div>
</div>
