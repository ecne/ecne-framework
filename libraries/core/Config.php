<?php

/**
 * Class \Ecne\Classes\Config
 * @author John O'Grady
 * @date 21/06/15
 */

namespace Ecne\Library\Core;

class Config
{
    /**
     * @method get
     * @param $path|string
     * @return bool
     */
    public static function get($path = null)
    {
        if ($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);
            foreach ($path as $bit) {
                if (isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }
            if (isset($config)) {
                return $config;
            } else {
                return false;
            }
        }
    }
} #End Class Definition
