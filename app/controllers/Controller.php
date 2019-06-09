<?php
/**
 *  @class Controller
 */

namespace Ecne\Controller;

use Ecne\BootStrap\View;
use Ecne\Model\Model;

class Controller
{
    /**
     * @var String $controllerName
     */
    protected $controllerName;
    /**
     * @var View $view
     */
    protected $view;
    /**
     * @var Model $model
     */
    protected $model;

    /**
     * @param String $controllerName
     */
    public function __construct($controllerName = 'index')
    {
        $this->view = new View();
        $this->controllerName = $controllerName;
    }

    public function index()
    {
        $this->view->render('index/index', [
            'title'=> 'Home'
        ]);
    }
}
