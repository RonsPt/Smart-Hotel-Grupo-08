<!-- BEGIN: Content -->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0"> <!-- Cambié a container-fluid -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Lista de Comidas</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><?php echo $selected_menu; ?></a>
                                </li>
                                <li class="breadcrumb-item active">
                                    <span><?php echo $selected_sub_menu; ?></span>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Tabla de comidas -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="table table-striped table-hover" id="datatable-meal" style="table-layout: fixed; width: 100%;"> <!-- Forzar la tabla a ocupar todo el espacio -->
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenarán los datos -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Tabla de comidas -->

            <!-- Modal para Crear Comida -->
            <div class="modal fade" id="create_meal_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="reset" class="btn-close reset" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-sm-5 pb-5">
                            <div class="text-center mb-2">
                                <h1 class="mb-1">Agregar nueva comida</h1>
                            </div>
                            <form method="POST" enctype="multipart/form-data" id="create_meal_form" class="row" onsubmit="return false">
                                <div class="col-12">
                                    <label class="form-label">SKU</label>
                                    <input type="text" name="meal_sku" class="form-control" placeholder="SKU" autofocus required />
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="meal_name" class="form-control" placeholder="Nombre de la comida" required />
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Descripción</label>
                                    <textarea name="meal_description" class="form-control" placeholder="Descripción"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Categoría</label>
                                    <select name="id_category" class="form-control" required>
                                        <!-- Aquí se llenará dinámicamente con las categorías disponibles -->
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Precio</label>
                                    <input type="number" name="meal_price" class="form-control" placeholder="Precio" />
                                </div>
                                <br>
                                <div class="col-12 text-center">
                                    <button id="btn_create_meal" type="submit" class="btn btn-primary mt-2 me-1">Guardar</button>
                                    <button type="reset" class="btn btn-outline-secondary mt-2 reset" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Modal Crear Comida -->

            <!-- Modal para Actualizar Comida -->
            <div class="modal fade" id="update_meal_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="reset" class="btn-close reset" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-sm-5 pb-5">
                            <div class="text-center mb-2">
                                <h1 class="mb-1">Actualizar Comida</h1>
                            </div>
                            <form method="POST" enctype="multipart/form-data" id="update_meal_form" class="row" onsubmit="return false">
                                <div class="col-12">
                                    <label class="form-label">SKU</label>
                                    <input type="text" name="meal_sku" class="form-control" placeholder="SKU" required />
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="meal_name" class="form-control" placeholder="Nombre de la comida" required />
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Descripción</label>
                                    <textarea name="meal_description" class="form-control" placeholder="Descripción"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Categoría</label>
                                    <select name="id_category" class="form-control" required>
                                        <!-- Aquí se llenará dinámicamente con las categorías disponibles -->
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Precio</label>
                                    <input type="number" name="meal_price" class="form-control" placeholder="Precio" readonly />
                                </div>
                                <input type="hidden" name="id_meal">
                                <br>
                                <div class="col-12 text-center">
                                    <button id="btn_update_meal" type="submit" class="btn btn-primary mt-2 me-1">Guardar</button>
                                    <button type="reset" class="btn btn-outline-secondary mt-2 reset" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Modal Actualizar Comida -->
        </div>
    </div>
</div>
<!-- END: Content -->

<!-- CSS para eliminar los espacios en blanco -->
<style>
    .container-fluid {
        width: 100%;  /* Asegurar que el contenedor ocupe todo el ancho */
        padding: 0;   /* Eliminar padding */
        margin: 0;    /* Eliminar márgenes */
    }

    #datatable-meal {
        width: 100%;
        table-layout: fixed; /* Forzar la tabla a ocupar todo el espacio */
    }

    .card {
        margin: 0;  /* Eliminar cualquier margen en la tarjeta */
    }

    .table {
        width: 100%; /* Asegurar que la tabla ocupe todo el ancho */
    }

    .content-wrapper {
        padding-left: 0px;  /* Eliminar el padding que pueda estar restringiendo el ancho */
        padding-right: 0px;
    }

    #datatable-meal th, #datatable-meal td {
        padding: 8px 10px; /* Reducir el padding interno */
        text-align: center;
        vertical-align: middle;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-body {
        padding-bottom: 20px;
    }

    .form-label {
        font-weight: bold;
    }

    .btn {
        margin-top: 10px;
    }

    .breadcrumb-wrapper {
        margin-top: 10px;
    }
</style>