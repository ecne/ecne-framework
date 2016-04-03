<?php

/**
 * Class \Ecne\Classes\Redirect
 * @author John O'Grady
 * @date 06/07/15
 */

namespace Ecne\Library\Core;

class Redirect
{
    /**
     * @method to
     * @access public
     * @param null $location
     */
    public static function to($location = null)
    {
        if ($location) {
            header('Location:' . $location);
            exit();
        }
    }
}   /** End Class Definition **/