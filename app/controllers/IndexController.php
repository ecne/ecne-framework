<?php

/**
 *  Class IndexController
 */

namespace Ecne\Controller;

use Ecne\Core\View as View;

class IndexController extends Controller
{
    /**
     *  @method index
     *  @param $parameters|array
     */
    public function index($parameters = array())
    {
        $this->view = new View('index/index');
        $this->view->render([
            'title'=>'Home',
        ]);
    }
}   # End Class Definition
