<?php

/**
 *  Class \Ecne\Classes\Date
 * @author John O'Grady
 * @date 22/06/15
 */

namespace Ecne\Library\Core;

class Date
{
    /**
     * @var array
     */
    private static $ordinals = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');

    /**
     * @method getDifference
     * @access public
     * @param $date1
     * @param $date2
     */
    public static function getDifference($date1, $date2)
    {

    }

    /**
     * @method getDifferenceFromNow
     * @access public
     * @param $date
     * @return string
     */
    public static function getDifferenceFromNow($date)
    {
        $then = date_create($date);
        $now = date_create(date('Y-m-d H:i:s'));
        $diff = date_diff($then, $now);
        if ($diff->format('%y') >= 1) {
            return $diff->format("%y years ago");
        } elseif ($diff->format('%m') >= 1) {
            if ($diff->format('%m') < 2) {
                return $diff->format('%m month ago');
            }
            return $diff->format("%m months ago");
        } elseif ($diff->format('%d') >= 1) {
            switch (true) {
                case ($diff->format('%d') >= 14 && $diff->format('%d') < 15):
                    return "about a fortnight ago";
                    break;
                case ($diff->format('%d') >= 7 && $diff->format('%d') < 8):
                    return "about a week ago";
                    break;
                case ($diff->format('%d') > 5 && $diff->format('%d') < 7):
                    return "less than a week ago";
                    break;
                case ($diff->format('%d') < 2):
                    return $diff->format('%d day ago');
                    break;
                default:
                    return $diff->format('%d days ago');
                    break;
            }
        } else if ($diff->format('%h') >= 1) {
            return $diff->format("%h hours ago");
        } elseif ($diff->format('%i') >= 1) {
            if ($diff->format('%i') >= 30 && $diff->format('%i') <= 35) {
                return "half an hour ago";
            } elseif ($diff->format('%i') < 2) {
                return $diff->format("%i minute ago");
            }
            return $diff->format("%i minutes ago");
        } elseif ($diff->format('%s') >= 1) {
            if ($diff->format('%s') < 2) {
                return $diff->format("%s second ago");
            } else {
                return $diff->format("%s seconds ago");
            }
        }
    }

    /**
     * @method getOrdinal
     * @access public
     * @param $num
     * @return string
     */
    public static function getOrdinal($num)
    {
        if (is_int($num)) {
            if ((($num % 100) >= 11) && (($num % 100) <= 13)) {
                return 'th';
            } else {
                return self::$ordinals[$num % 10];
            }
        } else {
            return false;
        }
    }
} #End Class Definition