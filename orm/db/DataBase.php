<?php

/**
 * @class DataBase
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
     * @var DBDriver $dbDriver
     */
    private static $dbDriver=null;
    /**
     * @var PDO $pdo
     */
    private static $pdo=null;
    /**
     * @var array
     */
    private static $log=[];

    /**
     * @throws \PDOException
     */
    public static function connect()
    {
        self::$dbDriver = new DBDriver(Config::get('mysql/driver'));
        try {
            $log=new Log('Connecting to database using '.self::$dbDriver->getDSN());
            self::$log[]=$log;
            self::$pdo = new PDO(self::$dbDriver->getDSN(), Config::get('mysql/username'), Config::get('mysql/password'));
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $log->finish();
        } catch (PDOException $e) {
            $log=new Log($e->getMessage());
            self::addLog($log);
            $log->finish();
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
            $log=new Log("Connecting to database using ". $dsn);
            self::$pdo = new PDO($dsn, $username, $password);
            $log->finish();
        } catch (PDOException $e) {
            $log=new Log($e->getMessage());
            $log->finish();
            die($e->getMessage());
        }
    }

    /**
     * @note disconnect pdo from database
     */
    public static function disconnect()
    {
        $log=new Log("Disconnecting...");
        $log->finish();
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
     * @param $query
     * @param array $parameters
     * @return \PDOStatement
     */
    public static function execute($query, $parameters=[])
    {
        $log=new Log($query);
        $q = self::$pdo->prepare($query);
        $q->execute($parameters);
        $log->finish();
        return $q;
    }

    /**
     * @return bool
     */
    public static function beginTransaction()
    {
        return self::$pdo->beginTransaction();
    }

    /**
     * @return bool
     */
    public static function rollback()
    {
        return self::$pdo->rollBack();
    }

    /**
     * @return bool
     */
    public static function commit()
    {
        return self::$pdo->commit();
    }

    /**
     * @param $entry
     */
    public static function addLog($entry)
    {
        self::$log[]=$entry;
    }

    /**
     * @return array
     */
    public static function getLog()
    {
        return self::$log;
    }

    /**
     *  @note clear log entries
     */
    public static function clearLog()
    {
        self::$log=[];
    }
}   #End Class Definition
