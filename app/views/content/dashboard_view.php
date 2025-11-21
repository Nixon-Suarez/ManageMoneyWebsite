<div class="container-fluid py-5 text-center">
    <?php
        use app\controllers\dashboardController;
        $insdashboard = new dashboardController ();
        $insdashboard = $insdashboard->getDataDashboard();
    ?>
        <br><br>
    <!-- Imagen centrada -->
    <div class="d-flex justify-content-center mb-3">
        <figure class="m-0">
            <img src="<?php echo APP_URL; ?>app/views/img/Money.png" alt="Money"
                 class="rounded-circle img-fluid" style="width: 128px; height: 128px; object-fit: cover;">
        </figure>
    </div>
    
    <!-- Bienvenida -->
    <div class="d-flex justify-content-center">
        <h2 class="h5 text-secondary">¡Bienvenido <span class="text-primary"><?php echo $_SESSION['nombre'] ?></span>!</h2>
    </div>
    <!-- Resumen Financiero -->
    <div class="container my-4">

    <!-- Resumen Financiero -->
    <div class="row g-4">

        <!-- Total Ingresos -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">
                        <i class="fas fa-arrow-circle-down text-success me-2"></i>
                        Total Ingresos
                    </h5>
                    <p class="fs-3 fw-semibold text-success mb-0">$XXXXXX</p>
                </div>
            </div>
        </div>

        <!-- Total Gastos -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">
                        <i class="fas fa-arrow-circle-up text-danger me-2"></i>
                        Total Gastos
                    </h5>
                    <p class="fs-3 fw-semibold text-danger mb-0">$XXXXXX</p>
                </div>
            </div>
        </div>

        <!-- Balance -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">
                        <i class="fas fa-balance-scale text-primary me-2"></i>
                        Balance
                    </h5>
                    <p class="fs-3 fw-semibold text-primary mb-0">$XXXXXX</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Tendencia Mensual -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">Tendencia Mensual</h5>
                </div>

                <div class="card-body">
                    <div class="chart-container" style="height: 350px;">
                        <canvas 
                            id="financeChart"
                            data-income='<?php echo json_encode($insdashboard["ingresos"]); ?>'
                            data-expense='<?php echo json_encode($insdashboard["gastos"]); ?>'>
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script id="app-script" src="<?php echo APP_URL;?>app/views/js/grafico.js"></script>
</div>