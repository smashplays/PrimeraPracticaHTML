<?php

require_once 'Database.php';

class Conexion{
    static function connectDb(){
        try{
            $db = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
            return $db;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}