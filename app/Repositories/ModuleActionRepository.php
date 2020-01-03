<?php

namespace App\Repositories;

use App\Contracts\IModuleAction;

class ModuleAction implements IModuleAction
{
    public function all($moduleId)
    {
        $moduleActions = ModuleAction::where('module_id', $module_id)->get();

        return $moduleActions->toArray();
    }

    public function getById($id)
    {
        $moduleAction = ModuleAction::findOrFail($id);
        
        return $moduleAction;
    }

    public function store($request)
    {
        $moduleAction = ModuleAction::create($request);

        return $moduleAction;
    }

    public function update($request, $id)
    {
        $moduleAction = ModuleAction::findOrFail($id);

        $moduleAction->fill($request);

        $moduleAction->save();

        return $moduleAction;
    }

    public function destroy($id)
    {
        $moduleAction = ModuleAction::findOrFail($id);

        if($moduleAction->delete())
        {
            return true;
        }

        return false;
    }
}
