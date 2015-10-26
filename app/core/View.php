<?php

namespace Ecne\Core;

use Ecne\Templator\Templator;

class View
{
    /**
     *  @var $templator|Templator
     */
    private $templator = null;

    /**
     *  @method __construct
     *  @return void
     */
    function __construct()
    {
        $this->templator = new Templator();
    }

    /**
     *  @method render
     *  @param $view|string
     *  @param $data|array
     *  @return void
     */
     public function render($view, $data = array())
     {
         $this->templator->render((VIEW_PATH . 'layout.htm'), (VIEW_PATH . $view . '.htm'), $data);
         //$this->output = ob_get_contents();
         //ob_end_clean();
     }
}
