<?php
/**
 *  @class Router
 */

namespace Ecne\Router;

use Ecne\BootStrap\Application;
use Ecne\Http\Request;
use Ecne\Library\Core\File;

class Router
{
    /**
     *  @var Application $app
     */
    protected $app;

    /**
     *  @var Route[] $routes
     */
    protected $routes = [];

    /**
     * @param Application $app
     * @param String $routeTable
     */
    public function __construct(Application $app, $routeTable)
    {
        $this->app = $app;
        if (file_exists($routeTable))
        {
            preg_match_all ('/\@(.*)\)/', file_get_contents($routeTable), $matches);
            foreach ($matches[0] as $match)
            {
                    preg_match_all('/^\@(.*)\(\'(.*)\'\,(.*)\'(.*)\@(.*)\'\)/', $match, $matches);
                    # 0 - whole string, 1 method, 2 - url, 3 - controller, 4 - action
                    $this->addRoute(new Route($matches[1][0], $matches[2][0], $matches[4][0], $matches[5][0]));
            }
        }
        return $this->routes;
    }

    /**
     *  @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    /**
     *  @param String $method
     *  @param String $uri
     *  @return Route
     */
    private function findRoute($method, $uri)
    {
        $requestedRouteDefinition = explode('/', $uri);
        unset($requestedRouteDefinition[0]);
        $requestedRouteDefinition = array_values($requestedRouteDefinition);
        foreach ($this->routes as $route) {
            if ($method === $route->getMethod()) {
                $routeDefinition = explode('/', $route->getURI());
                unset($routeDefinition[0]);
                $routeDefinition = array_values($routeDefinition);
                if (count($requestedRouteDefinition) === count($routeDefinition)) {
                    for ($i = 0; $i <= count($requestedRouteDefinition) / 2; $i += 2) {
                        if ($requestedRouteDefinition[$i] !== $routeDefinition[$i]) {
                            continue;
                        }
                        return $route;
                    }
                }
            }
        }
    }

    /**
     *  @param Request $request
     */
    public function route(Request $request)
    {
        if ($route = $this->findRoute($request->getMethod(), $request->getURI())) {
            if (File::exists(CONTROLLER_PATH . ucfirst($route->getControllerName()) . 'Controller.php')) {
                $route->getControllerName("Ecne\\Controller\\" . ucfirst($route->getControllerName()) . 'Controller');
                $controller = $route->getControllerName();
                $route->getController(new $controller);
                if (method_exists($route->getControllerName(), $route->getAction())) {
                    $explodedURL = explode('/', $request->getURI());
                    unset($explodedURL[0]);
                    $explodedURL = array_values($explodedURL);
                    if (count($explodedURL) > 1)
                    $route->getActionParameters($explodedURL);
                    $assocParameters = [];
                    if ($route->getActionParameters() !== null) {
                        for ($i = 0; $i < count($route->getActionParameters()); $i += 2) {
                            $assocParameters[] = $route->getActionParameters()[$i+1];
                        }
                    }
                }
                if ($route->getControllerName()) {
                    call_user_func_array(array($route->getController(), $route->getAction()), $assocParameters);
                }
            }
        } else {

        }
    }
}
