<?php
/**
 * @author John O'Grady
 * @date 07/11/2016
 */

namespace Ecne\Test;

use Ecne\ORM\DB\DataBase;
use PHPUnit_Framework_TestCase;

class DataBaseTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        DataBase::connectDSN('mysql:host=127.0.0.1;dbname=test', 'root', '');
        DataBase::execute('DROP TABLE IF EXISTS `Entity`');
        Database::execute('CREATE TABLE Entity (Id INT(11) NOT NULL AUTO_INCREMENT,Name VARCHAR(25) NOT NULL,RoleId INT(11) NOT NULL,PRIMARY KEY(Id))');
        DataBase::execute('INSERT INTO `Entity` (Id, Name, RoleId) VALUES (?, ?, ?)', [1, 'Batman', 1]);
    }

    /**
     * @note test the last inserted Id
     */
    public function testLastInsertID()
    {
        $this->assertEquals(DataBase::getLastInsertID(), 1);
    }

    /**
     * @note drop sample table and output log
     */
    public function tearDown()
    {
        parent::tearDown();
        DataBase::execute('DROP TABLE IF EXISTS `Entity`');
        echo "\n Outputting ORM Log \n";
        print_r(DataBase::getLog());
    }
}