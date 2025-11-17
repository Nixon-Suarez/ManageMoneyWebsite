<?php
    namespace app\models;
    use Illuminate\Database\Capsule\Manager as DB;
    use \PDO;
    use \PDOException;
    class mainModel{
        // protected function eloquent(){
        // return DB::connection();
        // } #usar cuando se quiera usar Query Builder de Eloquent
        public function limpiarCadena($cadena){
            $palabras = ["<script>", "</script>", "<script src>", "<script type=", "SELECT * FROM",  "DELETE FROM",  "INSERT INTO",  "DROP TABLE",  "DROP DATABASE", "TRUNCATE TABLA",   "SHOW TABLES;", "SHOW DATABASE;", "<?php", "?>", "--", "^", "<", ">", "[", "]", "==", ";", "::"];
            $cadena = trim($cadena); //quita espacios en blanco
            $cadena = stripslashes($cadena); //quita barras invertidas
            foreach ($palabras as $palabra) {
                $cadena = str_ireplace($palabra, "", $cadena); //reemplaza las palabras prohibidas por "" y lo guarda en $cadena
            }
            $cadena = trim($cadena);
            $cadena = stripslashes($cadena);
            $cadena = htmlspecialchars($cadena); //convierte caracteres especiales en entidades HTML
            return $cadena;
        }

        protected function verificarDatos($filtro, $cadena){
            if(preg_match("/^".$filtro."$/", $cadena)){
                return false; //si no coincide con el filtro devuelve false
            }else{
                return true; //si coincide con el filtro devuelve true
            }
        }

        public function paginadorTablas($pagina, $total_paginas, $url, $botones){
            $tabla = '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

            if($pagina <= 1){ # si la pagina es menor o igual a 1 se desactiva el boton de anterior
                $tabla .= '
                <a class="pagination-previous is-disabled" disabled> Anterior</a>
                <ul class="pagination-list">
                ';   
            }else{ # si la pagina es mayor a 1 se activa el boton de anterior
                $tabla .= '
                <a class="pagination-previous" href="'.$url.($pagina-1).'/">Anterior</a>
                <ul class="pagination-list">
                    <li><a class="pagination-link" href="'.$url.'.1/">1</a></li> <!-- se crea un boton con el numero 1 y se le asigna la url que lleva a la pagina uno -->
                    <li><span class="pagination-elliosis">&hellip;</span></li> <!-- son los ... -->
                '; 
            }

            $ContadorI = 0;
            for($i = $pagina; $i <= $total_paginas && $ContadorI < $botones; $i++) { # se inicia un ciclo for que va desde la pagina actual hasta la cantidad de paginas y se limita a la cantidad de botones
                $tabla .= '<li><a class="pagination-link" href="'.$url.$i.'/">'.$i.'</a></li>'; # se crea un boton con el numero de la pagina y se le asigna la url correspondiente
                $ContadorI++;
            }
            
            if($pagina == $total_paginas){ # si la pagina es igual a la cantidad de paginas se desactiva el boton de siguiente
                $tabla .= '
                </ul>
                <a class="pagination-next" is-disabled disabled>Siguiente</a>
                ';   
            }else{ # si la pagina es menor a la cantidad de paginas se activa el boton de siguiente
                $tabla .= '
                    <li><span class="pagination-elliosis">&hellip;</span></li>
                    <li><a class="pagination-link" href="'.$url.$total_paginas.'/">'
                    .$total_paginas.'</a></li> <!-- se crea un boton con el numero maximo de paginas y se le asigna la url que lleva a la pagina final -->
                </ul>
                <a class="pagination-next" href="'.$url.($pagina+1).'/">Siguiente</a>
                '; 
            }

            $tabla .= '</nav>';
            return $tabla;
        }
    } 