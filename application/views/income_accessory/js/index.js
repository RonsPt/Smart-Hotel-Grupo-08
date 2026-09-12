// -- Functions

// --
function destroy_datatable() {
    // --
    $('#datatable-income-accessory').dataTable().fnDestroy();
}

// --
function refresh_datatable() {
    // --
    $('#datatable-income-accessory').DataTable().ajax.reload();
}

// --
function load_datatable() {
    // --
    destroy_datatable();
    // --
    let dataTable = $('#datatable-income-accessory').DataTable({
        // --
        ajax: {
            url: BASE_URL + 'Income_Accessory/get_income_accessory',
            cache: false,
        },
        columns: [
            { data: 'proof_date' },
            { data: 'business_name' },
            { data: 'first_name' },         
            { data: 'vt_description' } ,   
            { 
                class: 'center',
                render: function (data, type, row, meta) {
                    // --
                    return (
                        row.proof_series + ' - ' + row.voucher_series
                    );
                }  
            },
            { data: 'full_purchase' },   
            {
                class: 'center',
                render: function (data, type, row, meta) {
                    // --
                    // ----- ORDEN DE BOTONES CORREGIDO -----
                    return (
                        // 1. Botón de Editar (ahora primero)
                        '<button class="btn btn-sm btn-info btn-round btn-icon btn_update" data-process-key="'+ row.id_income_accessory +'">' +
                        feather.icons['edit'].toSvg({ class: 'font-small-4' }) +
                        '</button>'
                        + ' ' +
                        // 2. Botón de Detalles (ahora segundo)
                        '<button class="btn btn-sm btn-primary btn-round btn-icon btn_details" data-process-key="'+ row.id_income_accessory +'" data-bs-toggle="modal" data-bs-target="#incomeAccessoryModal">' +
                        feather.icons['eye'].toSvg({ class: 'font-small-4' }) +
                        '</button>'
                        + ' ' + 
                        // 3. Botón de Borrar (sigue tercero)
                        '<button  class="btn btn-sm btn-danger btn-round btn-icon btn_delete" data-process-key="'+ row.id_income_accessory +'">' +
                        feather.icons['trash-2'].toSvg({ class: 'font-small-4' }) +
                        '</button>'
                    );
                    // ----- FIN DE LA CORRECCIÓN -----
                }
            },
        ],
        dom: functions.head_datatable(),
        buttons: functions.custom_buttons_datatable([6], '', false), 
        language: {
            url: BASE_URL + 'public/assets/json/languaje-es.json'
        }
    })

    // --
    dataTable.on('xhr', function() {
        let response = dataTable.ajax.json();
        if (response) {
            functions.toast_message(response.type, response.msg, response.status);
        }
    });
}

// -- Redirigir a nuevo controlador (Tu código de "Agregar")
$(document).on('click', '.dt-buttons .btn-primary', function(e) {

    var buttonText = $(this).text();

    if (buttonText.includes('Agregar') || buttonText.includes('Nuevo') || $(this).find('.fa-plus').length) {
        
        e.preventDefault(); 
        window.location.assign(BASE_URL + 'Income_Accessory_Details');
    }
});


// -----------------------------------------------------------------
// --- LÓGICA DE DETALLES (NUEVA) ---
// -----------------------------------------------------------------
$("#datatable-income-accessory").on("click", ".btn_details", function (e) {
    e.stopImmediatePropagation(); // Detiene otros scripts (como el de 06_assets.php)
    
    var id_income_accessory = $(this).data("process-key");

    $.ajax({
        url: BASE_URL + "Income_Accessory/get_income_accessory_details", // Nueva función en el controlador
        type: "GET",
        data: { id_income_accessory: id_income_accessory },
        dataType: 'json',
        success: function (response) {
            if (response.status === "OK" && response.result) {
                let data = response.result;
                
                // Rellenar los campos del modal (que crearemos en el .php)
                $("#incomeAccessoryModal #name_client").val(data.client_name);
                $("#incomeAccessoryModal #proof_date").val(data.proof_date);
                $("#incomeAccessoryModal #proof_series").val(data.proof_series);
                $("#incomeAccessoryModal #voucher_series").val(data.voucher_series);
                $("#incomeAccessoryModal #voucher_type").val(data.voucher_type_description);
                $("#incomeAccessoryModal #payment_type").val(data.payment_type_description);

                // Rellenar la tabla de accesorios dentro del modal
                let detailsHtml = '';
                let totalGeneral = 0;
                
                if (data.accessories && data.accessories.length > 0) {
                    data.accessories.forEach(item => {
                        detailsHtml += `
                            <tr>
                                <td>${item.accessory_description}</td>
                                <td>${item.stock}</td>
                                <td>S/ ${parseFloat(item.purchase_price).toFixed(2)}</td>
                                <td class="text-end">S/ ${parseFloat(item.subtotal).toFixed(2)}</td>
                            </tr>
                        `;
                        totalGeneral += parseFloat(item.subtotal);
                    });
                } else {
                    detailsHtml = '<tr><td colspan="6" class="text-center">No se encontraron accesorios para este ingreso.</td></tr>';
                }
                
                $("#incomeAccessoryModal #incomeAccessoryDetails").html(detailsHtml);
                $("#incomeAccessoryModal #full_purchase").val("S/ " + totalGeneral.toFixed(2));

            } else {
                Swal.fire("Error", "No se pudieron cargar los detalles: " + response.msg, "error");
            }
        },
        error: function (xhr, status, error) {
            console.error("Respuesta del servidor:", xhr.responseText);
            Swal.fire("Error", "No se pudo conectar con el servidor. Revise la consola.", "error");
        }
    });
});


// -----------------------------------------------------------------
// --- LÓGICA DE BORRADO (Tu código funcional) ---
// -----------------------------------------------------------------
$("#datatable-income-accessory").on("click", ".btn_delete", function (e) {
    e.stopImmediatePropagation(); 

    var id_income_accessory = $(this).data("process-key"); 

    if (id_income_accessory) {
        
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¡No podrás revertir esto! El stock se ajustará.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                
                $.ajax({
                    url: BASE_URL + "Income_Accessory/delete_income_accessory",
                    type: "POST",
                    data: { id_income_accessory: id_income_accessory }, 
                    dataType: 'json', 
                    success: function (response) {
                        if (response.status === "OK") {
                            Swal.fire(
                                "¡Eliminado!",
                                "El registro ha sido eliminado.",
                                "success"
                            );
                            refresh_datatable(); 
                        } else {
                            Swal.fire("Error", "Hubo un problema al eliminar: " + response.msg, "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Respuesta del servidor:", xhr.responseText);
                        Swal.fire("Error", "No se pudo completar la solicitud. Revise la consola.", "error");
                    },
                });
            }
        });

    } else {
        Swal.fire("Error", "ID no encontrado", "error");
    }
});

// -----------------------------------------------------------------
// --- LÓGICA DE EDICIÓN (Tu código funcional) ---
// -----------------------------------------------------------------
$("#datatable-income-accessory").on("click", ".btn_update", function (e) {
    e.stopImmediatePropagation(); 
    
    var id_income_accessory = $(this).data("process-key");
    
    // Redirigimos al controlador de detalles pasando el ID como parámetro GET
    window.location.href = BASE_URL + "Income_Accessory_Details/index?id=" + id_income_accessory;
});


// Carga inicial de la tabla
load_datatable();