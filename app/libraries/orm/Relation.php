<?php
/**
 * @author John O'Grady
 * @date: 15/01/2017
 */

namespace Ecne\ORM;

use Ecne\Model\Model;

class Relation
{
    /**
     * @var string
     */
    private $primaryClass;
    /**
     * @var string
     */
    private $foreignClass;
    /**
     * @var int
     */
    private $primaryKey;
    private $foreignKey;

    /**
     * Relation constructor.
     * @param $primaryClass
     * @param $primaryKey
     * @param $foreignClass
     * @param $foreignKey
     */
    public function __construct($primaryClass, $primaryKey, $foreignClass, $foreignKey)
    {

        /**
         * @note use reflection class to remove name space from class name
         */
        $primaryReflect=new \ReflectionClass($primaryClass);
        $this->primaryClass=$primaryReflect->getShortName();
        $this->primaryKey=$primaryKey;

        /**
         * @note use reflection class to remove name space from class name
         */
        $foreignReflect=new \ReflectionClass($foreignClass);
        $this->foreignClass=$foreignReflect->getShortName();
        $this->foreignKey=$foreignKey;
    }

    /**
     * @return mixed
     */
    public function fetch()
    {
        return $models=Model::type("{$this->foreignClass}")->eq($this->foreignKey, $this->primaryKey)->all();
    }
}