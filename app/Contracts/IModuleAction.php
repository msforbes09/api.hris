<?php

namespace App\Contracts;

interface IModuleAction
{
    public function all($moduleId);

    public function getById($moduleId, $id);

    public function store($request, $moduleId);

    public function update($request, $moduleId, $id);

    public function destroy($moduleId, $id);
}
