<?php

namespace Ecne\Core;

use Ecne\Controller\IndexController;
use Ecne\Controller\ErrorController;

class BootStrap
{
    private $controller = null;
    private $controllerName = null;
    private $controllerAction = null;
    private $controllerParameter = null;

    /**
     * @method getMethod
     * @return string
     */
     public function getMethod()
     {
         return ($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
     }
     /**
      * @method loadController
      * @param $url|array
      * @return void
      */
     private function loadController($url)
     {
         if (count($url)) {
             # controller specified
             $this->controllerName = ucfirst($url[0]) . 'Controller';
             # check if controller exists
             if (file_exists(CONTROLLER_PATH . $this->controllerName . '.php')) {
                 $this->controllerName = "Ecne\\Controller\\" . $this->controllerName;
                 $this->controller = new $this->controllerName;
                 if (isset($url[1])) {
                     # check if action exists in controller
                     if ( method_exists($this->controllerName, $url[1]) ) {
                            $this->controllerAction = $url[1];
                            if (isset($url[2])) {
                                $this->controllerParameter = $url[2];
                            }
                     } else {
                         # if action doesn't exist call index and pass params to method
                         $this->controllerAction = 'index';
                         $this->controllerParameter = $url[1];
                     }
                 } else {
                     $this->controllerAction = 'index';
                 }
             } else if (method_exists('Ecne\Controller\IndexController', $url[0])) {
                 $this->controllerName = 'IndexController';
                 $this->controllerAction = $url[0];
                 $this->controller = new IndexController();
             } else {
                 # controller doesn't exist. Load error controller
                 $this->controllerName = 'ErrorController';
                 $this->controllerAction = 'index';
                 $this->controller = new ErrorController();
             }
         } else {
             # no controller or action specified
             $this->controllerName = 'IndexController';
             $this->controllerAction = 'index';
             $this->controller = new IndexController();
         }
         if ($this->controllerName) {
             $this->controller->{$this->controllerAction}($this->controllerParameter);
         }
     }
     /**
      * @method parseURL
      * @param $url|array
      * @return array
      */
     private function parseURL($url)
     {
         return array_values(array_filter(explode('/', preg_replace("/[^a-zA-Z0-9-_\/]/", '', $url))));
     }
     /**
      * @method parse
      * @param $url|array
      * @param $method|string
      * @return void
      */
     public function parse($url, $method)
     {
         if ($url) {
             $this->loadController($this->parseURL($url));
         }
     }
}
