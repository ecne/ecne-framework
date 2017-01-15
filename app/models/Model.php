<?php
/**
 * @class Model
 * @author John O'Grady
 * @date 21/06/2015
 */

namespace Ecne\Model;

use Ecne\ORM\DB\DataBase;
use Ecne\ORM\QueryBuilder;
use Ecne\ORM\Relation;
use PDO;

class Model
{
    /**
     * @var string $table_
     */
    protected static $table_;
    /**
     * @var array
     */
    protected static $relations_=[];
    /**
     * @var string
     */
    protected static $primaryKey_ = 'Id';
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder_;
    /**
     * @var bool
     */
    protected $new_ = true;

    /**
     * Model constructor.
     * @param null $id
     */
    public function __construct($id=null)
    {
        if (static::$table_ === null) {
            /**
             * @note use reflection class to remove name space from class name
             */
            $reflect = new \ReflectionClass($this);
            static::$table_ = $reflect->getShortName();
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
     * @param $table
     * @param array|null $cols
     * @return mixed
     */
    public static function select($table, $cols = null)
    {
        self::$table_=$table;
        $caller = get_called_class();
        $callerClass=new $caller();
        $callerClass->queryBuilder_=new QueryBuilder($table);
        if ($cols !== null) {
            $callerClass->queryBuilder_->selectColumns($cols);
        }
        return $callerClass;
    }

    /**
     * @note define entity type which will be used as the table's names
     *
     * @param $type
     * @param null $columns
     * @return $this
     */
    public static function type($type, $columns=null)
    {
        self::$table_=$type;
        $caller=get_called_class();
        $callerClass=new $caller();
        $callerClass->queryBuilder_=new QueryBuilder($type);
        if ($columns !== null) {
            $callerClass->queryBuilder_->selectColumns($columns);
        }
        return $callerClass;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return static::$table_;
    }

    /**
     * @param $data
     * @return $this
     */
    public function hydrateClass($data)
    {
        $class=get_called_class();
        foreach($data as $key => $value) {
            $this->$key = $value;
        }

        foreach($class::$relations_ as $relation=>$data) {
            $rel=$relation.'_';
            $this->$rel=new Relation($class, $this->getPrimaryKeyValue(), $data[0], $data[1]);
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
     * @param $orderBy
     * @return $this
     */
    public function sort($orderBy)
    {
        $this->queryBuilder_->setOrder($orderBy);
        return $this;
    }

    /**
     * @return void
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
     * @return void
     */
    public function delete()
    {
        if (!$this->new_) {
            if ($this->queryBuilder_===null) {
                $this->queryBuilder_=new QueryBuilder($this->getType());
            }
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
    private function returnAggregateColumn()
    {
        $query=$this->queryBuilder_->go();
        return $query->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * @param $column
     * @param null $alias
     * @return mixed
     */
    public function avg($column, $alias=null)
    {
        $this->queryBuilder_->aggregateColumn("AVG", $column, $alias);
        return $this->returnAggregateColumn();
    }

    /**
     * @param $column
     * @param null $alias
     * @return mixed
     */
    public function min($column, $alias=null)
    {
        $this->queryBuilder_->aggregateColumn("MIN", $column, $alias);
        return $this->returnAggregateColumn();
    }

    /**
     * @param $column
     * @param null $alias
     * @return mixed
     */
    public function max($column, $alias=null)
    {
        $this->queryBuilder_->aggregateColumn("MAX", $column, $alias);
        return $this->returnAggregateColumn();
    }

    /**
     * @param $column
     * @param null $alias
     * @return mixed
     */
    public function count($column, $alias=null)
    {
        $this->queryBuilder_->aggregateColumn("COUNT", $column, $alias);
        return $this->returnAggregateColumn();
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
        return $query->fetchAll(\PDO::FETCH_OBJ);
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
