<?php

function conectarDB(): mysqli{
    $db =new  mysqli(
        "mysql-elsamgt.alwaysdata.net",
        "elsamgt",
        "DB_PASSelSamgt",
        "elsamgt_bienesraices"
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