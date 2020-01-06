<?php

namespace App\Repositories;

use App\UserType;
use App\Contracts\IUserType;
use App\Contracts\PaginationQuery;

class UserTypeRepository extends PaginationQuery implements IUserType 
{
    public function __construct()
    {
        $this->allowedOrderByFilter = ['name'];
    }

    public function all()
    {
        $paginationQuery = $this->getPaginationParams();

        $search = request('search') ?? '';

        $query = $this->allQuery($search);

        $userTypes = $query->orderBy($paginationQuery->orderBy, $paginationQuery->order)
            ->paginate($paginationQuery->perPage);       

        return $userTypes->toArray();
    }

    public function allQuery($search)
    {
        $userTypes = UserType::where('name', 'like', '%' . $search . '%');

        return $userTypes;
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
