<?php

namespace Ecne\Controller;

use Ecne\Core\View as View;
use Ecne\Model\Model as Model;

class Controller
{
    /**
     * @var string
     */
    protected $controllerName;
    /**
     * @var View
     */
    protected $view;
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param string $controllerName
     */
    public function __construct($controllerName = 'index')
    {
        $this->controllerName = $controllerName;
    }
    /**
     *  @method index
     *  @return void
     */
    public function index()
    {
        $this->view = new View('index/index');
        $this->view->render([
            'title'=> 'Home'
        ]);
    }
}
