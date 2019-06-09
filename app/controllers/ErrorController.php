<?php
/**
 *  @class ErrorController
 */

namespace Ecne\Controller;

class ErrorController extends Controller
{
    public function index()
    {
        $this->view->render('error/index', [
            'title'=>'Error 404'
        ]);
    }
}   #End Class Definition
