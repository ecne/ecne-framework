<?php

/**
 * Class QueryBuilder
 * @version 1.1
 * @date November 2016
 */

namespace Ecne\ORM;

use Ecne\ORM\DB\Database;

class QueryBuilder
{
    private $entityType;
    private $entityData = [];
    private $queryType;
    private $limit;
    private $offset;
    private $selectColumns = [];
    private $where = [];
    private $order = [];

    private $parameters = [];
    private $parameterCount = 0;

    private $conditionChain = [];

    const QUERY_TYPE_SELECT = 0;
    const QUERY_TYPE_INSERT = 1;
    const QUERY_TYPE_UPDATE = 2;
    const QUERY_TYPE_DELETE = 3;

    /**
     * @param $entityType
     */
    public function __construct($entityType)
    {
        $this->entityType = $entityType;
        $this->queryType = self::QUERY_TYPE_SELECT;
    }

    /**
     * @param $type
     */
    public function type($type)
    {
        $this->entityType = $type;
    }

    /**
     * @param $entityData
     */
    public function setEntityData($entityData)
    {
        $this->entityData=$entityData;
    }

    /**
     * @param $val
     * @return string
     */
    public function addParameter($val)
    {
        $placeholder = ":_".$this->parameterCount;
        $this->parameters[$placeholder]=$val;
        $this->parameterCount++;
        return $placeholder;
    }

    /**
     * @param $type
     */
    public function setQueryType($type)
    {
        $this->queryType=$type;
    }

    /**
     * @note public functions to add properties to statement
     * @param $field
     * @param $op
     * @param $val
     */

    public function addWhere($field, $op, $val)
    {
        $this->where[]=['field'=>$field, 'op'=>$op, 'value'=>$val];
    }

    /**
     * @param $limit
     * @param null $offset
     */
    public function setLimit($limit, $offset=null)
    {
        $this->limit=$limit;
        if ($offset != null) {
            $this->offset=$offset;
        }
    }

    /**
     * @note functions to build sql query
     */
    private function buildQueryType()
    {
        switch($this->queryType) {
            case self::QUERY_TYPE_SELECT:
                $selectColumns = (count($this->selectColumns))?join(', ', $this->selectColumns): '*';
                return 'SELECT '.$selectColumns.' FROM `'.$this->entityType.'`';
                break;
            case self::QUERY_TYPE_INSERT:
                $columns = '`'.implode('`, `', array_keys($this->entityData)). '`';
                $parameters = [];
                foreach($this->entityData as $k => $v) {
                    $parameters[] = $this->addParameter($v);
                }
                $parameters = implode(', ', $parameters);
                return 'INSERT INTO `'.$this->entityType.'(`'.$columns.') VALUES ('.$parameters.')';
                break;
            case self::QUERY_TYPE_UPDATE:
                $updates = [];
                foreach ($this->entityData as $k => $v) {
                    $updates[] = $k.'='.$this->addParameter($v);
                }
                return 'INSERT INTO `'.$this->entityType.'` SET ' . implode(', ', $updates[]);
                break;
            case self::QUERY_TYPE_DELETE:
                return 'DELETE FROM `'.$this->entityType.'`';
                break;
        }
    }

    /**
     * @return string
     */
    private function buildWhere()
    {
        $conditions=[];
        if (count($this->where) > 0) {
            $conditionCount=0;
            foreach($this->where as $where) {
                if ($conditionCount < count($this->conditionChain)) {
                    $conditions[] = $this->buildWhereCondition($where['field'], $where['op'], $where['value']).$this->conditionChain[$conditionCount];
                } else {
                    $conditions[] = $this->buildWhereCondition($where['field'], $where['op'], $where['value']);
                }
                $conditionCount++;
            }
            return ' WHERE '.implode(" ", $conditions);
        }
    }

    /**
     * @param $field
     * @param $op
     * @param $val
     * @return string
     */
    private function buildWhereCondition($field, $op, $val)
    {
        switch($op) {
            case 'IN':
            case 'NOT IN ':
                $elements=[];
                foreach($val as $element) {
                    $elements[]=$element;
                }
                return "$field $op (".implode(', ', $elements).")";
                break;
            case 'IS NULL':
            case 'IS NOT NULL':
                return "$field $op";
                break;
            case 'LIKE':
                return "$field $op".$this->addParameter($val)." ESCAPE'='";
            default:
                return "$field $op".$this->addParameter($val);
        }
    }

    /**
     * @return string
     */
    private function buildOrder()
    {
        if (count($this->order) > 0) {
            return ' ORDER BY '.implode(', ', $this->order);
        }
        return '';
    }

    /**
     * @return string
     */
    private function buildLimit()
    {
        if (isset($this->limit)) {
            return ' LIMIT '.$this->limit;
        }
        return '';
    }

    /**
     * @return string
     */
    private function buildOffset()
    {
        if (isset($this->offset)) {
            return ' OFFSET '.$this->offset;
        }
    }

    /**
     * @return string
     */
    private function buildQuery()
    {
        return join("",[
            $this->buildQueryType(),
            $this->buildWhere(),
            $this->buildOrder(),
            $this->buildLimit(),
            $this->buildOffset()
        ]);
    }

    /**
     * @return DataBase
     */
    public function go()
    {
        $sql = $this->buildQuery();
        $q = Database::execute($sql, $this->parameters);
        $this->reset();
        return $q;
    }

    /**
     * @note reset query properties
     */
    private function reset()
    {
        $this->entityData=null;
        $this->queryType=self::QUERY_TYPE_SELECT;
        $this->limit=null;
        $this->offset = null;
        $this->selectColumns=[];
        $this->where=[];
        $this->order=[];
        $this->parameters=[];
        $this->parameterCount=0;
    }
}