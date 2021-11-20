<?php

namespace App\Model\DataBase;

use PDO;
use PDOException;

class Connect
{
    private static $instance;
    
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new PDO(
                    "mysql:host=".HOST.";port=".PORT.";dbname=".DBNAME,
                    USER,
                    PASSWD,
                    OPTIONS,
                );
            } catch (PDOException) {
                die("Erro na conexão!");
            }
        }
        return self::$instance;
    }
}