// -- Functions

// --
function destroy_datatable() {
    // --
    $('#datatable-companies').dataTable().fnDestroy();
}

// --
function refresh_datatable() {
    // --
    $('#datatable-companies').DataTable().ajax.reload();
}

// --
function load_datatable() {
    // --
    destroy_datatable();
    // --
    let dataTable = $('#datatable-companies').DataTable({
        // --
        ajax: {
            url: BASE_URL + 'Companies/get_companies',
            cache: false,
        },
        columns: [
            { data: 'company_name' },
            { data: 'ruc' },
            { data: 'business_name' },
            {
                data: null,
                render: function (data, type, row) {
                    // --
                    let parts = [];
                    // --
                    if (row.contact_name) { parts.push('<strong>' + row.contact_name + '</strong>'); }
                    if (row.contact_phone) { parts.push('<i class="bi bi-telephone me-50"></i>' + row.contact_phone); }
                    if (row.contact_email) { parts.push('<i class="bi bi-envelope me-50"></i>' + row.contact_email); }
                    // --
                    return parts.length ? parts.join('<br>') : '<span class="badge bg-light-secondary">Sin contacto</span>';
                }
            },
            {
                data: 'corporate_tariff',
                render: function (data) {
                    return data != null ? 'S/ ' + parseFloat(data).toFixed(2) : 'S/ 0.00';
                }
            },
            {
                data: 'credit_limit',
                render: function (data) {
                    return data != null ? 'S/ ' + parseFloat(data).toFixed(2) : 'S/ 0.00';
                }
            },
            {
                data: 'guest_count',
                render: function (data) {
                    let n = parseInt(data, 10) || 0;
                    return n > 0
                        ? '<span class="badge bg-light-primary">' + n + ' huésped(es)</span>'
                        : '<span class="badge bg-light-secondary">Sin huéspedes</span>';
                }
            },
            {
                class: 'center',
                render: function (data, type, row) {
                    // --
                    return (
                        '<button class="btn btn-sm btn-info btn-round btn-icon btn_update" data-process-key="' + row.id_company + '">' +
                        feather.icons['edit'].toSvg({ class: 'font-small-4' }) +
                        '</button>'
                        + ' ' +
                        '<button  class="btn btn-sm btn-danger btn-round btn-icon btn_delete" data-process-key="' + row.id_company + '">' +
                        feather.icons['trash-2'].toSvg({ class: 'font-small-4' }) +
                        '</button>'
                    );
                }
            },
        ],
        dom: functions.head_datatable(),
        buttons: functions.custom_buttons_datatable([0, 1, 2, 3, 4, 5, 6], '#create_company_modal'),
        language: {
            url: BASE_URL + 'public/assets/json/languaje-es.json'
        }
    })

    // --
    dataTable.on('xhr', function () {
        // --
        var data = dataTable.ajax.json();
        // --
        functions.toast_message(data.type, data.msg, data.status);
    });
}

// --
function load_guests_options() {
    // --
    $.ajax({
        url: BASE_URL + 'Clients/get_business_name_cli',
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function (data) {
            // --
            if (data.status === 'OK') {
                // --
                let html = '';
                // --
                data.data.forEach(function (element) {
                    // --
                    let label = (element.business_name || '').trim() + (element.document_number ? ' - ' + element.document_number : '');
                    // --
                    html += '<option value="' + element.id + '">' + label + '</option>';
                });
                // --
                $('#create_guests').html(html);
                $('#update_guests').html(html);
                // --
                $('#create_guests').val(null).trigger('change');
                $('#update_guests').val(null).trigger('change');
            }
        }
    });
}

// --
function create_company(form) {
    // --
    $('#btn_create_company').prop('disabled', true);
    // --
    let params = new FormData(form);
    // --
    $.ajax({
        url: BASE_URL + 'Companies/create_company',
        type: 'POST',
        data: params,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            console.log('Cargando...');
        },
        success: function (data) {
            // --
            functions.toast_message(data.type, data.msg, data.status);
            // --
            if (data.status === 'OK') {
                // --
                $('#create_company_modal').modal('hide');
                form.reset();
                refresh_datatable();
            } else {
                // --
                $('#btn_create_company').prop('disabled', false);
            }
        }
    })
}

// --
function update_company(form) {
    // --
    $('#btn_update_company').prop('disabled', true);
    // --
    let params = new FormData(form);
    // --
    $.ajax({
        url: BASE_URL + 'Companies/update_company',
        type: 'POST',
        data: params,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            console.log('Cargando...');
        },
        success: function (data) {
            // --
            functions.toast_message(data.type, data.msg, data.status);
            // --
            if (data.status === 'OK') {
                // --
                $('#update_company_modal').modal('hide');
                form.reset();
                refresh_datatable();
            } else {
                // --
                $('#btn_update_company').prop('disabled', false);
            }
        }
    })
}

// -- Events

