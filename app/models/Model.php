<?php
/**
 * Class Model
 * @author John O'Grady
 * @date 21/06/2015
 */

namespace Ecne\Model;

use Ecne\ORM\DataBase;

class Model
{
    #region class properties
    /**
     * @var DataBase $datebase_
     */
    protected $datebase_;
    /**
     * @var Model $instance_
     */
    protected static $instance_;
    /**
     * @var bool $new_
     */
    protected $new_ = true;
    /**
     * @var string $primaryKey_
     */
    private $primaryKey_ = 'id';
    /**
     * @var mixed $primaryKeyValue_
     */
    protected $primaryKeyValue_;
    #endregion
    /**
     * @param mixed $id
     */
    public function __construct($id = null)
    {
        if ($id !== null) {
            if (count($this->eq('id', $id)->limit(1)->all()) > 0) {
                $this->new_ = false;
            }
        }
        $this->datebase_ = DataBase::getInstance();
    }
    /**
     * @param array|null $cols
     * @return mixed
     */

    public static function select($cols = null)
    {
        $caller = get_called_class();
        $callerClass = new $caller();
        $callerClass->database_ = DataBase::getInstance();
        if ($cols !== null) {
            $callerClass->datebase_->selectColumns($cols);
        }
        return $callerClass;
    }

    /**
     * @return mixed
     */

    public static function create()
    {
        $caller = get_called_class();
        $callerClass = new $caller();
        $callerClass->database_ = DataBase::getInstance();
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
    * @param $table
    * @return $this
    */
    public function fromTable($table)
    {
      $this->database_->fromTable($table);
      return $this;
    }
    /**
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function eq($field, $value)
    {
        $this->datebase_->addWhere($field, '=', $value);
        return $this;
    }
    /**
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function neq($field, $value)
    {
        $this->datebase_->addWhere($field, '!=', $value);
        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function lt($field, $value)
    {
        $this->datebase_->addWhere($field, '<', $value);
        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function lte($field, $value)
    {
        $this->datebase_->addWhere($field, '<=', $value);
        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function gt($field, $value)
    {
        $this->datebase_->addWhere($field, '>', $value);
        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function gte($field, $value)
    {
        $this->datebase_->addWhere($field, '>=', $value);
        return $this;
    }

    public function times()
    {
        $this->datebase_->times();
        return $this;
    }

    public function plus()
    {
        $this->datebase_->plus();
        return $this;
    }

    /**
     * @param int $limit
     * @param int|null $offset
     * @return $this
     */
    public function limit($limit, $offset = null)
    {
        $this->datebase_->limit($limit, $offset);
        return $this;
    }

    /**
     * @param array $insert
     * @return $this
     */
    public function insert($insert)
    {
        $this->datebase_->insert($insert);
        $this->datebase_->run();
        return $this;
    }

    /**
     * @param $update
     * @return $this
     */
    public function update($update)
    {
        $this->eq($this->primaryKey_, $this->primaryKeyValue_);
        $this->datebase_->update($update);
        $this->datebase_->run();
        return $this;
    }

    public function save()
    {
        if ($this->new_) {
            $this->datebase_->setQueryType(DataBase::QUERY_TYPE_INSERT);
            $this->insert($this->toAssocArray());
        } else {
            # update
            $this->datebase_->setQueryType(DataBase::QUERY_TYPE_UPDATE);
            $this->update($this->toAssocArray());
        }

    }

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
     * @return mixed
     */
    public function all()
    {
        $resultSet = $this->datebase_->run()->result();
        if (count($resultSet) > 0) {
            $this->new_ = false;
        }
        return $resultSet;
    }

    /**
     * @return mixed
     */
    public function one()
    {
        $resultSet = $this->datebase_->limit(1)->run()->one();
        $one = null;
        if (count($resultSet) > 0) {
            $one = $this->dispenseClass()->hydrateClass($resultSet);
            $primary = $this->primaryKey_;
            $one->primaryKeyValue_ = (int)$resultSet->$primary;
            $one->new_ = false;
        }
        return $one;
    }

    public function run()
    {
        $this->datebase_->run();
        return $this;
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
