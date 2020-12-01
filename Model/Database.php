<?php

class Database {

    // parametre a definir....

    private static $_host = "";
    private static $_user = "";
    private static $_pass = "";
    private static $_db = "aitd";
    private static $_driver = "mysql";

    public static $_connection;

    public static function createConnexion() {
        self::$_connection = new PDO(
            self::$_driver
            .":host=".self::$_host
            .";dbname=".self::$_db, 
            self::$_user, 
            self::$_pass
        );
    }
}