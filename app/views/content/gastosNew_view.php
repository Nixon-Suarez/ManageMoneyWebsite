<!-- Modal categoria -->
<div class="modal fade" id="modalGastos" tabindex="-1" aria-labelledby="modalGastosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGastosLabel">Gastos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="FormularioAjax" action="<?php echo APP_URL?>app/ajax/FunctionAjax.php" method="POST" autocomplete="off">
                    <input type="hidden" name="modulo_gastos" id="modulo_gastos" value="registrar_gastos">
                    <!-- ID para edición -->
                    <input type="hidden" name="gastos_id" id="gastos_id">
                    <!-- Descripcion -->
                    <div class="mb-3">
                        <label for="descripcion_gastos" class="form-label">Descripcion Gasto</label>
                        <label for="descripcion_gastos" class="form-label asterisco-obligatorio">*</label>
                        <textarea type="text" class="form-control"
                            id="descripcion_gastos" name="descripcion_gastos" required></textarea>
                    </div>
                    <!-- valor -->
                    <div class="mb-3">
                        <label for="valor_gastos" class="form-label">Descripcion Gasto</label>
                        <label for="valor_gastos" class="form-label asterisco-obligatorio">*</label>
                        <input type="number" class="form-control"
                            id="valor_gastos" name="valor_gastos" required>
                        </input>
                    </div>
                    <!-- mes -->
                    <div class="mb-3">
                        <label for="mes_gastos" class="form-label">Mes</label>
                        <label for="mes_gastos" class="form-label asterisco-obligatorio">*</label>
                        <select id="mes_gastos" name="mes_gastos" class="form-control" required>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    <!-- año -->
                    <div class="mb-3">
                        <label for="anio_gastos" class="form-label">Año</label>
                        <label for="anio_gastos" class="form-label asterisco-obligatorio">*</label>
                        <input type="number" class="form-control"
                            id="anio_gastos" name="anio_gastos" required>
                        </input>
                    <!-- Estado -->
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select id="estado_categoria" name="estado_categoria" class="form-control" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    <!-- categoria -->
                    <div class="mb-3">
                        <label for="categoria_gastos" class="form-label">Categoria Gasto</label>
                        <label for="categoria_gastos" class="form-label asterisco-obligatorio">*</label>
                        <select id="categoria_gastos" name="categoria_gastos" class="form-control" required>
                            <option value="seleccionar" selected disabled>seleccionar</option>
                            <?php
                                use app\controllers\categoriasController;
                                $insCategorias = new categoriasController();
                                $datos = $insCategorias->getCategorias('gasto');
                                foreach($datos as $categoria) {
                                    echo '<option value="'.$categoria['id_categoria_gasto'].'">'.$categoria['nombre_categoria_gasto'].'</option>';
                                }
                            ?>
                        </select>

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