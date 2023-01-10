<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];
    
    public function get($url, $fn){
        $this->rutasGET[$url] = $fn;
    }
    
    public function post($url, $fn){
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas(){

        session_start();

        $auth = $_SESSION['login'] ?? null;

        // ARREGLO DE RUTAS PROTEGIDAS
        $rutas_protegidas = ['/admin','/propiedades/crear','/propiedades/actualizar','/propiedades/eliminar','/vendedores/crear','/vendedores/actualizar','/vendedores/eliminar'];

        
        if (isset($_SERVER['PATH_INFO'])) {
            $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        } else {
            $urlActual = $_SERVER['REQUEST_URI'] === '' ? '/' : $_SERVER['REQUEST_URI'];
        }

        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        // PROTEGER LAS RUTAS
        if(in_array($urlActual, $rutas_protegidas) && !$auth){
            header('Location: /');
        }


        if ($fn) {
            // LA RUTA EXISTE Y HAY UNA FUNCION ASOCIADA
            call_user_func($fn, $this);
        } else {
            echo "Pagina no encontrada";
        }
    }


    // MUESTRA UNA VISTA
    public function render($view,  $datos=[]){

        foreach($datos as  $key => $value){
            $$key = $value;
        }

        ob_start();
        include __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();
        include __DIR__ . "/views/layout.php";
    }
}