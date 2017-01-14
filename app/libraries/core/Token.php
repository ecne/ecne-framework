<?php

/**
 * Class Ecne\Library\Core\Token
 * @author John O'Grady
 * @date 06/07/15
 */

namespace Ecne\Library\Core;

class Token
{
    /**
     * @access public
     * @param $name
     * @return string
     */
    public static function generate($name)
    {
        return Session::put($name, md5(uniqid()));
    }

    /**
     * @access public
     * @param $name
     * @param $token
     * @return bool
     */
    public static function check($name,$token)
    {
        if (Session::exists($name) && $token === Session::get($name)) {
            Session::delete($name);
            return true;
        } else {
            return false;
        }
    }
}   /** End Class Definition **/