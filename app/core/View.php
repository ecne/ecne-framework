<?php

namespace Ecne\Core;

use Ecne\Form\Form;
use \Ecne\Library\Core\File;
use \Ecne\Library\Core\Helper;

class View
{
    /**
     * @var string
     */
    private $view='';
    /**
     * @var array
     */
    private $data=array();
    /**
     * @var string
     */
    protected $output;


    function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function loginForm()
    {
        return $this->loginForm;
    }

    /**
     * @method form
     * @access public
     * @return \Ecne\Form\Form
     */
    public function form()
    {
        return $this->form;
    }

    public function data($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    /**
     * @param $view
     * @param array $data
     * @throws \Exception
     */
     public function render($view = null, $data = array())
     {
         $this->view = $view;
         $this->data = $data;
         if (File::exists(VIEW_PATH.$this->view.'.php')) {
             ob_start();
             include_once VIEW_PATH.'layout.php';
             include_once VIEW_PATH . $this->view.'.php';
             include_once VIEW_PATH.'footer.php';
             $this->output = ob_get_contents();
             ob_end_clean();
             echo $this->output;
         } else {
             throw new \Exception('View {$view} does not exist');
         }
     }
}
