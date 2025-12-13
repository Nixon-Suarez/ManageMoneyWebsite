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

                <button type="button" 
                        class="btn btn-custom" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalCategoria"
                        id="btnOpenRegistrar">
                    Registrar Categoría
                </button>
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
    <!-- Modal categoria -->
    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoriaLabel">Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form class="FormularioAjax" action="<?php echo APP_URL?>app/ajax/FunctionAjax.php" method="POST" autocomplete="off">

                        <input type="hidden" name="modulo_categoria" id="modulo_categoria" value="registrar_categoria">

                        <!-- ID para edición -->
                        <input type="hidden" name="categoria_id" id="categoria_id">

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre_categoria" class="form-label">Nombre Categoria</label>
                            <label for="nombre_categoria" class="form-label asterisco-obligatorio">*</label>
                            <input type="text" class="form-control"
                                id="nombre_categoria" name="nombre_categoria"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_categoria" class="form-label">Tipo</label>
                            <label for="tipo_categoria" class="form-label asterisco-obligatorio">*</label>
                            <select id="tipo_categoria" name="tipo_categoria" class="form-control" required>
                                <option value="ingreso" <?php echo ($current_tipo === 'ingreso') ? 'selected' : ''; ?>>Ingreso</option>
                                <option value="gasto"   <?php echo ($current_tipo === 'gasto') ? 'selected' : ''; ?>>Gasto</option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select id="estado_categoria" name="estado_categoria" class="form-control" required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <!-- Boton (Registrar/Actualizar) -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-custom" id="btnSubmitCategoria">
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>