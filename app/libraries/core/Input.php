<?php

/**
 * Class \Ecne\Classes\Input
 * @author John O'Grady
 * @date 06/07/15
 */

namespace Ecne\Library\Core;

class Input
{
    /**
     * @method exists
     * @access public
     * @param $key
     * @return bool
     */
    public static function exists($key)
    {
        return (isset($_GET[$key]) || isset($_POST[$key])) ? true : false;
    }

    /**
     * @method post
     * @access public
     * @return bool
     */
    public static function post()
    {
        return (count($_POST)) ? true : false;
    }

    /**
     * @method get
     * @access public
     * @param $key
     * @return string
     */
    public static function get($key)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        } else if (isset($_POST[$key])) {
            return $_POST[$key];
        } else {
            return '';
        }
    }

    /**
     * @method clean
     * @access public
     * @param $string
     * @return mixed
     */
    public static function clean($string)
    {
        return strip_tags(htmlspecialchars(preg_replace("/[^a-zA-Z0-9-_\/]/", '', $string)));
    }

    /**
     * @method cleaUserInput
     * @access public
     * @param $string
     * @return string
     */
    public static function cleanUserInput($string)
    {
        return strip_tags(htmlspecialchars($string));
    }

    public static function secure()
    {
        if (self::post()) {
            if (self::get(Config::get('token/name'))) {
                if (\Classes\Token::check(self::get(Config::get('token/name')))) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @method clear
     * @access public
     */
    public static function clear()
    {
        $_POST = array();
    }
} /** End Class Definition **/