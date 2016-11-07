<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 07/11/2016
 * Time: 10:37
 */

namespace Ecne\Test;

use Ecne\ORM\DB\Database;
use PHPUnit_Framework_TestCase;

class DataBastTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        DataBase::connectDSN('mysql:host=127.0.0.1;dbname=test', 'root', '');

        Database::execute(
            'CREATE TABLE IF NOT EXISTS Entity (
              Id INT(11) NOT NULL AUTO_INCREMENT,
              Name VARCHAR(25) NOT NULL,
              RoleId INT(11) NOT NULL,
              PRIMARY KEY(Id)
            );');
    }

    public function insertEntity()
    {
        DataBase::execute('INSERT INTO `Entity` (Name, RoleId) VALUES (?, ?)', [
            'Batman', 7
        ]);
    }

    public function testInsert(){
        $this->insertEntity();
        $query = DataBase::execute('SELECT Id FROM `Entity` WHERE Id = ?', [6]);
        $row = $query->fetch(\PDO::FETCH_OBJ);
        $this->assertEquals($row->Id, 6);
    }

}