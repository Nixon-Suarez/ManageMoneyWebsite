<div class="d-flex">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar-custom">
        <div class="p-3">
            <!-- Perfil usuario -->
            <div class="d-flex align-items-center mb-4 user-box sidebar-item">
                <img src="<?php echo APP_URL; ?>app/views/img/Money.png" class="img-profile me-2">
                <div class="sidebar-text">
                    <p class="mb-0 fw-bold text-white"><?php echo $_SESSION['nombre'] ?></p>
                    <small class="text-secondary"><?php echo $_SESSION['usuario'] ?></small>
                </div>
            </div>
            <!-- Menú -->
            <ul class="nav flex-column sidebar-menu">
                <li class="sidebar-header text-uppercase small sidebar-item">
                    <i class="fa fa-circle text-warning"></i>
                    <span class="sidebar-text">Menú principal</span>
                </li>
                <li class="nav-item sidebar-item">
                    <a class="nav-link text-white" href="#">
                        <i class="fa fa-home me-2"></i>
                        <span class="sidebar-text">Inicio</span>
                    </a>
                </li>
                <!-- <li class="nav-item sidebar-item">
                    <a class="nav-link text-white" href="#">
                        <i class="fa fa-file-text me-2"></i>
                        <span class="sidebar-text">Soluciones financieras</span>
                    </a>
                    <ul class="submenu list-unstyled">
                        <li><a href="#" class="nav-link text-white ps-5 py-2">Facturas</a></li>
                        <li><a href="#" class="nav-link text-white ps-5 py-2">Proveedores</a></li>
                        <li><a href="#" class="nav-link text-white ps-5 py-2">Informes</a></li>
                    </ul>
                </li> -->
            </ul>
        </div>
    </aside>
</div>