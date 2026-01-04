<div class="container mb-4 content">
    <!-- Títulos -->
    <h2 class="text-secondary">Gastos</h2>
    <h5 class="text-secondary mb-4">Lista de Gastos</h5>
    <div class="container content">
        <?php
            use app\controllers\dashboardController;
            $insdashboard = new dashboardController();
            echo $insdashboard->getDataDashboard(); 
        ?>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="row mt-4">
        <div class="col-md-10 mx-auto">
            <form class="FormularioAjax row g-3" 
                  action="<?php echo APP_URL; ?>app/ajax/FunctionAjax.php" 
                  method="POST" autocomplete="off">

                <input type="hidden" name="modulo_buscador" value="buscar_gasto">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">

                <?php 
                        $current_mes = isset($_SESSION[$url[0]]['mes']) ? $_SESSION[$url[0]]['mes'] : '';
                        $current_text = isset($_SESSION[$url[0]]['texto']) ? $_SESSION[$url[0]]['texto'] : '';
                        $selected = $current_mes == '' ? 'selected disabled' : '';
                ?>
                <!-- descripcion -->
                <div class="col-md-5">
                    <label for="txt_buscador" class="form-label">Descripcion</label>
                    <input id="txt_buscador" type="text" name="txt_buscador" 
                           class="form-control rounded-pill"
                           placeholder="¿Qué estás buscando?"
                           pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}"
                           maxlength="30"
                           value="<?php echo $current_text; ?>">
                </div>

                <!-- mes -->
                <div class="col-md-5">
                    <label for="mes" class="form-label">Mes</label>
                    <select id="mes" name="mes" class="form-control rounded-pill">
                        
                        <?php
                            use app\controllers\gastoController;
                            $insGasto = new gastoController();
                            echo '<option value="" '.$selected.'>Todos los meses</option>';
                            $meses = $insGasto->opcionesMesesGastoControlador();
                            foreach ($meses as $mes) {
                                $isSelected = ($mes->id_mes == $current_mes) ? 'selected' : '';
                                echo '<option value="' . $mes->id_mes . '"'.$isSelected.'>' . $mes->nombre_mes . '</option>';
                            }
                        ?>
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
                        data-bs-target="#modalGastos"
                        id="btnOpenRegistrarGastos">
                    Registrar Gasto
                </button>
            </form>
        </div>
    </div>
    <div>
        <?php 
            $pagina = isset($url[1]) ? $url[1] : 1;
            echo $insGasto->listarGastoControlador($pagina, 10, $url[0], $current_text, $current_mes);
        ?>
    </div>
    <?php
        include "gastosNew_view.php";
    ?>
</div>