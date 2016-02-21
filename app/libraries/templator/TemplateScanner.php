<?php
/**
 * @author natedrake
 * @date 21/02/2016
 */

namespace Ecne\Templator;


class TemplateScanner extends Scanner
{
    /**
     * @var array
     */
    private $codeBlocks = array();
    /**
     * @var array
     */
    private $echoBlocks = array();

    /**
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @return array
     */
    public function getCodeBlocks()
    {
        return $this->codeBlocks;
    }

    /**
     * @return array
     */
    public function getEchoBlocks()
    {
        return $this->echoBlocks;
    }

    public function render($input)
    {
        $this->input = $input;
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
     * @return object
     */
    private function scanEcho()
    {
        if ($token = $this->scanToken('/^[#]{2}([^*!##]+)[#]{2}/', 'echo'))  {
            $this->echoBlocks[] = $token->getRaw();
            return $token;
        }
    }

    /**
     * @return object
     */
    private function scanCodeBlock()
    {
        if ($token = $this->scanToken('/^[\#][\{]([\w\s\<\>\/]+)[}][#]/', 'codeblock')) {
            $this->codeBlocks[] = $token->getRaw();
            return $token;
        }
    }

    /**
     * @return mixed
     */
    public function next()
    {
        $scanners = array(
            'scanWhiteSpace',
            'scanCloseTag',
            'scanOpenTag',
            'scanText',
            'scanSelfClosingTag',
            'scanEcho',
            'scanCodeBlock',
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