<?php

namespace App\Repositories;

use App\User;
use App\Contracts\IUser;

class UserRepository implements IUser
{
    public function all() {
        $paginationQuery = $this->validatePaginationQuery();

        $users = User::select('users.*', 'user_types.name as user_type')
            ->leftJoin('user_types', 'user_types.id', 'users.user_type_id')
            ->orderBy($paginationQuery->orderBy, $paginationQuery->order)
            ->paginate($paginationQuery->per_page);

        return $users->toArray();
    }

    protected function validatePaginationQuery()
    {
        $per_page = is_numeric(request('per_page')) ? request('per_page') : 10;
        $orderBy = $this->filterOrderby(request('order_by'));
        $order = $this->filterOrder(request('order'));

        return (Object) [
            'per_page' => $per_page,
            'orderBy' => $orderBy,
            'order' => $order
        ];
    }

    protected function filterOrderby($orderBy)
    {
        $allowed = ['email', 'username', 'user_type'];

        return in_array($orderBy, $allowed) ? $orderBy : $allowed[0];
    }

    public function filterOrder($order)
    {
        $allowed = ['asc', 'desc'];

        return in_array($order, $allowed) ? $order : $allowed[0];
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