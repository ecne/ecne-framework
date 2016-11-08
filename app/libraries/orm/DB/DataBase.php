<?php

/**
 * Class DataBase
 * @version 1.1
 * @date May 2015
 **/

namespace Ecne\ORM\DB;

use Ecne\Library\Core\Config;
use PDO;
use PDOException;

class DataBase
{
    /**
     * @var DataBaseDriver $dbDriver
     */
    private static $dbDriver;
    /**
     * @var PDO $pdo
     */
    private static $pdo;

    /**
     * @throws \PDOException
     */
    public static function connect()
    {
        self::$dbDriver = new DBDriver(Config::get('mysql/driver'));
        try {
            self::$pdo = new PDO(self::$dbDriver->getDSN(), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @param $dsn
     * @param $username
     * @param $password
     */
    public static function connectDSN($dsn, $username, $password)
    {
        try {
            self::$pdo = new PDO($dsn, $username, $password);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function disconnect()
    {
        self::$pdo = null;
    }

    /**
     * @return string
     */
    public static function getLastInsertID()
    {
        return self::$pdo->lastInsertID();
    }

    /**
     * @method execute
     * @param $query |string
     * @param array $parameters
     * @return DataBase
     */
    public static function execute($query, $parameters=[])
    {
        echo $query;
        $q = self::$pdo->prepare($query);
        $q->execute($parameters);
        return $q;
    }
}   #End Class Definition
