<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/01/2017
 * Time: 19:29
 */

namespace Ecne\Model;


class Blog extends Model
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * @param null $table
     * @param null $cols
     * @return mixed
     */
    public static function select($table=null, $cols=null)
    {
        return parent::select('Blog', $cols);
    }
}