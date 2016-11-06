<?php

/**
 * Class DataBase
 * @note Supplies an API for querying various databases independent of DataBase driver,
 *      -- and calls most queries for general use in most web projects
 * @author John OGrady <ogradyjp@ogradyjohn.com or ogradjp@gmail.com>
 * @version 1.0
 * @date May 2015
 **/

namespace Ecne\ORM;

use \PDO;
use \PDOStatement;
use Ecne\Library\Core\Config;

class DataBase
{
    #region constants
    const QUERY_TYPE_SELECT = "SELECT ";
    const QUERY_TYPE_DELETE = "DELETE ";
    const QUERY_TYPE_UPDATE = "UPDATE ";
    const QUERY_TYPE_INSERT = "INSERT INTO ";
    const SQL_LIMIT = " LIMIT ";
    const SQL_OFFSET = ", ";
    const SQL_ORDER = " ORDER BY ";
    const SQL_ASC = " ASC ";
    const SQL_DESC = " DESC ";
    const SQL_WHERE = " WHERE ";
    const SQL_AND = " AND ";
    const SQL_OR = " OR ";
    const SQL_VALUES = " VALUES ";
    const SQL_SET = " SET ";

    const ORDER_DESCENDING = " DESC ";
    const ORDER_ASCENDING = " ASC ";
    #endregion

    #region class properties
    /**
     * @var DBDriver $dbDriver
     */
    private $dbDriver;

    /**
     * @var DataBase $instance
     */
    private static $instance;
    /**
     * @var \PDO $pdo
     */
    private $pdo;
    /**
     * @var \PDOStatement $query
     */
    private $query;
    /**
     * @var string $queryType
     */
    private $queryType;
    /**
     * @var bool $error
     */
    private $error = false;
    /**
     * @var array $results
     */
    private $results;
    /**
     * @var int $count
     */
    private $count = 0;
    /**
     * @var string $table
     */
    private $table;
    /**
     * @var array $selectColumns
     */
    private $selectColumns = array(" * ");
    /**
     * @var array $update
     */
    private $insert = array();
    /**
     * @var array $update
     */
    private $update = array();
    /**
     * @var int
     */
    private $limit;
    /**
     * @var int
     */
    private $offset;
    /**
     * @var string
     */
    private $orderBy = null;
    /**
     * @var array
     */
    private $paramArray = array();
    /**
     * @var int $paramCount;
     */
    private $paramCount = 0;
    /**
     * @var array $whereClause
     */
    private $whereClause = array();
    /**
     * @var array $condChain
     */
    private $condChain = array();
    #endregion

