<?php

/**
 *  Class IndexController
 *
 *
 *
 */

namespace Ecne\Controller;

class IndexController extends Controller
{
    /**
     *  @method index
     *  @param $parameters|array
     */
    public function index($parameters = array())
    {
        $this->view->render('index/index', [
            'title'=>'Home'
        ]);
    }
    /**
     *  @method about
     *  @return void
     */
    public function about()
    {
        $this->view->render('index/about', [
            'title'=> 'About Us'
        ]);
    }
    /**
     *  @method contact
     *  @return void
     */
    public function contact()
    {
        $this->view->render('index/contact', [
            'title'=>'Contact Us'
        ]);
    }
}   # End Class Definition
