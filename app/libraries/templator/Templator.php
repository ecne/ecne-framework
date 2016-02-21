<?php

namespace Ecne\Templator;

use Ecne\Core\View;

class Templator
{
    /**
     * @var string
     */
    private static $extension = '.htm';
    /**
     * @var array
     */
    private $data = array();
    /**
     * @var string
     */
    private $view;
    /**
     * @var \Ecne\Templator\Scanner $scanner
     */
    private $scanner;
    /**
     * @var \Ecne\Templator\Lexer $lexer
     */
    private $lexer;
    /**
     * @var string
     */
    private $layout;

    /**
     * @param $view
     */
    function __construct($view)
    {
        $this->view = $view;
        $this->scanner = new TemplateScanner();
    }

    public static function debug($message)
    {
        echo '<pre>';
        echo htmlspecialchars($message);
        echo '</pre><br><br>';
    }

    /**
     * @param $data
     */
    public function render($data)
    {
        $this->data = $data;
        $headerTokens = array();
        if (file_get_contents($this->view)) {
            // get output of layout/header.inc
            $this->layout = file_get_contents(VIEW_PATH.'layout'.self::$extension);
            $this->scanner->render($this->layout);
            while($this->scanner->next()->getType() !='eos') {
                $this->scanner->next();
            }
            foreach($this->scanner->getTokens() as $tk) {
                //Templator::debug($tk->getRaw());
            }
            $headerTokens = $this->scanner->getTokens();
            $this->scanner->setTokens(array());
            // get output of view
            $input = file_get_contents($this->view);
            $this->scanner->render($input);
            while ($this->scanner->next()->getType() !== 'eos') {
                $this->scanner->next();
            }
        }
        $this->lexer = new Lexer($this->scanner->getTokens(), $headerTokens);
        $this->lexer->go($this->data);

        foreach ($this->lexer->getTokens() as $token) {
            if ($token->getType() === 'eos') {
                continue;
            }
        }
        $this->lexer->build();
    }
}
