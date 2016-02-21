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
     * @param $view
     */
    function __construct($view)
    {
        $this->view = $view;
        $this->scanner = new TemplateScanner();
    }

    /**
     * @param $data
     */
    public function render($data)
    {
        $this->data = $data;
        if (file_get_contents($this->view)) {
            $input = file_get_contents(VIEW_PATH.'layout'.self::$extension) . file_get_contents($this->view);
            $this->scanner->render($input);
            while ($this->scanner->next()->getType() !== 'eos') {
                $this->scanner->next();
            }
        }
        $this->lexer = new Lexer($this->scanner->getTokens());
        $this->lexer->go($this->data);

        foreach ($this->lexer->getTokens() as $token) {
            if ($token->getType() === 'eos') {
                continue;
            }
        }
        $this->lexer->build();
    }
}
