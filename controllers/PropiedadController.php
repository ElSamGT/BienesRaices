<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController
{

    public static function index(Router $router)
    {
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();

        // MUESTRA MENSAJE CONDICIONAL
        $mensaje = $_GET['mensaje'] ?? null;

        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'mensaje' => $mensaje,
            'vendedores' => $vendedores
        ]);
    }
    public static function crear(Router $router)
    {

        $propiedad = new Propiedad;
        $vendedores_id = Vendedor::all();

        // ARREGLO CON MENSAJES DE ERRORES
        $errores = Propiedad::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // CREA UNA NUEVA INSTANCIA
            $propiedad = new Propiedad($_POST['propiedad']);

            /* SUBIDA DE ARCHIVOS */

            // GENERAR NOMBRE UNICO A IMAGEN
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            // SETEA LA IMAGEN
            // REALIZA UN RESIZE A LA IMAGEN CON 'INTERVENTION' 
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            // VALIDAR
            $errores = $propiedad->validar();

            // REVISAR QUE EL ARREGLO DE ERRORES ESTE VACIO
            if (empty($errores)) {

                //CREAR LA CARPETA PARA SUBIR IMAGENES

                if (!is_dir(CARPETA_IMAGENES)) { // VERIFICA QUE NO EXISTA LA CARPETA
                    mkdir(CARPETA_IMAGENES);  // CREA UN DIRECTORIO
                }

                // GUARDA LA IMAGEN EN EL SERVIDOR
                $image->save(CARPETA_IMAGENES . $nombreImagen);

                // GUARDAR EN LA BD
                $propiedad->guardar();
            }
        }


        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores_id' => $vendedores_id,
            'errores' => $errores
        ]);
    }
    public static function actualizar(Router $router)
    {
        $id = validarORedireccionar('/admin', 'propiedad','Propiedad');

        $propiedad = Propiedad::find($id);
        $vendedores_id = Vendedor::all();

        // ARREGLO CON MENSAJES DE ERRORES
        $errores = Propiedad::getErrores();

        //METODO POST PARA ACTUALIZAR
        // EJECUTA EL CODIGO DESPUES DE QUE EL USUARIO ENVIA EL FORMULARIO
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ASIGNAR LOS ATRIBUTOS

            /*
    $args = [];
    $args['titulo'] = $_POST['titulo'] ?? null;
    $args['precio'] = $_POST['precio'] ?? null;
    $args['imagen'] = $_POST['imagen'] ?? null;
    $args['descripcion'] = $_POST['descripcion'] ?? null;
    $args['habitaciones'] = $_POST['habitaciones'] ?? null;
    $args['wc'] = $_POST['wc'] ?? null;
    $args['estacionamiento'] = $_POST['estacionamiento'] ?? null;
    $args['vendedores_id'] = $_POST['vendedores_id'] ?? null;
    */
            $args = $_POST['propiedad'];

            $propiedad->sincronizar($args);

            // VALIDACION
            $errores = $propiedad->validar();

            //SUBIDA DE ARCHIVOS
            // GENERAR NOMBRE UNICO A IMAGEN
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }
            // REVISAR QUE EL ARREGLO DE ERRORES ESTE VACIO
            if (empty($errores)) {
                if ($_FILES['propiedad']['tmp_name']['imagen']) {
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $propiedad->guardar();
            }
        }

        $router->render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores_id' => $vendedores_id
        ]);
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // VALIDAR ID
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                $tipo = $_POST['tipo'];
                if (validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                }
            }
        }
    }
}
