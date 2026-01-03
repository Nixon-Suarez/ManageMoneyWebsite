<?php
require_once "../../config/app.php";
require_once "../views/inc/session_start.php";
require_once "../../autoload.php";

use app\controllers\userController;
use app\controllers\categoriasController;
use app\controllers\searchController;
use app\controllers\gastoController;

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
            echo $insBuscador->buscarDatosControlador("categoria");
        if ($_POST['modulo_buscador'] == "buscar_gasto")
            echo $insBuscador->buscarDatosControlador("gasto");
    }
    if (isset($_POST['modulo_gastos'])) {
        $insGastos = new gastoController();
        if ($_POST['modulo_gastos'] == "registrar_gastos") {
            echo $insGastos->registrarGastoControlador();
        }
        if ($_POST['modulo_gastos'] == "actualizar_gastos") {
            echo $insGastos->actualizarGastoControlador();
        }
        if ($_POST['modulo_gastos'] == "eliminar_gastos") {
            echo $insGastos->CambioEstadoGastoControlador();
        }
    }
} else {
    session_destroy();
    header("Location: " . APP_URL . "login.php/");
}
