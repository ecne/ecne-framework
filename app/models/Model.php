<?php
/**
 * Class Model
 * @author John O'Grady
 * @date 21/06/2015
 */

namespace Ecne\Model;

use Ecne\ORM\DB\Database;
use Ecne\ORM\QueryBuilder;

/**
 * @property mixed database_
 */
class Model
{
    /**
     * @note static properties
     */
    protected static $table_;
    protected static $primaryKey_ = 'Id';

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder_;
    protected $new_ = true;

    public function __construct($id=null)
    {
        if (static::$table_ === null) {
            static::$table_ = get_class($this);
        }

        if ($id !== null) {
            $this->queryBuilder_ = new QueryBuilder($this->getType());
            $this->eq(self::$primaryKey_, $id)->limit(1);
            /**
             * @var \PDOStatement $query
             */
            $query = $this->queryBuilder_->go();
            if ($r = $query->fetch(PDO::FETCH_OBJ)) {
                $this->hydrateClass($r);
                $this->new_=false;
            }
            $query->closeCursor();
        }
    }

    /**
     * @param array|null $cols
     * @return mixed
     */

    public static function select($cols = null)
    {
        $caller = get_called_class();
        $callerClass = new $caller();
        $callerClass->queryBuilder_ = new QueryBuilder(self::$table_);
        if ($cols !== null) {
            $callerClass->queryBuilder->selectColumns($cols);
        }
        return $callerClass;
    }

    /**
     * @param $data
     * @return $this
     */
    public function hydrateClass($data)
    {
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
    /**
    *  @return $this
    */
    public function dispenseClass()
    {
        $caller = get_called_class();
        return new $caller();
    }

    /**
     * @note define entity type which will be used as the table's names
     *
    * @param $type
    * @return $this
    */
    public function type($type)
    {
      $this->queryBuilder_->type($type);
      return $this;
    }

    public function getType()
    {
        return static::$table_;
    }

    /**
     * @note filter where field equals value
     *
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function eq($field, $value)
    {
        $this->queryBuilder_->addWhere($field, '=', $value);
        return $this;
    }

    /**
     * @note filter where field does not equal value
     *
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function neq($field, $value)
    {
        $this->queryBuilder_->addWhere($field, '!=', $value);
        return $this;
    }

    /**
     * @note filter field where value less than
     *
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function lt($field, $value)
    {
        $this->queryBuilder_->addWhere($field, '<', $value);
        return $this;
    }

    /**
     * @note filter field where value less than or equal
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function lte($field, $value)
    {
        $this->queryBuilder_->addWhere($field, '<=', $value);
        return $this;
    }

    /**
     * @note filter field where value greater than
     *
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function gt($field, $value)
    {
        $this->queryBuilder_->addWhere($field, '>', $value);
        return $this;
    }

    /**
     * @note filter field where value greater than or equal
     *
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function gte($field, $value)
    {
        $this->queryBuilder_->addWhere($field, '>=', $value);
        return $this;
    }

    /**
     * @note filter field by values in list
     *
     * @param $field
     * @param $values
     * @return $this
     */
    public function in($field, $values)
    {
        $this->queryBuilder_->addWhere($field, 'IN', $values);
        return $this;
    }

    /**
     * @param $field
     * @param $values
     * @return $this
     */
    public function notIn($field, $values)
    {
        $this->queryBuilder_->addWhere($field, 'NOT IN', $values);
        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    public function like($field, $value)
    {
        $this->queryBuilder_->addWhere($field, 'LIKE', $value);
        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    public function notLike($field, $value)
    {
        $this->queryBuilder_->addWhere($field, 'NOT LIKE', $value);
        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function notNull($field)
    {
        $this->queryBuilder_->addWhere($field, 'IS NOT NULL', '');
        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function isNull($field)
    {
        $this->queryBuilder_->addWhere($field, 'IS NULL', '');
        return $this;
    }

    /**
     * @return $this
     */
    public function times()
    {
        $this->queryBuilder_->times();
        return $this;
    }

    /**
     * @return $this
     */
    public function plus()
    {
        $this->queryBuilder_->plus();
        return $this;
    }

    /**
     * @note limit results with optional offset
     *
     * @param int $limit
     * @param int|null $offset
     * @return $this
     */
    public function limit($limit, $offset = null)
    {
        $this->queryBuilder_->setLimit($limit, $offset);
        return $this;
    }

    /**
     * @note sort results
     *
     * @param $orderBy
     * @return $this
     * @internal param $col
     * @internal param $order
     *
     */
    public function sort($orderBy)
    {
        $this->queryBuilder_->orderBy($orderBy);
        return $this;
    }

    /**
     * @return null
     */
    public function save()
    {
        if ($this->queryBuilder_ === null) {
            $this->queryBuilder_ = new QueryBuilder($this->getType());
        }
        if ($this->new_) {
            $this->queryBuilder_->setQueryType(QueryBuilder::QUERY_TYPE_INSERT);
        } else {
            $this->queryBuilder_->setQueryType(QueryBuilder::QUERY_TYPE_UPDATE);
            $this->eq($this->getPrimaryKey(), $this->getPrimaryKeyValue());
        }

        $this->queryBuilder_->setEntityData($this->toAssocArray());
        $query = $this->queryBuilder_->go();

        if ($this->new_) {
            $primaryKey = $this->getPrimaryKey();
            if (!property_exists(get_called_class(), $primaryKey)) {
                $this->$primaryKey = DataBase::getLastInsertID();
            }
            $this->new_=false;
        }
        $query->closeCursor();
    }

    /**
     *
     */
    public function delete()
    {
        if (!$this->new_) {
            $this->eq($this->getPrimaryKey(), $this->getPrimaryKeyValue());
            $this->queryBuilder_->setQueryType(QueryBuilder::QUERY_TYPE_DELETE);
            $this->queryBuilder_->go();
        }
    }

    /**
     * @return array
     */
    public function toAssocArray()
    {
        $properties = array();
        foreach (get_object_vars($this) as $key => $value) {
            if ($key[(strlen($key)-1)] !== '_') {
                $properties[$key] = $value;
            }
        }
        return $properties;
    }

    /**
     * @return $this|null
     */
    public function one()
    {
        /**
         * @var \PDOStatement $query
         */
        $this->queryBuilder_->setLimit(1);
        $query = $this->queryBuilder_->go();
        $result = $query->fetch(\PDO::FETCH_OBJ);
        $one = null;
        if (count($result)) {
            $one = $this->dispenseClass()->hydrateClass($result);
            $primaryKey = $this->getPrimaryKey();
            $one->$primaryKey = $result->$primaryKey;
            $one->new_=false;
        }
        return $one;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        /**
         * @var \PDOStatement $query
         */
        $query = $this->queryBuilder_->go();
        return $query->fetchAll();
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return static::$primaryKey_;
    }

    /**
     * @return mixed|null
     */
    public function getPrimaryKeyValue()
    {
        $primaryKey = $this->getPrimaryKey();
        if (isset($this->$primaryKey)) {
            return $this->$primaryKey;
        } else {
            return null;
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * @param $name
     * @param $arg
     */
    public function __set($name, $arg)
    {
        $this->$name = $arg;
    }
} #End Class Definition
