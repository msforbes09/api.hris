<?php

namespace App\Repositories;

use App\ModuleAction;
use App\Contracts\IModuleAction;

class ModuleActionRepository implements IModuleAction
{
    public function all($moduleId)
    {
        $moduleActions = ModuleAction::where('module_id', $moduleId)->get();

        return $moduleActions->toArray();
    }

    public function getById($moduleId, $id)
    {
        $moduleAction = ModuleAction::findOrFail($id);
        
        return $moduleAction;
    }

    public function store($request, $moduleId)
    {
        $request['module_id'] = $moduleId;

        $moduleAction = ModuleAction::create($request);

        return $moduleAction;
    }

    public function update($request, $moduleId, $id)
    {
        $moduleAction = ModuleAction::findOrFail($id);

        $moduleAction->fill($request);

        $moduleAction->save();

        return $moduleAction;
    }

    public function destroy($moduleId, $id)
    {
        $moduleAction = ModuleAction::findOrFail($id);

        if($moduleAction->delete())
        {
            return true;
        }

        return false;
    }
}
