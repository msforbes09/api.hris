<?php

namespace App\Repositories;

use App\User;
use App\Contracts\IUser;
use App\Contracts\PaginationQuery;

class UserRepository extends PaginationQuery implements IUser
{
    public function __construct()
    {
        $this->allowedOrderByFilter = ['email', 'username', 'user_type', 'name', 'active'];
    }

    public function all() {
        $paginationQuery = $this->getPaginationParams();

        $status = $this->userStatusQuery();
        $search = request('search') ?? '';

        $query = $this->userQuery($status, $search);

        $users = $this->getPaginatedQuery($query, $paginationQuery);       

        return $users->toArray();
    }

    protected function getPaginatedQuery($query, $paginationQuery)
    {
        return $query->orderBy($paginationQuery->orderBy, $paginationQuery->order)
            ->paginate($paginationQuery->perPage);
    }

    protected function userQuery($status, $search)
    {
        $query = User::select('users.*', 'user_types.name as user_type')
            ->leftJoin('user_types', 'user_types.id', 'users.user_type_id')
            ->whereIn('users.active', $status)
            ->where(function($query) use ($search){
                $query->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.username', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('user_types.name', $search);
            });
        
        return $query;
    }

    protected function userStatusQuery()
    {
        $status = request('status');

        switch ($status) {
            case 'active':
                return [1];
                break;
            case 'inactive':
                return [0];
                break;
            default:
                return [1,0];
                break;
        }
    }

    public function getById($id) {
        return User::findOrFail($id);
    }

    public function getByUsername($username)
    {
        $user = User::where('username', $username)->first();

        return $user;
    }

    public function getByEmail($email)
    {
        $user = User::where('email', $email)->first();

        return $user;
    }

    public function getWithUserType($id)
    {
        $user = User::with('user_type')->findOrFail($id);

        return $user;
    }

    public function store($request)
    {
        $request['active'] = 1;

        $user = User::create($request);

        return $user;
    }

    public function update($request, $id)
    {
        $user = $this->getUserbyId($id);

        $user->fill($request);

        $user->save();

        return $user;
    }

    public function destroy($id)
    {
        $user = $this->getUserbyId($id);

        if($user->delete())
        {
            return true;
        }

        return false;
    }
}