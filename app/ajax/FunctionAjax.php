<?php
require_once "../../config/app.php";
require_once "../views/inc/session_start.php";
require_once "../../autoload.php";

use app\controllers\userController;
use app\controllers\categoriasController;
use app\controllers\searchController;

if (isset($_POST['modulo_usuario'])) {
    $insUsuario = new userController();
    if ($_POST['modulo_usuario'] == "registrar") {
        echo $insUsuario->registrarUsuarioControlador();
    }
}
if (isset($_SESSION['id']) && $_SESSION['id'] != "") {
    if (isset($_POST['modulo_categoria'])) {
        $insCategorias = new categoriasController();
        if ($_POST['modulo_categoria'] == "registrar_categoria") {
            echo $insCategorias->crearCategoriaControlador();
        }
        if ($_POST['modulo_categoria'] == "actualizar_categoria") {
            echo $insCategorias->actualizarCategoriaControlador();
        }
        if ($_POST['modulo_categoria'] == "eliminar_categoria") {
            echo $insCategorias->CambioEstadoCategoriaControlador();
        }
    }
    if (isset($_POST['modulo_buscador'])) {
        $insBuscador = new searchController();
        if ($_POST['modulo_buscador'] == "buscar_categoria")
            echo $insBuscador->buscarDatosControlador();
    }
} else {
    session_destroy();
    header("Location: " . APP_URL . "login.php/");
}
