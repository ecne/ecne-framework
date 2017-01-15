<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/01/2017
 * Time: 22:08
 */

namespace Ecne\Test;


use Ecne\Model\User;
use Ecne\ORM\DB\DataBase;

class RelationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        DataBase::connectDSN('mysql:host=127.0.0.1;dbname=test', 'root', '');
        DataBase::execute('DROP TABLE IF EXISTS `Entity`');
        Database::execute('CREATE TABLE Entity (Id INT(11) NOT NULL AUTO_INCREMENT,Name VARCHAR(25) NOT NULL,RoleId INT(11) NOT NULL,PRIMARY KEY(Id))');
        DataBase::execute('INSERT INTO `Entity` (Id, Name, RoleId) VALUES (?, ?, ?)', [1, 'Batman', 1]);

        DataBase::execute('DROP TABLE IF EXISTS `User`');
        Database::execute('CREATE TABLE `User` (Id INT(11) NOT NULL AUTO_INCREMENT,Username VARCHAR(25) NOT NULL,PRIMARY KEY(Id))');
        DataBase::execute('INSERT INTO `User` (Id, Username) VALUES (?, ?)', [1, 'natedrake']);

        DataBase::execute('DROP TABLE IF EXISTS `Blog`');
        Database::execute('CREATE TABLE `Blog` (Id INT(11) NOT NULL AUTO_INCREMENT,PostedBy int(11) NOT NULL,PRIMARY KEY(Id), FOREIGN KEY(PostedBy) REFERENCES User(Id))');
        DataBase::execute('INSERT INTO `Blog` (Id, PostedBy) VALUES (?, ?)', [1, 1]);
        DataBase::execute('INSERT INTO `Blog` (Id, PostedBy) VALUES (?, ?)', [2, 1]);
        DataBase::execute('INSERT INTO `Blog` (Id, PostedBy) VALUES (?, ?)', [3, 1]);
        DataBase::execute('INSERT INTO `Blog` (Id, PostedBy) VALUES (?, ?)', [4, 1]);
    }

    public function testRelation()
    {
        $user=User::select()->eq('Id',1)->one();
        $this->assertGreaterThan(0, count($user->blogposts_->fetch()));

    }

    public function tearDown()
    {
        parent::tearDown();
        DataBase::execute('DROP TABLE IF EXISTS `Entity`');
        DataBase::execute('DROP TABLE IF EXISTS `Blog`');
        DataBase::execute('DROP TABLE IF EXISTS `User`');
        DataBase::disconnect();
    }
}