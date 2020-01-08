<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Module;
use App\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ToolController extends Controller
{
    public function userManagement()
    {
        return response()->json([
            'user_types' => UserType::all()
        ]);
    }

    public function moduleManagement()
    {
        return response()->json([
            'modules' => Module::with('moduleActions')->get()
        ]);
    }
}
