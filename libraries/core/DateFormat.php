<?php

/**
 *  Class \Ecne\Classes\DateFormat
 * @author John O'Grady
 * @date 06/07/15
 */

namespace Ecne\Library\Core;

class DateFormat
{
    const EASY = 'jS \of F';

    private static $instance;
    /**
     * The date string
     *
     * @var string
     */
    private $date;

    /**
     * The date format
     *
     * @var string
     */
    private $format;

    public static function get()
    {
        self::$instance = new DateFormat();
        return self::$instance;
    }

    public function date($date)
    {
        $this->date = $date;
        return $this;
    }

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }

    public function run()
    {
        $date = new \DateTime($this->date);
        return $date->format($this->format);
    }
}