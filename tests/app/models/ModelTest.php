<?php

namespace Ecne\Test;

use Ecne\Model\Model;
use Ecne\Model\User;
use Ecne\ORM\DB\DataBase;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setup();
        DataBase::connectDSN('mysql:host=127.0.0.1;dbname=test', 'root', '');
        DataBase::execute('DROP TABLE IF EXISTS `Entity`');
        Database::execute('CREATE TABLE Entity (Id INT(11) NOT NULL AUTO_INCREMENT,Name VARCHAR(25) NOT NULL,RoleId INT(11) NOT NULL,PRIMARY KEY(Id))');
        DataBase::execute('INSERT INTO `Entity` (Id, Name, RoleId) VALUES (?, ?, ?)', [1, 'Batman', 1]);

        DataBase::execute('DROP TABLE IF EXISTS `User`');
        Database::execute('CREATE TABLE `User` (Id INT(11) NOT NULL AUTO_INCREMENT,Username VARCHAR(25) NOT NULL,PRIMARY KEY(Id))');
        DataBase::execute('INSERT INTO `User` (Id, Username) VALUES (?, ?)', [1, 'natedrake']);
    }

    public function testAggregateFunction()
    {
        $model = Model::type('Entity')->count('Id', 'count');
        $this->assertEquals(1, $model->count);
    }

    public function testUserUpdate()
    {
        $user=User::select()->eq('Username', 'natedrake')->one();
        $user->Username='natedrake1';
        $user->save();

        $newUser=User::select()->eq('Username', 'natedrake1')->one();
        $this->assertEquals(1, $newUser->Id);
    }

    public function testDelete()
    {
        $user=Model::type('Entity')->eq('Id',1)->one();
        $user->delete();

        $count=Model::type('Entity')->count('Id', 'count');
        $this->assertEquals(0,$count->count);
    }

    public function tearDown()
    {
        parent::tearDown();
        DataBase::execute('DROP TABLE IF EXISTS `Entity`');
        DataBase::execute('DROP TABLE IF EXISTS `User`');
        DataBase::disconnect();
    }

}