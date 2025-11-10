<body>
    <div class="login-card">
        <h5 class="text-center text-uppercase mb-4">Iniciar Sesión</h5>

        <form action="" method="POST" autocomplete="off">
            <!-- Usuario -->
            <div class="mb-3">
                <label for="login_usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="login_usuario" name="login_usuario"
                    pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
            </div>

            <!-- Clave -->
            <div class="mb-3">
                <label for="login_clave" class="form-label">Clave</label>
                <input type="password" class="form-control" id="login_clave" name="login_clave"
                    pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
            </div>

            <!-- Botón -->
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-custom">Iniciar sesión</button>
            </div>

            <?php
                if(isset($_POST['login_usuario']) && isset($_POST['login_clave'])){
                    $insLogin->iniciarSesionControlador();
                }
            ?>
        </form>

        <p class="footer-text">© 2025 ManageMoneyWebsite</p>
    </div>

</body>
</html>
