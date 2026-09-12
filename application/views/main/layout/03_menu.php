    <!-- BEGIN: Main Menu-->

    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto"><span class="brand-logo">
                             <img src="./img/elparaiso.png" alt="Login" style="width: 100%; height: 100%; object-fit: cover;" />
                    </a></li>
                </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" navigation-header"><span data-i18n="Modules">Módulos</span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                    <circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                </li>

                <?php 
                // --
                $html = '';
                // -- Params for selected view
                $selected_menu = '';
                $selected_sub_menu = '';
                // --
                foreach ($params['menu']['modules'] as $item) { 
                    // --
                    if (count($item['sub_menu']) > 0) {
                        // --
                    }  else {
                        // --
                    }
                    // --
                    $html .= '<li class=" nav-item"><a class="d-flex align-items-center"><i data-feather="' . $item["icon"] . '"></i><span class="menu-title text-truncate">' . $item["description"] . '</span></a>';
                    $html .= '<ul class="menu-content">';
                    // --
                    foreach ($item['sub_menu'] as $row) {
                        // --
                        if ($row['url'] == $params['menu']['view']) {
                            // --
                            $selected_menu = $item['description'];
                            $selected_sub_menu = $row['description'];
                            // --
                            $html .= '<li class="active"><a class="d-flex align-items-center" href="' . BASE_URL . $row["url"] . '"><i data-feather="' . $row["icon"] . '"></i><span class="menu-item text-truncate">' . $row["description"] . '</span></a>';
                        } else {
                            $html .= '<li class=""><a class="d-flex align-items-center" href="' . BASE_URL . $row["url"] . '"><i data-feather="' . $row["icon"] . '"></i><span class="menu-item text-truncate">' . $row["description"] . '</span></a>';
                        }
                    }
                    // --
                    $html .= '</ul>';
                    $html .= '</li>';
                }
                // --
                echo $html;
                ?>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->