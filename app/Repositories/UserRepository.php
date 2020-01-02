<?php

namespace App\Repositories;

use App\User;
use App\Contracts\IUser;

class UserRepository implements IUser
{
    public function allPerPage($per_page) {
        return User::with('user_type')
            ->paginate($per_page)
            ->toArray();
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