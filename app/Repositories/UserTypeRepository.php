<?php

namespace App\Repositories;

use App\UserType;
use App\Contracts\IUserType;
use App\Contracts\PaginationQuery;

class UserTypeRepository extends PaginationQuery implements IUserType 
{
    public function all()
    {
        $userTypes = UserType::all();

        return ['user_types' => $userTypes];
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
