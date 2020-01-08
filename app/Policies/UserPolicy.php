<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    protected $module = '001';

    public function viewAny(User $user)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'V-' . $this->module)->count();
    }

    public function view(User $user, User $model)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'V-' . $this->module)->count() || $user->id === $model->id;
    }

    public function create(User $user)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'S-' . $this->module)->count();
    }

    public function update(User $user, User $model)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'U-' . $this->module)->count() || $user->id == $model->id;
    }

    public function delete(User $user, User $model)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'D-' . $this->module)->count() && $user->id !== $model->id;
    }
}
