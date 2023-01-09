<?php

namespace Controllers;

use MVC\Router;
use Model\Vendedor;

class VendedoresController {
    public static function crear(Router $router){

        $errores = Vendedor::getErrores();
        $vendedor = new Vendedor; 
        
// EJECUTA EL CODIGO DESPUES DE QUE EL USUARIO ENVIA EL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CREAR UNA NUEVA INSTANCIA
    $vendedor = new Vendedor($_POST['vendedor']);

    // VALIDAR QUE NO HAYA CAMPOS VACIOS
    $errores = $vendedor->validar();

    // NO HAY ERRORES
    if(empty($errores)){
        $vendedor->guardar();
    }
}

        $router -> render('vendedores/crear', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }
    public static function actualizar(Router $router){

        $errores = Vendedor::getErrores();
        $id = validarORedireccionar('/admin','vendedor','Vendedor');

        //OBTENER DATOS DEL VENDEDOR A ACTUALIZAR
        $vendedor = Vendedor::find($id);

        
// EJECUTA EL CODIGO DESPUES DE QUE EL USUARIO ENVIA EL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ASIGNAR LOS VALORES A LOS CAMPOS
    $args = $_POST['vendedor'];

    // SINCRONIZAR OBJETO EN MEMORIA CON LO QUE EL USUARIO ESCRIBIO
    $vendedor->sincronizar($args);

    // VALIDACION
    $errores = $vendedor->validar();

    if (empty($errores)) {
        $vendedor->guardar();
    }
}


        $router->render('vendedores/actualizar', [
            'errores' => $errores,
            'vendedor' => $vendedor

        ]);
    }
    public static function eliminar(){
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            // VALIDAR EL ID
            $id = $_POST['id'];
            $id= filter_var($id, FILTER_VALIDATE_INT);
            if($id){
                // VALIDAEL TIPO A ELIMINAR
                $tipo = $_POST['tipo'];
                if(validarTipoContenido($tipo)){
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar();
                }
            }
        }
    }
}