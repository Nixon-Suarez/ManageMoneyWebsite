<?php 
    namespace app\controllers;
    use app\models\mainModel;
    use Illuminate\Database\Capsule\Manager as DB;

    class dashboardController extends mainModel {   
        public function getDataDashboard() {
            $check_egreso = DB::table("egreso")
                ->selectRaw('id_mes AS mes, SUM(valor_egreso) AS total')
                ->where('id_usuario', $_SESSION['id'])
                ->groupBy('id_mes')
                ->orderBy('id_mes')
                ->get();
            $expenseData = array_fill(1, 12, 0);
            foreach ($check_egreso as $row) {
                $expenseData[(int)$row->mes] = (float)$row->total;
            }

            $check_ingreso = DB::table("ingreso")
                ->selectRaw('id_mes AS mes, SUM(valor_ingreso) AS total')
                ->where('id_usuario', $_SESSION['id'])
                ->groupBy('id_mes')
                ->orderBy('id_mes')
                ->get();
            $incomeData = array_fill(1, 12, 0);
            foreach ($check_ingreso as $row) {
                $incomeData[(int)$row->mes] = (float)$row->total;
            }
            // $incomeData = [1200, 1500, 1800, 2000, 2200, 2450]; // Ejemplo de datos de ingresos mensuales
            // $expenseData = [800, 900, 1000, 1100, 1200, 1275]; // Ejemplo de datos de gastos mensuales
            return ['ingresos' => array_values($incomeData), 'gastos' => array_values($expenseData)];
        }
    }
?>
<script id="app-script" src="<?php echo APP_URL;?>app/views/js/grafico.js"></script>
