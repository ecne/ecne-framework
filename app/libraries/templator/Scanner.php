<?php
/**
 * @author natedrake
 * @date 21/02/2016
 */

namespace Ecne\Templator;


abstract class Scanner
{
    /**
     * @var string
     */
    protected $input;
    /**
     * @var int
     */
    protected $inputIndex;
    /**
     * @var array
     */
    protected $tokens = array();

    /**
     * @param $length
     */
    protected function consumeInput($length)
    {
        $this->inputIndex+=$length;
        $this->input = substr($this->input, $length);
    }

    public function setTokens($tokens = array())
    {
        $this->tokens = $tokens;
    }

    /**
     * @param $type
     * @param $raw
     * @return Token
     */
    protected function createToken($type, $raw)
    {
        return new Token($type, $raw, $this->inputIndex);
    }

    /**
     * @param $regex
     * @param $type
     * @return object
     */
    protected function scanToken($regex, $type)
    {
        $matches = array();
        if (preg_match($regex, $this->input, $matches)) {
            $this->consumeInput(strlen($matches[0]));
            if (isset($matches[1])) {
                return $this->createToken($type, $matches[0]);
            }
        }
    }

    /**
     * @return object
     */
    protected function scanWhiteSpace()
    {
        return $this->scanToken('/^([\s]+)/', 'whitespace');
    }

    /**
     * @return object|void
     */
    protected function scanEOS()
    {
        if (strlen($this->input)) {
            return;
        } else {
            return $this->createToken('eos', 'eos');
        }
    }

    /**
     * @return mixed
     */
    abstract public function next();
}