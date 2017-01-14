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
     * @access public
     * @param $key
     * @return bool
     */
    public static function exists($key)
    {
        return (isset($_GET[$key]) || isset($_POST[$key])) ? true : false;
    }

    /**
     * @access public
     * @return bool
     */
    public static function post()
    {
        return (count($_POST)) ? true : false;
    }

    /**
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
     * @access public
     * @param $string
     * @return mixed
     */
    public static function clean($string)
    {
        return strip_tags(htmlspecialchars(preg_replace("/[^a-zA-Z0-9-_\/]/", '', $string)));
    }

    /**
     * @access public
     * @param $string
     * @return string
     */
    public static function cleanUserInput($string)
    {
        return strip_tags(htmlspecialchars($string));
    }

    /**
     * @access public
     * @param $name
     * @return bool
     */
    public static function secure($name)
    {
        if (self::post()) {
            if (self::get($name)) {
                if (Token::check($name, self::get($name))) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @access public
     * @return void
     */
    public static function clear()
    {
        $_POST = array();
    }
} /** End Class Definition **/