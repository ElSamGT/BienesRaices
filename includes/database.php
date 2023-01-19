<?php

function conectarDB(): mysqli{
    $db =new  mysqli(
        $_ENV["mysql-elsamgt.alwaysdata.net"],
        $_ENV["elsamgt"],
        $_ENV["DB_PASSelSamgt"],
        $_ENV["elsamgt_bienesraices"]
    );

    $db->set_charset("utf8");


    if (!$db) {
        echo "Error No se pudo conectar";
        echo "Errno de depuracion: " . mysqli_connect_errno();
        echo "Error de depuracion: " . mysqli_connect_error();
        exit;
    } 
    return $db;
}