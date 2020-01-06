<?php

namespace App\Repositories;

use App\UserType;
use App\Contracts\IUserType;

class UserTypeRepository implements IUserType 
{
    public function all()
    {
        return ['all' => UserType::all()->toArray()];
    }

    public function getById($id)
    {
        return UserType::findOrFail($id);
    }

    public function access($userType, $access)
    {
        $userType->module_actions()->sync($access);
    }
}
