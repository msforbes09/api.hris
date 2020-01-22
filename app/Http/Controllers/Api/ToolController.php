<?php

namespace App\Http\Controllers\Api;

use App\Key;
use App\User;
use App\Module;
use App\Company;
use App\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ToolController extends Controller
{
    public function userManagement()
    {
        return [
            'user_types' => UserType::all()
        ];
    }

    public function moduleManagement()
    {
        return [
            'modules' => Module::with('moduleActions')->get()
        ];
    }

    public function clientManagement()
    {
        return [
            'companies' => Company::all()
        ];
    }
}