    /**
     *  @method default constructor
     *  @access private
     *  @note uses singleton pattern so only one instance of our database is used for all queries and connections...
     *  @note host, db, username, and password are all supplied by the Config::get method getting data from our gloBal config array defined in Core/init.php
     */
    private function __construct()
    {
        $this->dbDriver = new DBDriver(Config::get('mysql/driver'));
        $this->queryType = self::QUERY_TYPE_SELECT;
        try {
            $this->pdo = new PDO($this->dbDriver->getDSN(), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
    /**
     * @method getInstance
     * @access public
     * @note if an instance of DataBase doesn't exist create a new instance of DataBase, if an instance of DataBase does exists return that
     * @return DataBase
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DataBase();
        }
        return self::$instance;
    }
    /**
     * @method setQueryType
     * @access public
     * @param string $queryType
     * @return DataBase $this
     */
    public function setQueryType($queryType)
    {
        $this->queryType = $queryType;
        return $this;
    }
    /**
     * @method addParameter
     * @access public
     * @param $val
     * @return string
     */
    public function addParameter($val)
    {
        $placeholder = ":_" . $this->paramCount;
        $this->paramArray[$placeholder] = $val;
        $this->paramCount++;
        return $placeholder;
    }
    /**
     * @method selectColumns
     * @access public
     * @param $cols
     * @return $this
     */
    public function selectColumns($cols)
    {
        $this->selectColumns = $cols;
        return $this;
    }
    /**
     * @method fromTable
     * @access public
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->table = $type;
        return $this;
    }
    /**
     * @method insert
     * @access public
     * @param $insert
     * @return $this
     */
    public function insert($insert)
    {
        if (count($insert)) {
            $this->insert = $insert;
        }
        return $this;
    }
    /**
     * @method update
     * @access public
     * @param $update
     * @return $this
     */
    public function update($update)
    {
        if (count($update)) {
            $this->update = $update;
        }
        return $this;
    }
    /**
     *  @method addWhere
     *  @param $field|string
     *  @param $op|string
     *  @param $val|mixed
     */
    public function addWhere($field, $op, $val)
    {
        $this->whereClause[] = array('field' => $field, 'op' => $op, 'val' => $val);
    }
    /**
     *
     *
     */
    public function times()
    {
        $this->condChain[] = self::SQL_AND;
    }
    /**
     *  @method plus
     *  @return void
     */
    public function plus()
    {
        $this->condChain[] = self::SQL_OR;
    }
    /**
     * @method orderBy
     * @param $orderBy
     * @return DataBase
     */
    public function orderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }
    /**
     * @method limit
     * @param $limit|int
     * @param $offset|int
     * @return DataBase
     */
    public function limit($limit, $offset = null)
    {
        $this->limit = $limit;
        if ($offset !== null) {
            $this->offset = $offset;
        }
        return $this;
    }

    public function delete()
    {
        $this->setQueryType(self::QUERY_TYPE_DELETE);
    }

    /**
     * @method buildQueryType
     * @return string
     */
    public function buildQueryType()
    {
        switch ($this->queryType) {
            case self::QUERY_TYPE_SELECT:
                $selcols = " * ";
                if (is_array($this->selectColumns)) {
                    $selcols = join(", " , $this->selectColumns);
                } else if (isset($this->selectColumns)) {
                    $selcols = $this->selectColumns;
                }
                return self::QUERY_TYPE_SELECT . $selcols .  " FROM "  . $this->table;
                break;
            case self::QUERY_TYPE_INSERT:
                $sql = self::QUERY_TYPE_INSERT . $this->table;
                $cols = array();
                $vals = array();
                foreach ($this->insert as $col => $val) {
                    array_push($cols, $col);
                    array_push($vals, $this->addParameter($val));
                }
                $sql .=  " (" . join(", ", $cols) . " )" . self::SQL_VALUES . " (" . join(", ", $vals) . " )";
                return $sql;
                break;
            case self::QUERY_TYPE_UPDATE:
                # update table set col=val
                $update = array();
                $sql = self::QUERY_TYPE_UPDATE . $this->table . self::SQL_SET;
                foreach($this->update as $key => $val) {
                    if (!is_int($val)) {
                        $val = $this->wrapInSingleQuotes($val);
                    }
                    $update[] = $key . "=" . $val;
                }
                $sql .= implode(", ", $update);
                return $sql;
                break;
            case self::QUERY_TYPE_DELETE:
                $sql = self::QUERY_TYPE_DELETE . " FROM " . $this->table;
                return $sql;
                break;
            default:
                break;
        }
        return "";
    }
    /**
     * @method buildWhere
     * @param array
     * @return DataBase
     */
    public function buildWhere()
    {
        $conditions = array();
        if (count($this->whereClause) > 0) {
            $condCount = 0;
            foreach ($this->whereClause as $where) {
                if ($condCount < count($this->condChain)) {
                    $conditions[] = " {$where['field']} {$where['op']} " . $this->addParameter($where['val']) . $this->condChain[$condCount];
                } else {
                    $conditions[] = " {$where['field']} {$where['op']} "  . $this->addParameter($where['val']);
                }
                $condCount++;
            }
            return self::SQL_WHERE . implode(" ", $conditions);
        }
        return "";
    }
    /**
     * @method buildOrderBy
     * @return string
     */
    public function buildOrderBy()
    {
        if (isset($this->orderBy) && is_array($this->orderBy)) {
            $sql = " ORDER BY ";
            $i=1;
            foreach($this->orderBy as $k => $v) {
                if ($i === count($this->orderBy)) {
                    $sql .= "$k $v";
                } else {
                    $sql .= "$k $v, ";
                }
                $i++;
            }
            return $sql;
        } else {
            return "";
        }
    }
    /**
     * @method buildLimit
     * @return string
     */
    public function buildLimit()
    {
        if (isset($this->limit)) {
            $sql = " LIMIT " . $this->addParameter($this->limit);
            if (isset($this->offset)) {
                $sql .= ", " . $this->addParameter($this->offset);
            }
            return $sql;
        }
        return "";
    }
    /**
     * @method buildQuery
     * @return string
     */
    public function buildQuery()
    {
        return join(" ", array(
            $this->buildQueryType(),
            $this->buildWhere(),
            $this->buildOrderBy(),
            $this->buildLimit()
        ));
    }
    /**
     * @method run
     * @return DataBase
     */
    public function run()
    {
        $this->execute($this->buildQuery());
        return $this;
    }
    /**
     * @method execute
     * @param $query|string
     * @return DataBase
     */
    public function execute($query)
    {
        $this->error = false;
        if ($query) {
            if ($this->query = $this->pdo->prepare($this->escapeDoubleSpaces($query))) {
                if (count($this->paramArray)) {
                    foreach ($this->paramArray as $param => $val) {
                        if (is_null($val)) {
                            $var = PDO::PARAM_NULL;
                        } elseif (is_int($val)) {
                            $var = PDO::PARAM_INT;
                        } elseif (is_bool($val)) {
                            $var = PDO::PARAM_BOOL;
                        } else {
                            $var = PDO::PARAM_STR;
                            if ($this->queryType === self::QUERY_TYPE_UPDATE) {
                                $val = $this->wrapInSingleQuotes($val);
                            }
                        }
                        $this->query->bindValue($param, $val, $var);
                    }   #End Foreach
                }
                if ($this->query->execute()) {
                    $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
                    $this->count = $this->query->rowCount();
                } else {
                    $this->error = true;
                }
            }
        }
        $this->reset();
        return $this;
    }
    /**
     *  @method reset
     *  @return void
     */
    public function reset()
    {
        $this->limit = null;
        $this->offset = null;
        $this->queryType = self::QUERY_TYPE_SELECT;
        $this->whereClause = array();
        $this->insert = array();
        $this->orderBy = null;
        $this->query = null;
        $this->condChain = array();
        $this->paramArray = array();
        $this->paramCount = 0;
    }
    /**
     * @method result
     * @return mixed
     */
    public function result()
    {
        return $this->results;
    }
    /**
     * @method one
     * @return mixed
     */
    public function one()
    {
        if (count($this->results)) {
            return $this->results[0];
        } else {
            return null;
        }
    }
    /**
     * @method error
     * @return bool
     */
    public function error()
    {
        return $this->error;
    }
    /**
     * @method escapeDoubleSpaces
     * @param $string|string
     * @return string
     */
    public function escapeDoubleSpaces($string)
    {
        return preg_replace('/[\s]{1,}/', " ", $string);
    }
    /**
     *  @method wrapInSingleQuotes
     *  @param $string|string
     *  @return string
     */
    public function wrapInSingleQuotes($string)
    {
        return "'{$string}'";
    }
    /**
     *  @method wrapInDoubleQuotes
     *  @param $string|string
     *  @return string
     */
    public function wrapInDoubleQuotes($string)
    {
        return '"'.$string.'"';
    }
    /**
     *  @method pdo
     *  @return PDO
     */
    public function pdo()
    {
        return $this->pdo;
    }
}   #End Class Definition
