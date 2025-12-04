<body>
    <div class="login-card">
        <h5 class="text-center text-uppercase mb-4">Iniciar Sesión</h5>

        <form action="" method="POST" autocomplete="off">
            <!-- Usuario -->
            <div class="mb-3">
                <label for="login_usuario" class="form-label">Usuario</label>
                <label for="register_usuario" class="form-label asterisco-obligatorio">*</label>
                <input type="text" class="form-control" id="login_usuario" name="login_usuario"
                    pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
            </div>

            <!-- Clave -->
            <div class="mb-3">
                <label for="login_clave" class="form-label">Clave</label>
                <label for="register_usuario" class="form-label asterisco-obligatorio">*</label>
                <input type="password" class="form-control" id="login_clave" name="login_clave"
                    pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
            </div>

            <!-- Botón iniciar sesión-->
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-custom">Iniciar sesión</button>
            </div>

            <!-- Botón Registrarse-->
            <div class="d-grid mt-4">
                <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalRegister">Registrarse</button>
            </div>

            <?php
                if(isset($_POST['login_usuario']) && isset($_POST['login_clave'])){
                    $insLogin->iniciarSesionControlador();
                }
            ?>
        </form>

        <p class="footer-text">© 2025 ManageMoneyWebsite</p>
    </div>

    <!-- Modal de Registro -->
    <div class="modal fade" id="modalRegister" tabindex="-1" aria-labelledby="modalRegisterLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRegisterLabel">Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form class="FormularioAjax" action="<?php echo APP_URL?>app/ajax/FunctionAjax.php" method="POST" autocomplete="off">
                        <input type="hidden" name="modulo_usuario" value="registrar">
                        <!-- Usuario -->
                        <div class="mb-3">
                            <label for="register_usuario" class="form-label">Usuario</label>
                            <label for="register_usuario" class="form-label asterisco-obligatorio">*</label>
                            <input type="text" class="form-control" id="register_usuario" name="register_usuario"
                                pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                        </div>

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="register_clave" class="form-label">Nombre Completo</label>
                            <label for="register_usuario" class="form-label asterisco-obligatorio">*</label>
                            <input type="text" class="form-control" id="register_nombre" name="register_nombre"
                                pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="100" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="register_clave" class="form-label">Email</label>
                            <input type="email" class="form-control" id="register_email" name="register_email" maxlength="100">
                        </div>
                        
                        <!-- Clave -->
                        <div class="mb-3">
                            <label for="register_clave" class="form-label">Clave</label>
                            <label for="register_usuario" class="form-label asterisco-obligatorio">*</label>
                            <input type="password" class="form-control" id="register_clave1" name="register_clave1"
                                pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                        </div>

                        <!-- Clave confirmacion -->
                        <div class="mb-3">
                            <label for="register_clave" class="form-label">Confirmacion Clave</label>
                            <label for="register_usuario" class="form-label asterisco-obligatorio">*</label>
                            <input type="password" class="form-control" id="register_clave2" name="register_clave2"
                                pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                        </div>

                        <!-- Botón Registrase-->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-custom">Registrar</button>
                        </div>
                    </form>
                    <p class="footer-text">© 2025 ManageMoneyWebsite</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
