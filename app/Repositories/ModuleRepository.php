<?php

namespace App\Repositories;

use App\Module;
use App\Contracts\IModule;
use App\Contracts\PaginationQuery;

class ModuleRepository extends PaginationQuery implements IModule 
{
    public function __construct()
    {
        $this->allowedOrderByFilter = ['code', 'name'];
    }

    public function all()
    {
        $paginationQuery = PaginationQuery::validatePaginationQuery();

        $modules = Module::orderBy($paginationQuery->orderBy, $paginationQuery->order)
            ->paginate($paginationQuery->perPage);

        return $modules->toArray();
    }


    public function getById($id)
    {
        $module = Module::findOrFail($id);

        return $module;
    }

    public function store($request)
    {
        $newModule = Module::create($request);

        return $newModule;
    }

    public function update($request, $id)
    {
        $updatedModule = Module::findOrFail($id);

        $updatedModule->fill($request);

        $updatedModule->save();

        return $updatedModule;
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);

        if($module->delete())
        {
            return true;
        }

        return false;
    }

}