<?php

namespace Ecne\Templator;

class Lexer
{
    /**
     * @var Token[]
     */
    private $tokens = array();
    /**
     * @var Token[]
     */
    private $parsedTokens = array();
    /**
     * @var Token[]
     */
    private $layoutTokens = array();
    /**
     * @var Token[]
     */
    private $bodyTokens = array();
    /**
     * @var array
     */
    private $variables = array();

    /**
     * @param $tokens
     * @param $layoutTokens
     */
    function __construct($tokens = array(), $layoutTokens = array())
    {
        $this->bodyTokens = $tokens;
        $this->layoutTokens = $layoutTokens;
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

    /**
     * @param $variables
     */
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
            } else if ($token->getType() === Token::$TYPE_EXTENDS) {
                continue;
            } else if ($token->getType() === Token::$TYPE_EOS) {
                continue;
            }
            $this->parsedTokens[] = $token;
        }
    }

    private function buildHead()
    {
        $ishead = true;
        $x = 0;
        foreach($this->layoutTokens as $token) {
            if ($token->getType() === 'extends') {
                break;
            } else {
                $this->tokens[] = $token;
                unset($this->layoutTokens[$x]);
            }
            $x++;
        }
    }

    public function build()
    {
        $this->buildHead();
        foreach($this->bodyTokens as $token) {
            $this->tokens[] = $token;
        }
        foreach($this->layoutTokens as $ltk) {
            $this->tokens[] = ($ltk);
        }
        $this->parseTokens();
        foreach($this->parsedTokens as $tk) {
            echo $tk->getRaw();
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    private function parseVariable($name)
    {
        return $this->variables[$name];
    }

    /**
     * @param $code
     */
    private function parseCode($code)
    {
        $codeScanner = new CodeScanner();
        $codeScanner->compile($code);
        while($codeScanner->next()->getType() !== 'eos') {
            $codeScanner->next();
        }
    }

    /**
     * @param $name
     * @return bool
     */
    private function isVariable($name)
    {
        if (isset($this->variables[$name])) {
            return true;
        }
        return false;
    }
}