<div class="container py-4 content">
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
                    <p class="fs-3 fw-semibold text-success mb-0">
                        <?php
                            use app\controllers\dashboardController;
                            $insdashboard = new dashboardController();
                            $getTotalIncome = $insdashboard->getTotalIncome();
                            echo '$' . number_format($getTotalIncome, 2);
                        ?>
                    </p>
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
                    <p class="fs-3 fw-semibold text-danger mb-0">
                        <?php
                            $getTotalExpense = $insdashboard->getTotalExpense();
                            echo '$' . number_format($getTotalExpense, 2);
                        ?>
                    </p>
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
                    <p class="fs-3 fw-semibold text-primary mb-0">
                        <?php
                            $getBalance = $insdashboard->getBalance();
                            echo '$' . number_format($getBalance, 2);
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
            echo $insdashboard->getDataDashboard();
        ?>
    </div>

</div>