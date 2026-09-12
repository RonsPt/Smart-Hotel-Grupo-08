    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Campus Starts -->
                <section id="income-accesory">

                    <!-- Header title -->
                    <div class="content-header row">
                        <div class="content-header-left col-md-9 col-12 mb-2">
                            <div class="row breadcrumbs-top">
                                <div class="col-12">
                                    <h2 class="content-header-title float-start mb-0">Lista de <?php echo strtolower($selected_sub_menu); ?></h2>
                                    <div class="breadcrumb-wrapper">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="#"><?php echo $selected_menu; ?></a>
                                            </li>
                                            <li class="breadcrumb-item active"><span><?php echo $selected_sub_menu; ?></span>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Header table-->

                    <!-- Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <table class="table" id="datatable-income-accessory">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Proovedor</th>.
                                            <th>Usuario</th>
                                            <th>Documento</th>
                                            <th>Numero</th>
                                            <th>Total De Compra</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Table -->

                </section>
                <!-- Permissions ends -->

                <div class="modal fade" id="incomeAccessoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                        <div class="modal-content">
                            <div class="modal-header bg-transparent">
                                <button type="reset" class="btn-close reset" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pb-5 px-sm-5 pt-50">
                                <div class="text-center mb-2">
                                    <h1 class="mb-1">Detalle de Ingreso de Accesorios</h1>
                                </div>

                                <form method="GET" enctype="multipart/form-data" id="details_accessory_form" class="row" onsubmit="return false">
                                    <div class="mb-1 col-md-6">
                                        <label class="form-label">Proveedor</label>
                                        <input type="text" id="name_client" class="form-control bg-light fw-bold" disabled />
                                    </div>

                                    <div class="mb-1 col-md-6">
                                        <label class="form-label">Fecha de Ingreso</label>
                                        <input type="text" id="proof_date" class="form-control bg-light fw-bold" disabled />
                                    </div>

                                    <div class="mb-1 col-md-6">
                                        <label class="form-label">Serie</label>
                                        <input type="text" id="proof_series" class="form-control bg-light fw-bold" disabled />
                                    </div>

                                    <div class="mb-1 col-md-6">
                                        <label class="form-label">Número de Correlativo</label>
                                        <input type="text" id="voucher_series" class="form-control bg-light fw-bold" disabled />
                                    </div>

                                    <div class="mb-1 col-md-6">
                                        <label class="form-label">Tipo de Comprobante</label>
                                        <input type="text" id="voucher_type" class="form-control bg-light fw-bold" disabled />
                                    </div>

                                    <div class="mb-1 col-md-6">
                                        <label class="form-label">Tipo de Pago</label>
                                        <input type="text" id="payment_type" class="form-control bg-light fw-bold" disabled />
                                    </div>

                                    <div class="mb-1 col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="bg-primary text-white fw-bold">Accesorio</th>
                                                    <th class="bg-primary text-white fw-bold">Cantidad</th>
                                                    <th class="bg-primary text-white fw-bold">P. Compra</th>
                                                    <th class="bg-primary text-white fw-bold text-end">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="incomeAccessoryDetails">
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mb-1 col-md-6">
                                    </div>
                                    <div class="mb-1 col-md-6">
                                        <label class="form-label">Importe Total</label>
                                        <input type="text" id="full_purchase" class="form-control bg-light fw-bold text-end" disabled />
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->