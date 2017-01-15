<?php

/**
 *  Class IndexController
 */

namespace Ecne\Controller;

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

    public function contact()
    {
        $this->view->render('index/contact', [
            'title'=>'Contact',
        ]);
    }

    public function about()
    {
        $this->view->render('index/about', [
            'title'=>'About',
        ]);
    }
}   # End Class Definition
