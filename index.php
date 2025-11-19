<?php
    require_once "./config/app.php";
    require_once "./app/views/inc/session_start.php";
    require_once "autoload.php";

    if(isset($_GET['view'])){
        $url = explode("/",$_GET['view']); # explode -> splits a string usinga a delimiter
    }else{
        $url=["login"];
    }
    $view = $url[0];
?>
<!DOCTYPE html>
<html lang="en">
  <?php require_once "./app/views/inc/head.php";?>
  <body class="<?php echo ($view == 'login' || $view == 'index' || $view == '') ? 'body-login' : 'body-dashboard'; ?>">
    <?php
        use app\controllers\viewsController;
        use app\controllers\loginController;

        $insLogin = new loginController();

        $viewsController= new viewsController();
        $vista=$viewsController->obtenerVistasControlador($url[0]);
        if($vista=="login" || $vista=="404"){
            require_once "./app/views/content/".$vista."_view.php";
        }else{
            // si no ha iniciado sesion no permite acceder 
            if((!isset($_SESSION['id']) || $_SESSION['id'] == "") || (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == "")){
                $insLogin->cerrarSesionControlador();
                exit();
            }
            else{
            require_once "./app/views/inc/navbar.php";
            require_once $vista;
            }
        }
        require_once "./app/views/inc/script.php";
    ?>
  </body>
</html>