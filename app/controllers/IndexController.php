<?php

/**
 *  Class IndexController
 */

namespace Ecne\Controller;

use Ecne\Core\View;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     *  @method index
     *  @param $parameters|array
     */
    public function index($parameters = array())
    {
        $this->view->render('index/index', [
            'title'=>'Home',
        ]);
    }
}   # End Class Definition
