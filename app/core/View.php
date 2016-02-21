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
     * @param $view
     */
    function __construct($view)
    {
        $this->templator = new Templator((VIEW_PATH . $view . '.htm'));
    }

    /**
     *  @method render
     *  @param $data|array
     *  @return void
     */
     public function render($data = array())
     {
         $this->templator->render($data);
     }
}
