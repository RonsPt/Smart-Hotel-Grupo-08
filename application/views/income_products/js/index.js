// ------------------- DESTROY DATATABLE -------------------
function destroy_datatable() {
  $("#datatable-income-products").dataTable().fnDestroy();
}

// ------------------- REFRESH DATATABLE -------------------
function refresh_datatable() {
  $("#datatable-income-products").DataTable().ajax.reload();
}

// ------------------- LISTADO DATATABLE -------------------
function load_datatable() {
  destroy_datatable();

  let dataTable = $("#datatable-income-products").DataTable({
    order: [[2, "desc"]],
    ajax: {
      url: BASE_URL + "Income_Products/get_income_products",
      cache: false,
      dataSrc: function (json) {
        if (json.warning && json.warning.show) {
          showWarningAlert(json.warning);
        }
        return json.data;
      },
    },

   columns: [
  { data: "name", defaultContent: "" }, // CLIENTE

  {
    data: null,
    render: function (data, type, row) {
      return `${row.series ?? ""} - ${row.number_serial ?? ""}`;
    },
  }, // NUMERO

  { data: "expiration_date", defaultContent: "" }, // FECHA REGISTRO (temporal, porque este es el campo que sí te llega)

  { data: "payment_type_description", defaultContent: "" }, // TIPO DE PAGO

  { data: "voucher_type_description", defaultContent: "" }, // VOUCHER (solo si en backend ya mandas esto)
  // si no existe "voucher", aquí se verá vacío

  { data: "total_purchase", defaultContent: "0.00" }, // TOTAL

  {
    className: "center",
    render: function (data, type, row) {
      let buttons = "";

      buttons += `<button class="btn btn-sm btn-primary btn_details" data-process-key="${row.id_income_products}">
        ${feather.icons["eye"].toSvg({ class: "font-small-2" })}
      </button> `;

      buttons += `<button class="btn btn-sm btn-danger btn_delete_custom" data-process-key="${row.id_income_products}">
        ${feather.icons["trash-2"].toSvg({ class: "font-small-2" })}
      </button> `;

      buttons += `<button class="btn btn-sm btn-success">
        Descargar
      </button>`;

      return buttons;
    },
  },
],

    dom: functions.head_datatable(),
    buttons: functions.custom_buttons_datatable(
      [6],
      "#create_income_products_modal"
    ),
    language: { url: BASE_URL + "public/assets/json/languaje-es.json" },
  });

  dataTable.on("xhr", function () {
    let data = dataTable.ajax.json();
    //console.log("Datos recibidos:", data);
  });
}

$(document).on("click", ".btn_update_disabled", function () {
  Swal.fire({
    icon: "error",
    title: "Producto Rechazado",
    text: "Este producto está rechazado y no se puede editar."
  });
});

// ------------------- DETAILS -------------------
$("#datatable-income-products").on("click", ".btn_details", function () {
  var id_income_products = $(this).data("process-key");

  $.ajax({
      url: BASE_URL + "Income_Products/income_products_details",
      type: "GET",
      data: { id_income_products: id_income_products },
      dataType: "json",
      success: function (response) {
          if (response.status === "OK") {
              var data = response.result;

              $("#name_client").val(data.client_name);
              $("#sale_date").val(data.sale_date || "Fecha no disponible");
              $("#voucher_type").val(data.voucher_type);
              $("#payment_type").val(data.payment_type);
              $("#series").val(data.series);
              $("#number_serial").val(data.number_serial);
              $("#expiration_date").val(data.expiration_date);
              $("#payment_shape").val(data.payment_shape);

              $("#incomeProductDetails").empty();
              var total = 0;

              data.products.forEach(function (product) {
                  var cantidad = parseInt(product.quantity) || 0;
                  var precio = parseFloat(product.full_purchase) || 0;
                  var precioTotal = parseFloat(product.subtotal) || 0;

                  total += precioTotal;

                  var row = `
                      <tr>
                          <td>${product.product_name}</td>
                          <td>${cantidad}</td>
                          <td>S/ ${precio.toFixed(2)}</td>
                          <td>S/ ${precioTotal.toFixed(2)}</td>
                      </tr>
                  `;
                  $("#incomeProductDetails").append(row);
              });

              $("#full_purchase").val("S/ " + total.toFixed(2));
              $("#incomeProductModal").modal("show");
          } else {
              alert("No se encontraron detalles para este ingreso de productos.");
          }
      },
      error: function () {
          alert("Ocurrió un error al obtener los detalles.");
      }
  });
});

// ------------------- UPDATE -------------------
$("#datatable-income-products").on("click", ".btn_update", function () {
    var id_income_products = $(this).data("process-key");
    window.location.href = BASE_URL + "Income_Products_Update/index.php?id=" + id_income_products;
});

// ------------------- DELETE -------------------
$("#datatable-income-products").on("click", ".btn_delete_custom", function () {
  var id_income_products = $(this).data("process-key");

  if (id_income_products) {
    Swal.fire({
      title: "¿Estás seguro de eliminar este ingreso?",
      text: "Esta acción no se puede deshacer.",
      icon: "error",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: BASE_URL + "Income_Products/delete_income_products",
          type: "POST",
          data: { id_income_product: id_income_products },
          success: function (response) {
            if (response.status === "OK") {
              Swal.fire("¡Eliminado!","El registro ha sido eliminado.","success");
              location.reload();
            } else {
              Swal.fire("Error","Hubo un problema al eliminar.","error");
            }
          },
          error: function () {
            Swal.fire("Error","No se pudo completar la solicitud.","error");
          },
        });
      }
    });
  } else {
    Swal.fire("Error", "ID no encontrado", "error");
  }
});

// -- Redirect new controller
$(document).on('click', '.create-new', function() {
    window.location.assign(BASE_URL + 'Income_Products_Details');
});

//--- Notificacion - Registros Pendientes
function showWarningAlert(warning) {
  Swal.mixin({
      toast: true,
      position: 'bottom-end',
      showConfirmButton: true,
      showCancelButton: true,
      confirmButtonText: 'Ver pendientes',
      cancelButtonText: 'Cerrar',
      timer: 15000,
      timerProgressBar: true,
      customClass: {
          confirmButton: 'btn btn-warning btn-sm',
          cancelButton: 'btn btn-outline-secondary btn-sm ms-1',
          container: 'p-20'
      },
      buttonsStyling: false
  }).fire({
      icon: 'warning',
      title: '<i class="fas fa-exclamation-triangle"></i> Registros Pendientes!',
      html: `
          <div class="text-justify" style="max-width: 300px">
              <p class="mb-2">${warning.message}</p>
              <hr class="my-2">
              <p class="text-muted small mb-0">
                  <i class="fas fa-info-circle me-1"></i>
                  Por favor: Tenga en cuenta que los registros pendientes que no sean aceptados dentro del plazo establecido serán rechazados.
              </p>
          </div>
      `
  }).then((result) => {
      if (result.isConfirmed) {
        window.location.assign(BASE_URL + 'Income_Products_Pending');
      }
  });
}

load_datatable();