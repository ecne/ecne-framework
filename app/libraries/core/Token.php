<?php

/**
 * Class Ecne\Classes\Token
 * @author John O'Grady
 * @date 06/07/15
 */

namespace Ecne\Library\Core;

class Token
{
    /**
     * @method generate
     * @access public
     * @return string
     */
    public static function generate()
    {
        return Session::put(Config::get('token/name'), md5(uniqid()));
    }

    /**
     * @method check
     * @access public
     * @param $token
     * @return bool
     */
    public static function check($token)
    {
        $tokenName = Config::get('token/name');
        if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        } else {
            return false;
        }
    }
}   /** End Class Definition **/