<?php
/**
 *  @class Controller
 */

namespace Ecne\Controller;

use Ecne\Core\View;
use Ecne\Model\Model;

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
        $this->view = new View();
        $this->controllerName = $controllerName;
    }
    /**
     *  @method index
     *  @return void
     */
    public function index()
    {
        $this->view->render('index/index', [
            'title'=> 'Home'
        ]);
    }
}
