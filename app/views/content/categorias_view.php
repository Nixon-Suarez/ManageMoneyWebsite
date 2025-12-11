<div class="container mb-4 content">
    <!-- Títulos -->
    <h2 class="text-secondary">Categoria</h2>
    <h5 class="text-secondary mb-4">Lista de Categoria</h5>

    <!-- Formulario de búsqueda -->
    <div class="row">
        <div class="col-md-10 mx-auto">
            <form class="FormularioAjax row g-3" 
                  action="<?php echo APP_URL; ?>app/ajax/FunctionAjax.php" 
                  method="POST" autocomplete="off">

                <input type="hidden" name="modulo_buscador" value="buscar_categoria">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">

                <!-- Nombre -->
                <div class="col-md-5">
                    <label for="txt_buscador" class="form-label">Nombre Categoria</label>
                    <input id="txt_buscador" type="text" name="txt_buscador" 
                           class="form-control rounded-pill"
                           placeholder="¿Qué estás buscando?"
                           pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}"
                           maxlength="30">
                </div>

                <!-- Tipo -->
                <div class="col-md-5">
                    <label for="tipo_categoria" class="form-label">Tipo</label>
                    <?php 
                        $current_tipo = isset($_SESSION[$url[0]]['tipo_categoria']) ? $_SESSION[$url[0]]['tipo_categoria'] : 'ingreso';
                    ?>
                    <select id="tipo_categoria" name="tipo_categoria" class="form-control rounded-pill" required>
                        <option value="ingreso" <?php echo ($current_tipo === 'ingreso') ? 'selected' : ''; ?>>Ingreso</option>
                        <option value="gasto"   <?php echo ($current_tipo === 'gasto') ? 'selected' : ''; ?>>Gasto</option>
                    </select>
                </div>

                <!-- Botón -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary rounded-pill w-100">
                        Buscar
                    </button>
                </div>

                <!-- Botón Registrar Categoria-->
                <div class="d-grid mt-4">
                    <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalRegisterCategoria">Registrar Categoria</button>
                </div>

            </form>
        </div>
    </div>
    <div>
        <?php 
            use app\controllers\categoriasController;
            $insCategorias = new categoriasController();
            $pagina = isset($url[1]) ? $url[1] : 1;
            $filtro = isset($_SESSION[$url[0]]['texto']) ? $_SESSION[$url[0]]['texto'] : "";
            echo $insCategorias->listarCategoriaControlador($pagina, 10, $url[0], $filtro, $current_tipo);
        ?>
    </div>
    <!-- Modal crear categoria -->
    <div class="modal fade" id="modalRegisterCategoria" tabindex="-1" aria-labelledby="modalRegisterLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRegisterLabel">Registro Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form class="FormularioAjax" action="<?php echo APP_URL?>app/ajax/FunctionAjax.php" method="POST" autocomplete="off">
                        <input type="hidden" name="modulo_usuario" value="registrar_categoria">
                        <!-- Nombre Categoria -->
                        <div class="mb-3">
                            <label for="register_nombre_categoria" class="form-label">Nombre Categoria</label>
                            <label for="register_nombre_categoria" class="form-label asterisco-obligatorio">*</label>
                            <input type="text" class="form-control" id="register_nombre_categoria" name="register_nombre_categoria"
                                pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                        </div>

                        <!-- Tipo -->
                        <div class="mb-3">
                            <label for="register_tipo_categoria" class="form-label">Tipo</label>
                            <label for="register_tipo_categoria" class="form-label asterisco-obligatorio">*</label>
                            <?php 
                                $current_tipo = isset($_SESSION[$url[0]]['tipo_categoria']) ? $_SESSION[$url[0]]['tipo_categoria'] : 'ingreso';
                            ?>
                            <select id="register_tipo_categoria" name="register_tipo_categoria" class="form-control" required>
                                <option value="ingreso" <?php echo ($current_tipo === 'ingreso') ? 'selected' : ''; ?>>Ingreso</option>
                                <option value="gasto"   <?php echo ($current_tipo === 'gasto') ? 'selected' : ''; ?>>Gasto</option>
                            </select>
                        </div>
                        
                        <!-- Estado -->
                        <div class="mb-3">
                            <label for="register_estado_categoria" class="form-label">Estado</label>
                            <label for="register_estado_categoria" class="form-label asterisco-obligatorio">*</label>
                            <select id="register_estado_categoria" name="register_estado_categoria" class="form-control" required>
                                <option value="activo" selected>Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>

                        <!-- Botón Registrase-->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-custom">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>