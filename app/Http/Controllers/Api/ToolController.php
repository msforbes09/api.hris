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

    public function companyManagement()
    {
        return [
            'companies' => Company::all()
        ];
    }

    public function keywordManagement()
    {
        $keys = Key::all();
        $allKeys = [];

        foreach ($keys as $key) {
            $allKeys[$key->name] = $key->keywords;
        }

        return $allKeys;
    }
}
