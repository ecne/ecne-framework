<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 07/11/2016
 * Time: 21:53
 */

namespace Ecne\Test;

use Ecne\ORM\DB\DataBase;
use Ecne\Model\Model;

class ModelTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        DataBase::connectDSN('mysql:host=127.0.0.1;dbname=test', 'root', '');
    }

    public function insertModel()
    {
        $model = Model::select()->type('Entity');
        $model->Name = "peter parker";
        $model->RoleId = 7;
        $model->save();
    }

    public function testInsertModel()
    {

    }
}