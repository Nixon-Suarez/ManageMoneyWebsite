<nav class="navbar navbar-expand-lg navbar-custom shadow-sm fixed-top">
    <div class="container-fluid">
        <!-- Marca -->
        <a class="navbar-brand" href="<?php echo APP_URL; ?>?view=dashboard">
            <img src="<?php echo APP_URL; ?>app/views/img/Money.png" alt="Logo" width="112" height="28">
        </a>

        <!-- Botón colapsable (móvil) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
            aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Enlaces -->
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo APP_URL; ?>?view=dashboard">Dashboard</a>
                </li>

                <!-- Usuarios -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="usuariosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Usuarios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="usuariosDropdown">
                        <li><a class="dropdown-item" href="<?php // echo APP_URL; ?>?view=userNew/">Nuevo</a></li>
                        <li><a class="dropdown-item" href="<?php // echo APP_URL; ?>?view=userList">Lista</a></li>
                        <li><a class="dropdown-item" href="<?php // echo APP_URL; ?>?view=userSearch">Buscar</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Usuario -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="usuarioDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php // echo $_SESSION['usuario']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuarioDropdown">
                        <li><a class="dropdown-item" href="<?php // echo APP_URL . "?view=userUpdate/" . $_SESSION['id'] . "/"; ?>">Mi cuenta</a></li>
                        <li><a class="dropdown-item" href="<?php // echo APP_URL . "?view=userPhoto/" . $_SESSION['id'] . "/"; ?>">Mi foto</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo APP_URL; ?>?view=logout/" id="btn_exit">Salir</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>