// --
$(document).on('click', '.btn_update', function () {
    // --
    let value = $(this).attr('data-process-key');
    // --
    let params = { 'id_company': value }
    // --
    $.ajax({
        url: BASE_URL + 'Companies/get_company_by_id',
        type: 'GET',
        data: params,
        dataType: 'json',
        contentType: false,
        processData: true,
        cache: false,
        success: function (data) {
            // --
            if (data.status === 'OK') {
                // --
                let item = data.data
                // --
                $('#update_company_form :input[name=id_company]').val(item.id_company);
                $('#update_company_form :input[name=company_name]').val(item.company_name);
                $('#update_company_form :input[name=ruc]').val(item.ruc);
                $('#update_company_form :input[name=business_name]').val(item.business_name || '');
                $('#update_company_form :input[name=contact_name]').val(item.contact_name || '');
                $('#update_company_form :input[name=contact_email]').val(item.contact_email || '');
                $('#update_company_form :input[name=contact_phone]').val(item.contact_phone || '');
                $('#update_company_form :input[name=commercial_conditions]').val(item.commercial_conditions || '');
                $('#update_company_form :input[name=corporate_tariff]').val(item.corporate_tariff);
                $('#update_company_form :input[name=credit_limit]').val(item.credit_limit);
                // --
                let guestIds = (item.guests || []).map(function (g) { return String(g.id_person); });
                // --
                $('#update_guests').val(guestIds).trigger('change');
                // --
            }
        }
    })
    // --
    $('#update_company_modal').modal('show');
})

// --
$(document).on('click', '.btn_delete', function () {
    // --
    let value = $(this).attr('data-process-key');
    // --
    let params = { 'id_company': value }
    // --
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡No podrás revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, eliminar!',
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ms-1'
        },
        buttonsStyling: false,
        preConfirm: _ => {
            return $.ajax({
                url: BASE_URL + 'Companies/delete_company',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (data) {
                    // --
                    functions.toast_message(data.type, data.msg, data.status);
                    // --
                    if (data.status === 'OK') {
                        // --
                        refresh_datatable();
                    }
                }
            })
        }
    }).then(result => {
        if (result.isConfirmed) {
        }
    });
});

// -- Search RUC (create)
$(document).on('click', '#create_company_modal .btn_get_company_data', function () {
    // --
    let ruc = $('#create_company_form :input[name=ruc]').val();
    // --
    if (!ruc || ruc.length !== 11) {
        functions.toast_message('warning', 'Ingrese un RUC de 11 dígitos.', 'AVISO');
        return;
    }
    // --
    $.ajax({
        url: BASE_URL + 'Companies/get_company_data',
        type: 'GET',
        data: { nroDoc: ruc },
        dataType: 'json',
        cache: false,
        success: function (response) {
            // --
            functions.toast_message(response.type, response.msg, response.status);
            // --
            if (response.status === 'OK') {
                // --
                let name = response.data.razonSocial || response.data.nombre || '';
                // --
                $('#create_company_form :input[name=company_name]').val(name);
                $('#create_company_form :input[name=business_name]').val(name);
            }
        },
    });
});

// -- Search RUC (update)
$(document).on('click', '#update_company_modal .btn_get_company_data_edit', function () {
    // --
    let ruc = $('#update_company_form :input[name=ruc]').val();
    // --
    if (!ruc || ruc.length !== 11) {
        functions.toast_message('warning', 'Ingrese un RUC de 11 dígitos.', 'AVISO');
        return;
    }
    // --
    $.ajax({
        url: BASE_URL + 'Companies/get_company_data',
        type: 'GET',
        data: { nroDoc: ruc },
        dataType: 'json',
        cache: false,
        success: function (response) {
            // --
            functions.toast_message(response.type, response.msg, response.status);
            // --
            if (response.status === 'OK') {
                // --
                let name = response.data.razonSocial || response.data.nombre || '';
                // --
                $('#update_company_form :input[name=company_name]').val(name);
                $('#update_company_form :input[name=business_name]').val(name);
            }
        },
    });
});

// -- Reset forms
$(document).on('click', '.reset', function () {
    // --
    $('#create_company_form').validate().resetForm();
    $('#update_company_form').validate().resetForm();
})

// -- Validate form
$('#create_company_form').validate({
    // --
    submitHandler: function (form) {
        create_company(form);
    }
})

// -- Validate form
$('#update_company_form').validate({
    // --
    submitHandler: function (form) {
        update_company(form);
    }
})

// -- Reset form on modal hidden
$('.modal').on('hidden.bs.modal', function () {
    // --
    $(this).find('form')[0].reset();
    // --
    $('#update_guests, #create_guests').val(null).trigger('change');
    // -- Enable buttons
    $('#btn_create_company').prop('disabled', false);
    $('#btn_update_company').prop('disabled', false);
});

// --
load_guests_options();
load_datatable();