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
        $inicio = false;
        $router->render('paginas/nosotros');
    }
    public static function propiedades(Router $router){

        $propiedades = Propiedad::all();
        $inicio = false;
        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }
    public static function propiedad(Router $router){
        

        $id = validarORedireccionar('/propiedades','propiedad','Propiedad');

        // BUSCAR LA PROPIEDAD POR US ID
        $propiedad = Propiedad::find($id);
        $inicio = false;

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad,
            'inicio' => $inicio
        ]);
    }
    public static function blog(Router $router){
        $inicio = false;
        $router->render('paginas/blog', [
            'inicio' => $inicio
        ]);
    }
    public static function entrada(Router $router){

        $inicio = false;
        $router->render('paginas/entrada', [
            'inicio' => $inicio

        ]);
    }
    public static function contacto(Router $router){

        $mensaje = null;
        $inicio = false;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $respuestas= $_POST['contacto'];


            // CREAR UNA INSTANCIA DE PHPMAILER
            $mail = new PHPMailer();

            // configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp-relay.sendinblue.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bikuhan@hotmail.com';
            $mail->Password = 'xsmtpsib-3cc0fb975fe8856a2e36c795c79dd2617fa711501cbcbeed2c8a7f7959ff71b3-R9WvCFOAfw2GXK0P';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // CONFIGURAR CONTENIDO DEL MAIL
            $mail->setFrom('Sam@bienesraices.com');
            $mail->addAddress($respuestas['email']);
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
            'mensaje' => $mensaje,
            'inicio' => $inicio
        ]);
    }


}