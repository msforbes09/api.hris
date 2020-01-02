<?php

namespace App\Repositories;

use App\Module;
use App\Contracts\IModule;
use App\Contracts\PaginationQuery;

class ModuleRepository extends PaginationQuery implements IModule 
{
    public function __construct()
    {
        $this->setAllowedOrderByFilter();
    }

    protected function setAllowedOrderByFilter()
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


    public function getById()
    {
        throw new \Exception('Method getById() is not implemented.');
    }

    public function destroy()
    {
        throw new \Exception('Method destroy() is not implemented.');
    }
}