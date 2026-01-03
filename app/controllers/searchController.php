<?php 
    namespace app\controllers;
    use App\Models\CategoriaGasto;
    use app\models\mainModel;

    class searchController extends mainModel {
        public function modulosBusquedaControlador($modulo){
            $listaModulos = [
                'categorias',
                'gastos'
            ];
            if(in_array($modulo, $listaModulos)){
                return false;
            }else{
                return true;
            }
        }
        public function buscarDatosControlador($tipo_busqueda){
            $url = $this->limpiarCadena($_POST['modulo_url']);
            $texto = $this->limpiarCadena($_POST['txt_buscador']);
            if($this->modulosBusquedaControlador($url)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No podemos procesar su busqueda, por favor intente nuevamente",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            #Verificando integridad de los datos
            if (!empty($texto)){
                if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}", $texto)){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "El nombre no coincide con el formato solicitado",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                }
            }
            if($tipo_busqueda == "categoria"){
                $tipo_categoria = $this->limpiarCadena($_POST['tipo_categoria']);
                if($tipo_categoria == ""){
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "Ingrese un termino de busqueda",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                }
                $_SESSION[$url] = [
                    "texto" => $texto,
                    "tipo_categoria" => $tipo_categoria
                ];

                $alerta = [
                    "tipo" => "redireccionar",
                    "titulo" => "Busqueda realizada",
                    "texto" => "Se ha realizado la busqueda correctamente",
                    "icono" => "success",
                    "url" => APP_URL."?view=".$url."/1/"
                ];
                return json_encode($alerta);
            }else if($tipo_busqueda == "gasto"){
                $mes = isset($_POST['mes']) ? $this->limpiarCadena($_POST['mes']) : "";
                $_SESSION[$url] = [
                    "texto" => $texto,
                    "mes" => $mes
                ];
                $alerta = [
                    "tipo" => "redireccionar",
                    "titulo" => "Busqueda realizada",
                    "texto" => "Se ha realizado la busqueda correctamente",
                    "icono" => "success",
                    "url" => APP_URL."?view=".$url."/1/"
                ];
                return json_encode($alerta);
            }else{
                $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "tipo de busqueda no admitido",
                        "icono" => "error"
                    ];
                return json_encode($alerta);
            }
        }
    }