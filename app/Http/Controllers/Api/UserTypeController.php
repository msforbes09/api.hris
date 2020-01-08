<?php

namespace App\Http\Controllers\Api;

use App\UserType;
use App\ModuleAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserTypeController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Successfully retrieved user types.',
            'data' => UserType::with('module_actions')->get()
        ]);
    }

    public function updateAccess($id)
    {
        $userType = UserType::findOrFail($id);

        $accesses = request('accesses');


        $actions = ModuleAction::all()->filter(function ($module_action) use ($accesses)
        {
            return in_array($module_action->id, $accesses);
        });


        $userType->module_actions()->sync($actions);


        return $userType->module_actions;
    }
}
