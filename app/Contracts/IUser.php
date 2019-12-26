<?php

namespace App\Contracts;

class IUser
{
     public function all() {}

    public function getUserbyId($id) {}

    public function getUserByUsername($username) {}
}