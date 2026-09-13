<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Companies Starts -->
                <section id="companies">
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
                                <table class="table" id="datatable-companies">
                                    <thead>
                                        <tr>
                                            <th>Empresa</th>
                                            <th>RUC</th>
                                            <th>Razón Social</th>
                                            <th>Contacto</th>
                                            <th>Tarifa Corporativa</th>
                                            <th>Límite de Crédito</th>
                                            <th>Huéspedes</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Table -->

                    <!-- Create Company Modal -->
                    <div class="modal fade" id="create_company_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="reset" class="btn-close reset" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-sm-5 pb-5">
                                    <div class="text-center mb-2">
                                        <h1 class="mb-1">Agregar nueva empresa</h1>
                                    </div>
                                    <form method="POST" enctype="multipart/form-data" id="create_company_form" class="row" onsubmit="return false">
                                        <div class="col-12">
                                            <label class="form-label">Nombre de la empresa <span class="text-danger">*</span></label>
                                            <input type="text" name="company_name" class="form-control" placeholder="Nombre comercial" autofocus data-msg="Ingrese el nombre." required />
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">RUC <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" maxlength="11" name="ruc" class="form-control" placeholder="Nº de RUC" data-msg="Ingrese el RUC." required />
                                                <button type="button" class="btn btn-primary btn_get_company_data">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Razón social</label>
                                            <input type="text" name="business_name" class="form-control" placeholder="Razón social" data-msg="" />
                                        </div>
                                        <div class="col-12">
                                            <h6 class="mt-1 mb-0">Contacto</h6>
                                        </div>
                                        <div class="col-12">
                                            <div>
                                                <label class="form-label">Nombre de contacto</label>
                                                <input type="text" name="contact_name" class="form-control" placeholder="Persona de contacto" data-msg="" />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div>
                                                <label class="form-label">Teléfono de contacto</label>
                                                <input type="text" name="contact_phone" class="form-control" placeholder="Teléfono" data-msg="" />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div>
                                                <label class="form-label">Email de contacto</label>
                                                <input type="email" name="contact_email" class="form-control" placeholder="user@example.com" data-msg="" />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Condiciones comerciales</label>
                                            <textarea name="commercial_conditions" class="form-control" cols="2" rows="2" style="max-height: 90px;" placeholder="Plazos de pago, descuentos, condiciones especiales..."></textarea>
                                        </div>
                                        <div class="col-6">
                                            <div>
                                                <label class="form-label">Tarifa corporativa <span class="text-danger">*</span></label>
                                                <input type="text" name="corporate_tariff" class="form-control" placeholder="0.00" data-msg="Ingrese la tarifa." required />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div>
                                                <label class="form-label">Límite de crédito <span class="text-danger">*</span></label>
                                                <input type="text" name="credit_limit" class="form-control" placeholder="0.00" data-msg="Ingrese el límite." required />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Huéspedes asociados</label>
                                            <select name="guests[]" id="create_guests" class="form-control form-select select2" multiple placeholder="Seleccione huéspedes"></select>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button id="btn_create_company" type="submit" class="btn btn-primary mt-2 me-1">Guardar</button>
                                            <button type="reset" class="btn btn-outline-secondary mt-2 reset" data-bs-dismiss="modal" aria-label="Close">
                                                <span>Cancelar</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Create Company Modal -->

                    <!-- Update Company Modal -->
                    <div class="modal fade" id="update_company_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="reset" class="btn-close reset" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-sm-5 pb-5">
                                    <div class="text-center mb-2">
                                        <h1 class="mb-1">Actualizar Empresa</h1>
                                    </div>
                                    <form method="POST" enctype="multipart/form-data" id="update_company_form" class="row" onsubmit="return false">
                                        <div class="col-12">
                                            <label class="form-label">Nombre de la empresa <span class="text-danger">*</span></label>
                                            <input type="text" name="company_name" class="form-control" placeholder="Nombre comercial" autofocus data-msg="Ingrese el nombre." required />
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">RUC <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" maxlength="11" name="ruc" class="form-control" placeholder="Nº de RUC" data-msg="Ingrese el RUC." required />
                                                <button type="button" class="btn btn-primary btn_get_company_data_edit">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Razón social</label>
                                            <input type="text" name="business_name" class="form-control" placeholder="Razón social" data-msg="" />
                                        </div>
                                        <div class="col-12">
                                            <h6 class="mt-1 mb-0">Contacto</h6>
                                        </div>
                                        <div class="col-12">
                                            <div>
                                                <label class="form-label">Nombre de contacto</label>
                                                <input type="text" name="contact_name" class="form-control" placeholder="Persona de contacto" data-msg="" />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div>
                                                <label class="form-label">Teléfono de contacto</label>
                                                <input type="text" name="contact_phone" class="form-control" placeholder="Teléfono" data-msg="" />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div>
                                                <label class="form-label">Email de contacto</label>
                                                <input type="email" name="contact_email" class="form-control" placeholder="user@example.com" data-msg="" />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Condiciones comerciales</label>
                                            <textarea name="commercial_conditions" class="form-control" cols="2" rows="2" style="max-height: 90px;" placeholder="Plazos de pago, descuentos, condiciones especiales..."></textarea>
                                        </div>
                                        <div class="col-6">
                                            <div>
                                                <label class="form-label">Tarifa corporativa <span class="text-danger">*</span></label>
                                                <input type="text" name="corporate_tariff" class="form-control" placeholder="0.00" data-msg="Ingrese la tarifa." required />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div>
                                                <label class="form-label">Límite de crédito <span class="text-danger">*</span></label>
                                                <input type="text" name="credit_limit" class="form-control" placeholder="0.00" data-msg="Ingrese el límite." required />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Huéspedes asociados</label>
                                            <select name="guests[]" id="update_guests" class="form-control form-select select2" multiple placeholder="Seleccione huéspedes"></select>
                                        </div>

                                        <input type="hidden" name="id_company">

                                        <div class="col-12 text-center">
                                            <button id="btn_update_company" type="submit" class="btn btn-primary mt-2 me-1">Guardar</button>
                                            <button type="reset" class="btn btn-outline-secondary mt-2 reset" data-bs-dismiss="modal" aria-label="Close">
                                                <span>Cancelar</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Update Company Modal -->

                </section>
                <!-- Companies ends -->
            </div>
        </div>
    </div>
    <!-- END: Content-->