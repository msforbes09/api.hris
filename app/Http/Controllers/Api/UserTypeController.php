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
       return UserType::with('moduleActions')->get();
    }

    public function updateAccess(UserType $userType)
    {
        $actions = ModuleAction::whereIn('id', request('accesses'))->get();

        $userType->moduleActions()->sync($actions);

        return response()->json([
            'message' => 'Successfully updated accesses for this user type.'
        ]);
    }
}
