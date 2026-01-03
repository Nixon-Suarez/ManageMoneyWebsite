<?php

namespace app\controllers;

use app\models\mainModel;
use App\Models\Egreso;
use App\Models\Mes;
use App\Models\CategoriaGasto;

class gastoController extends mainModel
{
    public function listarGastoControlador($pagina, $registros, $url, $busqueda, $mes)
    {
        $pagina = $this->limpiarCadena($pagina);
        $registros = $this->limpiarCadena($registros);
        $busqueda = isset($busqueda) ? $this->limpiarCadena($busqueda) : "";
        $url = $this->limpiarCadena($url);
        $mes = isset($mes) ? $this->limpiarCadena($mes) : "";
        $url = APP_URL . "?view=" . $url . "/";
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        $inicio = ($pagina > 0) ? (($registros * $pagina) - $registros) : 0;
        $registros = ($registros > 0) ? (int)$registros : 10;
        // Consulta
        $query = Egreso::where('id_usuario', $_SESSION['id']);

        // Filtro por mes
        if ($mes !== '' && !empty($mes)) {
            $query->where('id_mes', $mes);
        }

        // Filtro por búsqueda
        if (!empty($busqueda) && $busqueda != "") {
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
                                <td>' . $rows->descripcion . " " . $rows->egresoAdjunto . '</td>
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
        } else {
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

    public function registrarGastoControlador()
    {
        #Almacenar Datos
        $descripcion = isset($_POST['descripcion_gastos']) ? $this->limpiarCadena($_POST['descripcion_gastos']) : "";
        $valor = isset($_POST['valor_gastos']) ? $this->limpiarCadena($_POST['valor_gastos']) : "";
        $mes = isset($_POST['mes_gastos']) ? $this->limpiarCadena($_POST['mes_gastos']) : "";
        $anio = isset($_POST['anio_gastos']) ? $this->limpiarCadena($_POST['anio_gastos']) : "";
        $estado = isset($_POST['estado_gastos']) ? $this->limpiarCadena($_POST['estado_gastos']) : "";
        $categoria = isset($_POST['categoria_gastos']) ? $this->limpiarCadena($_POST['categoria_gastos']) : "";

        // verificar campos obligatorios
        if ($descripcion == "" || $valor == "" || $mes == "" || $anio == "" || $estado == "" || $categoria == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        #Verificando integridad de los datos
        if (!is_numeric($valor) || $valor <= 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El valor del gasto debe ser un número válido mayor a 0",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $anioActual = date('Y');
        if ($anio < 2000 || $anio > ($anioActual + 1)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El año debe estar entre 2000 y " . ($anioActual + 1),
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        if ($mes < 1 || $mes > 12 || $this->verificarDatos("[0-9]{1,2}", $mes)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El mes del gasto no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        if ($estado != "activo" && $estado != "inactivo") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El estado del gasto no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $check_categoria = CategoriaGasto::where('id_categoria_gasto', $categoria)
            ->where('id_usuario', $_SESSION['id'])
            ->first();

        if (!$check_categoria) {
            return json_encode([
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "La categoría seleccionada no existe",
                "icono" => "error"
            ]);
        }
        $categoriaId = $check_categoria->id_categoria_gasto;

        // $check_descripcion = Egreso::where("descripcion", $descripcion)
        //     ->where("id_usuario", $_SESSION['id'])
        //     ->first();
        // if ($check_descripcion && !isset($_POST['confirmar'])) {
        //     $alerta = [
        //         "tipo" => "confirmar",
        //         "titulo" => "Gasto duplicado",
        //         "texto" => "Ya existe un gasto con esta descripción. ¿Deseas registrarlo de todas formas?",
        //         "icono" => "warning"
        //     ];
        //     return json_encode($alerta);
        // }
        // Directorio de imagenes
        $img_dir = "../views/img/loads/";
        $document_name = $_FILES['gasto_documento']['name'];
        $archivo_gasto = null;
        if ($document_name != "" && $_FILES['gasto_documento']['size'] > 0) {
            //  creando directorio si no existe
            if (!file_exists($img_dir)) {
                if (!mkdir($img_dir, 0777)) {
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "No se pudo crear el directorio",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                }
            }
            // limitar que tipo de archivo esta entrando (se valida con el tipo de mime)
            $mimePermitidos = [
                'image/jpeg',
                'image/png',
                'application/pdf',
                'application/msword', // .doc
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // .docx
            ];

            $mimeArchivo = mime_content_type($_FILES['gasto_documento']['tmp_name']);

            if (!in_array($mimeArchivo, $mimePermitidos)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Archivo no permitido, solo se permiten archivos .jpg, .jpeg, .png, .pdf, .doc, .docx",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            # limitar el peso del archivo
            if (($_FILES['gasto_documento']['size'] / 1024) > 10000) { // 10MB
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El archivo no puede ser mayor a 10MB",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            #Extencion del archivo
            switch ($mimeArchivo) {
                case 'image/jpeg':
                    $extension = '.jpg';
                    break;
                case 'image/png':
                    $extension = '.png';
                    break;
                case 'image/jpg':
                    $extension = '.jpg';
                    break;
                case 'application/pdf':
                    $extension = '.pdf';
                    break;
                case 'application/msword':
                    $extension = '.doc';
                    break;
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    $extension = '.docx';
                    break;
                default:
                    $extension = '.jpg';
            }

            chmod($img_dir, 0777);

            // renombra la archivo_gasto
            $nombreLimpio = str_ireplace(" ", "_", pathinfo($document_name, PATHINFO_FILENAME));
            $archivo_gasto = $nombreLimpio . "_" . rand(1000, 9999) . "_" . time() . $extension;

            // mover la img al directorio de imagenes
            if (!move_uploaded_file($_FILES['gasto_documento']['tmp_name'], $img_dir . $archivo_gasto)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "Error al subir el archivo, intente nuevamente",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        }
        // Preparando datos para el registro
        $datos_gasto_reg = [
            "valor_egreso" => $valor,
            "descripcion" => $descripcion,
            "fecha_actualizacion" => date("Y-m-d"),
            "id_mes" => $mes,
            "id_usuario" => $_SESSION['id'],
            "categoria_eg" => $categoriaId,
            "egresoAdjunto" => $archivo_gasto,
            "EgresoEstado" => $estado,
            "año" => $anio
        ];
        try{
            $nuevo_gasto = Egreso::create($datos_gasto_reg);
            if ($nuevo_gasto) {
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Gasto registrado",
                    "texto" => "El gasto " . $descripcion . " ha sido registrado exitosamente",
                    "icono" => "success"
                ];
            } else {
                if(is_file($img_dir.$archivo_gasto)){ #valida si la img existe en el directorio
                        chmod($img_dir.$archivo_gasto, 0777);
                        unlink($img_dir.$archivo_gasto); #si existe la elimina
                    }
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo registrar el gasto, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
        } catch (\Exception $e) {
            if (is_file($img_dir . $archivo_gasto)) {
                chmod($img_dir . $archivo_gasto, 0777);
                unlink($img_dir . $archivo_gasto);
            }
            
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error de base de datos",
                "texto" => "Error: " . $e->getMessage(),
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }

    public function CambioEstadoGastoControlador() {}

    public function actualizarGastoControlador() {}

    public function opcionesMesesGastoControlador()
    {
        $mes_ids = Egreso::where('EgresoEstado', 'activo')
            ->distinct()
            ->pluck('id_mes'); // Obtiene solo los id_mes
        $meses = Mes::whereIn('id_mes', $mes_ids)
            ->get();
        return $meses;
    }
}