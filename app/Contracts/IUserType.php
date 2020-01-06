<?php

namespace App\Contracts;

interface IUserType
{
    public function all();

    public function getById($id);

    public function access($userType, $access);
}
