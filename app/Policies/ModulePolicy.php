<?php

namespace App\Policies;

use App\Module;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Module $module)
    {
        $action = $module->moduleActions->where('code', 'view')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();
    }

    public function create(User $user, Module $module)
    {
        $action = $module->moduleActions->where('code', 'create')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();

    }

    public function show(User $user, Module $module)
    {
        $action = $module->moduleActions->where('code', 'show')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();

    }

    public function update(User $user, Module $module)
    {
        $action = $module->moduleActions->where('code', 'update')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();

    }

    public function delete(User $user, Module $module)
    {
        $action = $module->moduleActions->where('code', 'delete')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();

    }

    public function restore(User $user, Module $module)
    {
        $action = $module->moduleActions->where('code', 'restore')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();

    }

    public function send(User $user, Module $module)
    {
        $action = $module->moduleActions->where('code', 'send')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();
    }
}
