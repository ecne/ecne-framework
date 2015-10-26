<?php

namespace Ecne\Templator;

class Templator
{
    private $layout = null;
    private $view = null;
    private $html = null;
    private $data = array();
    private $variables = array();

    public function __construct()
    {
    }
    /**
     *  @method render
     *  @param $output|string
     *  @param $data|array
     *  @return void
     */
    public function render($layout, $view, $data)
    {
        $this->data = $data;
        $this->html = $this->sort($layout, $view);
        $this->parse();
        $this->splash($this->html);
    }
    /**
     *  @method parse
     *  @return void
     */
    private function parse()
    {
        $start = null;
        $finish = null;
        for($i = 0; $i < strlen($this->html); $i++) {
            if (substr($this->html, $i, 2) == '{{') {
                $start = $i;
            }
            if ($start && substr($this->html, $i, 2) == '}}') {
                $finish = $i+2;
                $this->variables[] = trim(substr($this->html, (int)$start, (int)($finish - $start)));
                $start = null;
                $finish = null;
            }
        }
        foreach($this->variables as $variable) {
            $var = str_replace('{{', '', $variable);
            $var = str_replace('}}', '', $var);
            $var = trim($var);
            # check for global variables
            if (preg_match('/[_]{2}[A-Z]+/', $var)) {
                $var = preg_replace('/[_]{2}/', '', $var);
                $this->html = preg_replace('/[{]{2}[\s]{0,}[_]{2}['.$var.']+[\s]{0,}[}]{2}/', $this->getGlobalVariable($var), $this->html);
                echo $var;
                continue;
            }
            if (isset($this->data[$var])) {
                $this->html = preg_replace('/[{]{2}[\s]{0,}['.$var.']+[\s]{0,}[}]{2}/', $this->data[$var], $this->html);
            } else {
                $this->html = preg_replace('/[{]{2}[\s]{0,}['.$var.']+[\s]{0,}[}]{2}/', '', $this->html);
            }
        }
    }
    /**
     *  @access private
     *  @method sort
     *  @param $layout|string
     *  @param $view|string
     *  @return string
     */
    private function sort($layout, $view)
    {
        $layoutContent = file_get_contents($layout);
        $viewContent = file_get_contents($view);
        $header = null;
        $footer = null;
        $midSection = $viewContent;
        if (strpos($layoutContent, '--[[')) {
            $header = substr($layoutContent, 0, strpos($layoutContent, '--[['));
            if (strpos($layoutContent, ']]--')) {
                $footer = substr($layoutContent, strpos($layoutContent, ']]--') +4);
            } else {}
        } else {}
        return $header . $midSection . $footer;
    }
    /**
     *  @access private
     *  @method splash
     *  @param $html|string
     *  @return void
     */
    private function splash($html)
    {
        ob_start();
        echo $html;
        $output = ob_get_contents();
        ob_end_clean();
        echo $output;
    }
    /**
     *  @access private
     *  @method getGlobalVariable
     *  @param name|string
     *  @return mixed
     */
    private function getGlobalVariable($name)
    {
        return \Ecne\Library\Core\Config::get($name);
    }
}
