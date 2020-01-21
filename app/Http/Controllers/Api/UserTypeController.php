<?php

namespace App\Http\Controllers\Api;

use App\Module;
use App\UserType;
use App\ModuleAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserTypeController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'access')->first();
    }

    public function index()
    {
        $this->authorize('view', $this->module);

       return UserType::with('moduleActions')->get();
    }

    public function show(UserType $userType)
    {
        $this->authorize('show', $this->module);

        $userType->moduleActions;

        return $userType;
    }

    public function updateAccess(UserType $userType)
    {
        $this->authorize('update', $this->module);

        $actions = ModuleAction::whereIn('id', request('accesses'))->get();

        $userType->moduleActions()->sync($actions);

        return response()->json([
            'message' => 'Successfully updated accesses for this user type.'
        ]);
    }
}
