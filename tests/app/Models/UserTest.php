<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/01/2017
 * Time: 19:19
 */

namespace Ecne\Test;


use Ecne\Model\User;
use Ecne\ORM\DB\DataBase;

class UserTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        parent::setUp();
        DataBase::connectDSN('mysql:host=127.0.0.1;dbname=test', 'root', '');
    }

    public function testOne()
    {
        $user = User::select()->eq('Username', 'x14101718')->one();
        $this->assertEquals(1, count($user));
    }

    public function testAll()
    {
        $users = User::select()->gt('Id', 1)->all();
        $this->assertGreaterThan(0, count($users));
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}