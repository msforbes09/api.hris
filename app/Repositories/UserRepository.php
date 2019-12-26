<?php

namespace App\Repositories;

use App\User;
use App\Contracts\IUser;

class UserRepository implements IUser
{
    public function allPerPage($per_page) {
        return User::paginate($per_page);
    }

    public function getUserbyId($id) {
        return User::findOrFail($id);
    }

    public function getUserByUsername($username)
    {
        $user = User::where('username', $username)
            ->first();

        return $user;
    }
}