<?php

namespace Ecne\Controller;

use Ecne\Core\View;
use Ecne\Model\Model;

class Controller
{
    protected $controllerName;
    protected $view;
    protected $model;
    /**
     *  @method __construct
     *  @return void
     */
    public function __construct($controllerName = 'index')
    {
        $this->controllerName = $controllerName;
        $this->view = new View();
    }
    /**
     *  @method index
     *  @param $paramaters|array
     *  @return void
     */
    public function index($parameters = array())
    {
        $this->view->render('index/index', [
            'title'=> 'TestTitle'
        ]);
    }
}
