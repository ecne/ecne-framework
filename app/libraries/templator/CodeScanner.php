<?php

namespace Ecne\Templator;

class CodeScanner extends Scanner
{
    /**
     * @var array
     */
    private $availableVariables = array(
        'foreach',
        'for',
        'if',
        'else',
        'elseif',
        'end',
        'var'
    );

    /**
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param $input
     */
    public function compile($input)
    {
        $this->input = preg_replace('/[}]{1}[#]{1}/', '', (preg_replace('/^[#]{1}[{]{1}/', '', $input)));
    }

    /**
     * @return object
     */
    private function scanForEach()
    {
        return $this->scanToken('/^foreach/', 'foreach');
    }

    /**
     * @return object
     */
    private function scanVariable()
    {
        return $this->scanToken('/^(\$[^*!\s]+)/', 'variable');
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
    function next()
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