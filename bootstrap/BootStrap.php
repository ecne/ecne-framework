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
            /**
             * @note controller specified
             */
            $this->controllerName = ucfirst($url[0]) . 'Controller';
            /**
             * @note check if controller exists
             */
            if (file_exists(CONTROLLER_PATH . $this->controllerName . '.php')) {
                $this->controllerName = "Ecne\\Controller\\" . $this->controllerName;
                $this->controller = new $this->controllerName;
                if (isset($url[1])) {
                    /**
                     * @note check if action exists in controller
                     */
                    if (method_exists($this->controllerName, $url[1]) ) {
                        $this->controllerAction = $url[1];
                        if (isset($url[2])) {
                            $parameters=(array_slice($url, 2, count($url)-2));
                            $assocParameters=array();

                            for($i=0;$i<=count($parameters)-1; $i+=2) {
                                $assocParameters[$parameters[$i]]=$parameters[$i+1];
                            }
                            $this->controllerParameter=$assocParameters;
                        }
                    } else {
                        /**
                         * @note if action doesn't exist call index and pass params to method
                         */
                        $this->controllerAction = 'index';

                        $parameters=(array_slice($url, 1, count($url)-1));
                        $assocParameters=array();

                        for($i=0;$i<count($parameters)-1; $i+=2) {
                            $assocParameters[$parameters[$i]]=$parameters[$i+1];
                        }
                        $this->controllerParameter=$assocParameters;
                    }
                } else {
                    $this->controllerAction = 'Index';
                }
            } else if (method_exists('Ecne\Controller\IndexController', $url[0])) {
                $this->controllerName = 'IndexController';
                $this->controllerAction = $url[0];
                $this->controller = new IndexController();
            } else {
                # controller doesn't exist. Load error controller
                $this->controllerName = 'ErrorController';
                $this->controllerAction = 'Index';
                $this->controller = new ErrorController();
            }
        } else {
            # no controller or action specified
            $this->controllerName = 'IndexController';
            $this->controllerAction = 'Index';
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
        return (array_values(array_filter(explode('/', preg_replace("/[^a-zA-Z0-9-_\/]/", '', $url)))));
    }
    /**
     * @method parse
     * @param $url|array
     * @return void
     */
    public function parse($url)
    {
        if ($url) {
            $this->loadController($this->parseURL($url));
        }
    }
}