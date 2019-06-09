<?php
/**
 *  @class View
 */

namespace Ecne\Router;

class Route
{
    protected $method;
    protected $uri;
    protected $controllerName;
    protected $controller;
    protected $action;
    protected $actionParameters;

    public function __construct($method, $uri, $controllerName, $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->controllerName = $controllerName;
        $this->action = $action;
    }

    public function getMethod($method = null)
    {
        if ($method === null) return $this->method;
        else $this->method = $method;
    }

    public function getURI($uri = null)
    {
        if ($uri === null) return $this->uri;
        else $this->uri = $uri;
    }

    public function getControllerName($controllerName=null)
    {
        if ($controllerName === null) return $this->controllerName;
        else $this->controllerName = $controllerName;
    }

    public function getController($controller=null)
    {
        if ($controller === null) return $this->controller;
        else $this->controller = $controller;
    }

    public function getAction($action=null)
    {
        if ($action === null) return $this->action;
        else $this->action == $action;
    }

    public function getActionParameters($actionParameters=null)
    {
        if ($actionParameters === null) return $this->actionParameters;
        else $this->actionParameters = $actionParameters;
    }
}
