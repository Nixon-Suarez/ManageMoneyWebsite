<?php   
    namespace app\controllers;
    use app\models\mainModel;

    class loginController extends mainModel {
        public function iniciarSesionControlador(){
            #Almacenar Datos
            $usuario = $this->limpiarCadena($_POST['login_usuario']);
            $clave = $this->limpiarCadena($_POST['login_clave']);
        }
    }