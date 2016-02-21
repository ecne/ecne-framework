<?php

namespace Ecne\Templator;


class CodeScanner
{

    private $input;
    private $inputIndex=0;
    private $availableVariables = array(
        'foreach',
        'for',
        'if',
        'else',
        'elseif',
        'end',
        'var'
    );
    private $tokens = array();

    public function getTokens()
    {
        return $this->tokens;
    }

    public function compile($input)
    {
        $this->input = preg_replace('/[}]{1}[#]{1}/', '', (preg_replace('/^[#]{1}[{]{1}/', '', $input)));
    }

    public function createToken($type, $raw)
    {
        return new Token($type, $raw, $this->inputIndex);
    }

    /**
     * @param $length
     */
    private function consumeInput($length)
    {
        Templator::debug($this->input."<br><br>");
        $this->inputIndex+=$length;
        $this->input = substr($this->input, $length);
    }

    /**
     * @param $regex
     * @param $type
     * @return object
     */
    private function scanToken($regex, $type)
    {
        if ($type === 'foreach') {
            //echo "Foreach Token being scanned!<br>";
        }
        $matches = array();
        if (preg_match($regex, $this->input, $matches)) {
            $this->consumeInput(strlen($matches[0]));
            /**
            echo "###{$type}:|".$matches[0].'|###<br>';
            if (isset($matches[1]))
                echo "###[1]|".$matches[1].'|###<br>';
            **/
            if (isset($matches[1])) {
                return $this->createToken($type, $matches[0]);
            }
        }
    }

    /**
     * @return object
     */
    private function scanWhiteSpace()
    {
        return $this->scanToken('/^([\s]+)/', 'whitespace');
    }

    private function scanForEach()
    {
        return $this->scanToken('/^foreach/', 'foreach');
    }

    private function scanVariable()
    {
        return $this->scanToken('/^(\$[^*!\s]+)/', 'variable');
    }

    /**
     * @return object
     */
    private function scanOpenTag()
    {
        return $this->scanToken('/^[<]{1}([\=\"\!\#\-\_\.\:\w\s\/]+)[>]{1}/', 'opentag');
    }

    /**
     * @return object
     */
    private function scanText()
    {
        return $this->scanToken('/^([\w\.\,\?\-\/]+)/', 'text');
    }

    /**
     * @return object
     */
    private function scanSelfClosingTag()
    {
        return $this->scanToken('/^[<]{1}([^*]+)[\/][>]{1}/', 'selfclosingtag');
    }

    /**
     * @return object
     */
    private function scanCloseTag()
    {
        return $this->scanToken('/^[<][\/]([\w]+)[>]/', 'endtag');
    }

    /**
     * @return object|void
     */
    private function scanEOS()
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
    public function next()
    {
        $scanners = array(
            'scanWhiteSpace',
            'scanOpenTag',
            'scanText',
            'scanSelfClosingTag',
            'scanCloseTag',
            'scanForEach',
            'scanEOS'
        );
        foreach($scanners as $scanner) {
            $token = $this->$scanner();
            if ($token !== null) {
                $this->tokens[] = $token;
                return $token;
            }
        }
    }
}