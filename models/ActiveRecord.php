<?php

namespace Model;

class ActiveRecord{

    
    // BASE DE DATOS
    protected static $db;
    protected static $columnasDB= [];
    protected static $tabla = '';


    // ERRORES
    protected static $errores = [];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $vendedores_id;

    
    // DEFINIR CONEXION A BASE DE DATOS
    public static function setDB($database){
        self::$db =$database;
    }

    public function guardar(){
        if(!is_null($this->id)){
            // ACTUALIZAR
            $this->actualizar();
        }else{
            // CREANDO UN NUEVO REGISTRO
            $this->crear();
        }
    }

    public function actualizar(){

        // SANITIZAR LOS DATOS
        $atributos = $this->sanitizarDatos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        $query = " UPDATE "  . static::$tabla . " SET "; 
        $query .= join(', ', $valores); 
        $query .= " WHERE id = '". self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        $resultado = self::$db->query($query);

        if ($resultado) {
            //REDIRECCIONAR AL USUARIO DESPUES DE HACER UN REGISTRO
            header("Location: /admin?mensaje=2");  // header solo funciona si no hay codigo HTML antes
        }
    }

    public function crear(){

        // SANITIZAR LOS DATOS
        $atributos = $this->sanitizarDatos();

        // INSERTAR EN LA BASE DE DATOS
       // $query = " INSERT INTO TABLA (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id) VALUES ('$this->titulo','$this->precio','$this->imagen','$this->descripcion','$this->habitaciones','$this->wc','$this->estacionamiento','$this->creado','$this->vendedores_id')";
        $query = " INSERT INTO "  . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos)); 
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($atributos)); 
        $query .= " ')";

        $resultado = self::$db->query($query);
        if ($resultado) {
            //REDIRECCIONAR AL USUARIO DESPUES DE HACER UN REGISTRO
            header("Location: /admin?mensaje=1");  // header solo funciona si no hay codigo HTML antes
        }
    }

    // ELIMINAR UN REGISTRO
    public function eliminar(){
        // ELIMINAR Registro
        $query = "DELETE FROM " . static::$tabla . " WHERE id =" . self::$db->escape_string($this->id) . " LIMIT 1 ";
        $resultado = self::$db->query($query);

        if($resultado){
            $this->borrarImagen();
            header('Location: /admin?mensaje=3');
        }
        debuguear($query);
    }

    // IDENTIFICAR Y UNIR LOS ATRIBUTOS DE LA DB
    public function atributos(){
        $atributos = [];
        foreach(static::$columnasDB as $columna){
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarDatos(){
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }

    // SUBIDA DE ARCHIVO
    public function setImagen($imagen){
        //ELIMINA LA IMAGEN PREVIA ( SI LA HAY)
        if(!is_null($this->id)){
            $this->borrarImagen();
        }

        // ASIGNAR AL ATRIBUTO DE IMAGEN EL NOMBRE DE LA IMAGEN
        if ($imagen) {
            $this->imagen=$imagen;
        }
    }

    // ELIMINA EL ARCHIVO
    public function borrarImagen(){
        // COMPROBAR SI EXISTE EL ARCHIVO
        $existe =file_exists(CARPETA_IMAGENES . $this->imagen);
        if($existe){
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }


    // VALIDACION
    public static function getErrores(){
        return static::$errores;
    }

    public function validar(){
        static::$errores= [];
        return static::$errores;
    }

    // LISTA TODAS LOS REGISTROS
    public static function all(){
        $query = "SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSQL($query);
        
        return $resultado;
    }

    // OBTIENE DETERMINADO NUMERO DE REGISTROS
    public static function get($cantidad){
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

        $resultado = self::consultarSQL($query);
        
        return $resultado;
    }

    // BUSCA UN REGISTRO POR SU ID
    public static function find($id){
        // CONSULTA PARA OPTENER REGISTRO
        $query = " SELECT * FROM " . static::$tabla . " WHERE id = ". $id;

        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

    public static function consultarSQL($query){
        // CONSULTAR BD
        $resultado = self::$db->query($query);
        // ITERAR LOS RESULTADOS
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // LIBERAR LA MEMORIA
        $resultado->free();
        // RETORNAR LOS RESULTADOS
        return $array;
    }

    protected static function crearObjeto($registro){
        $objeto = new static;

        foreach($registro as $key => $value ){
            if(property_exists( $objeto , $key)){
                $objeto->$key = $value;
            
            }
        }
        return $objeto;
    }

    // ACTUALIZAR O SINCRONISAR EL OBJETO EN MEMORIA CON LOS CAMBIOS REALIZADOS POR EL USUARIO
    public function sincronizar($args = []){
        foreach($args as $key => $value){
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }


}