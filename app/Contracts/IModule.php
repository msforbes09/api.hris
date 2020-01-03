<?php

namespace App\Contracts;

interface IModule
{
    public function all();

    public function getById($id);

    public function store($request);

    public function update($request, $id);

    public function destroy($id);
}
