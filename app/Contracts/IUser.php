<?php

namespace App\Contracts;

interface IUser
{
    public function all();

    public function getById($id);

    public function getByUsername($username);

    public function getByEmail($email);

    public function getWithUserType($id);

    public function store($request);

    public function update($request, $id);

    public function destroy($id);
}