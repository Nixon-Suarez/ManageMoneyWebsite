<?php 
    namespace app\controllers;

    use app\models\mainModel;
    use Illuminate\Database\Capsule\Manager as DB;

    class dashboardController extends mainModel {   
        public function getDataDashboard() {
            // Egresos
            $check_egreso = DB::table("egreso")
                ->selectRaw('id_mes AS mes, SUM(valor_egreso) AS total')
                ->where('id_usuario', $_SESSION['id'])
                ->groupBy('id_mes')
                ->orderBy('id_mes')
                ->get();
            if($check_egreso->isNotEmpty()){
                $expenseData = array_fill(1, 12, 0);
                foreach ($check_egreso as $row) {
                    $expenseData[(int)$row->mes] = (float)$row->total;
                }
                $expenseData = array_values($expenseData);
            }else{
                $expenseData  = null;
            }
            // Ingresos
            $check_ingreso = DB::table("ingreso")
                ->selectRaw('id_mes AS mes, SUM(valor_ingreso) AS total')
                ->where('id_usuario', $_SESSION['id'])
                ->groupBy('id_mes')
                ->orderBy('id_mes')
                ->get();
            if($check_ingreso->isNotEmpty()){
                $incomeData = array_fill(1, 12, 0);
                foreach ($check_ingreso as $row) {
                    $incomeData[(int)$row->mes] = (float)$row->total;
                }
                $incomeData = array_values($incomeData);
            }else{
                $incomeData = null;
            }
            // $incomeData = [1200, 1500, 1800, 2000, 2200, 2450]; // Ejemplo de datos de ingresos mensuales
            // $expenseData = [800, 900, 1000, 1100, 1200, 1275]; // Ejemplo de datos de gastos mensuales
            if($check_ingreso->isNotEmpty() || $check_egreso->isNotEmpty()){
                $dashboard ='<!-- dashboard -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="fw-bold mb-0">Tendencia Gastos</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="height: 350px;">
                                        <canvas 
                                            id="financeChart"
                                            data-income='.json_encode($incomeData).'
                                            data-expense='.json_encode($expenseData).'>
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
            }else if ($check_ingreso->isEmpty() && $check_egreso->isEmpty()){
                $dashboard = 
                '<div id="chartMessage" class="alert alert-danger" role="alert">
                    No hay información financiera disponible para mostrar 📊
                </div>';
            }
            return $dashboard;
        }
        public function getTotalIncome() {
            $totalIncome = DB::table("ingreso")
                ->where('id_usuario', $_SESSION['id'])
                ->sum('valor_ingreso');
            return $totalIncome;
        }

        public function getTotalExpense() {
            $totalExpense = DB::table("egreso")
                ->where('id_usuario', $_SESSION['id'])
                ->sum('valor_egreso');
            return $totalExpense;
        }
        public function getBalance() {
            $totalIncome = $this->getTotalIncome();
            $totalExpense = $this->getTotalExpense();
            $balance = $totalIncome - $totalExpense;
            return $balance;
        }
    }
?>
<script id="app-script" src="<?php echo APP_URL;?>app/views/js/grafico.js"></script>
