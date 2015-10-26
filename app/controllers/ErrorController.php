<?php

namespace Ecne\Controller;

class ErrorController extends Controller
{
    /**
     *  @method index
     *  @param $parameters|array
     *  @return void
     */
    public function index($parameters = array())
    {
        $this->view->render('error/index', [
            'title'=>'Error 404',
            'error.status'=>404
        ]);
    }
}   #End Class Definition
