<?php   
    namespace app\controllers;
    use app\models\mainModel;

    class loginController extends mainModel {
        public function iniciarSesionControlador(){
            #Almacenar Datos
            $usuario = $this->limpiarCadena($_POST['login_usuario']);
            $clave = $this->limpiarCadena($_POST['login_clave']);

            // verificar campos obligatorios
            if($usuario=="" || $clave==""){
                echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Ocurrió un error inesperado',
                                text: 'No has llenado todos los campos que son obligatorios'
                                });
					</script>";
            }
            else{
                #Verificando integridad de los datos
                if($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)){
                    echo 
                    "<script>
                            Swal.fire({  
                                icon: 'error',
                                title: 'Ocurrió un error inesperado',
                                text: 'El USUARIO no coincide con el formato solicitado'
                                });
                    </script>";
                }elseif($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave)){
                    echo 
                    "<script>
                        Swal.fire({
                                icon: 'error',
                                title: 'Ocurrió un error inesperado',
                                text: 'La CLAVE no coincide con el formato solicitado'
                            });
                    </script>";
                }else{
                    $check_user = mainModel::eloquent()->table("usuario")->where("usuario_usuario", $usuario)->first();
                    if($check_user){
                        if($check_user->usuario_usuario == $usuario && password_verify($clave, $check_user->usuario_clave)){
                            $_SESSION['id'] = $check_user->id_usuario;
                            $_SESSION['nombre'] = $check_user->nombre_usuario;
                            $_SESSION['email'] = $check_user->email;
                            $_SESSION['tipo'] = $check_user->tipo;
                            $_SESSION['usuario'] = $check_user->usuario_usuario;
                            if(headers_sent()){
                                echo "<script> window.location.href='".APP_URL."?view=dashboard/'; </script>";
                            }else{
                                header("Location: ".APP_URL."?view=dashboard/"); # redirige a la pagina de inicio
                            }
                        }else{
                            echo 
                            "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Ocurrió un error inesperado',
                                    text: 'Usuario o clave incorrectos'
                                    });
                            </script>";
                        }
                    }else{
                        echo 
                        "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Ocurrió un error inesperado',
                                text: 'Usuario o clave incorrectos'
                                });
                        </script>";
                    }
                }
            }
        }
        public function cerrarSesionControlador(){
            session_destroy(); 
            if(headers_sent()){
                echo "<script> window.location.href='".APP_URL."?view=login/'; </script>";
            }else{
                header("Location: ".APP_URL."?view=login/"); # redirige a la pagina de inicio
            }
        }
    }