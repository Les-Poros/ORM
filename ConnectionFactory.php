<?php

namespace Connection;

class ConnectionFactory{

    private static $db;

    public static function makeConnection(array $tab) {
        try {
            $dsn = 'mysql:host='. $tab['host'] . ';dbname='.$tab['dbname'];
            self::$db = new \PDO($dsn, $tab['username'], $tab['password'], array(
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false
            ));
            self::$db->prepare('SET NAMES \'UTF8\'')->execute();
        
        } catch (\PDOException $e) {
            throw new \PDOException("connection: $dsn " . $e->getMessage());
        }
        return self::$db;
    }

    public static function getConnection() {
        return self::$db;
    }

}