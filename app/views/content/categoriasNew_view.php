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