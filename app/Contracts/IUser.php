<?php

namespace App\Contracts;

interface IUser
{
    public function allPerPage($per_page);

    public function getUserbyId($id);

    public function getUserByUsername($username);
}