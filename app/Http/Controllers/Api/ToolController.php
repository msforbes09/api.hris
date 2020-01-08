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
        return [
            'user_types' => UserType::all()
        ];
    }

    public function moduleManagement()
    {
        $modules = Module::with('moduleActions')->get()->map(function($module) {
            return [
                'id' => $module->id,
                'code' => $module->code,
                'label' => $module->name,
                'children' => $module->moduleActions->map(function($action)
                {
                    return [
                        'id' => $action->id,
                        'code' => $module->code,
                        'label' => $action->name
                    ];
                })
            ];
        });

        return [
            'modules' => $modules
        ];
    }
}
