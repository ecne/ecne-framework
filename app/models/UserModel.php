<?php

namespace Ecne\Model;

class UserModel extends Model
{
    protected $id;
    protected $name;
    protected $username;
    protected $email;
    protected $salt;
    protected $password;

    public function __construct()
    {
        parent::__construct();
    }
}   #End Class Definition
