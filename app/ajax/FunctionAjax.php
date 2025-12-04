<?php
    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";
    use app\controllers\userController;
    use app\controllers\categoriasController;
    use app\controllers\searchController;

    if(isset($_POST['modulo_usuario'])){
        $insUsuario = new userController();
        $insCategorias = new categoriasController();
        if($_POST['modulo_usuario'] == "registrar"){
            echo $insUsuario->registrarUsuarioControlador();
        }
        if(isset($_SESSION['usuario_id']) && $_SESSION['usuario_id']!=""){
            if($_POST['modulo_usuario'] == "categorias"){
                echo $insCategorias->crearCategoriaControlador();
            }
            // if($_POST['modulo_usuario'] == "actualizar"){
            //     echo $insUsuario->actualizarUsuarioControlador();
            // }
        }
    }if(isset($_POST['modulo_buscador'])){
        $insBuscador = new searchController();
        if($_POST['modulo_buscador'] == "buscar_categoria")
            echo $insBuscador->buscarDatosControlador();
        // if($_POST['modulo_buscador'] == "eliminar")
        //     echo $insBuscador->eliminarBuscadorControlador();
    }else{
        session_destroy();
        header("Location: ".APP_URL."login.php/");
    }
