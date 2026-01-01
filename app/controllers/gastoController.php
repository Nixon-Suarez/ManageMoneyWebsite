<?php   
    namespace app\controllers;
    use app\models\mainModel;
    use App\Models\Egreso;  
    use App\Models\Mes;  

    class gastoController extends mainModel {
        public function listarGastoControlador($pagina, $registros, $url, $busqueda, $mes){
            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
            $busqueda = $this->limpiarCadena($busqueda);
            $url = $this->limpiarCadena($url);
            $mes = $this->limpiarCadena($mes);
            $url = APP_URL . "?view=" . $url . "/";
            $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
            $inicio = ($pagina > 0) ? (($registros * $pagina) - $registros) : 0;
            $registros = ($registros > 0) ? (int)$registros : 10;
            // Consulta
            $query = Egreso::where('id_usuario', $_SESSION['id']);
            
            // Filtro por mes
            if ($mes !== 'all') {
                $query->where('id_mes', $mes);
            }

            // Filtro por búsqueda
            if (!empty($busqueda)) {
                $query->where('descripcion', 'LIKE', "%{$busqueda}%");
            }

            $consulta_total = (clone $query)->count();

            $consulta_datos = $query->orderBy('id_egreso', 'DESC')
                ->skip($inicio)
                ->take($registros)
                ->get();
            
            $numeroPaginas = ceil($consulta_total / $registros);
            $tabla = ' 
                <br>
                <!-- Tabla -->
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover">
                        <thead class="custom-header text-center">
                            <tr>
                                <th>#</th>
                                <th>Descripcion</th>
                                <th>Valor</th>
                                <th>Mes</th>
                                <th>Categoria</th> 
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
            if ($consulta_total && $pagina <= $numeroPaginas) {
                $contador = $inicio + 1;
                $pag_inicio = $inicio + 1;
                foreach ($consulta_datos as $rows) {
                    if ($rows->EgresoEstado == "activo") {
                        $btn_apagar_class = 'btn-danger btn-sm';
                    } else {
                        $btn_apagar_class = 'btn-primary btn-sm';
                    }
                    $tabla .= '
                            <tr class="text-center">
                                <td>' . $contador . '</td>
                                <td>' . $rows->descripcion ." ". $rows->egresoAdjunto .'</td>
                                <td>' . $rows->valor_egreso . '</td>
                                <td>' . $rows->id_mes . '</td>
                                <td>' . $rows->categoria_eg . '</td>
                                <td>' . strtoupper($rows->EgresoEstado) . '</td>
                                <td class="d-flex justify-content-center gap-2">
                                    <!-- Editar -->
                                    <button type="button" class="btn btn-success btn-sm btnOpenEditar" data-bs-toggle="modal" data-bs-target="#modalGasto" data-id="' . $rows->id_egreso . '" data-descripcion="' . $rows->descripcion . '" data-estado="' . $rows->EgresoEstado . '"data-valor="' . $rows->valor_egreso . '"data-mes="' . $rows->id_mes . '"data-categoria="' . $rows->categoria_eg . '">
                                        Editar
                                    </button>

                                    <!-- Eliminar -->
                                    <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/FunctionAjax.php" method="POST">
                                        <input type="hidden" name="modulo_gasto" value="eliminar_gasto">
                                        <input type="hidden" name="gasto_id" value="' . $rows->id_egreso . '"> 
                                        <button type="submit" class="btn ' . $btn_apagar_class . ' btn-sm">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        ';
                    $contador++;
                }
                $pag_final = $contador - 1;
            }else   {
                if ($consulta_total) {
                    $tabla .= '
                            <tr class="text-center">
                                <td colspan="4">
                                    <a href="' . $url . '1/"
                                    class="btn btn-info text-white rounded-pill btn-sm mt-3 mb-3">
                                        Haga clic acá para recargar el listado
                                    </a>
                                </td>
                            </tr>
                        ';
                } else {
                    $tabla .= '
                            <tr class="text-center">
                                <td colspan="7">
                                    No hay registros en el sistema
                                </td>
                            </tr>
                        ';
                }
            }
            $tabla .= '
                            </tbody>
                        </table>
                    </div>
                ';
            if ($consulta_total && $pagina <= $numeroPaginas) {
                $tabla .= '<p class="text-secondary text-center mb-3">Mostrando categoria <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $consulta_total . '</strong></p>';
                $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
            }
            return $tabla;
        }

        public function registrarGastoControlador(){
            
        }

        public function eliminarGastoControlador(){
        }

        public function actualizarGastoControlador(){
        }

        public function opcionesMesesGastoControlador(){
            $mes_ids = Egreso::where('EgresoEstado', 'activo')
                ->distinct()
                ->pluck('id_mes'); // Obtiene solo los id_mes
            $meses = Mes::whereIn('id_mes', $mes_ids)
                ->get();
            $opciones = '';
            foreach ($meses as $mes) {
                $opciones .= '<option value="' . $mes->id_mes . '">' . $mes->nombre_mes . '</option>';
            }
            return $opciones;
        }
    }