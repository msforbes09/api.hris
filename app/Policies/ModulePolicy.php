<?php

namespace App\Policies;

use App\Module;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
{
    use HandlesAuthorization;

    public function allows(User $user, Module $module, $action)
    {
        return $user
            ->allowedModuleActions()
            ->where('module_id', $module->id)
            ->where('name', $action)
            ->first();
    }
}
