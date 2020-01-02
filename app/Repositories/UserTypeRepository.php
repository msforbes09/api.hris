<?php

namespace App\Repositories;

use App\UserType;
use App\Contracts\IUserType;

class UserTypeRepository implements IUserType 
{
    public function getAll()
    {
        return UserType::all()->toArray();
    }
}