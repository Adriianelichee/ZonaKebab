<?php
//TERMINADO
class Conexion{
    private static $con = null;
    public static function getConection():PDO{
        if (self::$con == null) {
            
                $username = "root"; 
                $password = "1234"; 
                $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
                self::$con = new PDO("mysql:host=localhost;dbname=mydb", $username, $password, $opciones);
            
        }
        return self::$con;
    }
}
