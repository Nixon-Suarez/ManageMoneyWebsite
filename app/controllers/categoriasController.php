<?php 
    namespace app\controllers;
    use app\models\mainModel;
    use App\Models\CategoriaGasto;
    use App\Models\CategoriaIngreso;
    

    class categoriasController extends mainModel {   
        public function crearCategoriaControlador(){
            // Código para crear una categoría
        }
        public function listarCategoriaControlador($pagina, $registros, $url, $busqueda, $tipo_categoria){
            $pagina = $this->limpiarCadena($pagina);
            $registros = $this->limpiarCadena($registros);
            $busqueda = $this->limpiarCadena($busqueda);
            $tipo_categoria = $this->limpiarCadena($tipo_categoria);
            $url = $this->limpiarCadena($url);
            $url= APP_URL ."?view=". $url."/";
            $pagina = (isset($pagina) && $pagina>0) ? (int)$pagina : 1;
            $inicio = ($pagina>0) ? (($registros * $pagina) - $registros) : 0;
            $registros = ($registros > 0) ? (int)$registros : 10;
            $tipo_categoria = ($tipo_categoria != "") ? $tipo_categoria : "ingreso";
            if($tipo_categoria == "gasto"){
                $id = 'id_categoria_gasto';
                $nombre = 'nombre_categoria_gasto';
                $modelo = CategoriaGasto::class;
            }else if ($tipo_categoria == "ingreso"){
                $id = 'id_categoria_ingreso';
                $nombre = 'nombre_categoria_ingreso';
                $modelo = CategoriaIngreso::class;
            }else{
                return "<script>
                    Swal.fire({
                        title: 'Ocurrió un error inesperado',
                        text: 'Tipo de categoría no válido',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                </script>";
            }
            if(!empty($busqueda)){
                $consulta_datos = $modelo::where($nombre, 'LIKE', "%$busqueda%")
                    ->where('id_usuario', $_SESSION['id'])
                    ->orderBy($id, 'DESC')
                    ->skip($inicio)
                    ->take($registros)
                    ->get();
                $consulta_total = $modelo::where($nombre, 'LIKE', "%$busqueda%")
                    ->where('id_usuario', $_SESSION['id'])
                    ->count();
            }else{
                $consulta_datos = $modelo::orderBy($id, 'DESC')
                    ->where('id_usuario', $_SESSION['id'])
                    ->skip($inicio)
                    ->take($registros)
                    ->get();
                $consulta_total =  $modelo::where('id_usuario', $_SESSION['id'])->count();
            }
            $numeroPaginas = ceil($consulta_total / $registros);
            $tabla = ' 
                <br>
                <!-- Tabla -->
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover">
                        <thead class="custom-header text-center">
                            <tr>
                                <th>#</th>
                                <th>Nombre Categoria</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
            if($consulta_total && $pagina<=$numeroPaginas){
                $contador=$inicio+1;
                $pag_inicio=$inicio+1;
                foreach($consulta_datos as $rows){
                    $tabla.='
                        <tr class="text-center">
                            <td>'.$contador.'</td>
                            <td>'.$rows->nombre_categoria_ingreso.'</td>
                            <td>'.$rows->estado.'</td>
                            <td class="d-flex justify-content-center gap-2">
                                <!-- Editar -->
                                <a href="'.APP_URL.'?view=categoria_gasto_update&categoria_id_up='.$rows['id_categoria_gasto'].'" class="btn btn-success btn-sm">
                                    Editar
                                </a>
                                <!-- Eliminar -->
                                <form class="FormularioAjax" action="'.APP_URL.'app/ajax/FuntionAjax.php" method="POST">
                                    <input type="hidden" name="modulo_categoria" value="eliminar">
                                    <input type="hidden" name="usuario_id" value="'.$rows['id_usuario'].'">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    ';
                    $contador++;
                }
                $pag_final=$contador-1;
            }else{
                if($consulta_total){
                    $tabla.='
                        <tr class="text-center">
                            <td colspan="4">
                                <a href="'.$url.'1/"
                                class="btn btn-info text-white rounded-pill btn-sm mt-3 mb-3">
                                    Haga clic acá para recargar el listado
                                </a>
                            </td>
                        </tr>
                    ';
                }else{
                    $tabla.='
                        <tr class="text-center">
                            <td colspan="4">
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
            if($consulta_total && $pagina<=$numeroPaginas){
                $tabla.='<p class="text-secondary text-centermb-4">Mostrando usuarios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$consulta_total.'</strong></p>';
                $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
            }
            return $tabla;
            
        }
    }