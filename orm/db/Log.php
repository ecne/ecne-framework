<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/01/2017
 * Time: 16:18
 */

namespace Ecne\ORM\DB;


class Log
{
    public $message;

    /**
     * @var float $start
     */
    public $start;
    /**
     * @var float $end
     */
    public $end;
    /**
     * @var float $duration
     */
    public $duration;

    public function __construct($message)
    {
        $this->message=$message;
        $this->start=microtime(true);
    }

    public function finish()
    {
        $this->end=microtime(true);
        $this->duration=$this->end-$this->start;
        DataBase::addLog($this);
    }
}