<?php
/**
 *  @class Application
 */

namespace Ecne\BootStrap;

class Application
{
    /**
     *  @var Application $instance
     */
    private static $instance = null;
    /**
     *  @var Router $router
     */
    protected $router;

    /**
     *  @note empty constructor, and clone method to force singleton type
     */
    private function __construct(){}
    private function __clone(){}

    /**
     *  @return Application
     */
    public static function instance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     *  @param Router $router
     */
    public function router(Router $router=null)
    {
        if ($router === null) {
            return $this->router;
        }
        $this->router = $router;
        return $this;
    }
}
