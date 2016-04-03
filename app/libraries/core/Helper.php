<?php

/**
 * Class \Ecne\Classes\Helper
 * @author John O'Grady
 * @date 06/07/15
 */

namespace Ecne\Library\Core;

class Helper
{
    /**
     * @method replaceDirSeparator
     * @access public
     * @param $dir
     * @return mixed
     */
    public static function replaceDirSeparator($dir)
    {
        if (preg_match('|/|', $dir)) {
            return preg_replace('|/|', '\\', $dir);
        } else {
            return $dir;
        }
    }

    public static function returnDoubleQuotes($string)
    {
        return '"' . $string . '"';
    }
}