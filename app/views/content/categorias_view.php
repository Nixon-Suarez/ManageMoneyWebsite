<div class="container mb-4">
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
                    <label class="form-label">Nombre Categoria</label>
                    <input type="text" name="txt_buscador" 
                           class="form-control rounded-pill"
                           placeholder="¿Qué estás buscando?"
                           pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}"
                           maxlength="30">
                </div>

                <!-- Tipo -->
                <div class="col-md-5">
                    <label class="form-label">Tipo</label>
                    <?php 
                        $current_tipo = isset($_SESSION[$url[0]]['tipo_categoria']) ? $_SESSION[$url[0]]['tipo_categoria'] : 'ingreso';
                    ?>
                    <select name="tipo_categoria" class="form-control rounded-pill" required>
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
</div>