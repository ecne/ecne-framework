<?php
/**
 *  @class BootStrap
 */

namespace Ecne\BootStrap;

use Ecne\Http\Request;
use Ecne\Router\Router;

class BootStrap
{
    /**
     *  @param Request $request
     */
    public function bootstrap(Request $request)
    {
        /**
         *  @var Application $app
         */
        $app = Application::instance();
        /**
         *  @var Router $router
         */
        $router = new Router($app, BASE_PATH . '/routes/routes.php');
        $router->route($request);
    }
}
