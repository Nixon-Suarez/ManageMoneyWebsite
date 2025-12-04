<?php
    namespace app\models;
    class viewsModel{
        protected function obtenerVistasModelo($vistas){
            $listaBlanca = [
                "dashboard",
                "logout",   
                "categorias"
            ];

            if(in_array($vistas, $listaBlanca)) {
                if (is_file("./app/views/content/{$vistas}_view.php")) {
                    $contenido = "./app/views/content/{$vistas}_view.php";
                }else {
                    $contenido = "404";
                }
            }elseif ($vistas == "login" || $vistas == "index") {
                $contenido = "login";
            }else{
                $contenido = "404";
            }
            return $contenido;
        }
    }