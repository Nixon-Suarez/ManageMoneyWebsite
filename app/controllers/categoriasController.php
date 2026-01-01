<?php

namespace app\controllers;

use app\models\mainModel;
use App\Models\CategoriaGasto;
use App\Models\CategoriaIngreso;


class categoriasController extends mainModel
{
    public function crearCategoriaControlador()
    {
        #Almacenar Datos
        $nombre_categoria = $this->limpiarCadena($_POST['nombre_categoria']);
        $tipo_categoria = $this->limpiarCadena($_POST['tipo_categoria']);
        $estado_categoria = $this->limpiarCadena($_POST['estado_categoria']);
        // verificar campos obligatorios
        if ($nombre_categoria == "" || $tipo_categoria == "" || $estado_categoria == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        #Verificando integridad de los datos
        if ($this->verificarDatos("^[a-zA-Z0-9]{4,20}$", $nombre_categoria)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El nombre de la categoría no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        // verificar que el nombre sea unico segun el tipo
        if ($tipo_categoria == "gasto") {
            $nombre = 'nombre_categoria_gasto';
            $modelo = CategoriaGasto::class;
        } else if ($tipo_categoria == "ingreso") {
            $nombre = 'nombre_categoria_ingreso';
            $modelo = CategoriaIngreso::class;
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El tipo de categoría no es válido",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $check_nombre = $modelo::where($nombre, $nombre_categoria)
            ->where("id_usuario", $_SESSION['id'])
            ->first();
        if ($check_nombre) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El nombre de la categoría ya se encuentra registrado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        // Preparando datos para el registro
        $datos_categoria_reg = [
            $nombre => $nombre_categoria,
            "id_usuario" => $_SESSION['id'],
            "estado" => $estado_categoria
        ];
        $nueva_categoria = $modelo::create($datos_categoria_reg);
        if ($nueva_categoria) {
            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Usuario registrado",
                "texto" => "La categoria " . $nombre_categoria . " ha sido registrada exitosamente",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "No se pudo registrar la categoría",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }
    public function listarCategoriaControlador($pagina, $registros, $url, $busqueda, $tipo_categoria)
    {
        $pagina = $this->limpiarCadena($pagina);
        $registros = $this->limpiarCadena($registros);
        $busqueda = $this->limpiarCadena($busqueda);
        $tipo_categoria = $this->limpiarCadena($tipo_categoria);
        $url = $this->limpiarCadena($url);
        $url = APP_URL . "?view=" . $url . "/";
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        $inicio = ($pagina > 0) ? (($registros * $pagina) - $registros) : 0;
        $registros = ($registros > 0) ? (int)$registros : 10;
        $tipo_categoria = ($tipo_categoria != "") ? $tipo_categoria : "ingreso";
        if ($tipo_categoria == "gasto") {
            $id = 'id_categoria_gasto';
            $nombre = 'nombre_categoria_gasto';
            $modelo = CategoriaGasto::class;
        } else if ($tipo_categoria == "ingreso") {
            $id = 'id_categoria_ingreso';
            $nombre = 'nombre_categoria_ingreso';
            $modelo = CategoriaIngreso::class;
        } else {
            return "<script>
                    Swal.fire({
                        title: 'Ocurrió un error inesperado',
                        text: 'Tipo de categoría no válido',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                </script>";
        }
        // consulta
        $query = $modelo::where('id_usuario', $_SESSION['id']);
        // Filtro por búsqueda
        if (!empty($busqueda)) {
            $query->where($nombre, 'LIKE', "%$busqueda%");
        }
        $consulta_total = (clone $query)->count();

        $consulta_datos = $query->orderBy($id, 'DESC')
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
                                <th>Nombre Categoria</th>
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
                if ($rows->estado == "activo") {
                    $btn_apagar_class = 'btn-danger btn-sm';
                } else {
                    $btn_apagar_class = 'btn-primary btn-sm';
                }
                $tabla .= '
                        <tr class="text-center">
                            <td>' . $contador . '</td>
                            <td>' . $rows->$nombre . '</td>
                            <td>' . strtoupper($rows->estado) . '</td>
                            <td class="d-flex justify-content-center gap-2">
                                <!-- Editar -->
                                <button type="button" class="btn btn-success btn-sm btnOpenEditar" data-bs-toggle="modal" data-bs-target="#modalCategoria" data-id="' . $rows->$id . '" data-nombre="' . $rows->$nombre . '" data-estado="' . $rows->estado . '">
                                    Editar
                                </button>

                                <!-- Eliminar -->
                                <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/FunctionAjax.php" method="POST">
                                    <input type="hidden" name="modulo_categoria" value="eliminar_categoria">
                                    <input type="hidden" name="categoria_id" value="' . $rows->$id . '">
                                    <input type="hidden" name="tipo_categoria" value="' . $tipo_categoria . '">
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
        if ($consulta_total && $pagina <= $numeroPaginas) {
            $tabla .= '<p class="text-secondary text-center mb-3">Mostrando categoria <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $consulta_total . '</strong></p>';
            $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
        }
        return $tabla;
    }

    public function actualizarCategoriaControlador()
    {
        #Almacenar Datos para la validacion de la categoria
        $tipo_categoria = $this->limpiarCadena($_POST['tipo_categoria']);
        $id = $this->limpiarCadena($_POST['categoria_id']);
        // verificar que la categoria exista
        if ($tipo_categoria == "gasto") {
            $nombre = 'nombre_categoria_gasto';
            $modelo = CategoriaGasto::class;
            $categoria_id = 'id_categoria_gasto';
        } else if ($tipo_categoria == "ingreso") {
            $nombre = 'nombre_categoria_ingreso';
            $modelo = CategoriaIngreso::class;
            $categoria_id = 'id_categoria_ingreso';
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El tipo de categoría no es válido",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $categoria_exist = $modelo::where($categoria_id, $id)
            ->where("id_usuario", $_SESSION['id'])
            ->first();
        if (!$categoria_exist) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El tipo de categoría no existe",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        #Almacenar Datos para la actualizacion de la categoria
        $estado_categoria = $this->limpiarCadena($_POST['estado_categoria']);
        $nombre_categoria = $this->limpiarCadena($_POST['nombre_categoria']);
        // verificar campos obligatorios
        if ($nombre_categoria == "" || $tipo_categoria == "" || $estado_categoria == "" || $id == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        #Verificando integridad de los datos
        if ($this->verificarDatos("^[a-zA-Z0-9]{4,20}$", $nombre_categoria)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El nombre de la categoría no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        // verificar que el nombre sea unico segun el tipo
        $check_nombre = $modelo::where($nombre, $nombre_categoria)
            ->where("id_usuario", $_SESSION['id'])
            ->where($categoria_id, '!=', $id)
            ->first();
        if ($check_nombre) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El nombre de la categoría ya se encuentra registrado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        // Preparando datos para el registro
        $datos_categoria_reg = [
            $nombre => $nombre_categoria,
            "id_usuario" => $_SESSION['id'],
            "estado" => $estado_categoria
        ];
        $nueva_categoria = $modelo::where($categoria_id, $id)
            ->update($datos_categoria_reg);
        if ($nueva_categoria) {
            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Usuario registrado",
                "texto" => "La categoria " . $nombre_categoria . " ha sido actualizada exitosamente",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "No se pudo actualizar la categoría",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }
    public function CambioEstadoCategoriaControlador()
    {
        #Almacenar Datos
        $tipo_categoria = $this->limpiarCadena($_POST['tipo_categoria']);
        $id = $this->limpiarCadena($_POST['categoria_id']);
        // verificar que la categoria exista
        if ($tipo_categoria == "gasto") {
            $modelo = CategoriaGasto::class;
            $categoria_id = 'id_categoria_gasto';
        } else if ($tipo_categoria == "ingreso") {
            $modelo = CategoriaIngreso::class;
            $categoria_id = 'id_categoria_ingreso';
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El tipo de categoría no es válido",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $categoria_exist = $modelo::where($categoria_id, $id)
            ->where("id_usuario", $_SESSION['id'])
            ->first();
        if (!$categoria_exist) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "El tipo de categoría no existe",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        // Cambiar estado
        $nuevo_estado = ($categoria_exist->estado == "activo") ? "inactivo" : "activo";
        $cambio_estado = $modelo::where($categoria_id, $id)
            ->update(["estado" => $nuevo_estado]);
        if ($cambio_estado) {
            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Cambio de estado exitoso",
                "texto" => "El estado de la categoría ha sido cambiado a " . $nuevo_estado . " exitosamente",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "No se pudo cambiar el estado de la categoría",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }
    public function getCategorias($tipo){
        if($tipo == 'gasto'){
            $modelo = CategoriaGasto::class;
        }else if($tipo == 'ingreso'){
            $modelo = CategoriaIngreso::class;
        }else{
            return [];
        }
        $check_categoria = $modelo::where('id_usuario', $_SESSION['id'])
            ->where('estado', 'activo')
            ->get();
        return $check_categoria;
    }
}
