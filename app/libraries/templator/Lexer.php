<?php

namespace Ecne\Templator;


class Lexer
{
    /**
     * @var Token[]
     */
    private $tokens;
    /**
     * @var Token[]
     */
    private $parsedTokens;
    /**
     * @var array
     */
    private $variables = array();

    /**
     * @param $tokens
     */
    function __construct($tokens = array())
    {
        $this->tokens = $tokens;
    }

    /**
     * @return Token
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param $tokens
     */
    public function setTokens($tokens = array())
    {
        $this->tokens = $tokens;
    }

    public function go($variables)
    {
        $this->variables = $variables;
        $this->parseTokens();
    }


    private function parseTokens()
    {
        foreach($this->tokens as $token) {
            $matches = array();
            if ($token->getType() === Token::$TYPE_CODEBLOCK) {
                /**
                $this->parseCode($token->getRaw());
                $token->setRaw('');
                **/
            } else if ($token->getType() === Token::$TYPE_ECHO) {
                if (preg_match('/^[#]{2}([^*]+)[#]{2}/', $token->getRaw(), $matches)) {
                    if ($matches[1]) {
                        $var = preg_replace('/^[\s\$]+/', '', $matches[1]);
                        $var = (preg_replace('/[\s]+/', '', $var));
                        if ($this->isVariable($var)) {
                            $token->setRaw($this->parseVariable($var));
                        }
                    }
                }
            } else if ($token->getType() === Token::$TYPE_EOS) {
                continue;
            }
            $this->parsedTokens[] = $token;
        }
    }

    public function build()
    {
        foreach($this->parsedTokens as $token) {
            echo $token->getRaw();
        }
    }

    private function parseVariable($name)
    {
        return $this->variables[$name];
    }

    private function parseCode($code)
    {
        $codeScanner = new CodeScanner();
        $codeScanner->compile($code);
        while($codeScanner->next()->getType() !== 'eos') {
            $codeScanner->next();
        }
    }

    private function isVariable($name)
    {
        if (isset($this->variables[$name])) {
            return true;
        }
        return false;
    }
}