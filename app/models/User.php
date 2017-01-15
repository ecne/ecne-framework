<?php

namespace Ecne\Model;

class User extends Model
{
    /**
     * @var array
     */
    protected static $relations_=array(
        'blogposts'=>array('\Ecne\Model\Blog', 'PostedBy')
    );

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param null $table
     * @param null $cols
     * @return mixed
     */
    public static function select($table=null, $cols=null)
    {
        return parent::select('User', $cols);
    }
}   #End Class Definition
