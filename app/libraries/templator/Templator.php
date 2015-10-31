<?php

namespace Ecne\Templator;

use Ecne\Library\Core\Config;

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
     * @param $layout
     * @param $view
     * @param $data
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
        $conditionalMatches = array();
        preg_match_all('/[-]{2}[{]{2}[\s\S]*?[}]{2}[-]{2}/', $this->html, $conditionalMatches);
        $conditionalMatches = $conditionalMatches[0];
        $this->parseConditions($conditionalMatches);

        preg_match_all('/[{]{2}[\s]{0,}[a-zA-Z_0-9]+[\s]{0,}[}]{2}/', $this->html, $this->variables);
        $this->variables = $this->variables[0];
        $this->parseVariables();
    }

    private function parseConditions($conditions){
        foreach($conditions as $condition) {
            $ifStatement = null;
            $trueBlock = null;
            $elseBlock = null;
            $ifBlockStartPos = 0;
            $ifBlockEndPos = 0;
            $elseBlockStartPos = 0;
            $elseBlockEndPos = 0;
            if (preg_match('/[:]{1}[a-z]+/', $condition)) {
                preg_match('/[:]{1}[a-z]+/', $condition, $firstBlock, PREG_OFFSET_CAPTURE);
                $ifStatement = str_replace(':', '', $firstBlock[0][0]);
                $ifBlockStartPos = $firstBlock[0][1] + strlen($firstBlock[0][0]);
                if (preg_match('/[:]{2}/', $condition)) {
                    preg_match('/[:]{2}/', $condition, $secondBlock, PREG_OFFSET_CAPTURE);
                    $ifBlockEndPos = $secondBlock[0][1];
                    $elseBlockStartPos  = $secondBlock[0][1];
                    $elseBlockEndPos = strlen($condition);
                } else {
                    $ifBlockEndPos = strlen($condition);
                }
            }
            $output = null;
            if ($this->getGlobalVariable($ifStatement)) {
                $output = $this->escapeConditional(substr($condition, $ifBlockStartPos, ($ifBlockEndPos - $ifBlockStartPos)));
            } else {
                $output = $this->escapeConditional(substr($condition, $elseBlockStartPos, ($elseBlockEndPos - $elseBlockStartPos)));
            }
            $this->html = str_replace($condition, $output, $this->html);
        }
    }
    /**
     *  
     */
    private function parseVariables()
    {
        foreach($this->variables as $variable){
            # check for global variables
            if (preg_match('/[_]{2}[A-Z]+/', $variable)) {
                $var = $this->trim(preg_replace('/[_]{2}/', '', $variable));
                $this->html = preg_replace('/[{]{2}[\s]{0,}[_]{2}['.$var.']+[\s]{0,}[}]{2}/', $this->getGlobalVariable($var), $this->html);
                continue;
            }
            $variable = $this->trim($variable);
            if (isset($this->data[$variable])) {
                $this->html = preg_replace('/[{]{2}[\s]{0,}'.$variable.'[\s]{0,}[}]{2}/', $this->data[$variable], $this->html);
            } else {
                $this->html = preg_replace('/[{]{2}[\s]{0,}['.$variable.']+[\s]{0,}[}]{2}/', '', $this->html);
            }
        }
    }

    /**
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
        $this->data = array();
    }
    /**
     *  @param name|string
     *  @return mixed
     */
    private function getGlobalVariable($name)
    {
        return Config::get($name);
    }
    /**
     *  @param $variable|string
     *  @return string
     */
    private function trim($variable)
    {
        return trim(str_replace('}}', '', str_replace('{{', '', $variable)));
    }
    /**
     *  @param $conditional|string
     *  @return string
     */
    private function escapeConditional($conditional)
    {
         return str_replace('}}--', '', str_replace('--{{', '', str_replace('::', '', $conditional)));
    }
    /**
     *
     *  @param $string|string
     */
    public function debug($string)
    {
        if (is_array($string)) {
            foreach($string as $str) {
                echo '<pre>';
                echo $str;
                echo '</pre>';
            }
        } else {
            echo '<pre>';
            echo $string;
            echo '</pre>';
        }
    }
}
