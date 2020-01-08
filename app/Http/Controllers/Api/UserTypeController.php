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
            'data' => UserType::with('moduleActions')->get()
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


        $userType->moduleActions()->sync($actions);


        return response()->json([
            'message' => 'Successfully updated accesses for this user type.'
        ]);
    }
}
