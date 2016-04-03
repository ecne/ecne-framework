<?php

/**
 * Class \Ecne\Classes\Hash
 * @author John O'Grady
 * @date 22/06/15
 */

namespace Ecne\Library\Core;

class Hash
{
    /**
     * @method make
     * @access public
     * @param $string
     * @param string $salt
     * @return string
     */
    public static function make($string, $salt)
    {
        return hash('sha256', $string . $salt);
    }

    /**
     * @method salt
     * @access public
     * @return string
     */
    public static function salt()
    {
        $length = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_ECB);
        return mcrypt_create_iv($length);
    }

    /**
     * @method unique
     * @access public
     * @return string
     */
    public static function unique()
    {
        return self::make(uniqid(), self::salt());
    }
} #End Class Definition