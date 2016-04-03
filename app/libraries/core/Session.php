<?php
/**
 * Class \Ecne\Classes\Session
 * @author John O'Grady
 * @date 06/07/15
 */

namespace Ecne\Library\Core;

class Session
{

    /**
     * @method init
     */
    public static function init()
    {
        session_start();
    }

    /**
     *
     * @method put
     * @access public
     * @param string $name
     * @param string $value
     * @return string
     *
     */
    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * @method exists
     * @param $name
     * @return bool
     */
    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }

    /**
     * @method delete
     * @param $name
     */
    public static function delete($name)
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * @method get
     * @param $name
     * @return bool
     */
    public static function get($name)
    {
        if (!empty($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return false;
        }
    }

    /**
     * @method flash
     * @param $name
     * @param string $string
     * @return bool
     */
    public static function flash($name, $string = '')
    {
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    }

    /**
     *  @method destroy
     */
    public static function destroy()
    {
        session_destroy();
    }
}    /** End Class Definition **/