<?php
/**
 *  @class View
 */

namespace Ecne\BootStrap;

use \Ecne\Library\Core\Helper;

class View
{
    /**
     *  @var String
     */
    private $view;
    /**
     *  @var Array
     */
    private $data;
    /**
     *  @var String
     */
    protected $output;

    /**
     *  @param String $data
     */
    public function data($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    /**
     *  @param String $view
     *  @param Array $data
     *  @throws \Exception
     */
     public function render($view = null, $data = array())
     {
         $this->view = $view;
         $this->data = $data;
     }
}
