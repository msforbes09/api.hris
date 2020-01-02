<?php

namespace App\Repositories;

use App\User;
use App\Contracts\IUser;
use App\Contracts\PaginationQuery;

class UserRepository extends PaginationQuery implements IUser
{
    public function __construct()
    {
        $this->allowedOrderByFilter = ['email', 'username', 'user_type'];
    }

    public function all() {
        $paginationQuery = $this->validatePaginationQuery();

        $users = User::select('users.*', 'user_types.name as user_type')
            ->leftJoin('user_types', 'user_types.id', 'users.user_type_id')
            ->orderBy($paginationQuery->orderBy, $paginationQuery->order)
            ->paginate($paginationQuery->perPage);

        return $users->toArray();
    }

    public function getUserbyId($id) {
        return User::findOrFail($id);
    }

    public function getUserByUsername($username)
    {
        $user = User::where('username', $username)->first();

        return $user;
    }

    public function getUserByEmail($email)
    {
        $user = User::where('email', $email)->first();

        return $user;
    }

    public function getUserWithType($id)
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