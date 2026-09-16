/* Registro por documento: una identidad seleccionada y una sola petición para guardar la reserva. */
$(function () {
    const reservation = document.getElementById('create_reservation_form');
    const guestForm = document.getElementById('create_guest_reservation_form');
    let room = null;
    let people = [];
    let returnToReservation = false;
    let searchVersion = 0;
    let guestVersion = 0;
    let savingGuest = false;
    let savingReservation = false;
    const colors = { Disponible: '#228B55', Ocupado: '#BE3444', Reservado: '#A76100', Limpieza: '#007D9C', Libre: '#4566A1' };

    function notify(message, type = 'warning') { functions.toast_message(type, message, type === 'success' ? 'Correcto' : 'Aviso'); }
    function request(url, data = {}, method = 'GET') {
        return $.ajax({ url: BASE_URL + url, type: method, data, dataType: 'json', timeout: 25000 });
    }
    function values(form) { return Object.fromEntries(new FormData(form).entries()); }
    function selectedType(selector) { return $(selector).find('option:selected').text(); }
    function documentValid(type, number) {
        const rules = { DNI: /^[0-9]{8}$/, RUC: /^[0-9]{11}$/, CE: /^[A-Z0-9]{6,12}$/ };
        return !!rules[type] && rules[type].test(number);
    }
    function doc(typeSelector, numberSelector) {
        const number = $(numberSelector).val().trim().toUpperCase();
        if (!documentValid(selectedType(typeSelector), number)) {
            throw new Error('Ingrese DNI de 8 dígitos, RUC de 11 o CE de 6 a 12 caracteres alfanuméricos.');
        }
        return { document_type: $(typeSelector).val(), document_number: number };
    }
    function clearPerson() {
        searchVersion++;
        people = [];
        $('#id_guest').empty().append(new Option('Busque una persona', ''));
        $('#person_summary').text('');
        $('#btn_new_guest').addClass('d-none');
    }
    function showPeople(rows) {
        people = rows;
        const select = $('#id_guest').empty().append(new Option('Seleccione una coincidencia', ''));
        rows.forEach(p => {
            const option = new Option(`${p.name} — ${p.document_number}${Number(p.status) !== 1 ? ' (inactivo)' : ''}`, p.id);
            option.disabled = Number(p.status) !== 1;
            select.append(option);
        });
        if (rows.length === 1 && Number(rows[0].status) === 1) { select.val(rows[0].id); }
        select.trigger('change');
    }
    $('#id_guest').on('change', function () {
        const person = people.find(p => String(p.id) === this.value);
        $('#person_summary').text(person ? [person.name, person.nationality, person.phone, person.email].filter(Boolean).join(' · ') : '');
        $('#btn_view_history').toggleClass('d-none', !person);
    });

    function escapeHtml(value) { return $('<div>').text(value ?? '').html(); }

    async function loadHistory(idPerson) {
        $('#history_profile').html('<p class="text-muted">Cargando ficha…</p>');
        $('#history_stays').html('<tr><td colspan="5" class="text-muted">Cargando…</td></tr>');
        $('#history_modal').modal('show');
        try {
            // 8.2 paso 1: Consultar ficha
            const profile = await request('Reception/get_person_profile', { id_person: idPerson });
            if (profile.status !== 'OK') { throw new Error(profile.msg); }
            const p = profile.data;
            const nombre = p.business_name || [p.first_names, p.last_names].filter(Boolean).join(' ') || p.name;
            $('#history_profile').html(`
                <p class="mb-1"><strong>${escapeHtml(nombre)}</strong> — ${escapeHtml(p.document_type)} ${escapeHtml(p.document_number)}</p>
                <p class="mb-1">Nacionalidad: ${escapeHtml(p.nationality || 'N/A')} · Nacimiento: ${escapeHtml(p.birth_date || 'N/A')} ${p.birth_place ? '(' + escapeHtml(p.birth_place) + ')' : ''}</p>
                <p class="mb-1">Contacto: ${escapeHtml(p.phone || 'N/A')} · ${escapeHtml(p.email || 'N/A')}</p>
                <p class="mb-0">Dirección: ${escapeHtml(p.address || 'N/A')}</p>
            `);
            // 8.2 paso 2: Recuperar reservas y estadías anteriores
            const stays = await request('Reception/get_person_stays', { id_person: idPerson });
            if (stays.status !== 'OK') { throw new Error(stays.msg); }
            const rows = stays.data.map(s => `<tr>
                <td>${escapeHtml(s.checkin_date)} ${escapeHtml(s.checkin_time)}</td>
                <td>${escapeHtml(s.checkout_date || '—')} ${escapeHtml(s.checkout_time || '')}</td>
                <td>${escapeHtml(s.room_number)}</td>
                <td>${escapeHtml(s.type_name)}</td>
                <td>${escapeHtml(s.status)}</td>
            </tr>`).join('');
            $('#history_stays').html(rows || '<tr><td colspan="5" class="text-muted">Sin estadías registradas.</td></tr>');
        } catch (e) {
            $('#history_profile').html(`<p class="text-danger">${escapeHtml(e.message || 'No se pudo cargar la ficha.')}</p>`);
            $('#history_stays').html('<tr><td colspan="5" class="text-danger">No se pudo cargar el historial.</td></tr>');
        }
    }
    $('#btn_view_history').on('click', function () {
        const idPerson = $('#id_guest').val();
        if (idPerson) { loadHistory(idPerson); }
    });
    $('#client_document_type, #document_number_reservation').on('input change', clearPerson);

    async function loadRooms() {
        try {
            const state = $('#btn_room_status').val();
            const result = await request(state ? 'Reception/get_room_by_status' : 'Reception/get_rooms', state ? { room_status: state } : {});
            if (result.status !== 'OK') { throw new Error(result.msg); }
            const container = $('#data-container').empty();
            result.data.forEach(item => {
                const card = $('<div class="card text-white p-2" style="width:17rem"></div>').css('background-color', colors[item.room_status] || '#555');
                $('<h4 class="text-white"></h4>').text(`Habitación ${item.room_number}`).appendTo(card);
                $('<p></p>').text(`${item.type_name} · ${item.person_limit} personas`).appendTo(card);
                $('<p></p>').text(item.room_status).appendTo(card);
                if (['Disponible', 'Reservado'].includes(item.room_status)) {
                    $('<button type="button" class="btn btn-light mb-1">Registrar reserva</button>').on('click', () => openReservation(item)).appendTo(card);
                }
                if (item.room_status === 'Limpieza') {
                    $('<button type="button" class="btn btn-light">Terminar limpieza</button>').on('click', async function () {
                        $(this).prop('disabled', true);
                        try {
                            const data = await request('Reception/clean_rooms', { id_room: item.id_room }, 'POST');
                            if (data.status !== 'OK') { throw new Error(data.msg); }
                            loadRooms();
                        } catch (e) { notify(e.message || 'No se pudo actualizar.'); $(this).prop('disabled', false); }
                    }).appendTo(card);
                } else {
                    $('<button type="button" class="btn btn-outline-light">Ver estado</button>').on('click', () => {
                        $('#create_timer_form [name=id_room]').val(item.id_room);
                        $('#timer_modal').modal('show');
                    }).appendTo(card);
                }
                container.append(card);
            });
            if (!result.data.length) { container.text('No hay habitaciones en este estado.'); }
        } catch (e) { notify(e.message || 'No se pudieron cargar las habitaciones.', 'error'); }
    }

    function openReservation(item) {
        reservation.reset();
        room = item;
        clearPerson();
        $('#reservation_feedback').text('');
        $('#room_summary').text(`Habitación ${item.room_number} · ${item.type_name} · ${item.bed_type}`);
        $(reservation).find('[name=id_room]').val(item.id_room);
        const now = new Date();
        const localDate = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
        $('#fechaInicio').val(localDate);
        $('#horaInicio').val(`${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`);
        $('#update_habitacion_modal').modal('show');
    }

    $('#btn_buscar_bd').on('click', async function () {
        clearPerson();
        const version = searchVersion;
        $(this).prop('disabled', true);
        try {
            const result = await request('Reception/get_guest', doc('#client_document_type', '#document_number_reservation'));
            if (version !== searchVersion) { return; }
            if (result.status !== 'OK') { throw new Error(result.msg); }
            showPeople(result.data);
            if (!result.data.length) {
                $('#person_summary').text('No existe una ficha. Registre sus datos para continuar.');
                $('#btn_new_guest').removeClass('d-none');
            } else if (result.data.length > 1) {
                $('#person_summary').text('Hay varias fichas históricas: seleccione la correcta.');
            } else if (Number(result.data[0].status) !== 1) {
                $('#person_summary').text('La ficha está inactiva. Revísela en Clientes.');
            }
        } catch (e) { if (version === searchVersion) { $('#person_summary').text(e.message || 'Error de conexión. Vuelva a buscar.'); } }
        finally { $(this).prop('disabled', false); }
    });

    function guestType() {
        const business = selectedType('#client_document') === 'RUC';
        $('.natural-person').toggleClass('d-none', business).find('input').prop('required', !business).prop('disabled', business);
        $('.business-person').toggleClass('d-none', !business).find('input').prop('required', business).prop('disabled', !business);
        $('#btn_buscar_api').prop('disabled', !['DNI', 'RUC'].includes(selectedType('#client_document')));
    }
    $('#client_document, #document_number').on('input change', function () {
        guestVersion++;
        $('#guest_matches').empty(); $('#guest_feedback').text('');
        $(guestForm).find('[name=first_names], [name=last_names], [name=company_name], [name=nationality], [name=address], [name=phone], [name=email]').val('');
        guestType();
    });
    function openGuest(fromReservation) {
        returnToReservation = fromReservation;
        guestForm.reset(); guestVersion++;
        $('#guest_feedback').text(''); $('#guest_matches').empty();
        if (fromReservation) {
            $('#client_document').val($('#client_document_type').val());
            $('#document_number').val($('#document_number_reservation').val());
            $('#update_habitacion_modal').one('hidden.bs.modal', () => $('#create_guest_reservation_modal').modal('show')).modal('hide');
        } else { $('#create_guest_reservation_modal').modal('show'); }
        guestType();
    }
    $('#btn_add_client').on('click', () => openGuest(false));
    $('#btn_new_guest').on('click', () => openGuest(true));
    $('#create_guest_reservation_modal').on('hidden.bs.modal', function () {
        guestVersion++;
        if (returnToReservation) { $('#update_habitacion_modal').modal('show'); }
        returnToReservation = false;
    });
    function choosePerson(person) {
        if (Number(person.status) !== 1) { notify('La ficha está inactiva. Revísela en Clientes.'); return; }
        if (returnToReservation) {
            $('#client_document_type').val(person.id_document_type);
            $('#document_number_reservation').val(person.document_number);
            clearPerson(); showPeople([person]);
        }
        $('#create_guest_reservation_modal').modal('hide');
    }
    function guestMatches(rows) {
        $('#guest_matches').empty();
        rows.forEach(person => {
            $('<button type="button" class="btn btn-outline-primary"></button>')
                .text(`${person.name} · ${person.phone || person.email || 'Sin contacto'}${Number(person.status) !== 1 ? ' (inactivo)' : ''}`)
                .prop('disabled', Number(person.status) !== 1).on('click', () => choosePerson(person)).appendTo('#guest_matches');
        });
    }
    $('#buscar_huesped').on('click', async function () {
        const version = ++guestVersion;
        $(this).prop('disabled', true);
        try {
            const result = await request('Reception/get_guest', doc('#client_document', '#document_number'));
            if (version !== guestVersion) { return; }
            if (result.status !== 'OK') { throw new Error(result.msg); }
            guestMatches(result.data);
            $('#guest_feedback').text(result.data.length ? 'Ya existen fichas con este documento. Seleccione una.' : 'No existe: complete los datos y guarde.');
        } catch (e) { if (version === guestVersion) { $('#guest_feedback').text(e.message || 'Error de conexión.'); } }
        finally { $(this).prop('disabled', false); }
    });
    $('#btn_buscar_api').on('click', async function () {
        const version = ++guestVersion;
        $(this).prop('disabled', true);
        try {
            const document = doc('#client_document', '#document_number');
            const type = selectedType('#client_document');
            const local = await request('Reception/get_guest', document);
            if (version !== guestVersion) { return; }
            if (local.status !== 'OK') { throw new Error(local.msg); }
            if (local.data.length) { guestMatches(local.data); $('#guest_feedback').text('La persona ya existe. Seleccione su ficha.'); return; }
            if (!['DNI', 'RUC'].includes(type)) { throw new Error('Complete el CE manualmente.'); }
            const result = await request('Clients/get_company_data', { nroDoc: document.document_number });
            if (version !== guestVersion) { return; }
            if (result.status !== 'OK') { throw new Error('La consulta externa no está disponible. Complete los campos manualmente.'); }
            const p = result.data;
            if (type === 'DNI') {
                $('#nombre').val(p.nombres || '');
                $('#apellido').val([p.apellidoPaterno || p.apellido_paterno, p.apellidoMaterno || p.apellido_materno].filter(Boolean).join(' '));
            } else { $('#razon_social').val(p.razonSocial || p.razon_social || ''); }
            $('#direccion').val(p.direccion_completa || p.direccion || '');
            $('#guest_feedback').text('Revise los datos y complete nacionalidad y contacto antes de guardar.');
        } catch (e) { if (version === guestVersion) { $('#guest_feedback').text(e.message || 'No se pudo consultar. Puede registrar manualmente.'); } }
        finally { guestType(); }
    });
    $(guestForm).on('submit', async function (event) {
        event.preventDefault();
        if (savingGuest || !guestForm.reportValidity()) { return; }
        savingGuest = true;
        const data = values(guestForm);
        $(guestForm).find('button').prop('disabled', true);
        $(guestForm).find('input, select').prop('disabled', true);
        $('#create_guest_reservation_modal .btn-close').prop('disabled', true);
        try {
            const result = await request('Reception/create_guest_reservation', data, 'POST');
            if (result.status === 'CONFLICT') { guestMatches(result.data); $('#guest_feedback').text(result.msg); return; }
            if (result.status !== 'OK') { throw new Error(result.msg); }
            notify(result.msg, 'success'); choosePerson(result.data);
        } catch (e) { $('#guest_feedback').text(e.message || 'No se pudo guardar. Reintente; se comprobará si ya existe.'); }
        finally {
            savingGuest = false;
            $(guestForm).find('button, input, select').prop('disabled', false);
            $('#create_guest_reservation_modal .btn-close').prop('disabled', false);
            guestType();
        }
    });

    function estimate() {
        if (!room) { return; }
        const start = new Date(`${$('#fechaInicio').val()}T${$('#horaInicio').val()}`);
        const end = new Date(`${$('#fechaFin').val()}T${$('#horaFin').val()}`);
        const minutes = (end - start) / 60000;
        if (!Number.isFinite(minutes) || minutes <= 0) { $('#payment_room').val(''); return; }
        const rest = minutes % 1440;
        const total = Math.floor(minutes / 1440) * Number(room.price_day) + (rest > 0 ? Number(rest <= 240 ? room.price_temporary : rest <= 720 ? room.price_half : room.price_day) : 0);
        $('#payment_room').val(total.toFixed(2));
        $('#pre_payment').attr('max', total.toFixed(2));
        $('#fechaFin').attr('min', $('#fechaInicio').val());
    }
    $('#fechaInicio, #horaInicio, #fechaFin, #horaFin').on('change', estimate);
    $(reservation).on('submit', async function (event) {
        event.preventDefault();
        if (savingReservation || !reservation.reportValidity()) { return; }
        const data = values(reservation);
        savingReservation = true;
        $(reservation).find('button, input, select').prop('disabled', true);
        $('#update_habitacion_modal .btn-close').prop('disabled', true);
        try {
            const result = await request('Reception/create_reservation', data, 'POST');
            if (result.status !== 'OK') { throw new Error(result.msg); }
            $('#update_habitacion_modal').modal('hide');
            notify(`Reserva ${result.data.id_reservation} guardada. Total: ${Number(result.data.payment_room).toFixed(2)}`, 'success');
            loadRooms();
        } catch (e) { $('#reservation_feedback').text(e.message || 'No se recibió confirmación. Consulte Reservas antes de reintentar.'); }
        finally {
            savingReservation = false;
            $(reservation).find('button, input, select').prop('disabled', false);
            $('#update_habitacion_modal .btn-close').prop('disabled', false);
        }
    });
    $('#create_timer_form').on('submit', async function (event) {
        event.preventDefault();
        const button = $(this).find('button').prop('disabled', true);
        try {
            const result = await request('Reception/update_state', values(this), 'POST');
            if (result.status !== 'OK') { throw new Error(result.msg); }
            $('#timer_modal').modal('hide'); loadRooms();
        } catch (e) { notify(e.message || 'No se pudo actualizar.'); }
        finally { button.prop('disabled', false); }
    });
    $('#fecha_nacimiento').attr('max', new Date().toISOString().slice(0, 10));
    $('#btn_room_status').on('change', loadRooms);
    request('Reception/get_document_types').then(result => {
        if (result.status !== 'OK') { notify(result.msg); return; }
        $('.document-types').each(function () {
            $(this).empty().append(new Option('Seleccione', ''));
            result.data.filter(t => ['DNI', 'RUC', 'CE'].includes(t.description)).forEach(t => $(this).append(new Option(t.description, t.id)));
        });
    }).fail(() => notify('No se pudo cargar el catálogo de documentos. Recargue la página.'));
    loadRooms();
});
