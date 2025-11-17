<?php
    namespace app\controllers;
    use app\models\mainModel;
    use App\Models\Usuario;  

    class userController extends mainModel{
        public function registrarUsuarioControlador(){
            #Almacenar Datos
            $usuario_nombre = $this->limpiarCadena($_POST['register_nombre']);
            $usuario = $this->limpiarCadena($_POST['register_usuario']);
            $usuario_email = $this->limpiarCadena($_POST['register_email']);
            $usuario_clave_1 = $this->limpiarCadena($_POST['register_clave1']);
            $usuario_clave_2 = $this->limpiarCadena($_POST['register_clave2']);
            // verificar campos obligatorios
            if($usuario_nombre=="" || $usuario=="" || $usuario_clave_1=="" || $usuario_clave_2==""){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            #Verificando integridad de los datos
            if($this->verificarDatos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$", $usuario_nombre)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El nombre no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }elseif($this->verificarDatos("^[a-zA-Z0-9]{4,20}$", $usuario)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El usuario no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }elseif($this->verificarDatos("^[a-zA-Z0-9$@.-]{7,100}$", $usuario_clave_1)){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "La clave no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            # Verificando si el email es valido y no se encuentra registrado
            if($usuario_email!=""){
                if(filter_var( $usuario_email, filter: FILTER_VALIDATE_EMAIL)){ # verifica si el email es valido
                    $check_email = Usuario::where("email", $usuario_email)->first();
                    if($check_email){
                        $alerta = [
                            "tipo" => "simple",
                            "titulo" => "Ocurrio un error inesperado",
                            "texto" => "El email no es valido",
                            "icono" => "error"
                        ];
                        return json_encode($alerta);
    
                    }
                }else{
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrio un error inesperado",
                        "texto" => "El email no es valido",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);

                }
            }
            # Verificando el usuario
            // $check_usuario = mainModel::eloquent()->table("usuario")->where("usuario", $usuario)->first();
            $check_usuario = Usuario::where("usuario_usuario", $usuario)->first();
            if($check_usuario){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "El usuario ya se encuentra registrado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
            #Verificando si las claves son iguales
            if($usuario_clave_1!=$usuario_clave_2){
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "Las claves no coinciden",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }else{
                $clave_procesada = password_hash($usuario_clave_1, PASSWORD_BCRYPT, ["cost" => 10]); # encripta la clave
            }
            #Preparando datos para el registro
            $datos_usuario_reg = [
                "nombre_usuario" => $usuario_nombre,
                "usuario_usuario" => $usuario,
                "clave" => $clave_procesada,
                "email" => $usuario_email,
                "tipo" => 2,
                "estado" => 1
            ];
            $agregar_usuario = Usuario::create($datos_usuario_reg);
            if($agregar_usuario){
                $alerta = [
                    "tipo" => "limpiar",
                    "titulo" => "Usuario registrado",
                    "texto" => "El usuario ".$usuario_nombre." ha sido registrado exitosamente",
                    "icono" => "success"
                ];
            }else{
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "No se pudo registrar el usuario, intente nuevamente",
                    "icono" => "error"
                ];
            }
            return json_encode($alerta);
        }

        public function listarUsuariosControlador(){

        }

        public function eliminarUsuarioControlador(){

        }

        public function actualizarUsuarioControlador(){

        }
    }