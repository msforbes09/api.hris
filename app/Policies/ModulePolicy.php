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

    public function create(User $user)
    {
        $action = $module->moduleActions->where('code', 'create')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();

    }

    public function show(User $user)
    {
        $action = $module->moduleActions->where('code', 'show')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();

    }

    public function update(User $user)
    {
        $action = $module->moduleActions->where('code', 'update')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();

    }

    public function destroy(User $user)
    {
        $action = $module->moduleActions->where('code', 'destroy')->first();

        if (! $action) return;

        return $user->userType->moduleActions->where('id', $action->id)->first();

    }
}
