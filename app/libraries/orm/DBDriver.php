<?php

namespace Ecne\ORM;

use Ecne\Library\Core\Config;

class DBDriver
{
    const MYSQL = 'MYSQL';
    const POSTGRESQL = 'POSTGRESQL';
    const SQLITE = 'SQLITE';
    const MARIADB = 'MARIADB';

    /**
     * @var $driver|string
     */
    private $driver;

    /**
     * @var $DSN|string
     */
    private $DSN;

    /**
     *  @method construct
     *  @param $driverType
     *  @return void
     */
    public function __construct($driverType)
    {
        $this->driver = $driverType;
    }

    /**
     *  @method getDSN
     *  @return string
     */
    public function getDSN()
    {
        switch ($this->driver) {
            case self::MYSQL:
                $this->DSN = 'mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db');
                break;
            case self::POSTGRESQL:
                $this->DSN = 'pgsql:dbname=' . Config::get('mysql/db') . ';host=' . Config::get('mysql/host');
                break;
            case self::SQLITE:
                $this->DSN = 'sqlite:' . Config::get('mysql/db');
                break;
            case self::MARIADB:
                break;
            default:
                break;
        }
        return $this->DSN;
    }
}   #End Class Definition
