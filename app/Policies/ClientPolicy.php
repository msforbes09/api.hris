<?php

namespace App\Policies;

use App\User;
use App\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    protected $module = '002';

    public function viewAny(User $user)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'V-' . $this->module)->count();
    }

    public function view(User $user, Client $model)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'V-' . $this->module)->count();
    }

    public function create(User $user)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'S-' . $this->module)->count();
    }

    public function update(User $user, Client $model)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'U-' . $this->module)->count();
    }

    public function delete(User $user, Client $model)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'D-' . $this->module)->count();
    }
}
