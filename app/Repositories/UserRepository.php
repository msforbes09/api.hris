<?php

namespace App\Repositories;

use App\Contracts\IUser;

class UserRepository implements IUser
{
    public function all() {}

    public function getUserbyId($id) {}

    public function getUserByUsername($username)
    {
        $user = User::where('username', $username)
            ->first();

        return $user;
    }
}