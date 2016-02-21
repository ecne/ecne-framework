<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 08/02/2016
 * Time: 23:18
 */

namespace Ecne\Templator;


class Token
{
    #region static variables
    public static $TYPE_WHITESPACE = 'whitespace';
    public static $TYPE_OPENTAG = 'opentag';
    public static $TYPE_TEXT = 'text';
    public static $TYPE_SELFCLOSING = 'selfclosingtag';
    public static $TYPE_CLOSING = 'endtag';
    public static $TYPE_ECHO = 'echo';
    public static $TYPE_CODEBLOCK = 'codeblock';
    public static $TYPE_EXTENDS = 'extends';
    public static $TYPE_EOS = 'eos';
    #endregion
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $raw;
    /**
     * @var int
     */
    private $position;
    /**
     * @var int
     */
    private $start;
    /**
     * @var int
     */
    private $end;


    function __construct($type, $raw, $position)
    {
        $this->type = $type;
        $this->raw = $raw;
        $this->position = $position;
        $this->start = $this->position;
        $this->end = $this->start+(strlen($this->raw));
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function clear()
    {
        return $this->raw;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    public function getRaw()
    {
        return $this->raw;
    }


    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param $raw
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;
    }

    /**
     * @param $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @param $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }
}