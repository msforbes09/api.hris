<?php

namespace App\Contracts;

interface IModuleAction
{
    public function all($moduleId);

    public function getById($id);

    public function store($request);

    public function update($request, $id);

    public function destroy($id);
}
