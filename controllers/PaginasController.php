<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;


class PaginasController{
    public static function index(Router $router){

        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }
    public static function nosotros(Router $router){
        $router->render('paginas/nosotros');
    }
    public static function propiedades(Router $router){

        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }
    public static function propiedad(Router $router){
        

        $id = validarORedireccionar('/propiedades','propiedad','Propiedad');

        // BUSCAR LA PROPIEDAD POR US ID
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }
    public static function blog(Router $router){
        $router->render('paginas/blog', [

        ]);
    }
    public static function entrada(Router $router){


        $router->render('paginas/entrada', [

        ]);
    }
    public static function contacto(Router $router){

        $mensaje = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $respuestas= $_POST['contacto'];


            // CREAR UNA INSTANCIA DE PHPMAILER
            $mail = new PHPMailer();

            // configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'c02ba56b99990e';
            $mail->Password = 'b198e5cea5f961';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            // CONFIGURAR CONTENIDO DEL MAIL
            $mail->setFrom('admin@bienesraices.com');
            $mail->addAddress($respuestas['email'], $respuestas['Nombre']);
            $mail->Subject = 'Tienes un Nuevo Mensaje';

            //HABILITAR HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            // DEFINIR EL CONTENIDO DEL MAIL
            $contenido = '<html>';
            $contenido .= '<p>Tienes un nuevo mensaje</p>';
            $contenido .= '<p>Nombre: ' . $respuestas['nombre'] . ' </p>';

            // ENVIAR DE FORMA CONDICIONAL ALGUNOS CAMPOS DE EMAIL O TELEFONO
            if($respuestas['contacto'] === 'telefono') {
                $contenido .= '<p>Eligio ser contactado por Telefono: </p>';
                $contenido .= '<p>Telefono: ' . $respuestas['telefono'] . ' </p>';
                $contenido .= '<p>Fecha de Contacto: ' . $respuestas['fecha'] . ' </p>';
                $contenido .= '<p>Hora de Contacto: ' . $respuestas['hora'] . ' </p>';

            } else{
                $contenido .= '<p>Eligio ser contactado por Email: </p>';
                $contenido .= '<p>Email: ' . $respuestas['email'] . ' </p>';
            }

            $contenido .= '<p>Mensaje: ' . $respuestas['mensaje'] . ' </p>';
            $contenido .= '<p>Vende o Compra: ' . $respuestas['tipo'] . ' </p>';
            $contenido .= '<p>Precio o Presupuesto: $' . $respuestas['precio'] . ' </p>';
            $contenido .= '</html>';

            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            // ENVIAR EL MAIL
            if($mail->send()){
                $mensaje =  "Mensaje enviado Correctamente";
            } else {
                $mensaje = "El mensaje no se pudo enviar...";
            }


        }

        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }


}