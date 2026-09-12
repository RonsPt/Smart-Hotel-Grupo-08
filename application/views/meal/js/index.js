// Función para cargar la tabla DataTable
function load_datatable() {
    destroy_datatable();  // Destruye la tabla antes de cargarla de nuevo, si existe

    let dataTable = $('#datatable-meal').DataTable({
        ajax: {
            url: BASE_URL + 'Meal/get_meal',
            cache: false,
        },
        columns: [
            { data: 'meal_sku' },
            { data: 'meal_name' },
            { data: 'meal_description' },
            { data: 'description' },  // Categoría
            { data: 'meal_price' },
            {
                class: 'center',
                render: function (data, type, row) {
                    return (
                        '<button class="btn btn-sm btn-info btn-round btn-icon btn_update" data-process-key="' + row.id_meal + '">' +
                        feather.icons['edit'].toSvg({ class: 'font-small-4' }) +
                        '</button>' +
                        ' ' +
                        '<button class="btn btn-sm btn-danger btn-round btn-icon btn_delete" data-process-key="' + row.id_meal + '">' +
                        feather.icons['trash-2'].toSvg({ class: 'font-small-4' }) +
                        '</button>'
                    );
                }
            }
        ],
        dom: functions.head_datatable(),
        buttons: functions.custom_buttons_datatable([2], '#create_meal_modal'),
        language: {
            url: BASE_URL + 'public/assets/json/languaje-es.json'
        }
    });

    dataTable.on('xhr', function () {
        var data = dataTable.ajax.json();
        functions.toast_message(data.type, data.msg, data.status);
    });
}

// Función para destruir DataTable si ya existe
function destroy_datatable() {
    if ($.fn.DataTable.isDataTable('#datatable-meal')) {
        $('#datatable-meal').DataTable().destroy();
    }
}

// Función para refrescar DataTable
function refresh_datatable() {
    $('#datatable-meal').DataTable().ajax.reload();
}

// Función para manejar la carga exitosa de la comida por ID
function successHandler(data) {
    console.log('Respuesta de get_meal_by_id:', data);
    if (data.status === 'OK') {
        let item = data.data;
        // Rellenar el formulario
        $('#update_meal_form :input[name=id_meal]').val(item.id_meal);
        $('#update_meal_form :input[name=meal_sku]').val(item.meal_sku);
        $('#update_meal_form :input[name=meal_name]').val(item.meal_name);
        $('#update_meal_form :input[name=meal_description]').val(item.meal_description);
        $('#update_meal_form :input[name=id_category]').val(item.id_category);
        $('#update_meal_form :input[name=meal_price]').val(item.meal_price);
    } else {
        console.log('No se encontraron datos de la comida.');
    }
}

// Función para manejar errores en las solicitudes AJAX
function errorHandler(xhr, status, error) {
    console.error('Error en la solicitud AJAX:', error);
}

// Función para cargar categorías
function load_categories_once() {
    $.ajax({
        url: BASE_URL + 'Meal/get_categories',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.status === 'OK') {
                categories = data.data;
                localStorage.setItem('mealCategories', JSON.stringify(categories));
                console.log('Categorías cargadas:', categories);
            } else {
                console.log('Error: No se encontraron categorías.');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar categorías:', error);
        }
    });
}

// Función para rellenar el select de categorías
function populate_categories(selectElement) {
    if (categories.length > 0) {
        let options = '<option value="">Seleccionar categoría</option>';
        categories.forEach(category => {
            options += `<option value="${category.id}">${category.description}</option>`;
        });
        $(selectElement).html(options);  // Rellenar el select con las categorías
    } else {
        console.log('No hay categorías disponibles para cargar.');
    }
}

// Cargar categorías en el modal de creación
$('#create_meal_modal').on('show.bs.modal', function () {
    populate_categories('#create_meal_form select[name=id_category]');
});

// Cargar categorías en el modal de actualización
$('#update_meal_modal').on('show.bs.modal', function () {
    populate_categories('#update_meal_form select[name=id_category]');
});

// Cargar datos en el modal de actualización
$(document).on('click', '.btn_update', function () {
    let value = $(this).attr('data-process-key');
    let params = { 'id_meal': value };

    $('#update_meal_modal').modal('show');

    $.ajax({
        url: BASE_URL + 'Meal/get_meal_by_id',
        type: 'GET',
        data: params,
        dataType: 'json',
        success: successHandler,
        error: errorHandler
    });
});

// Resetear formularios al cerrar el modal
$('.modal').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
    $('#btn_create_meal').prop('disabled', false);
    $('#btn_update_meal').prop('disabled', false);
});

// Validar formularios
$('#create_meal_form').validate({
    submitHandler: function (form) {
        create_meal(form);
    }
});

$('#update_meal_form').validate({
    submitHandler: function (form) {
        update_meal(form);
    }
});

// Cargar DataTable y categorías al cargar la página
$(document).ready(function () {
    load_datatable();
    load_categories_once();
});

// Crear nueva comida
function create_meal(form) {
    let formData = $(form).serialize(); // Serializar los datos del formulario

    $.ajax({
        url: BASE_URL + 'Meal/create_meal',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            if (response.status === 'OK') {
                alert('Comida creada exitosamente');
                refresh_datatable(); // Refrescar DataTable después de la creación
            } else {
                alert(response.msg);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
            alert('Error al crear la comida');
        }
    });
}

// Actualizar comida existente
function update_meal(form) {
    let formData = $(form).serialize(); // Serializar los datos del formulario

    $.ajax({
        url: BASE_URL + 'Meal/update_meal', // URL para actualizar la comida
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            if (response.status === 'OK') {
                alert('Comida actualizada exitosamente');
                $('#update_meal_modal').modal('hide'); // Cerrar el modal de actualización
                refresh_datatable(); // Refrescar DataTable después de la actualización
            } else {
                alert(response.msg);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
            alert('Error al actualizar la comida');
        }
    });
}

// Acción para el botón de eliminar
$(document).on('click', '.btn_delete', function () {
    let id_meal = $(this).attr('data-process-key');

    if (confirm('¿Estás seguro de que deseas eliminar esta comida?')) {
        $.ajax({
            url: BASE_URL + 'Meal/delete_meal',
            type: 'POST',
            data: { id_meal: id_meal },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'OK') {
                    alert('Comida eliminada exitosamente');
                    refresh_datatable();  // Refrescar la tabla después de eliminar
                } else {
                    alert(response.msg);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
                alert('Error al eliminar la comida');
            }
        });
    }
});
